<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiResponse;

class SubscriptionPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = SubscriptionPackage::orderBy('created_at', 'asc')->get();
        return ApiResponse::send(200, compact('packages'), 'Subscription packages retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'rule_json' => 'required|array',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, 'Validation Error', $validator->errors());
        }

        // Retrieve values from rule_json array
        $rules = collect($request->rule_json)->keyBy('name');

        $is_reviewed = $rules->get('is_reviewed')['value'] ?? false;
        $is_advertised = $rules->get('is_advertised')['value'] ?? false;
        $is_free_shipping = $rules->get('is_free_shipping')['value'] ?? false;

        // Create the subscription package
        $package = SubscriptionPackage::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'duration' => $request->duration,
            'price' => $request->price,
            'is_reviewed' => $is_reviewed,
            'is_advertised' => $is_advertised,
            'is_free_shipping' => $is_free_shipping,
        ]);

        return ApiResponse::send(201, compact('package'), 'Subscription package created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubscriptionPackage  $subscriptionPackage
     * @return \Illuminate\Http\Response
     */
    public function show(SubscriptionPackage $subscriptionPackage)
    {
        return ApiResponse::send(200, compact('subscriptionPackage'), 'Subscription package retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubscriptionPackage  $subscriptionPackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubscriptionPackage $subscriptionPackage)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'desc' => 'sometimes|string',
            'duration' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0',
            'rule_json' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, 'Validation Error', $validator->errors());
        }

        // Retrieve values from rule_json array if it exists
        $rules = collect($request->rule_json)->keyBy('name');

        $is_reviewed = $rules->get('is_reviewed')['value'] ?? $subscriptionPackage->is_reviewed;
        $is_advertised = $rules->get('is_advertised')['value'] ?? $subscriptionPackage->is_advertised;
        $is_free_shipping = $rules->get('is_free_shipping')['value'] ?? $subscriptionPackage->is_free_shipping;

        // Update the subscription package
        $subscriptionPackage->update([
            'name' => $request->name ?? $subscriptionPackage->name,
            'desc' => $request->desc ?? $subscriptionPackage->desc,
            'duration' => $request->duration ?? $subscriptionPackage->duration,
            'price' => $request->price ?? $subscriptionPackage->price,
            'is_reviewed' => $is_reviewed,
            'is_advertised' => $is_advertised,
            'is_free_shipping' => $is_free_shipping,
        ]);

        return ApiResponse::send(200, compact('subscriptionPackage'), 'Subscription package updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubscriptionPackage  $subscriptionPackage
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubscriptionPackage $subscriptionPackage)
    {
        $subscriptionPackage->delete();
        return ApiResponse::send(200, null, 'Subscription package deleted successfully.');
    }
}
