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
        $search = $request->query('search', '');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $status = $request->query('status', '');
        $sort = $request->query('sort', 'latest');

        // Query to get all users who are merchants (not admins) and have a merchant record
        $query = User::where('is_admin', false)
            ->whereHas('merchants', function ($q) use ($status) {
                if ($status) {
                    $q->where('status', $status);
                }
            })
            ->with(['merchants' => function ($q) use ($status) {
                if ($status) {
                    $q->where('status', $status);
                }
            }]);

        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Apply sorting
        if ($sort === 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            // order by rel merchant status
            $query->orderBy('status', 'desc');
        }

        // Paginate results
        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return ApiResponse::send(200, $users, 'Merchants retrieved successfully.');
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
    
    public function showMerchantDetail(Request $request, $merchant_id) {
        $merchant = Merchant::where('id', $merchant_id)->first();
        if (!$merchant) {
            return ApiResponse::send(404, null, 'Merchant not found.');
        }

        // if merchant have service also load the service
        $merchant->load('services');
        $merchant->load('users');

        return ApiResponse::send(200, compact('merchant'), 'Merchant detail retrieved successfully.');
    }

    
    public function updateMerchantStatus(Request $request, User $user) {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,verified,pending,inactive',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        if (!$user->is_admin) {
            // rel user with merchant
            $merchant = $user->merchants->first();
            $merchant->update([
                'status' => $request->status,
            ]);
        }

        return ApiResponse::send(200, compact('user'), 'Merchant status updated successfully.');
    }

    public function updateMyMerchant(Request $request) {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:translator,interpreter,both',
            'bank' => 'required|string',
            'bank_account' => 'required|string',
            'cv_url' => 'nullable|string',
            'merchant_id'=> 'nullable|string',
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

            $merchant_type = $merchant['type'];

            $merchant->update([
                'type' => $request->type,
                'bank_id' => $request->bank_id,
                'bank' => $request->bank,
                'bank_account' => $request->bank_account,
                'status' => $request->status,
                'cv_url' => $request->cv_url,
                'portfolios' => json_encode($request->portfolios),
                'certificates' => json_encode($request->certificates),
                'status' => $merchant->status,  
            ]);

            if($merchant_type != $request->type) {
                // set the merchant status to pending
                $merchant->update([
                    'status' => 'pending',
                ]);
            }

            // If the user has added bank account or cv_url, then it is not the first time anymore
            if($request->has('bank_account')) {
                $merchant->is_first_time = false;
            }

            if($request->has('merchant_id')) {
                $merchant->services()->create([
                    'name' => 'Standard',
                    'price' => '50000',
                    'type' => 'standard',
                    'time_estimated' => 1,
                    'time_estimated_unit' => 'days',
                    'desc' => 'Standard service',
                    'working_hours' => 'Anytime',
                ]);
            }

            // save the merchant
            $merchant->save();
        }

        return ApiResponse::send(200, compact('user'), 'Merchant user updated successfully.');
    }

    public function updateMyMerchantStatus(Request $request) {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,verified,pending,inactive',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        if (!$user->is_admin) {
            // rel user with merchant
            
            $merchant = $user->merchants->first();
            $merchant->update([
                'status' => $request->status,
            ]);
        }

        return ApiResponse::send(200, compact('user'), 'Merchant status updated successfully.');
    }
    

    public function updateMyMerchantFile(Request $request) {
        $validator = Validator::make($request->all(), [
            'cv_url' => 'string',
            'portfolios' => 'array',
            'certificates' => 'array',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        // Get the authenticated user
        $user = auth()->user();

        if (!$user->is_admin) {
            // rel user with merchant
            $merchant = $user->merchants->first();

            $merchant->update([
                'cv_url' => $request->cv_url,
                'portfolios' => json_encode($request->portfolios),
                'certificates' => json_encode($request->certificates),
            ]);
        }

        return ApiResponse::send(200, compact('user'), 'Merchant file updated successfully.');
    }

    
}
