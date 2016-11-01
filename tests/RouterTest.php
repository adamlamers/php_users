<?php
namespace PHPUsers\Tests;

use PHPUnit\Framework\TestCase;
use PHPUsers\Router;

class RouterTest extends TestCase
{
    /**
     * Test adding a route to the routing table
     */
    public function testAddRoute()
    {
        $router = new Router();

        $this->assertEquals(true, $router->add('GET', '/testroute', 'TestController@index'));
        $this->assertEquals(true, $router->add('POST', '/testroute', 'TestController@testPost'));
        $this->assertEquals(false, $router->add('FAKE', '/testroute', 'None@none'));

        return $router;
    }

    /**
     * Test that dispatching a GET route succeeds.
     * @depends testAddRoute
     */
    public function testRouteDispatchSucceeds($router)
    {
        $_SERVER = array();
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->assertEquals(true, $router->dispatch('/testroute'));
        return $router;
    }

    /**
     * Test that dispatching a POST route with the same name as a GET route succeeds.
     * @depends testRouteDispatchSucceeds
     */
    public function testRouteDispatchPOSTSucceeds($router)
    {
        $_SERVER = array();
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->assertEquals('POST', $router->dispatch('/testroute'));
        return $router;
    }

    /**
     * Test that a non-existant route dispatch fails with a 404 error.
     * @depends testRouteDispatchSucceeds
     */
    public function testRouteDispatchFails($router)
    {
        $_SERVER = array();
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $response = json_decode($router->dispatch('/nonexistant'));
        $this->assertEquals(http_response_code(), 404);
        $this->assertEquals($response->status, 'fail');
        $this->assertEquals($response->message, 'Resource not found.');
    }
}
