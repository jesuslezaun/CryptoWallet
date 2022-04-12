<?php

namespace App\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class testController
{
    public function __invoke(int $id): JsonResponse
    {
        $user = DB::table('users')->where('id', $id)->first();

        return response()->json([
            'id' => $user->name
        ], Response::HTTP_OK);
    }
}
