<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Service;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get query parameters for filtering and pagination
        $filters = $request->only(['category','from','to', 'main_skills','additional_skills', 'price_to', 'working_hours']);
        $search = $request->input('search');
        $perPage = $request->input('per_page', 15); // Default to 15 items per page
        $page = $request->input('page', 1);

        // Build the query
        $query = Service::query();

        // Apply filters
        foreach ($filters as $key => $value) {
            if ($value !== null) {
                
                switch ($key) {
                    case 'category':
                        // where service->merchant type is equal to value
                        $query->whereHas('merchants', function ($q) use ($value) {
                            $q->where('type', $value);
                        });
                        break;
                    case 'from':
                        // the $value is id of language source like 27c92079-d297-44cc-a27c-9a0bbfeee390
                        // the value of language source is array of object [{"id": "27c92079-d297-44cc-a27c-9a0bbfeee390", "code": "id", "name": "Indonesian", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "b6bd8e7f-8764-4d54-a62e-25fb6332cc94", "code": "ar", "name": "Arabic", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "a169e68d-5e47-4a5b-9438-caa38ad7a7fb", "code": "cn", "name": "Chinese", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "dc7cbeb0-6af4-41ee-9981-e912728975f4", "code": "da", "name": "Danish", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "df5dfa10-148c-46d3-9f7f-68c7b39094bc", "code": "nl", "name": "Dutch", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "9624908a-be4c-4f36-ae98-3f59a32e65a6", "code": "en", "name": "English", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "ec04b685-26a0-4649-9bff-f7552f8ea8cf", "code": "fi", "name": "Finnish", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "efeef846-74e7-4d4d-8c37-29ded5d1edb6", "code": "fr", "name": "French", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "bc21c724-86b2-4128-9cc8-1e18a0dc07ce", "code": "el", "name": "Greek", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "3566bffd-7226-446e-97fd-0b016758c687", "code": "de", "name": "German", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "c2b8148a-1c9d-4c02-a391-95ecf2df3e60", "code": "he", "name": "Hebrew", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "577cfeb6-dc5d-45bd-9553-95f4f66bc7fd", "code": "hi", "name": "Hindi", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "b021d817-efb0-470e-ac67-61e9913be1ff", "code": "it", "name": "Italian", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "dcee12ce-f626-4089-9f95-a31fdacfef20", "code": "ja", "name": "Japanese", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "ddb59b12-be91-4e47-8fe7-1a8ed99cd86c", "code": "ko", "name": "Korean", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "109e05e9-e121-4479-a5ff-cb37f834815b", "code": "my", "name": "Malay", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "b709e1e3-688e-4199-bc61-ee2fe8685c7e", "code": "pl", "name": "Polish", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "b08ecda9-9172-466b-a61e-3231c2e28e7c", "code": "pt", "name": "Portuguese", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "e0e1313b-5854-41f5-bc9a-f0f1a3f34a08", "code": "ru", "name": "Russian", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "e879dcf0-9566-45f8-bb96-a763247db143", "code": "es", "name": "Spanish", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "b9955e74-4a46-4d2b-95ac-4b5a8614957b", "code": "sv", "name": "Swedish", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "e8880a9b-90fc-4490-9c3e-66f008753f80", "code": "th", "name": "Thai", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "61141c90-f903-433f-8094-b51ebbd3da7c", "code": "tr", "name": "Turkish", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}, {"id": "ebfff1c5-b20b-47cc-8140-940fa78014bb", "code": "vi", "name": "Vietnamese", "created_at": "2024-06-19T15:50:08.000000Z", "updated_at": "2024-06-19T15:50:08.000000Z"}]
                        // where service has language source equal to value
                        $query->whereJsonContains('language_sources', ['id' => $value]);
                        
                        break;

                    case 'to':
                        // where service has language destination equal to value
                        $query->whereJsonContains('language_destinations', ['id' => $value]);
                        break;

                    case 'main_skills':
                        // Split the value by comma
                        $mainSkills = explode(',', $value);
                    
                        // Filter services where merchant users have main skills equal to the value
                        $query->whereHas('merchants.users', function ($q) use ($mainSkills) {
                            foreach ($mainSkills as $skillId) {
                                $q->whereJsonContains('main_skills', ['id' => $skillId]);
                            }
                        });
                        break;

                    case 'additional_skills':
                        // split value by comma and where service->merchant->users has additional skills equal to value
                        $additionalSkills = explode(',', $value);
                        $query->whereHas('merchants.users', function ($q) use ($additionalSkills) {
                            foreach ($additionalSkills as $skillId) {
                                $q->whereJsonContains('additional_skills', ['id' => $skillId]);
                            }
                        });
                        break;

                    case 'price_to':
                        // where service price is less than or equal to value
                        $query->where('price', '<=', $value);
                        break;

                    case 'working_hours':
                        // where service working hours text equal to value
                        $query->where('working_hours', $value);
                        break;
                    default:
                        
                        $query->where($key, $value);
                        break;
                    }
                    
            }
        }

        // Apply search filter to service->merchant->user fullname 
        if ($search) {
            $query->whereHas('merchants.users', function ($q) use ($search) {
                $q->where('fullname', 'LIKE', "%{$search}%");
            });
        }

        // where merchant status is verified
        $query->whereHas('merchants', function ($q) {
            $q->where('status', 'verified');
        });
        
        // Eager load relationships
        $query->with(['merchants', 'merchants.users']);

        // Get the services
        $services = $query->paginate($perPage, ['*'], 'page', $page);

        // Add new field called languages get from service language sources and set to empty array if null
        $services->map(function ($service) {
            $service->languages = $service->language_sources ?? [];
            return $service;        });

        return ApiResponse::send(200, compact('services'), 'Services retrieved successfully.');
    }

    /**
     * Store a newly created service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'type' => 'required|string',
            'time_estimated' => 'required|numeric',
            'time_estimated_unit' => 'required|string',
            'desc' => 'nullable|string',

        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $service = Service::create($request->all());

        // add rel merchant service
        $merchant = Merchant::where('id', $request->merchant_id)->first();
        $service->merchants()->attach($merchant);

        return ApiResponse::send(201, compact('service'), 'Service created successfully.');
    }

    /**
     * Display the specified service.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        return ApiResponse::send(200, compact('service'), 'Service retrieved successfully.');
    }

    /**
     * Update the specified service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'type' => 'required|string',
            'time_estimated' => 'required|numeric',
            'time_estimated_unit' => 'required|string',
            'desc' => 'nullable|string',
            'language_sources' => 'required|array',
            'language_destinations' => 'required|array',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $service->fill($request->all())->save();

        return ApiResponse::send(200, compact('service'), 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return ApiResponse::send(200, null, 'Service deleted successfully.');
    }

    /**
     * Get the authenticated user's merchant services.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserMerchantServices()
    {
        $user = Auth::user();

        if (!$user) {
            return ApiResponse::send(401, null, 'User not authenticated.');
        }

        $merchants = $user->merchants;
        $services = [];

        foreach ($merchants as $merchant) {
            $services = array_merge($services, $merchant->services->toArray());
        }

        return ApiResponse::send(200, compact('services'), 'User merchant services retrieved successfully.');
    }

    /**
     * Get the current user's service using auth token.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMyService()
    {
        $user = Auth::user();

        if (!$user) {
            return ApiResponse::send(401, null, 'User not authenticated.');
        }

        $services = Service::whereHas('merchants', function ($query) use ($user) {
            $query->where('merchant_user_id', $user->id);
        })->get();

        return ApiResponse::send(200, compact('services'), 'Services retrieved successfully.');
    }

    /**
     * Update the authenticated user's service.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function updateMyService(Request $request, Service $service)
    {
        $user = Auth::user();

        if (!$user) {
            return ApiResponse::send(401, null, 'User not authenticated.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'type' => 'required|string',
            'time_estimated' => 'required|numeric',
            'time_estimated_unit' => 'required|string',
            'desc' => 'nullable|string',
            'language_sources' => 'required|array',
            'language_destinations' => 'required|array',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $service->fill($request->all())->save();

        return ApiResponse::send(200, compact('service'), 'Service updated successfully.');
    }
}
