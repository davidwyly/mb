<?php declare(strict_types=1);

use Davidwyly\Mb\Http\Request;
use Davidwyly\Mb\Http\Controller\PatientController;

require_once(__DIR__ . '/../config/bootstrap.php');

try {
    $request    = new Request();
    $controller = new PatientController($request);
    $controller->create();
} catch (\Exception $e) {
    die(json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT));
}
