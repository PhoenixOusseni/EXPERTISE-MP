<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Inscription d'un apprenant.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            ...$request->validated(),
            'role' => UserRole::Apprenant,
        ]);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('auth')->plainTextToken,
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user  = User::where($field, $login)->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Identifiants incorrects.'],
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'login' => ['Votre compte a été désactivé.'],
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken($request->input('device_name', 'auth'))->plainTextToken,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('activeSubscription.plan');

        return response()->json([
            'user' => $user->makeHidden('activeSubscription'),
            'has_active_subscription' => $user->activeSubscription !== null,
            'active_subscription' => $user->activeSubscription
                ? new SubscriptionResource($user->activeSubscription)
                : null,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie.']);
    }
}
