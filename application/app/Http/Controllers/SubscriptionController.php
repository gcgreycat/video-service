<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'package_id' => 'required|exists:packages,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }
        /** @var User $user */
        $user = auth()->user();
        try {
            $this->authorize('create', Subscription::class);
        } catch (AuthorizationException $e) {
            return response()->json(['errors' => 'Not authorized'], 401);
        }

        $subscription = $user->subscribeOnPackage($data['package_id']);

        return response()->json(['message' => 'success', 'subscription' => $subscription], 201);
    }

    public function edit(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'subscription_id' => 'required|exists:subscriptions,id',
            'package_id' => 'required|exists:packages,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        /** @var Subscription $subscription */
        $subscription = Subscription::find($data['subscription_id']);
        try {
            $this->authorize('update', $subscription);
        } catch (AuthorizationException $e) {
            return response()->json(['errors' => 'Not authorized'], 401);
        }

        $package = Package::find($data['package_id']);
        $subscription->package()->associate($package);
        $subscription->save();

        return response()->json(['message' => 'success', 'subscription' => $subscription]);
    }

    public function delete(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'subscription_id' => 'required|exists:subscriptions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        /** @var Subscription $subscription */
        $subscription = Subscription::find($data['subscription_id']);
        try {
            $this->authorize('delete', $subscription);
        } catch (AuthorizationException $e) {
            return response()->json(['errors' => 'Not authorized'], 401);
        }

        $subscription->delete();

        return response()->json(['message' => 'success']);
    }
}
