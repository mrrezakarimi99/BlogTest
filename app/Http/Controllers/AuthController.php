<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\AuthResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @throws AuthException
     */
    public function login(LoginRequest $request): AuthResource
    {
        if (!auth()->attempt($request->validated())) {
            throw new AuthException('invalid info!!', Response::HTTP_UNAUTHORIZED);
        }
        $token = $this->createToken();
        $user = auth()->user();
        return new AuthResource($user, $token);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
//        $user =  auth()->user()->token();
//        $user->revoke();
        auth()->user()->token()->delete();
        return response()->json([
            'message' => 'log out success'
        ], Response::HTTP_OK);
    }

    /**
     * @return string
     */
    private function createToken(): string
    {
        auth()->user()->tokens()->delete();
        return auth()->user()->createToken('Login')->accessToken;
    }
}
