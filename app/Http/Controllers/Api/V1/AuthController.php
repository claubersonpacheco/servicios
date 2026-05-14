<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::query()
            ->with('roles', 'permissions')
            ->where('email', $credentials['email'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais informadas nao conferem.'],
            ]);
        }

        $plainToken = Str::random(80);

        PersonalAccessToken::query()->create([
            'user_id' => $user->id,
            'name' => $credentials['device_name'] ?? $request->userAgent() ?? 'mobile',
            'token' => hash('sha256', $plainToken),
        ]);

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $plainToken,
            'user' => new UserResource($user),
        ]);
    }

    public function me(Request $request): UserResource
    {
        return new UserResource($request->user()->load('roles', 'permissions'));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->attributes->get('access_token')?->delete();

        return response()->json(['message' => 'Sessao encerrada com sucesso.']);
    }
}
