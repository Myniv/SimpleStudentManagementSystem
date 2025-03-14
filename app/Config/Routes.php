<?php

use App\Controllers\AcademicController;
use App\Controllers\AuthController;
use App\Controllers\CoursesController;
use App\Controllers\EnrollmentController;
use App\Controllers\Home;
use App\Controllers\StudentController;
use App\Controllers\StudentGradesController;
use App\Controllers\UsersController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group(
    'admin',
    ['filter' => 'role:admin'],
    function (RouteCollection $routes) {
        $routes->get('dashboard', [Home::class, 'dashboard']);
        $routes->get('student', [StudentController::class, 'index']);
        $routes->get('student/show/(:num)', [StudentController::class, 'show/$1']);
        $routes->match(['get', 'post'], 'student/create', [StudentController::class, 'create']);
        $routes->match(['get', 'put'], 'student/update/(:num)', [StudentController::class, 'update']);
        $routes->delete('student/delete/(:num)', [StudentController::class, 'delete/$1']);
    }
);

$routes->group(
    'lecturer',
    ['filter' => 'role:lecturer'],
    function (RouteCollection $routes) {
        $routes->get('dashboard', [Home::class, 'dashboard']);
        $routes->get('courses', [CoursesController::class, 'index']);
        $routes->match(['get', 'post'], 'courses/create', [CoursesController::class, 'create']);
        $routes->match(['get', 'put'], 'courses/update/(:num)', [CoursesController::class, 'update']);
        $routes->delete('courses/delete/(:num)', [CoursesController::class, 'delete/$1']);

        $routes->get('student-grades', [StudentGradesController::class, 'index']);
        $routes->match(['get', 'post'], 'student-grades/create', [StudentGradesController::class, 'create']);
        $routes->match(['get', 'put'], 'student-grades/update/(:num)', [StudentGradesController::class, 'update']);
        $routes->delete('student-grades/delete/(:num)', [StudentGradesController::class, 'delete/$1']);

        $routes->get('enrollments', [EnrollmentController::class, 'index']);
        $routes->match(['get', 'post'], 'enrollments/create', [EnrollmentController::class, 'create']);
        $routes->match(['get', 'put'], 'enrollments/update/(:num)', [EnrollmentController::class, 'update']);
        $routes->delete('enrollments/delete/(:num)', [EnrollmentController::class, 'delete/$1']);
    }
);

// $routes->group(
//     'enrollments',
//     ['filter' => 'role:student,lecturer'],
//     function (RouteCollection $routes) {
//         $routes->get('/', [EnrollmentController::class, 'index']);
//         $routes->match(['get', 'post'], 'create', [EnrollmentController::class, 'create']);
//         $routes->match(['get', 'put'], 'update/(:num)', [EnrollmentController::class, 'update']);
//         $routes->delete('delete/(:num)', [EnrollmentController::class, 'delete/$1']);
//     }
// );

$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    // Registrasi
    $routes->get('register', 'AuthController::register', ['as' => 'register']);
    $routes->post('register', 'AuthController::attemptRegister');


    // Route lain seperti login, dll
    $routes->get('login', 'AuthController::login', ['as' => 'login']);
    $routes->post('login', 'AuthController::attemptLogin');
});

$routes->get('unauthorized', [AuthController::class, 'unauthorized'], ['as' => 'unauthorized']);
$routes->get('register-student', [UsersController::class, 'createUserStudent']);
$routes->post('store-register-student', [UsersController::class, 'storeUserStudent']);



$routes->group(
    'student',
    ['filter' => 'role:student'],
    function ($routes) {
        $routes->get('profile', [StudentController::class, 'profile']);
        $routes->get('dashboard', [Home::class, 'dashboard']);
        $routes->get('enrollments', [EnrollmentController::class, 'index']);
    }
);

$routes->group(
    'admin/users',
    ['filter' => 'role:admin'],
    function ($routes) {
        $routes->get('/', [UsersController::class, 'index']);
        $routes->get('create', [UsersController::class, 'create']);
        $routes->post('store', [UsersController::class, 'store']);
        $routes->get('edit/(:num)', [UsersController::class, 'edit/$1']);
        $routes->put('update/(:num)', [UsersController::class, 'update/$1']);
        $routes->delete('delete/(:num)', [UsersController::class, 'delete/$1']);
    }
);
