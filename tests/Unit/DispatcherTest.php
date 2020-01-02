<?php
/**
 * DispatcherTest.php
 * @author Bruce Ingalls
 * @copyright 2019
 */

namespace Tests;

use App\Dispatcher;
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    public function setUp(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    public function testDefaultPage()
    {
        $view = Dispatcher::invoke();
        DispatcherTest::assertEquals('{method: "App\Controllers\HomeCtrl::get", data: "array (
)"}', $view);
    }

    public function testLoginController()
    {
        $view = Dispatcher::invoke('LoginCtrl', 'login', ['name' => 'guest', 'password' => 'password']);
        DispatcherTest::assertEquals('{"error_code": 404, "error_message": "Page not found"}', $view);
    }

    public function testMissingPage()
    {
        $view = Dispatcher::invoke('MissingCtrl');
        DispatcherTest::assertEquals('{"error_code": 404, "error_message": "Page not found"}', $view);
    }

}
