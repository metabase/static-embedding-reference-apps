<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * Show the embedded dashboard for the signed-in user's customer.
     */
    public function show(Request $request): View
    {
        // Every user of one customer gets the same locked value, so they all
        // see that customer's data.
        $customer = $request->user()->customer;

        return view('dashboard', [
            'customer' => $customer,
            'metabaseSiteUrl' => config('services.metabase.site_url'),
            'token' => $this->signDashboardToken($customer),
        ]);
    }

    /**
     * Sign a guest embed token that locks the dashboard to one customer.
     *
     * The dashboard's Customer ID filter is set to "Locked" in Metabase, so
     * Metabase only accepts a value for it from a token signed with the
     * embedding secret key. Nothing in the browser can change or remove the
     * value.
     */
    private function signDashboardToken(Customer $customer): string
    {
        $payload = [
            'resource' => ['dashboard' => config('services.metabase.dashboard_id')],
            'params' => [
                // Keyed by the filter's slug. The value comes from the
                // signed-in user's customer, never from the request. Always
                // pass an ID: an empty list would turn the filter off and show
                // every customer's rows.
                'customer_id' => [$customer->id],
            ],
            'exp' => time() + 10 * 60, // 10 minute expiration
        ];

        return JWT::encode($payload, config('services.metabase.secret_key'), 'HS256');
    }
}
