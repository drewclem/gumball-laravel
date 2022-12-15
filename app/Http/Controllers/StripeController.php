<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class StripeController extends Controller
{
    public function createSubscription(Request $request) {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $session = $stripe->checkout->sessions->create([
            'customer' => $request->customerId,
            'success_url' => env('APP_URL') . '/settings',
            'cancel_url' => env('APP_URL') . '/settings',
            'line_items' => [
                [
                    'price' => env('STRIPE_PRODUCT_ID'),
                    'quantity' => 1,
                ]
            ],
            'mode' => 'subscription',
            'payment_method_types' => ['card']
        ]);

        return $session;
    }

    public function manageSubscription(Request $request) {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $session = $stripe->billingPortal->sessions->create([
            'customer' => $request->customerId,
            'return_url' => env('APP_URL') . '/settings',
          ]);

        // return redirect($session->url);
    }
}
