<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'active',
            ]);

            // if user is a facilitator, create a merchant record
            if ($request->as == 'facilitator') {
                $user->merchants()->create([
                    // 'type' => 'translator',
                    'bank_id' => NULL,
                    'bank' => NULL,
                    'bank_account' => NULL,
                    'cv_url' => NULL,
                    'portfolios' => NULL,
                    'certificates' => NULL,
                    'rating' => 0,
                    'recomended_count' => 0,
                    'status' => 'pending',
                ]);
            }

            $token = JWTAuth::fromUser($user);

            DB::commit();

            return ApiResponse::send(201, compact('user', 'token'), 'User registered successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return ApiResponse::send(500, null, null, 'Registration failed.');
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return ApiResponse::send(401, null, null, 'Invalid email or password.');
        }

        // get only  user data :
        // email, fullname, photo, address, phone, is_admin, status
        $user = Auth::user()->only('email', 'fullname', 'photo', 'address', 'phone', 'is_admin', 'status', 'personal_description', 'main_skills', 'additional_skills');

        // check if user is a facilitator
        $merchant = Auth::user()->merchants->first();
        $user['is_facilitator'] = $merchant ? true : false;
        $user['merchant_status'] = $merchant->status ?? null;
        $user['is_first_time'] = $merchant->is_first_time ?? null;

        return ApiResponse::send(200, compact('token', 'user'), 'Login successful.');
    }

    public function profile()
    {
        $user = Auth::user();

        return ApiResponse::send(200, compact('user'), 'User profile retrieved successfully.');
    }

    public function updateMyProfile(){
        $user = Auth::user();

        $validator = Validator::make(request()->all(), [
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'photo' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'personal_description' => 'nullable|string',
            'main_skills' => 'nullable|array',
            'additional_skills' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $user->fill(request()->only([
            'fullname',
            'email',
            'photo',
            'phone',
            'address',
            'personal_description',
            'main_skills',
            'additional_skills',
        ]));

        $user->save();

        return ApiResponse::send(200, compact('user'), 'User profile updated successfully.');
    }

    public function updateMyPassword()
    {
        $user = Auth::user();

        $validator = Validator::make(request()->all(), [
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $user->password = Hash::make(request()->password);
        $user->save();

        return ApiResponse::send(200, null, 'Password updated successfully.');
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return ApiResponse::send(500, null, null, 'Error during authentication');
        }

        $authUser = User::createOrGetUser($user);
        $token = JWTAuth::fromUser($authUser);

        return ApiResponse::send(200, compact('authUser', 'token'), 'Authentication successful.');
    }
}
