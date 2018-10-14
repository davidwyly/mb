<?php declare(strict_types=1);

namespace Davidwyly\Mb\Http\Controller\Collect;

use Davidwyly\Mb\Exception\ControllerException;

trait Form
{
    /**
     * @return array
     * @throws ControllerException
     */
    protected function collectForm(): array
    {
        if (empty($this->request->post)) {
            throw new ControllerException("Empty form data in request", self::HTTP_CLIENT_ERROR);
        }
        return $this->request->post;
    }
}
