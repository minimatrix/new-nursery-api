<?php

namespace App\Http\Controllers;

use App\Models\Nursery;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Exception;

class WebhookController extends Controller
{
    public function handleStripeWebhook(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret')
            );
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        $this->handleWebhookEvent($event);

        return response('Webhook handled', 200);
    }

    private function handleWebhookEvent(Event $event): void
    {
        switch ($event->type) {
            case 'customer.subscription.deleted':
                $this->handleSubscriptionCancelled($event->data->object);
                break;
            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;
            case 'invoice.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
        }
    }

    private function handleSubscriptionCancelled($subscription): void
    {
        $nursery = Nursery::where('stripe_subscription_id', $subscription->id)->first();
        if ($nursery) {
            $nursery->update([
                'subscription_ends_at' => now(),
                'stripe_subscription_id' => null,
                'subscription_plan_id' => null
            ]);
        }
    }

    private function handleSubscriptionUpdated($subscription): void
    {
        $nursery = Nursery::where('stripe_subscription_id', $subscription->id)->first();
        if ($nursery) {
            $nursery->update([
                'subscription_ends_at' => now()->addSeconds($subscription->current_period_end),
            ]);
        }
    }

    private function handlePaymentFailed($invoice): void
    {
        $nursery = Nursery::where('stripe_customer_id', $invoice->customer)->first();
        if ($nursery) {
            // You might want to notify the nursery about the failed payment
            // Or implement your own logic here
        }
    }
}
