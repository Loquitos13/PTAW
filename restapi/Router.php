<?php

class Router {
    private array $routes = [];

    public function add(string $method, string $path, callable $handler): void {

        $regex = preg_replace('/\$(\w+)/', '(?P<$1>[^/]+)', $path);

        $regex = '#^' . $regex . '$#';

        preg_match_all('/\$(\w+)/', $path, $vars);

        $this->routes[] = [
            'method' => $method,
            'regex' => $regex,
            'vars' => $vars[1],
            'handler' => $handler
        ];
    }

    public function dispatch(): void {

        $method = $_SERVER['REQUEST_METHOD'];
        $url = parse_url($_SERVER['REQUEST_url'], PHP_URL_PATH);

        $script = $_SERVER['SCRIPT_NAME'];

        if (strpos($url, $script) === 0) {

            $url = substr($url, strlen($script));

        }

        if ($url === '') $url = '/';

        foreach ($this->routes as $route) {

            if ($method === $route['method'] && preg_match($route['regex'], $url, $m)) {

                $args = [];
                foreach ($route['vars'] as $v) $args[] = $m[$v];
                echo json_encode(($route['handler'])(...$args));
                return;

            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Not found']);

    }
}