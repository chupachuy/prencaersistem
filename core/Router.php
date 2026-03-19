<?php

class Router
{
    protected $routes = [];

    // Register a GET route
    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    // Register a POST route
    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }

        // Remove base path if necessary (e.g., /prenacersistem/)
        if (defined('BASE_URL') && BASE_URL !== '/' && strpos($path, BASE_URL) === 0) {
            $path = substr($path, strlen(BASE_URL));
        } else {
            // Find base path dynamically if BASE_URL isn't active
            $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
            if ($scriptName !== '/' && strpos($path, $scriptName) === 0) {
                $path = substr($path, strlen($scriptName));
            }
        }

        if ($path === '' || $path === false) {
            $path = '/';
        }

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            http_response_code(404);
            // We could load a specific 404 view here.
            echo "404 Not Found";
            exit;
        }

        if (is_array($callback)) {
            // Instantiate the controller if a class name is passed
            $callback[0] = new $callback[0]();
        }

        return call_user_func($callback);
    }
}
