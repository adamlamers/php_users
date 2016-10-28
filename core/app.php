<?php
namespace PHPUsers;

class Application
{

    public $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function run()
    {
        echo $this->router->dispatch($this->getRequestPath());
    }

    public function getRequestPath()
    {
        return $_SERVER['REQUEST_URI'];
    }
}
