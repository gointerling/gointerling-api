<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    /**
     * Get the first setting.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSetting()
    {
        $setting = Setting::first();

        if ($setting) {
            return ApiResponse::send(200, compact('setting'), 'Setting retrieved successfully.');
        }

        return ApiResponse::send(404, null, 'No settings found');
    }

    /**
     * Update the first setting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'logo' => 'sometimes|string|max:255',
            'bank' => 'sometimes|string|max:255',
            'bank_account' => 'sometimes|string|max:255',
            'bank_account_name' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $setting = Setting::first();

        if (!$setting) {
            return ApiResponse::send(404, null, 'No settings found');
        }

        $setting->update($request->only(['title', 'logo', 'bank', 'bank_account', 'bank_account_name']));

        return ApiResponse::send(200, compact('setting'), 'Setting updated successfully.');
    }

    public function getSettingBank()
    {
        $setting = Setting::first()->only(['bank', 'bank_account', 'bank_account_name']);

        if ($setting) {
            return ApiResponse::send(200, compact('setting'), 'Setting retrieved successfully.');
        }

        return ApiResponse::send(404, null, 'No settings found');
    }
}
