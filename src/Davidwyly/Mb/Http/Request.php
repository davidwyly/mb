<?php declare(strict_types=1);

namespace Davidwyly\Mb\Http;

use Davidwyly\Mb\Exception\RequestException;

class Request
{
    const FORM_HTTP_CONTENT_TYPES = [
        'application/form-data',
        'application/x-www-form-urlencoded',
    ];

    const XML_HTTP_CONTENT_TYPES = [
        'text/xml',
        'application/xml',
    ];

    const JSON_HTTP_CONTENT_TYPES = [
        'text/json',
        'application/json',
    ];

    /**
     * @var string
     */
    public $request_method;

    /**
     * @var string
     */
    public $http_content_type;

    /**
     * @var string
     */
    public $request_uri;

    /**
     * @var array
     */
    public $post = [];

    /**
     * @var array
     */
    public $get = [];

    /**
     * @var array
     */
    public $put = [];

    /**
     * @var array
     */
    public $patch = [];

    /**
     * @var array
     */
    public $delete = [];

    /**
     * Router constructor.
     *
     * @throws RequestException
     */
    public function __construct()
    {
        $this->request_method    = $this->getRequestMethod();
        $this->http_content_type = $this->getHttpContentType();
        $this->request_uri       = $this->getRequestUri();
        $this->loadRequest();
    }

    /**
     * @return bool
     */
    public function isJson(): bool
    {
        if (in_array($this->http_content_type, self::JSON_HTTP_CONTENT_TYPES)) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isXml(): bool
    {
        if (in_array($this->http_content_type, self::XML_HTTP_CONTENT_TYPES)) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isForm(): bool
    {
        if (in_array($this->http_content_type, self::FORM_HTTP_CONTENT_TYPES)) {
            return true;
        }
        return false;
    }

    /**
     * @throws RequestException
     *
     * @return void
     */
    private function loadRequest(): void
    {
        switch ($this->request_method) {
            case 'POST':
                $this->getPost();
                break;

            /**
             * TODO: currently unimplemented methods, add functionality as needed
             */
            case 'GET':
                // no break
            case 'PUT':
                // no break
            case 'PATCH':
                // no break
            case 'DELETE':
                // no break
            default:
                throw new RequestException("Request method '$this->request_method' not currently supported");
        }
    }

    /**
     * Collects POST data, depending on the context of the content type
     *
     * @return void
     */
    private function getPost(): void
    {
        switch (true) {
            case $this->isForm():
                $this->getFormPost();
                break;
            case $this->isXml():
                $this->post['xml'] = $this->getXmlInput();
                break;
            case $this->isJson():
                $this->post['json'] = $this->getJsonInput();
                break;
            default:
                $this->post[$this->http_content_type] = $this->getPostInput();
        }
    }

    /**
     * Collects POST URL-encoded form fields and performs rudimentary sanitization
     *
     * @return void
     */
    private function getFormPost(): void
    {
        foreach ($_POST as $key => $value) {
            $this->post[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
    }

    /**
     * Returns XML content from POST
     *
     * @return \SimpleXMLElement
     */
    private function getXmlInput(): \SimpleXMLElement
    {
        return new \SimpleXMLElement($this->getPostInput());
    }

    /**
     * Returns JSON content from POST
     *
     * @return \stdClass
     */
    private function getJsonInput(): \stdClass
    {
        return json_decode($this->getPostInput());
    }

    /**
     * Returns generic content (unknown content type) from POST
     *
     * @return string
     */
    private function getPostInput(): string
    {
        return (string)file_get_contents("php://input");

    }

    /**
     * @return string
     * @throws RequestException
     */
    private function getRequestUri(): string
    {
        if (!isset($_SERVER['REQUEST_URI'])) {
            throw new RequestException("Request URI not found");
        }
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * @return string
     * @throws RequestException
     */
    private function getRequestMethod(): string
    {
        if (!isset($_SERVER['REQUEST_METHOD'])) {
            throw new RequestException("Request method not found");
        }
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return string
     * @throws RequestException
     */
    private function getHttpContentType(): string
    {
        if (!isset($_SERVER['HTTP_CONTENT_TYPE'])) {
            throw new RequestException("HTTP Content Type not found");
        }
        return $_SERVER['HTTP_CONTENT_TYPE'];
    }
}
