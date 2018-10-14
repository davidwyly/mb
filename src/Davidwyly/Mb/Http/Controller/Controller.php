<?php declare(strict_types=1);

namespace Davidwyly\Mb\Http\Controller;

use Davidwyly\Mb\Http\Request;

abstract class Controller
{
    const HTTP_SUCCESS      = 200;
    const HTTP_CREATED      = 201;
    const HTTP_CLIENT_ERROR = 400;
    const HTTP_NOT_FOUND    = 404;
    const HTTP_SERVER_ERROR = 500;

    /**
     * @var Request
     */
    public $request;

    /**
     * Controller constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param     $data
     * @param int $http_response_code
     */
    protected function renderSuccess($data, $http_response_code = self::HTTP_SUCCESS): void
    {
        http_response_code($http_response_code);
        die(json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * @param \Exception $e
     * @param int        $http_response_code
     */
    protected function renderFail(\Exception $e, $http_response_code = self::HTTP_SERVER_ERROR): void
    {
        http_response_code($http_response_code);
        die(json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT));
    }
}
