<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdvertisementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $query = Advertisement::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('tagline', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }

        $query->with('package');

        // sort by latest date
        $query->orderBy('created_at', 'desc');
        
        // only get the advertisements that are not expired and active
        // $query->where('valid_until_date', '>=', now())
        //     ->where('status', 'active');

        $advertisements = $query->paginate($perPage, ['*'], 'page', $page);

        return ApiResponse::send(200, compact('advertisements'), 'Advertisements retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'package_id' => 'required|exists:advertisement_packages,id',
            'image_url' => 'nullable|string|max:255',
            'cta_link' => 'nullable|string|max:255',
            'valid_until_date' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $advertisement = Advertisement::create($request->all());

        // return the advertisement with status code 201 and limit only id on response
        $ads_id = $advertisement->id;
        
        return ApiResponse::send(201, compact('ads_id'), 'Advertisement created successfully.');
    }

    public function show(Advertisement $advertisement)
    {
        return ApiResponse::send(200, compact('advertisement'), 'Advertisement retrieved successfully.');
    }

    public function update(Request $request, Advertisement $advertisement)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'package_id' => 'required|exists:advertisement_packages,id',
            'image_url' => 'nullable|string|max:255',
            'cta_link' => 'nullable|string|max:255',
            'valid_until_date' => 'nullable|date',
            'payment_file_url' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',

        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $advertisement->update($request->all());
        return ApiResponse::send(200, compact('advertisement'), 'Advertisement updated successfully.');
    }

    public function destroy(Advertisement $advertisement)
    {
        $advertisement->delete();
        return ApiResponse::send(204, null, 'Advertisement deleted successfully.');
    }

    public function display()
    {
        $advertisements = Advertisement::with('package')->get();

        if ($advertisements->isEmpty()) {
            return ApiResponse::send(404, null, 'No advertisements available.');
        }

        $advertisementPool = [];

        foreach ($advertisements as $advertisement) {
            $packagePriority = $advertisement->package->priority;

            switch ($packagePriority) {
                case 1:
                    $advertisementPool = array_merge($advertisementPool, array_fill(0, 50, $advertisement));
                    break;
                case 2:
                    $advertisementPool = array_merge($advertisementPool, array_fill(0, 30, $advertisement));
                    break;
                case 3:
                    $advertisementPool = array_merge($advertisementPool, array_fill(0, 20, $advertisement));
                    break;
            }
        }

        // only get the advertisements that are not expired and active
        $advertisementPool = array_filter($advertisementPool, function ($advertisement) {
            return $advertisement->valid_until_date >= now() && $advertisement->status === 'active';
        });

        $selectedAdvertisement = $advertisementPool[array_rand($advertisementPool)];

        return ApiResponse::send(200, compact('selectedAdvertisement'), 'Advertisement retrieved successfully.');
    }

    public function getMyAds(Request $request){
        $search = $request->query('search', '');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $status = $request->query('status', '');
        $user = Auth::user();

        if (!$user) {
            return ApiResponse::send(401, null, 'Unauthenticated.');
        }

        $query = Advertisement::where('user_id', $user->id);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('tagline', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }

        if ($status) {
            $query->where('status', $status);
        }

        // rel to advertisement package
        $query->with('package');

        $advertisements = $query->paginate($perPage, ['*'], 'page', $page);

        return ApiResponse::send(200, compact('advertisements'), 'Advertisements retrieved successfully.');
    }

    public function storeMyAds(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return ApiResponse::send(401, null, 'Unauthenticated.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'package_id' => 'required|exists:advertisement_packages,id',
            'image_url' => 'nullable|string|max:255',
            'cta_link' => 'nullable|string|max:255',
            'valid_until_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $adData = $request->all();
        $adData['user_id'] = $user->id;
        $adData['status'] = 'pending';

        $advertisement = Advertisement::create($adData);
        return ApiResponse::send(201, compact('advertisement'), 'Advertisement created successfully.');
    }

    public function updateMyAdsPayment(Request $request, Advertisement $advertisement)
    {
        $user = Auth::user();

        if (!$user) {
            return ApiResponse::send(401, null, 'Unauthenticated.');
        }

        $validator = Validator::make($request->all(), [
            'payment_file_url' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $advertisement->update([
            'payment_file_url' => $request->payment_file_url,
            'status' => 'pending',
        ]);

        return ApiResponse::send(200, compact('advertisement'), 'Advertisement payment updated successfully.');
    }

    public function setAdsStatus(Request $request, Advertisement $advertisement)
    {
        $user = Auth::user();

        if (!$user) {
            return ApiResponse::send(401, null, 'Unauthenticated.');
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        // set valid until date to duration months from now if status is active
        $validUntilDate = $request->status === 'active' ? now()->addMonths($advertisement->package->duration) : null;

        $advertisement->update([
            'status' => $request->status,
            'valid_until_date' => $validUntilDate,
        ]);

        return ApiResponse::send(200, compact('advertisement'), 'Advertisement status updated successfully.');
    }

}