<?php

namespace App\AppModules\TrackedUrls;

class UpdateMethod {
    const SPECIFIC_TIME = 'time';
    const INTERVAL = 'interval';
    
    public static $all = [
        self::INTERVAL, self::SPECIFIC_TIME,
    ];
}
