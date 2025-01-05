<?php

namespace App\Services;

use App\Models\Nursery;
use App\Models\SubscriptionPlan;
use Stripe\StripeClient;
use Exception;

class StripeService
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function createCustomer(Nursery $nursery): string
    {
        $customer = $this->stripe->customers->create([
            'email' => $nursery->email,
            'name' => $nursery->name,
            'metadata' => [
                'nursery_id' => $nursery->id
            ]
        ]);

        return $customer->id;
    }

    public function createSubscription(Nursery $nursery, SubscriptionPlan $plan): array
    {
        if (!$nursery->stripe_customer_id) {
            throw new Exception('Nursery has no Stripe customer ID');
        }

        $subscription = $this->stripe->subscriptions->create([
            'customer' => $nursery->stripe_customer_id,
            'items' => [
                ['price' => $plan->stripe_price_id],
            ],
            'metadata' => [
                'nursery_id' => $nursery->id,
                'plan_id' => $plan->id
            ]
        ]);

        return [
            'subscription_id' => $subscription->id,
            'current_period_end' => $subscription->current_period_end,
        ];
    }

    public function cancelSubscription(string $subscriptionId): void
    {
        $this->stripe->subscriptions->cancel($subscriptionId);
    }

    public function createSetupIntent(Nursery $nursery): string
    {
        $setupIntent = $this->stripe->setupIntents->create([
            'customer' => $nursery->stripe_customer_id,
            'payment_method_types' => ['card'],
        ]);

        return $setupIntent->client_secret;
    }
}
