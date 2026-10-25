<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\AppModules\Auth\AppAuth;
use App\AppModules\Auth\ApiTokenService;

/**
 * Determines the current users and ensure that their API token hasn't expired and their account is enabled.
 */
class AuthenticateUser {
    private $tokenService;

    public function __construct(ApiTokenService $tokenService) {
        $this->tokenService = $tokenService;
    }

    public function handle($request, Closure $next) {
        $this->authenticate($request);
        return $next($request);
    }
    
    private function authenticate($request) {
        // Get the token param for the request.
        $token = Auth::guard('api')->getTokenForRequest();
        
        // Make sure we have a token.
        if (!$token) {
            return;
        }

        // Get the user for the token.
        ['result' => $result, 'user' => $user] = $this->tokenService->getUserForToken($token);
        
        // Make sure the token is valid.
        if ($result === ApiTokenService::AUTH_INVALID) {
            return response('Your session has expired. Please log in.', 401);
        }

        // Make sure the token hasn't expired.
        if ($result === ApiTokenService::AUTH_EXPIRED) {
            return response('Your session has expired. Please log in.', 401);
        }

        // Make sure the user isn't disabled.
        if ($user->is_disabled) {
            return response('Your account has been disabled. Please contact support for assistance', 440);
        }

        // Refresh the token time and set the current user.
        $this->tokenService->refreshTokenTime($user);
        AppAuth::setUser($user);
    }
}
