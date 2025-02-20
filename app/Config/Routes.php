<?php

use App\Controllers\AcademicController;
use App\Controllers\StudentController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/students/show/(:num)', [StudentController::class, 'show/$1']);
$routes->get('/students', [StudentController::class, 'index']);

$routes->get('/academics', [AcademicController::class, 'index']);
$routes->get('/academics/statistics', [AcademicController::class, 'academicStatistics']);