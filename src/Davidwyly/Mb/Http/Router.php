<?php

namespace Davidwyly\Mb\Http;

use Davidwyly\Mb\Http\Controller\Controller;

class Router
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $post = [];

    /**
     * @var array
     */
    private $get = [];

    /**
     * @var array
     */
    private $put = [];

    /**
     * @var array
     */
    private $patch = [];

    /**
     * @var array
     */
    private $delete = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * On destruct, find the proper route and call it
     */
    public function __destruct()
    {
        $request_method     = mb_strtolower($this->request->request_method);
        $request_method_map = $this->{$request_method};
        if (array_key_exists($this->request->request_uri, $request_method_map)) {
            call_user_func_array($request_method_map[$this->request->request_uri], [$this->request]);
        } else {
            http_response_code(Controller::HTTP_NOT_FOUND);
        }
    }

    /**
     * @param string   $route
     * @param callable $callback
     */
    public function post(string $route, callable $callback): void
    {
        $this->post[$this->cleanRoute($route)] = $callback;
    }

    /**
     * @param string   $route
     * @param callable $callback
     */
    public function get(string $route, callable $callback): void
    {
        $this->get[$this->cleanRoute($route)] = $callback;
    }

    /**
     * @param string   $route
     * @param callable $callback
     */
    public function put(string $route, callable $callback): void
    {
        $this->put[$this->cleanRoute($route)] = $callback;
    }

    /**
     * @param string   $route
     * @param callable $callback
     */
    public function patch(string $route, callable $callback): void
    {
        $this->patch[$this->cleanRoute($route)] = $callback;
    }

    /**
     * @param string   $route
     * @param callable $callback
     */
    public function delete(string $route, callable $callback): void
    {
        $this->delete[$this->cleanRoute($route)] = $callback;
    }

    /**
     * @param string $route
     *
     * @return string
     */
    private function cleanRoute(string $route): string
    {
        if (empty($route)) {
            return '/';
        }
        return rtrim($route, '/');
    }
}
