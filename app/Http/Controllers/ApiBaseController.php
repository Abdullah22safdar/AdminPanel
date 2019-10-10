<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * Class ApiBaseController
 * @package App\Http\Controllers
 */
class ApiBaseController extends Controller
{

    /**
     * @param $code
     * @param array $data
     * @param string $message
     * @return JsonResponse
     */
    public function sendSuccess($code, $data = [], $message= 'has success')
    {
        return response()->json(array_merge([
            'success'=>true,
            'message'=>$message
        ], $data), $code);
    }

    /**
     * @param $code
     * @param array $data
     * @param string $message
     * @return JsonResponse
     */
    public function sendError($code, $data = [], $message= 'failed')
    {
        return response()->json(array_merge([
            'success'=> false,
            'message' => $message
        ], $data), $code);

    }
}
