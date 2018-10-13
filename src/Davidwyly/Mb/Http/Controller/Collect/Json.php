<?php declare(strict_types=1);

namespace Davidwyly\Mb\Http\Controller\Collect;

trait Json
{
    protected function collectJson(): \stdClass
    {
        return $this->request->post['json'];
    }
}
