<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('successResponse')) {
    /**
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    function successResponse(mixed $data = null, string $message = ''): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => exists($data) ? $data : 'عملیات موفقیت آمیز بود.',
            'message' => $message
        ]);
    }
}

if (!function_exists('errorResponse')) {
    /**
     * @param string $message
     * @param int $errorCode
     * @return JsonResponse
     */
    function errorResponse(string $message, int $errorCode = 400): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message
        ], $errorCode);
    }
}
