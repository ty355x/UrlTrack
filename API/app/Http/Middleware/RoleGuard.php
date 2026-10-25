<?php

namespace App\Http\Middleware;

use Closure;
use App\AppModules\Auth\AppAuth;
use App\AppModules\Auth\ApiTokenService;

/**
 * Ensures that users who access a route are authenticated and have the specified roles.
 */
class RoleGuard {
    private $tokenService;

    public function __construct(ApiTokenService $tokenService) {
        $this->tokenService = $tokenService;
    }

    public function handle($request, Closure $next, $roles) {
        $user = AppAuth::getUser();
        
        if (!$user) {
            return response('Your session has expired. Please log in.', 401);
        }

        // Get the list of specified roles.
        $roleList = explode('|', $roles);

        // Ensure the user has the specified role.
        if (!in_array($user->role, $roleList)) {
            return response('You do not have permission to access this resource.', 403);
        }

        // Pass on the request.
        return $next($request);
    }
}
