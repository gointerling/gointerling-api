<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;

class SkillController extends Controller
{
    /**
     * Display a listing of the skills.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Get filter parameters from the request
        $merchantType = $request->query('merchant_type');
        $skillType = $request->query('skill_type');
        $search = $request->query('search', '');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        // Build the query with conditional filters
        $query = Skill::query();

        if ($merchantType) {
            $query->where('merchant_type', $merchantType);
        }

        if ($skillType) {
            $query->where('skill_type', $skillType);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Paginate results
        $skills = $query->paginate($perPage, ['*'], 'page', $page);


        if ($skills->isEmpty()) {
            return ApiResponse::send(404, null, 'No skills found.');
        }

        return ApiResponse::send(200, $skills, 'Skills retrieved successfully.');
    }

    /**
     * Store a newly created skill in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'merchant_type' => 'required|in:translator,interpreter,both',
            'skill_type' => 'required|in:main,additional',
        ]);

        $skill = Skill::create([
            'id' => Str::uuid(),
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'merchant_type' => $validatedData['merchant_type'],
            'skill_type' => $validatedData['skill_type'],
        ]);

        return ApiResponse::send(201, ['skill' => $skill], 'Skill created successfully.');
    }

    /**
     * Display the specified skill.
     *
     * @param  \App\Models\Skill  $skill
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Skill $skill)
    {
        return ApiResponse::send(200, ['skill' => $skill], 'Skill retrieved successfully.');
    }

    /**
     * Update the specified skill in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Skill  $skill
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Skill $skill)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'merchant_type' => 'required|in:translator,interpreter,both',
            'skill_type' => 'required|in:main,additional',
        ]);

        $skill->update($validatedData);

        return ApiResponse::send(200, ['skill' => $skill], 'Skill updated successfully.');
    }

    /**
     * Remove the specified skill from storage.
     *
     * @param  \App\Models\Skill  $skill
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Skill $skill)
    {
        $skill->delete();

        return ApiResponse::send(200, null, 'Skill deleted successfully.');
    }
}
