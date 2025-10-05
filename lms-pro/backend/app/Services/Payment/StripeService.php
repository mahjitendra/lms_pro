<?php

namespace App\Services\Payment;

use App\Models\Course\Course;
use App\Models\User;
use App\Services\Course\EnrollmentService;
use Exception;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeService
{
    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * Create or retrieve a Stripe Customer for a given User.
     *
     * @param User $user
     * @return Customer
     */
    protected function createOrRetrieveCustomer(User $user): Customer
    {
        if ($user->stripe_id) {
            return Customer::retrieve($user->stripe_id);
        }

        $customer = Customer::create([
            'email' => $user->email,
            'name' => $user->name,
        ]);

        $user->stripe_id = $customer->id;
        $user->save();

        return $customer;
    }

    /**
     * Create a Stripe Payment Intent.
     *
     * @param User $user
     * @param int $amount in cents
     * @param Course $course
     * @return PaymentIntent
     */
    public function createPaymentIntent(User $user, int $amount, Course $course): PaymentIntent
    {
        $customer = $this->createOrRetrieveCustomer($user);

        return PaymentIntent::create([
            'customer' => $customer->id,
            'amount' => $amount,
            'currency' => 'usd',
            'metadata' => [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
        ]);
    }

    /**
     * Handle incoming Stripe webhooks.
     *
     * @param string $payload
     * @param string|null $signature
     * @return void
     * @throws Exception
     */
    public function handleWebhook(string $payload, ?string $signature): void
    {
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $signature, $webhookSecret);
        } catch (SignatureVerificationException $e) {
            throw new Exception('Invalid Stripe webhook signature.');
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentSucceeded($paymentIntent);
                break;
            // ... handle other event types
            default:
                Log::info('Received unhandled Stripe event type: ' . $event->type);
        }
    }

    /**
     * Handle the logic for a successful payment.
     *
     * @param PaymentIntent $paymentIntent
     */
    protected function handlePaymentSucceeded(PaymentIntent $paymentIntent): void
    {
        $metadata = $paymentIntent->metadata;
        $userId = $metadata->user_id;
        $courseId = $metadata->course_id;

        $user = User::find($userId);
        $course = Course::find($courseId);

        if ($user && $course) {
            try {
                // Enroll the user in the course
                $this->enrollmentService->enrollUserInCourse($user, $course);
                Log::info("Successfully enrolled User ID {$userId} in Course ID {$courseId} after Stripe payment.");
            } catch (Exception $e) {
                Log::error("Failed to enroll user after successful payment: " . $e->getMessage(), [
                    'user_id' => $userId,
                    'course_id' => $courseId,
                ]);
            }
        }
    }
}