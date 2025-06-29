<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class EmployeeAuthController extends Controller
{
    /**
     *
     * POST /api/auth/login
     *
     * @param  \Illuminate\Http\Request  $r
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $r): JsonResponse
    {
        $cred = $r->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (! $token = auth()->attempt($cred)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     *
     * POST /api/auth/refresh
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     *
     * POST /api/auth/logout
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logged out']);
    }

    /**
     *
     * GET /api/auth/me
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user());
    }

    /**
     * @param  string  $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ]);
    }
}
