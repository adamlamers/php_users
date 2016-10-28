<?php
namespace PHPUsers;

class Application
{

    public $router;

    function __construct()
    {
        $this->router = new Router();
    }

    function run()
    {
        echo $this->router->dispatch($this->getRequestPath());
    }

    function getRequestPath()
    {
        return $_SERVER['REQUEST_URI'];
    }
}
