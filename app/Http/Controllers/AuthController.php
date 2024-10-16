<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     */
    public function login(AuthLoginRequest $request): \Illuminate\Http\JsonResponse
    {
        if (! auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::query()->where('email', $request->get('email'))->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully',
            'access_token' => $token,
        ], Response::HTTP_OK);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully',
        ], Response::HTTP_OK);
    }

    /**
     * Get the authenticated User.
     */
    public function me(Request $request): User
    {
        return $request->user();
    }
}
