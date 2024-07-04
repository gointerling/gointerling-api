<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Helpers\ApiResponse;

class FileUploadController extends Controller
{
    public function index()
    {
        $files = File::all();
        return ApiResponse::send(200, compact('files'), 'Files retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpg,png,pdf,svg',
            'file_name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $file = $request->file('file');
        $path = $file->store('uploads', 'public');

        // get the full path of the file with APP_URL and the path
        $url = env('APP_URL') . '/storage/' . $path;

        $fileRecord = File::create([
            'name' => $request->file_name,
            'extension' => $file->getClientOriginalExtension(),
            'url' => $url
        ]);

        return ApiResponse::send(201, compact('fileRecord'), 'File uploaded successfully.');
    }

    public function show($id)
    {
        $file = File::find($id);

        if (!$file) {
            return ApiResponse::send(404, null, 'File not found.');
        }

        return ApiResponse::send(200, compact('file'), 'File retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return ApiResponse::send(422, null, null, $validator->errors());
        }

        $file = File::find($id);

        if (!$file) {
            return ApiResponse::send(404, null, 'File not found.');
        }

        // Delete the old file
        Storage::disk('public')->delete($file->url);

        $newFile = $request->file('file');
        $path = $newFile->store('uploads', 'public');
        
        // get the full path of the file with APP_URL and the path
        $url = env('APP_URL') . '/storage/' . $path;

        $file->update([
            'name' => $newFile->getClientOriginalName(),
            'extension' => $newFile->getClientOriginalExtension(),
            'url' => $url
        ]);

        return ApiResponse::send(200, compact('file'), 'File updated successfully.');
    }

    public function destroy($id)
    {
        $file = File::find($id);

        if (!$file) {
            return ApiResponse::send(404, null, 'File not found.');
        }

        // Delete the file from storage
        Storage::disk('public')->delete($file->url);

        $file->delete();

        return ApiResponse::send(200, null, 'File deleted successfully.');
    }
}
