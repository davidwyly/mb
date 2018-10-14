<?php

namespace Davidwyly\Mb\Mock;

use Davidwyly\Mb\Http\Request;

final class MockRequest extends Request
{
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct(string $request_method, string $http_content_type, string $request_uri)
    {
        $this->request_method    = $request_method;
        $this->http_content_type = $http_content_type;
        $this->request_uri       = $request_uri;
    }
}
