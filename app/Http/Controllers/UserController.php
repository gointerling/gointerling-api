<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get query parameters for filtering and pagination
        $filters = $request->only(['fullname', 'email', 'status', 'isAdmin']);
        $search = $request->input('search');
        $perPage = $request->input('per_page', 15); // Default to 15 items per page

        // Build the query
        $query = User::query();

        // Apply filters
        foreach ($filters as $key => $value) {
            if ($value !== null) {
                $query->where($key, $value);
            }
        }

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }

        // Paginate the results
        $users = $query->paginate($perPage);

        return ApiResponse::send(200, compact('users'), 'Users retrieved successfully.');
    }

    /**
     * Store a newly created user in storage.
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
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return ApiResponse::send(201, compact('user'), 'User created successfully.');
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return ApiResponse::send(200, compact('user'), 'User retrieved successfully.');
    }

    /**
     * Update the specified user in storage.
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
            'status' => 'required|in:active,verified,pending,inactive',        
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        // fields to update
        $user->fill($request->only([
            'fullname',
            'email',
            'phone',
            'photo',
            'address',
            'credential_id',
            'status'
        ]))->save();

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return ApiResponse::send(200, compact('user'), 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return ApiResponse::send(200, null, 'User deleted successfully.');
    }

    public function updatePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'nullable|string|min:6',
        ]);

        $oldPassword = bcrypt($request->old_password);
        $isPasswordMatch = Hash::check($oldPassword, $user->password);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        if (!$isPasswordMatch) {
            return ApiResponse::send(422, null, 'Old password is incorrect.');
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return ApiResponse::send(200, compact('user'), 'User updated successfully.');
    }

    public function updateRole(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'is_admin' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $user->is_admin = $request->is_admin;
        $user->save();

        return ApiResponse::send(200, compact('user'), 'User updated successfully.');
    }
}
