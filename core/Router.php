<?php
namespace PHPUsers;

class Router
{
    private $table = array();
    private $allowedMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD'];

    /**
     * Adds a route to the Router.
     * @param array $methods An array of valid HTTP methods for this uri.
     * @param string $uri A regex that matches the request path that you want $handler to handle.
     * @param string $handler A string that corresponds to a controller in the form of Controller\@Method
     */
    public function add($methods, $uri, $handler)
    {
        if ($this->validateMethods($methods)) {
            array_push($this->table, new Route($methods, $uri, $handler));
            return true;
        }
        return false;
    }

    /**
     * Attempt to dispatch a request path matching $uri to its respective handler.
     * @param string $uri The request path to attempt to dispatch.
     */
    public function dispatch($uri)
    {
        foreach ($this->table as $route) {
            if($route->matches($_SERVER['REQUEST_METHOD'], $uri, $arguments)) {
                return $this->resolve($route, $arguments);
            }
        }

        http_response_code(404);
        $response = ['status' => 'fail', 'message' => 'Resource not found.'];
        return json_encode($response);
    }

    /**
     * Resolve a route and pass arguments to the handler method.
     * @param Route $route The route object that will be handling the request.
     * @param array $args The arguments to pass to the handler method.
     */
    private function resolve($route, $args)
    {
        $parts = explode('@', $route->getHandler());

        $controllerName = '\\PHPUsers\\Controllers\\'.$parts[0];
        $controllerMethod = $parts[1];
        $controller = new $controllerName();

        return call_user_func_array(array($controller, $controllerMethod), $args);
    }

    /**
     * Validate that the methods are valid and accepted HTTP methods.
     * @param array $methods The methods to be checked.
     */
    private function validateMethods($methods)
    {
        if (is_string($methods)) {
            return in_array($methods, $this->allowedMethods);
        }

        foreach ($methods as $method) {
            if (!in_array($method, $this->allowedMethods)) {
                return false;
            }
        }
        return true;
    }
}
