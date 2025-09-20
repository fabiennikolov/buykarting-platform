<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{

    /**
     * Display subscription plans and current subscription.
     */
    public function index(): Response
    {
        $user = auth()->user();
        $currentSubscription = $user->subscription;

        $plans = [
            'freemium' => [
                'name' => 'Freemium',
                'listings_limit' => 3,
                'price' => 0,
                'features' => [
                    '3 free listings',
                    'Basic support',
                    'Standard placement',
                ],
            ],
            'basic' => [
                'name' => 'Basic',
                'listings_limit' => 50,
                'price' => 9.99,
                'features' => [
                    '50 listings per month',
                    'Priority support',
                    'Enhanced placement',
                    'Basic analytics',
                ],
            ],
            'premium' => [
                'name' => 'Premium',
                'listings_limit' => 999999,
                'price' => 29.99,
                'features' => [
                    'Unlimited listings',
                    'VIP support',
                    'Featured placement',
                    'Advanced analytics',
                    'Priority customer support',
                ],
            ],
        ];

        return Inertia::render('Subscriptions/Index', [
            'currentSubscription' => $currentSubscription,
            'plans' => $plans,
            'remainingListings' => $user->remainingListings(),
            'activeListingsCount' => $user->activeListings()->count(),
        ]);
    }

    /**
     * Upgrade to a specific plan.
     */
    public function upgrade(Request $request): RedirectResponse
    {
        $request->validate([
            'plan' => ['required', 'string', 'in:basic,premium'],
        ]);

        $user = auth()->user();
        $currentSubscription = $user->subscription;

        // Prevent downgrading from premium to basic
        if ($currentSubscription->plan_name === 'Premium' && $request->plan === 'basic') {
            return redirect()->back()
                ->with('error', 'You cannot downgrade from Premium to Basic. Please contact support.');
        }

        // Create new subscription based on plan
        $planData = [
            'basic' => [
                'plan_name' => 'Basic',
                'listings_limit' => 50,
            ],
            'premium' => [
                'plan_name' => 'Premium',
                'listings_limit' => 999999,
            ],
        ];

        $newPlanData = $planData[$request->plan];

        // End current subscription
        $currentSubscription->update(['ends_at' => Carbon::now()]);

        // Create new subscription
        Subscription::create([
            'user_id' => $user->id,
            'plan_name' => $newPlanData['plan_name'],
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addMonth(),
            'listings_limit' => $newPlanData['listings_limit'],
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', "Successfully upgraded to {$newPlanData['plan_name']} plan!");
    }

    /**
     * Cancel subscription (downgrade to freemium).
     */
    public function cancel(): RedirectResponse
    {
        $user = auth()->user();
        $currentSubscription = $user->subscription;

        if ($currentSubscription->plan_name === 'Freemium') {
            return redirect()->back()
                ->with('error', 'You are already on the Freemium plan.');
        }

        // End current subscription
        $currentSubscription->update(['ends_at' => Carbon::now()]);

        // Create freemium subscription
        Subscription::createFreemium($user);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Your subscription has been cancelled. You are now on the Freemium plan.');
    }

    /**
     * Show upgrade confirmation page.
     */
    public function showUpgrade(string $plan): Response
    {
        if (!in_array($plan, ['basic', 'premium'])) {
            abort(404);
        }

        $user = auth()->user();
        $currentSubscription = $user->subscription;

        $planDetails = [
            'basic' => [
                'name' => 'Basic',
                'listings_limit' => 50,
                'price' => 9.99,
            ],
            'premium' => [
                'name' => 'Premium',
                'listings_limit' => 999999,
                'price' => 29.99,
            ],
        ];

        return Inertia::render('Subscriptions/Upgrade', [
            'plan' => $planDetails[$plan],
            'currentSubscription' => $currentSubscription,
        ]);
    }
}
