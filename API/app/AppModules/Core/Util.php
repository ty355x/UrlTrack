<?php

namespace App\AppModules\Core;

use Exception;
use ReflectionClass;

class Util {
    public static function makeExceptionMessage(Exception $e): string {
        return (new ReflectionClass($e))->getShortName() . ' - ' . $e->getMessage();
    }
}
