<?php

namespace App\Infrastructure\Controllers;

use App\Application\EarlyAdopter\IsEarlyAdopterService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class IsEarlyAdopterUserController extends BaseController
{
    private $isEarlyAdopterService;

    /**
     * IsEarlyAdopterUserController constructor.
     */
    public function __construct(IsEarlyAdopterService $isEarlyAdopterService)
    {
        $this->isEarlyAdopterService = $isEarlyAdopterService;
    }

    public function __invoke(string $email): JsonResponse
    {
        try {
            $isEarlyAdopter = $this->isEarlyAdopterService->execute($email);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'earlyAdopter' => $isEarlyAdopter
        ], Response::HTTP_OK);
    }
}
