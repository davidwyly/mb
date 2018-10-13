<?php declare(strict_types=1);

namespace Davidwyly\Mb\Http\Controller\Collect;

trait Xml
{
    protected function collectXml(): \SimpleXMLElement
    {
        return $this->request->post['xml'];
    }
}
