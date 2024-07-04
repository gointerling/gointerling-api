<?php

namespace App\Http\Controllers;

use App\Models\AdvertisementPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiResponse;

class AdvertisementPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = AdvertisementPackage::orderBy('created_at', 'desc')->get();
        return ApiResponse::send(200, compact('packages'), 'Advertisement packages retrieved successfully.');
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
            'duration' => 'required|integer|min:1',
            'size_x' => 'required|integer|min:1',
            'size_y' => 'required|integer|min:1',
            'priority' => 'required|in:1,2,3',
            'route_json' => 'required|array',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, 'Validation Error', $validator->errors());
        }

        $package = AdvertisementPackage::create($request->all());

        return ApiResponse::send(201, compact('package'), 'Advertisement package created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdvertisementPackage  $advertisementPackage
     * @return \Illuminate\Http\Response
     */
    public function show(AdvertisementPackage $advertisementPackage)
    {
        return ApiResponse::send(200, compact('advertisementPackage'), 'Advertisement package retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdvertisementPackage  $advertisementPackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdvertisementPackage $advertisementPackage)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'duration' => 'sometimes|integer|min:1',
            'size_x' => 'sometimes|integer|min:1',
            'size_y' => 'sometimes|integer|min:1',
            'priority' => 'sometimes|in:1,2,3',
            'route_json' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, 'Validation Error', $validator->errors());
        }

        $advertisementPackage->update($request->all());

        return ApiResponse::send(200, compact('advertisementPackage'), 'Advertisement package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdvertisementPackage  $advertisementPackage
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvertisementPackage $advertisementPackage)
    {
        $advertisementPackage->delete();

        return ApiResponse::send(204, null, 'Advertisement package deleted successfully.');
    }
}
