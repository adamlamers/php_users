<?php
namespace PHPUsers\Controllers;

class TestController
{
    /**
     * Test controller method that just returns true.
     */
    public function index()
    {
        return true;
    }

    public function testPost()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
