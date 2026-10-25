<?php

namespace App\AppModules\Auth;

use App\Model\User;

class AppAuth {
    private static $user;
    
    public static function getUser(): ?User {
        return self::$user;
    }
    
    public static function setUser(User $user) {
        self::$user = $user;
    }
    
    public static function clearUser(): void {
        self::$user = null;
    }
}
