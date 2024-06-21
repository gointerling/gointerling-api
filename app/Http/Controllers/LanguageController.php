<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;

class LanguageController extends Controller
{
    // Get all languages with optional pagination
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $query = Language::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
        }

        // sort by name
        $query->orderBy('name', 'asc');

        $languages = $query->paginate($perPage, ['*'], 'page', $page);

        if ($languages->isEmpty()) {
            return ApiResponse::send(404, null, 'No languages found.');
        }

        return ApiResponse::send(200, $languages, 'Languages retrieved successfully.');
    }

    // Get a single language by ID
    public function show($id)
    {
        $language = Language::find($id);

        if (!$language) {
            return ApiResponse::send(404, null, 'Language not found.');
        }

        return ApiResponse::send(200, $language, 'Language retrieved successfully.');
    }

    // Create a new language
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages,code'
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $language = Language::create([
            'id' => (string) Str::uuid(),
            'name' => $request->name,
            'code' => $request->code
        ]);

        return ApiResponse::send(201, $language, 'Language created successfully.');
    }

    // Update an existing language
    public function update(Request $request, $id)
    {
        $language = Language::find($id);

        if (!$language) {
            return ApiResponse::send(404, null, 'Language not found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:10|unique:languages,code,' . $id
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $language->update($request->only(['name', 'code']));

        return ApiResponse::send(200, $language, 'Language updated successfully.');
    }

    // Delete a language
    public function destroy($id)
    {
        $language = Language::find($id);

        if (!$language) {
            return ApiResponse::send(404, null, 'Language not found.');
        }

        $language->delete();

        return ApiResponse::send(200, null, 'Language deleted successfully.');
    }
}
