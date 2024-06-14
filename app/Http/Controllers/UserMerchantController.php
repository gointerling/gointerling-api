<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Validator;

class UserMerchantController extends Controller
{
    /**
     * Display a listing of the users who are merchants.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        if ($filter === 'merchant') {
            $users = User::where('is_admin', false)->has('merchants')->get();
        } else {
            $users = User::where('is_admin', false)->get();
        }

        if ($users->isEmpty()) {
            return ApiResponse::send(404, null, 'No merchants found.');
        }

        return ApiResponse::send(200, compact('users'), 'Merchants retrieved successfully.');
    }

    /**
     * Store a newly created merchant user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'is_admin' => 'required|boolean',
            'merchant_type' => 'required|in:translator,interpreter,both',
            'bank_id' => 'required|string',
            'bank' => 'required|string',
            'bank_account' => 'required|string',
            'status' => 'required|in:active,verified,pending,inactive',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => $request->is_admin,
        ]);

        if (!$request->is_admin) {
            Merchant::create([
                'user_id' => $user->id,
                'type' => $request->merchant_type,
                'bank_id' => $request->bank_id,
                'bank' => $request->bank,
                'bank_account' => $request->bank_account,
                'status' => $request->status,
                // Add other merchant fields as needed
            ]);
        }

        return ApiResponse::send(201, compact('user'), 'Merchant user created successfully.');
    }

    /**
     * Display the specified merchant user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (!$user->is_admin) {
            $user->load('merchants');
        }
        return ApiResponse::send(200, compact('user'), 'Merchant user retrieved successfully.');
    }

    /**
     * Update the specified merchant user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'is_admin' => 'required|boolean',
            'merchant_type' => 'required_if:is_admin,false|in:translator,interpreter,both',
            'bank_id' => 'required_if:is_admin,false|string',
            'bank' => 'required_if:is_admin,false|string',
            'bank_account' => 'required_if:is_admin,false|string',
            'status' => 'required_if:is_admin,false|in:active,verified,pending,inactive',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->is_admin = $request->is_admin;
        $user->save();

        if (!$user->is_admin) {
            $merchant = Merchant::where('user_id', $user->id)->first();
            if ($merchant) {
                $merchant->type = $request->merchant_type;
                $merchant->bank_id = $request->bank_id;
                $merchant->bank = $request->bank;
                $merchant->bank_account = $request->bank_account;
                $merchant->status = $request->status;
                $merchant->save();
            }
        }

        return ApiResponse::send(200, compact('user'), 'Merchant user updated successfully.');
    }

    /**
     * Remove the specified merchant user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (!$user->is_admin) {
            $merchant = Merchant::where('user_id', $user->id)->first();
            if ($merchant) {
                $merchant->delete();
            }
        }

        $user->delete();

        return ApiResponse::send(200, null, 'Merchant user deleted successfully.');
    }
}
