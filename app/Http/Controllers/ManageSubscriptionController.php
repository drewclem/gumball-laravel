<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ManageSubscriptionController extends Controller
{
    public function __invoke(Request $request)
    {
        return $request->user()
            ->newSubscription('Early Bird', env('STRIPE_PRODUCT_ID'))
            ->checkout([
                'success_url' => route('collections.index'),
                'cancel_url' => route('settings')
            ]);
    }
}
