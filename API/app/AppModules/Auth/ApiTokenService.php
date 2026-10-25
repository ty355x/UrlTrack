<?php

namespace App\AppModules\Auth;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Model\User;
use App\Query\UserQuery;

/**
 * Manages user API tokens.
 */
class ApiTokenService {
    const TOKEN_LENGTH = 80;
    const TOKEN_EXPIRE_TIME_HOURS = 24;
    
    const AUTH_VALID = 1;
    const AUTH_INVALID = -1;
    const AUTH_EXPIRED = -2;

    private $userQuery;

    public function __construct(UserQuery $userQuery) {
        $this->userQuery = $userQuery;
    }

    /**
     * Saves an API token for a user and returns the token.
     */
    public function makeTokenForUser(User $user): string {
        $token = Str::random(self::TOKEN_LENGTH);
        $user->api_token = hash('sha256', $token);
        $user->api_token_created_at = Carbon::now();
        $user->save();

        return $token;
    }

    /**
     * Gets the user for the specified API token.
     */
    public function getUserForToken(string $token): array {
        $tokenHash = hash('sha256', $token);
        $user = $this->userQuery->findByApiToken($tokenHash);

        if (!$user) {
            return ['result' => self::AUTH_INVALID, 'user' => null];
        }

        $elapsedTimeHours = (time() - strtotime($user->api_token_created_at)) / 3600;
        
        if ($elapsedTimeHours >= self::TOKEN_EXPIRE_TIME_HOURS) {
            return ['result' => self::AUTH_EXPIRED, 'user' => null];
        }

        return ['result' => self::AUTH_VALID, 'user' => $user];
    }

    /**
     * Updates a token's created time.
     */
    public function refreshTokenTime(User $user): void {
        $user->api_token_created_at = Carbon::now();
        $user->save();
    }
}
