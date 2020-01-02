<?php
/**
 * RouteTest.php
 * @author Bruce Ingalls
 * @copyright 2019
 */

namespace Tests;

use App\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function testHomeController(): void
    {
        $route = new Route('');
        static::assertEquals(Route::SUFFIX, $route->getCtrl());
    }

    public function testCtrlAndAction(): void
    {
        $route = new Route('/login/login');
        static::assertEquals('LoginCtrl', $route->getCtrl());
        static::assertEquals('login', $route->getAction());
    }

    public function testCtrlWithIncompleteVariables(): void
    {
        $route = new Route('/login/login/name/joe/password');
        static::assertEquals('LoginCtrl', $route->getCtrl());
        static::assertEquals('login', $route->getAction());
        static::assertEquals(
            ['name' => 'joe', 'password' => null, 0 => 'name', 1 => 'password'],
            $route->getVariables()
        );
    }

    public function testCtrlWithFuzzyPath(): void
    {
        // ToDo: add more fuzzing tests...
        $route = new Route('/funky/m@ké\\Tr0∫lÉ/metrics/מבחן');  // hebrew for 'test'
        static::assertEquals('FunkyCtrl', $route->getCtrl());
        static::assertEquals('m@ké\\\\Tr0∫lÉ', $route->getAction());
        static::assertEquals(['metrics' => 'מבחן', 0 => 'metrics'], $route->getVariables());
    }
}
