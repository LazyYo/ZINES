<?php
namespace Router;
/**
 *
 */
class Router
{
    private $url;
    private $routes = [];
    private $namesRoutes = [];

    function __construct($url)
    {
        $this->url = $url;
    }

    public function get($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    public function post($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    private function add($path, $callable, $name, $method)
    {
        $route = $this->routes[$method][] = new Route($path, $callable);
        if(is_string($callable) && $name === NULL) $name = $callable;
        if($name) $this->namedRoutes[$name] = $route;
        return $route;
    }
    public function process(){
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']]))
            throw new RouterException("REQUEST_METHOD does not exists", 1);

        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if($route->match($this->url)){

                if(FALSE !== strpos($this->url, 'admin') && !isset($_SESSION['user']))
                    throw new RouterException("Permission denied", 403);

                return $route->call();
            }
        }

        throw new RouterException("No route matches", 1);
    }

    public function url($name, $params = [])
    {
        if(!isset($this->namedRoutes[$name]))
            throw new RouterException("No Route matches this name", 1);

        return $this->namedRoutes[$name]->getUrl($params);
    }
}
