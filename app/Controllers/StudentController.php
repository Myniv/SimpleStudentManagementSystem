<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\StudentDb;
use App\Libraries\DataParams;
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
        $params = new DataParams([
            "search" => $this->request->getGet("search"),
            "study_program" => $this->request->getGet("study_program"),
            "academic_status" => $this->request->getGet("academic_status"),
            "entry_year" => $this->request->getGet("entry_year"),
            "perPage" => $this->request->getGet("perPage"),
            "sort" => $this->request->getGet("sort"),
            "order" => $this->request->getGet("order"),
            "page" => $this->request->getGet("page_students"),
        ]);
        $result = $this->studentModel->getFilteredProducts($params);

        $data = [
            'students' => $result['students'],
            'pager' => $result['pager']->links('students', 'custom_pager'),
            'total' => $result['total'],
            'search' => $params->search,
            'reset' => $params->getResetUrl(base_url('/students')),
            'order' => $params->order,
            'sort' => $params->sort,
            'page' => $params->page,
            'baseUrl' => base_url('/students'),
            'perPageOptions' => [
                ['value' => 5, 'selected' => ($params->perPage == 5) ? 'selected' : ''],
                ['value' => 10, 'selected' => ($params->perPage == 10) ? 'selected' : ''],
                ['value' => 25, 'selected' => ($params->perPage == 25) ? 'selected' : ''],
            ],
            'filterStudyProgram' => [
                ['name' => 'Programming Expert', 'value' => "Programming Expert", 'selected' => ($params->study_program == "Programming Expert") ? 'selected' : ''],
                ['name' => 'Artificial Intelligence', 'value' => "Artificial Intelligence", 'selected' => ($params->study_program == "Artificial Intelligence") ? 'selected' : ''],
                ['name' => 'Cyber Security', 'value' => "Cyber Security", 'selected' => ($params->study_program == "Cyber Security") ? 'selected' : ''],
            ],
            'filterAcademicStatus' => [
                ['name' => 'Active', 'value' => "Active", 'selected' => ($params->academic_status == "Active") ? 'selected' : ''],
                ['name' => 'On Leave', 'value' => "On Leave", 'selected' => ($params->academic_status == "On Leave") ? 'selected' : ''],
                ['name' => 'Graduated', 'value' => "Graduated", 'selected' => ($params->academic_status == "Graduated") ? 'selected' : ''],
            ],
            'filterEntryYear' => [
                ['name' => '2019', 'value' => "2019", 'selected' => ($params->entry_year == "2019") ? 'selected' : ''],
                ['name' => '2020', 'value' => "2020", 'selected' => ($params->entry_year == "2020") ? 'selected' : ''],
                ['name' => '2021', 'value' => "2021", 'selected' => ($params->entry_year == "2021") ? 'selected' : ''],
                ['name' => '2022', 'value' => "2022", 'selected' => ($params->entry_year == "2022") ? 'selected' : ''],
                ['name' => '2023', 'value' => "2023", 'selected' => ($params->entry_year == "2023") ? 'selected' : ''],
                ['name' => '2024', 'value' => "2024", 'selected' => ($params->entry_year == "2024") ? 'selected' : ''],
                ['name' => '2025', 'value' => "2025", 'selected' => ($params->entry_year == "2025") ? 'selected' : ''],
            ],
            'tableHeader' => [
                [
                    'name' => 'ID',
                    'href' => $params->getSortUrl('student_id', base_url('/students')),
                    'is_sorted' => $params->isSortedBy('student_id') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Student Name',
                    'href' => $params->getSortUrl('name', base_url('/students')),
                    'is_sorted' => $params->isSortedBy('name') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Study Program',
                    'href' => $params->getSortUrl('study_program', base_url('/students')),
                    'is_sorted' => $params->isSortedBy('study_program') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Current Semester',
                    'href' => $params->getSortUrl('current_semester', base_url('/students')),
                    'is_sorted' => $params->isSortedBy('current_semester') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Status',
                    'href' => $params->getSortUrl('academic_status', base_url('/students')),
                    'is_sorted' => $params->isSortedBy('academic_status') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Entry Year',
                    'href' => $params->getSortUrl('entry_year', base_url('/students')),
                    'is_sorted' => $params->isSortedBy('entry_year') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'GPA',
                    'href' => $params->getSortUrl('gpa', base_url('/students')),
                    'is_sorted' => $params->isSortedBy('gpa') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
            ],
        ];

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
