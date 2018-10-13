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
     * @var array
     */
    public $post = [];

    /**
     * Router constructor.
     *
     * @throws RequestException
     */
    public function __construct()
    {
        $this->request_method    = $this->getRequestMethod();
        $this->http_content_type = $this->getHttpContentType();
        $this->loadRequest();
    }

    public function isJson()
    {
        if (in_array($this->http_content_type, self::JSON_HTTP_CONTENT_TYPES)) {
            return true;
        }
        return false;
    }

    public function isXml()
    {
        if (in_array($this->http_content_type, self::XML_HTTP_CONTENT_TYPES)) {
            return true;
        }
        return false;
    }

    public function isForm()
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
             * currently unimplemented methods, add functionality as needed
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
     * @throws RequestException
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
                $this->post['xml'] = $this->getXmlStream();
                break;
            case $this->isJson():
                $this->post['json'] = $this->getJsonStream();
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
    private function getXmlStream(): \SimpleXMLElement
    {
        return new \SimpleXMLElement($this->getPostInput());
    }

    /**
     * Returns JSON content from POST
     *
     * @return \stdClass
     */
    private function getJsonStream(): \stdClass
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
