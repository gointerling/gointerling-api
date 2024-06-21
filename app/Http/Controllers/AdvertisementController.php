<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $advertisements = $query->paginate($perPage, ['*'], 'page', $page);

        return ApiResponse::success($advertisements);
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
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'package_id' => 'required|exists:packages,id',
            'image_url' => 'nullable|string|max:255',
            'cta_link' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $advertisement = Advertisement::create($request->all());
        return ApiResponse::success($advertisement, 'Advertisement created successfully', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function show(Advertisement $advertisement)
    {
        return ApiResponse::success($advertisement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Advertisement $advertisement)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'package_id' => 'required|exists:packages,id',
            'image_url' => 'nullable|string|max:255',
            'cta_link' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $advertisement->update($request->all());
        return ApiResponse::success($advertisement, 'Advertisement updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertisement $advertisement)
    {
        $advertisement->delete();
        return ApiResponse::success(null, 'Advertisement deleted successfully', 204);
    }
}
