<?php

namespace App\Http\Controllers\API\V1\Payment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StoreSubscriptionRequest; // To be created
use App\Services\Payment\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Get a list of available subscription plans.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $plans = $this->subscriptionService->getAvailablePlans();
        return ResponseHelper::success($plans);
    }

    /**
     * Create a new subscription for the authenticated user.
     *
     * @param StoreSubscriptionRequest $request
     * @return JsonResponse
     */
    public function store(StoreSubscriptionRequest $request): JsonResponse
    {
        try {
            $planId = $request->input('plan_id');
            $paymentMethodId = $request->input('payment_method_id'); // e.g., from Stripe.js

            $subscription = $this->subscriptionService->createSubscription(Auth::user(), $planId, $paymentMethodId);

            return ResponseHelper::success($subscription, 'Subscription created successfully.', 201);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to create subscription: ' . $e->getMessage());
        }
    }

    /**
     * Show the authenticated user's current subscription status.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $subscription = $this->subscriptionService->getSubscriptionForUser(Auth::user());

        if (!$subscription) {
            return ResponseHelper::notFound('No active subscription found.');
        }

        return ResponseHelper::success($subscription);
    }

    /**
     * Cancel the authenticated user's subscription.
     *
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {
        try {
            $this->subscriptionService->cancelSubscription(Auth::user());
            return ResponseHelper::success(null, 'Subscription cancelled successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to cancel subscription: ' . $e->getMessage());
        }
    }
}