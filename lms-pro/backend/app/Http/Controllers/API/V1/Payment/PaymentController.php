<?php

namespace App\Http\Controllers\API\V1\Payment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentIntentRequest; // To be created
use App\Http\Requests\Payment\StripeWebhookRequest; // To be created
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create a payment intent for a specific course.
     *
     * @param PaymentIntentRequest $request
     * @return JsonResponse
     */
    public function createPaymentIntent(PaymentIntentRequest $request): JsonResponse
    {
        try {
            $courseId = $request->input('course_id');
            $paymentMethod = $request->input('payment_method', 'stripe'); // Default to stripe

            $paymentIntent = $this->paymentService->createIntent(Auth::user(), $courseId, $paymentMethod);

            // The client_secret is used by the frontend (e.g., Stripe.js) to confirm the payment
            return ResponseHelper::success(['client_secret' => $paymentIntent->client_secret]);

        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to create payment intent: ' . $e->getMessage());
        }
    }

    /**
     * Handle incoming webhooks from payment providers.
     *
     * @param StripeWebhookRequest $request // Example for Stripe
     * @return JsonResponse
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        // The service will handle verifying the webhook signature and processing the event
        $this->paymentService->handleWebhook($request->getContent(), $request->header('Stripe-Signature'));

        return ResponseHelper::success(null, 'Webhook received.', 200);
    }
}