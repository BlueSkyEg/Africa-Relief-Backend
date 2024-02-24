<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Build a standardized API response.
     *
     * @param  string|null  $status
     * @param  string|null  $message
     * @param  mixed|null  $data
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
    */
    protected static function apiResponse(bool $success, string $message, $data = null, $errors = null, int $statusCode)
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'data'    => $data,
            'errors'  => $errors,
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Build a success API response.
     *
     * @param  string|null  $message
     * @param  mixed|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($message = null, $data = null, $statusCode = 200)
    {
        return $this->apiResponse(true, $message, $data, $errors = null, $statusCode);
    }

    /**
     * Build a validation error API response.
     *
     * @param  array  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationResponse($errors = [])
    {
        return $this->apiResponse(false, "validation error", $data = null,  ['errors' => $errors], 403);
    }

    /**
     * Build an error API response.
     *
     * @param  string|null  $message
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
    */
    protected function errorResponse($message = "server error", $statusCode = 500)
    {
        return $this->apiResponse(false, $message, $data = null, $errors = null, $statusCode);
    }
}
