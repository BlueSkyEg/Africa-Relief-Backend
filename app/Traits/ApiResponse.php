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
    protected static function apiResponse($status = "success", $message = null, $data = null, $statusCode)
    {
        $response = [
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
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
        return $this->apiResponse("success", $message, $data, $statusCode);
    }

    /**
     * Build a validation error API response.
     *
     * @param  array  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationResponse($errors = [])
    {
        return $this->apiResponse("error", "validation error", ['errors' => $errors], 200);
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
        return $this->apiResponse("error", $message, null, $statusCode);
    }
}
