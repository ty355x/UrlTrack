<?php

namespace App\AppModules\TrackedUrls;

use Carbon\Carbon;

class UpdateTimeDescription {
    public static function makeUpdateTimeDescription(string $updateMethod, ?string $updateTime, ?int $updateIntervalMins): string {
        if ($updateMethod === UpdateMethod::SPECIFIC_TIME) {
            return self::makeTimeDescription($updateTime);
        } elseif ($updateMethod === UpdateMethod::INTERVAL) {
            return self::makeIntervalDescription($updateIntervalMins);
        } else {
            return 'Invalid update method';
        }
    }
    
    private static function makeTimeDescription(?string $updateTime): string {
        if ($updateTime === null) {
            return 'Invalid update time';
        }
        
        $timeFormatted = Carbon::create($updateTime)->format('g:i A');
        return 'Daily at ' . $timeFormatted . ' EST';
    }
    
    private static function makeIntervalDescription(?int $updateIntervalMins): string {
        if ($updateIntervalMins === null || $updateIntervalMins <= 0) {
            return 'Invalid update interval';
        }
        
        $hours = intdiv($updateIntervalMins, 60);
        $mins = $updateIntervalMins % 60;
        
        $result = 'Every ';
        
        if ($hours > 0) {
            $result .= $hours . ' hours';
        }
        
        if ($hours > 0 && $mins > 0) {
            $result .= ', ';
        }
        
        if ($mins > 0) {
            $result .= $mins . ' mins';
        }
        
        return $result;
    }
}
