<?php

use App\Controllers\AcademicController;
use App\Controllers\StudentController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('students', function (RouteCollection $routes) {
    $routes->get('/', [StudentController::class, 'index']);
    $routes->get('/show/(:num)', [StudentController::class, 'show/$1']);
    $routes->match(['get', 'post'], 'create', [StudentController::class, 'create']);
    $routes->match(['get', 'put'], 'update/(:num)', [StudentController::class, 'update']);
    $routes->delete('delete/(:num)', [StudentController::class, 'delete/$1']);
});

$routes->get('/academics', [AcademicController::class, 'index']);
$routes->get('/academics/statistics', [AcademicController::class, 'academicStatistics']);