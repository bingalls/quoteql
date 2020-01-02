<?php

namespace App;

use ReflectionMethod;
use ReflectionException;

class Dispatcher
{
    /**
     * Call Controller parsed from Route
     * @param string $ctrl
     * @param string $action
     * @param array<string> $variables
     * @return string
     */
    public static function invoke(
        string $ctrl = Route::SUFFIX,
        string $action = '',
        array $variables = []
    ): string {
        if (! $action) {
            $action = strtolower(escapeshellcmd(($_SERVER['REQUEST_METHOD'])));     //fallback action
        }

        if ($ctrl === Route::SUFFIX) {  // controller path segment is '' or '/'
            $ctrl = Route::HOME . Route::SUFFIX;
        }

        try {
            $reflect = new ReflectionMethod('App\\Controllers\\' . $ctrl, $action);
            return $reflect->invoke(null, $variables);
        } catch (ReflectionException $rex) {
            if (! defined('PHPUNIT_COMPOSER_INSTALL') && ! defined('__PHPUNIT_PHAR__')) {
                header('Content-Type: application/json'); // is not PHPUnit
            }
            // TODO log($rex->getTraceAsString());
            return '{"error_code": 404, "error_message": "Page not found"}';
        }
    }
}
