<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MStudent;
use CodeIgniter\HTTP\ResponseInterface;

class StudentController extends BaseController
{
    private $studentModel;

    public function __construct()
    {
        $this->studentModel = new MStudent();
    }

    public function index()
    {
        $parser = \Config\Services::parser();
        $students = $this->studentModel->getStudentsArray();

        foreach ($students as &$student) {
            $student['status_cell'] = view_cell('AcademicStatusCell', ['status' => $student['status']]);
            $student['grade_cell'] = view_cell('LatestGradesCell', ['course' => $student['courses'], 'filter' => true]);
        }

        $data = ['students' => $students];

        $data['content'] = $parser->setData($data)
            ->render("students/v_student_list", ['cache' => HOUR, 'cache_name' => 'student_list']);

        return view('components/v_parser_layout', $data);
    }

    public function show($id)
    {
        $parser = \Config\Services::parser();
        $students = $this->studentModel->getStudentByIdArray($id);

        $students['status_cell'] = view_cell('AcademicStatusCell', ['status' => $students['status']]);
        $students['grade_cell'] = view_cell('LatestGradesCell', ['course' => $students['courses'], 'filter' => false]);
        $students['profile_picture'] = base_url("iconOrang.png");


        $data = $students;
        // print_r($students);

        $data['content'] = $parser->setData($data)
            ->render("students/v_student_profile", ['cache' => '1', 'cache_name' => 'student_profile']);

        return view('components/v_parser_layout', $data);
    }

}
