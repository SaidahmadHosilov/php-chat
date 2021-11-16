<?php

class Router
{
    private $routes;

    public function __construct()
    {
        $routesPath = ROOT.'/config/routes.php';
        $this->routes = include($routesPath);
    }

    /**
     * Returns request string
     */
    public function getURI()
    {
        if(!empty($_SERVER['REQUEST_URI'])){
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run()
    {
        $uri = $this->getURI();

        foreach ($this->routes as $uriPattern => $path) {
            
            if(preg_match("~$uriPattern~", $uri)){

                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                
                $segments = explode('/', $internalRoute);

                $controllerName = array_shift($segments) . 'Controller';
                $controllerName = ucfirst($controllerName);

                $actionName = explode( '?','action' . ucfirst(array_shift($segments)))[0];

                $parameters = $segments;

                // Set up Controllers

                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';

                if(file_exists($controllerFile)){
                    include_once($controllerFile);
                }

                // Set up Methods

                $controllerObject = new $controllerName;

                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
                
                if($result != null){
                    break;
                }
            }
        }
    }
}