<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->findUser();

        $data = [
            'user' => new UserResource($user),
            'access_token' => null,
        ];

        if ($request->expectsJson()) {
            /** @var string $userAgent */
            $userAgent = $request->userAgent();

            $token = $user->createToken($userAgent)->plainTextToken;

            $data['access_token'] = $token;

            return response()->json($data);
        }

        Auth::login($user, $request->filled('remember'));

        $request->session()->regenerate();

        return response()->json($data);
    }

    public function logout(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        if ($request->expectsJson()) {
            $user->currentAccessToken()->delete();

            return response()->noContent();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
