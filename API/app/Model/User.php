<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model {
    use SoftDeletes;
    
    protected $casts = [
        'id' => 'integer',
        'is_disabled' => 'boolean',
    ];    
    
    protected $hidden = [
        'password_hash',
        'api_token',
        'api_token_created_at',
    ];
}
