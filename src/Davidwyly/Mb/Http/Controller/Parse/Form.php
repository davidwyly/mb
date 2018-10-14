<?php declare(strict_types=1);

namespace Davidwyly\Mb\Http\Controller\Parse;

interface Form
{
    public function parseForm(array $data): array;
}
