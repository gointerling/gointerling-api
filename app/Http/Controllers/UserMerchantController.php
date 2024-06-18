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

        // Get all users who are merchants (not admins) and have a merchant record and also relationship with merchant
        $users = User::where('is_admin', false)
            ->whereHas('merchants')
            ->with('merchants')
            ->get();

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
                'rating' => $request->rating,
                'recomended_count' => $request->recomended_count,
                'cv_url' => null,
                'portfolios' => json_encode([]),
                'certificates' => json_encode([]),
                'is_first_time' => true,
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
    public function update(Request $request, User $user) {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'status' => 'required|in:active,verified,pending,inactive',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $user->update([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        if (!$user->is_admin) {
            $merchant = Merchant::where('user_id', $user->id)->first();
            $merchant->update([
                'type' => $request->merchant_type,
                'bank_id' => $request->bank_id,
                'bank' => $request->bank,
                'bank_account' => $request->bank_account,
                'status' => $request->status,
                'cv_url' => $request->cv_url,
                'portfolios' => json_encode($request->portfolios),
                'certificates' => json_encode($request->certificates),
            ]);
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

    /**
     * Display a listing of the merchants user detail.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function showMerchantDetail(Request $request, User $user) {
        $merchant = Merchant::where('user_id', $user->id)->first();
        if (!$merchant) {
            return ApiResponse::send(404, null, 'Merchant not found.');
        }

        return ApiResponse::send(200, compact('merchant'), 'Merchant detail retrieved successfully.');
    }

    public function updateMyMerchant(Request $request) {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:translator,interpreter,both',
            'bank' => 'required|string',
            'bank_account' => 'required|string',
            'cv_url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        // Get the authenticated user
        $user = auth()->user();

        // return user json
        $merchant_id = $user->load('merchants')->merchants[0]->id;

        if (!$user->is_admin) {
            // rel user with merchant
            $merchant = Merchant::where('id', $merchant_id)->first();

            $merchant->update([
                'type' => $request->type,
                'bank_id' => $request->bank_id,
                'bank' => $request->bank,
                'bank_account' => $request->bank_account,
                'status' => $request->status,
                'cv_url' => $request->cv_url,
                'portfolios' => json_encode($request->portfolios),
                'certificates' => json_encode($request->certificates),
            ]);

            // If the user has added bank account or cv_url, then it is not the first time anymore
            if($request->has('bank_account')) {
                $merchant->is_first_time = false;
            }

            // save the merchant
            $merchant->save();
        }

        return ApiResponse::send(200, compact('user'), 'Merchant user updated successfully.');
    }

    
}
