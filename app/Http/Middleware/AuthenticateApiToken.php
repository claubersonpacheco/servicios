<?php

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $plainToken = $request->bearerToken();

        if (! $plainToken) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $accessToken = PersonalAccessToken::query()
            ->with('user')
            ->where('token', hash('sha256', $plainToken))
            ->where(function ($query): void {
                $query
                    ->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (! $accessToken || ! $accessToken->user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $accessToken->forceFill(['last_used_at' => now()])->save();

        Auth::setUser($accessToken->user);
        $request->attributes->set('access_token', $accessToken);

        return $next($request);
    }
}
