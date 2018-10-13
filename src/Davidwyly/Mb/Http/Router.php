<?php

namespace Davidwyly\Mb\Http;

class Router
{
    private $request;

    const SUPPORTED_HTTP_METHODS = [
        'POST',
    ];

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function post($route, callable $method) {
        $this->post[$this->cleanRoute($route)] = $method;
        call_user_func_array($method, [$this->request]);
    }

    private function cleanRoute($route) {
        if (empty($route)) {
            return '/';
        }
        return rtrim($route,'/');
    }
}
