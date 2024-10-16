<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * UserController constructor.
     *
     * Initializes the UserController class.
     */
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * Retrieve the locations associated with the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request.
     * @return \Illuminate\Http\JsonResponse|null The JSON response containing user locations or null if not found.
     */
    public function getUserLocations(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $userLocations = $this->userService->getUserLocations($request->user());

            return response()->json([
                'success' => true,
                'message' => 'User locations fetched successfully',
                'data' => $userLocations,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error fetching user locations: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching user locations',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
