<?php declare(strict_types=1);

namespace Davidwyly\Mb\Http;

use Davidwyly\Mb\Exception\RequestException;
use Davidwyly\Mb\Http\Controller\Controller;

/**
 * @property array $post
 * @property array $get
 * @property array $put
 * @property array $patch
 * @property array $delete
 */
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
     * Collects request data
     *
     * @return void
     * @throws RequestException
     */
    private function loadRequest(): void
    {
        // HTTP GET method does not have a body
        if ($this->request_method == 'get') {
            return;
        }

        switch (true) {
            case $this->isForm():
                $this->{$this->request_method} = $this->getFormInput();
                break;
            case $this->isXml():
                $this->{$this->request_method}['xml'] = $this->getXmlInput();
                break;
            case $this->isJson():
                $this->{$this->request_method}['json'] = $this->getJsonInput();
                break;
            default:
                $this->{$this->request_method}[$this->http_content_type] = $this->getRawInput();
        }
    }

    /**
     * Collects POST URL-encoded form fields and performs rudimentary sanitization
     *
     * @return array
     */
    private function getFormInput(): array
    {
        $form_input = [];
        foreach ($_POST as $key => $value) {
            $form_input[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $form_input;
    }

    /**
     * Returns XML content from POST
     *
     * @return \SimpleXMLElement
     */
    private function getXmlInput(): \SimpleXMLElement
    {
        return new \SimpleXMLElement($this->getRawInput());
    }

    /**
     * Returns JSON content from POST
     *
     * @return \stdClass
     * @throws RequestException
     */
    private function getJsonInput(): \stdClass
    {
        $json = json_decode($this->getRawInput());
        if (is_null($json)) {
            throw new RequestException("JSON could not be decoded", Controller::HTTP_CLIENT_ERROR);
        }
        return $json;
    }

    /**
     * Returns generic content (unknown content type) from POST
     *
     * @return string
     */
    private function getRawInput(): string
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
        return mb_strtolower($_SERVER['REQUEST_URI']);
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
        return mb_strtolower($_SERVER['REQUEST_METHOD']);
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
        return mb_strtolower($_SERVER['HTTP_CONTENT_TYPE']);
    }
}
