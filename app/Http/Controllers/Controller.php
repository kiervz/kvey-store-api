<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function customResponse($message = '' , $data = [], $statusCode = 200, $isSuccess = true)
    {
        return response()->json([
            'code' => $statusCode,
            'success' => $isSuccess,
            'message' => $message,
            'response' => $data
        ], $statusCode);
    }
}
