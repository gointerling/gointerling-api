<?php
// app/Helpers/ApiResponse.php

namespace App\Helpers;

class ApiResponse
{
    public static function send($status, $data = null, $message = null, $error = null)
    {
        $response = [
            'status' => $status,
        ];

        if ($data) {
            $response['data'] = $data;
        }

        if ($message) {
            $response['message'] = $message;
        }

        if ($error) {
            $response['error'] = $error;
        }

        return response()->json($response, $status);
    }
}
