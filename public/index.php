<?php declare(strict_types=1);

use Davidwyly\Mb\Http\Request;
use Davidwyly\Mb\Http\Controller\PatientController;
use Davidwyly\Mb\Http\Router;

require_once(__DIR__ . '/../config/bootstrap.php');

try {
    $router = new Router(new Request());

    /**
     * Define routes and controller callbacks for when the route is invoked
     */
    $router->post('/patient', function (Request $request) {
        (new PatientController($request))->create();
    });

} catch (\Exception $e) {
    die(json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT));
}
