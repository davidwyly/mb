<?php declare(strict_types=1);

namespace Davidwyly\Mb\Http\Controller\Parse;

interface Json
{
    public function parseJson(\stdClass $data): array;
}
