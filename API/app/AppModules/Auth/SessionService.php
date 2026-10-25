<?php

namespace App\AppModules\Auth;

use App\AppModules\Auth\AppAuth;

class SessionService {
    public function getCurrentSession(): array {
        $user = AppAuth::getUser();
        
        if (!$user) {
            return ['is_authenticated' => false, 'user' => ['email' => null, 'role' => null]];
        } else {
            return ['is_authenticated' => true, 'user' => ['email' => $user->email, 'role' => $user->role]];
        }
    }
}
