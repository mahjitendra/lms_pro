<?php

namespace App\Services\Payment;

use App\Models\Course\Course;
use App\Models\User;
use Exception;

class PaymentService
{
    protected $stripeService;
    protected $payPalService;

    public function __construct(StripeService $stripeService, PayPalService $payPalService)
    {
        $this->stripeService = $stripeService;
        $this->payPalService = $payPalService;
    }

    /**
     * Create a payment intent using the specified payment provider.
     *
     * @param User $user
     * @param int $courseId
     * @param string $paymentMethod
     * @return mixed The payment intent object from the provider (e.g., Stripe's PaymentIntent).
     * @throws Exception
     */
    public function createIntent(User $user, int $courseId, string $paymentMethod)
    {
        $course = Course::findOrFail($courseId);
        $amount = $course->price; // Price should be in cents for Stripe

        switch ($paymentMethod) {
            case 'stripe':
                return $this->stripeService->createPaymentIntent($user, $amount, $course);
            case 'paypal':
                // return $this->payPalService->createOrder($user, $amount, $course);
                throw new Exception("PayPal payment provider is not yet implemented.");
            default:
                throw new Exception("Invalid payment provider selected.");
        }
    }

    /**
     * Handle an incoming webhook from a payment provider.
     *
     * @param string $payload
     * @param string|null $signature
     * @param string $provider
     * @return void
     * @throws Exception
     */
    public function handleWebhook(string $payload, ?string $signature, string $provider = 'stripe')
    {
        switch ($provider) {
            case 'stripe':
                $this->stripeService->handleWebhook($payload, $signature);
                break;
            // case 'paypal':
            //     $this->payPalService->handleWebhook($payload);
            //     break;
            default:
                throw new Exception("Webhook handler for provider '{$provider}' not found.");
        }
    }
}