<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\StudentDb;
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
        $students = $this->studentModel->findAll();

        $studentsArray = [];
        foreach ($students as $student) {
            $studentData = $student->toArray();
            $studentData['status_cell'] = view_cell('AcademicStatusCell', ['status' => $student->academic_status]);
            $studentsArray[] = $studentData;
        }

        $data = ['students' => $studentsArray];

        // print_r($students);
        $data['content'] = $parser->setData($data)
            ->render(
                "students/v_student_list",
                // ['cache' => 1800, 'cache_name' => 'student_list']
            );

        return view('components/v_parser_layout', $data);
    }

    public function show($id)
    {
        $parser = \Config\Services::parser();
        $getStudent = $this->studentModel->find($id);

        $student = $getStudent->toArray();
        $student['status_cell'] = view_cell('AcademicStatusCell', ['status' => $student['academic_status']]);
        // $student['grade_cell'] = view_cell('LatestGradesCell', ['course' => $student['courses'], 'filter' => false]);
        $student['profile_picture'] = base_url("iconOrang.png");


        $data = $student;
        // print_r($students);

        $data['content'] = $parser->setData($data)
            ->render(
                "students/v_student_profile",
                // ['cache' => 3600, 'cache_name' => 'student_profile']
            );

        return view('components/v_parser_layout', $data);
    }

    public function create()
    {
        $type = $this->request->getMethod();
        if ($type == "GET") {
            return view("students/v_student_form");
        }

        $formData = [
            'student_id' => $this->request->getPost('student_id'),
            'name' => $this->request->getPost('name'),
            'study_program' => $this->request->getPost('study_program'),
            'current_semester' => $this->request->getPost('current_semester'),
            'academic_status' => $this->request->getPost('academic_status'),
            'entry_year' => $this->request->getPost('entry_year'),
            'gpa' => $this->request->getPost('gpa'),
        ];

        if (!$this->studentModel->validate($formData)) {
            return redirect()->back()->withInput()->with('errors', $this->studentModel->errors());
        }

        $this->studentModel->save($formData);

        return redirect()->to('/students');
    }

    public function update($id)
    {
        $type = $this->request->getMethod();
        if ($type == "GET") {
            $data['student'] = $this->studentModel->find($id);
            return view("students/v_student_form", $data);
        }

        $formData = [
            'id' => $id,
            'student_id' => $this->request->getPost('student_id'),
            'name' => $this->request->getPost('name'),
            'study_program' => $this->request->getPost('study_program'),
            'current_semester' => $this->request->getPost('current_semester'),
            'academic_status' => $this->request->getPost('academic_status'),
            'entry_year' => $this->request->getPost('entry_year'),
            'gpa' => $this->request->getPost('gpa'),
        ];
        
        $this->studentModel->setValidationRule('student_id', "required|is_unique[students.student_id,id,{$id}]");

        if (!$this->studentModel->validate($formData)) {
            return redirect()->back()->withInput()->with('errors', $this->studentModel->errors());
        }

        $this->studentModel->save($formData);

        return redirect()->to('/students');
    }

    public function delete($id)
    {
        $this->studentModel->delete($id);
        return redirect()->to('/students');
    }

}
