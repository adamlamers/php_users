<?php
namespace PHPUsers;

class Route
{
    private $allowedMethods = [];
    private $uri;
    private $handler;

    public function __construct($methods, $uri, $handler)
    {
        if (is_string($methods)) {
            $methods = [$methods];
        }
        $this->allowedMethods = $methods;
        $this->uri = $uri;
        $this->handler = $handler;
    }

    /**
     * Returns the handler function that was assigned to this URI.
     */
    public function getHandler()
    {
        return $this->handler;
    }
}
