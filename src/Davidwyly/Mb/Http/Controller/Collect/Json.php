<?php declare(strict_types=1);

namespace Davidwyly\Mb\Http\Controller\Collect;

use Davidwyly\Mb\Exception\ControllerException;

trait Json
{
    /**
     * @return \stdClass
     * @throws ControllerException
     */
    protected function collectJson(): \stdClass
    {
        if (empty($this->request->post['json'])) {
            throw new ControllerException("Empty JSON in request", self::HTTP_CLIENT_ERROR);
        }
        return $this->request->post['json'];
    }
}