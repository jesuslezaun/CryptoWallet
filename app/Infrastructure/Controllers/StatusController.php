<?php

namespace App\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class StatusController extends BaseController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => 'Systems are up and running',
        ], Response::HTTP_OK);
    }
}
