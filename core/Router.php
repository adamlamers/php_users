<?php
namespace PHPUsers;

class Router
{
    private $table = array();
    private $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'HEAD'];

    public function add($methods, $uri, $handler)
    {
        if($this->validateMethods($methods)) {
            $this->table[$uri] = new Route($methods, $uri, $handler);
        }
    }

    public function dispatch($uri) {
        foreach($this->table as $routeUri => $route) {
            if(preg_match('#'.$routeUri.'#', $uri, $matches)) {
                array_shift($matches);
                return $this->resolve($route, $matches);
            }
        }

        http_response_code(404);
        echo "404";
    }

    public function dispatch2($uri) {

        if(array_key_exists($uri, $this->table)) {
            $route = $this->table[$uri];
            echo "Route exists: ".$route->getHandler()."<br>";
            return $this->resolve($uri, $route);
        } else {
            http_response_code(404);
            echo "404";
        }
    }

    private function resolve($route, $args) {
        $parts = explode('@', $route->getHandler());

        $controllerName = '\\PHPUsers\\Controllers\\'.$parts[0];
        $controllerMethod = $parts[1];
        $controller = new $controllerName();

        return call_user_func_array(array($controller, $controllerMethod), $args);
    }

    private function validateMethods($methods)
    {
        if(is_string($methods)) {
            return in_array($methods, $this->allowedMethods);
        }

        foreach($methods as $method) {
            if(!in_array($method, $this->allowedMethods)) {
                return false;
            }
        }
        return true;
    }
}
