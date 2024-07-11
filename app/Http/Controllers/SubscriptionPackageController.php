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
            'is_reviewed' => 'required|boolean',
            'is_advertised' => 'required|boolean',
            'is_free_shipped' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, 'Validation Error', $validator->errors());
        }

        $package = SubscriptionPackage::create($request->all());

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
            'is_reviewed' => 'sometimes|boolean',
            'is_advertised' => 'sometimes|boolean',
            'is_free_shipped' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, 'Validation Error', $validator->errors());
        }

        $subscriptionPackage->update($request->all());

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

        return ApiResponse::send(204, null, 'Subscription package deleted successfully.');
    }
}
