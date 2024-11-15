<?php

namespace Src\Http\Routing;

use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;

class Router
{
    private static array $routes = [];

    public static function addRoute(string $method, string $uri, callable|array $callback): Route
    {
        $route = new Route($method, $uri, $callback);

        self::$routes[] = $route;

        return $route;
    }

    public static function post(string $uri, callable|array $callback): Route
    {
        return self::addRoute('POST', $uri, $callback);
    }

    public static function get(string $uri, callable|array $callback): Route
    {
        return self::addRoute('GET', $uri, $callback);
    }

    public static function delete(string $uri, callable|array $callback): Route
    {
        return self::addRoute('DELETE', $uri, $callback);
    }

    public static function put(string $uri, callable|array $callback): Route
    {
        return self::addRoute('PUT', $uri, $callback);
    }

    public static function patch(string $uri, callable|array $callback): Route
    {
        return self::addRoute('PATCH', $uri, $callback);
    }

    public static function options(string $uri, callable|array $callback): Route
    {
        return self::addRoute('OPTIONS', $uri, $callback);
    }

    private static function resolveUri(string $uri, string $routeUri): array
    {
        $responseParams = [
            'code' => ResponseTypeEnum::NOT_FOUND,
            'params' => []
        ];

        $splitedRouteUri = explode('/', $routeUri);
        $splitedReqUri = explode('/', $uri);

        if (count($splitedRouteUri) !== count($splitedReqUri)) {
            return $responseParams;
        }

        $pattern = "/\{([^}]*)\}/";
        $matched = [];
        for ($i = 0; $i < count($splitedRouteUri); $i++) {
            preg_match($pattern, $splitedRouteUri[$i], $matches);
            if (isset($matches[1])) {
                $matched[$i] = $matches[1];
            } else {
                if ($splitedRouteUri[$i] !== $splitedReqUri[$i]) {
                    return $responseParams;
                }
            }
        }

        $params = [];
        foreach ($matched as $i => $name) {
            $params[$name] = $splitedReqUri[$i];
        }


        $responseParams['code'] = ResponseTypeEnum::OK;
        $responseParams['params'] = $params;

        return $responseParams;
    }

    private static function checkForParams(Route $route): bool
    {
        if (empty($route->getExpectedParams())) {
            return true;
        }

        foreach ($route->getExpectedParams() as $expectedParam) {
            if (is_string($expectedParam)) {
                if (!isset($_POST[$expectedParam])) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function run(): Response
    {
        $reqUri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        foreach (self::$routes as $route) {
            if ($route->getUri() === $reqUri && $route->getMethod() === $method) {
                $responseParams = self::resolveUri($route->getUri(), $reqUri);
                $responseCode = $responseParams['code'];

                if ($responseCode !== ResponseTypeEnum::OK) {
                    return (new Response($responseCode));
                }

                $middlewareResponseArray = $route->executeMiddleware();
                foreach ($middlewareResponseArray as $middlewareResponse) {
                    if ($middlewareResponse->getResponse() !== ResponseTypeEnum::OK) {
                        return $middlewareResponse;
                    }
                }

                $params = $responseParams['params'];
                $callback = $route->getCallback();
                if (is_array($callback)) {
                    $instance = new $callback[0];
                    $callback = [$instance, $callback[1]];
                }

                $response = call_user_func_array($callback, $params);
                if (!$response instanceof Response) {
                    throw new \Exception('All callback must return a response');
                }

                if (!self::checkForParams($route)) {
                    return (new Response(ResponseTypeEnum::BAD_REQUEST));
                }

                return $response;
            }
        }

        return (new Response(ResponseTypeEnum::NOT_FOUND));
    }
}
