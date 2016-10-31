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
     * Determine if this Route matches the request URI / method
     * @param string $method The HTTP method used to request the uri.
     * @param string $uri The URI being requested.
     * @return Arguments that should be passed to the resolved handler.
     */
    public function matches($method, $uri, &$arguments)
    {
        if (!in_array($method, $this->allowedMethods)) {
            return false;
        }

        if (preg_match('#'.$this->uri.'#', $uri, $arguments)) {
            array_shift($arguments);
            return true;
        }

        return false;
    }

    /**
     * Returns the handler function that was assigned to this URI.
     */
    public function getHandler()
    {
        return $this->handler;
    }
}
