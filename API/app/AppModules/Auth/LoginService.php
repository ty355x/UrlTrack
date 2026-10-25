<?php

namespace App\AppModules\Auth;

use Hash;
use App\Query\UserQuery;

class LoginService {
    private $tokenService;
    private $userQuery;
    
    public function __construct(ApiTokenService $tokenService, UserQuery $userQuery) {
        $this->tokenService = $tokenService;
        $this->userQuery = $userQuery;
    }
    
    public function attemptLogin(string $email, string $password): array {
        // Ensure they've supplied valid login inputs.
        if (!$this->validateCredentialsExist($email, $password)) {
            return $this->makeLoginErrorResponse('You must enter an email address and password');
        }

        // Find the user.
        $user = $this->userQuery->findByEmail($email);
        
        // Make sure we have a user.
        if (!$user) {
            return $this->makeLoginErrorResponse('Invalid email address');
        }
        
        // Validate the password.
        if (!Hash::check($password, $user->password_hash)) {
           return $this->makeLoginErrorResponse('Invalid password');
        }

        // Ensure the account isn't disabled.
        if ($user->is_disabled) {
            return $this->makeLoginErrorResponse('Your account has been disabled. Please contact support for assistance');
        }

        // Return an API token.
        $token = $this->tokenService->makeTokenForUser($user);
        return $this->makeLoginSuccessResponse($token);
    }
    
    public function logout(): array {
        return ['success' => true];
    }
    
    private function validateCredentialsExist(string $email, string $password): bool {
        return $email !== null && strlen($email) > 0 && $password !== null && strlen($password) > 0;
    }
    
    private function makeLoginSuccessResponse(string $token): array {
        return ['success' => true, 'token' => $token];
    }

    private function makeLoginErrorResponse(string $error): array {
        return ['success' => false, 'error' => $error];
    }
}
