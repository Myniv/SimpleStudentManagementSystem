<?php

use App\Controllers\AcademicController;
use App\Controllers\CoursesController;
use App\Controllers\EnrollmentController;
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

$routes->group('courses', function (RouteCollection $routes) {
    $routes->get('/', [CoursesController::class, 'index']);
    $routes->match(['get', 'post'], 'create', [CoursesController::class, 'create']);
    $routes->match(['get', 'put'], 'update/(:num)', [CoursesController::class, 'update']);
    $routes->delete('delete/(:num)', [CoursesController::class, 'delete/$1']);
});

$routes->group('enrollments', function (RouteCollection $routes) {
    $routes->get('/', [EnrollmentController::class, 'index']);
    $routes->match(['get', 'post'], 'create', [EnrollmentController::class, 'create']);
    $routes->match(['get', 'put'], 'update/(:num)', [EnrollmentController::class, 'update']);
    $routes->delete('delete/(:num)', [EnrollmentController::class, 'delete/$1']);
});