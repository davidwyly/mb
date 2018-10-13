<?php declare(strict_types=1);

namespace Davidwyly\Mb\Http\Controller\Parse;

interface Xml
{
    public function parseXml(\SimpleXMLElement $data): array;
}
