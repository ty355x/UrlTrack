<?php

namespace App\AppModules\Logging;

use App;

class Log {
    private static $entries = [];
    
    public static function debug(string $message): void {
        if (App::runningInConsole()) {
            echo $message . "\n";
        }
        
        self::$entries[] = $message;
    }
    
    public static function debugObj($obj): void {
        self::$entries[] = print_r($obj, true);
    }
    
    public static function getLog(): string {
        return implode("\n", self::$entries);
    }
}
