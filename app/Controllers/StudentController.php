<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\StudentDb;
use App\Libraries\DataParams;
use App\Models\MEnrollment;
use App\Models\MStudent;
use CodeIgniter\HTTP\ResponseInterface;
use Myth\Auth\Models\UserModel;

class StudentController extends BaseController
{
    private $studentModel;
    private $enrollmentModel;
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->studentModel = new MStudent();
        $this->enrollmentModel = new MEnrollment();
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

        $studyProgram = $this->studentModel->getAllStudyPrograms();
        foreach ($studyProgram as $value) {
            $value->selected = $params->study_program == $value->study_program ? 'selected' : '';
        }

        $academicStatus = $this->studentModel->getAllAcademicStatuses();
        foreach ($academicStatus as $value) {
            $value->selected = $params->academic_status == $value->academic_status ? 'selected' : '';
        }

        $entryYear = $this->studentModel->getAllEntryYear();
        foreach ($entryYear as $value) {
            $value->selected = $params->entry_year == $value->entry_year ? 'selected' : '';
        }

        $data = [
            'students' => $result['students'],
            'pager' => $result['pager']->links('students', 'custom_pager'),
            'total' => $result['total'],
            'search' => $params->search,
            'reset' => $params->getResetUrl(base_url('/admin/student')),
            'order' => $params->order,
            'sort' => $params->sort,
            'page' => $params->page,
            'baseUrl' => base_url('/admin/student'),
            'perPageOptions' => [
                ['value' => 5, 'selected' => ($params->perPage == 5) ? 'selected' : ''],
                ['value' => 10, 'selected' => ($params->perPage == 10) ? 'selected' : ''],
                ['value' => 25, 'selected' => ($params->perPage == 25) ? 'selected' : ''],
            ],
            'filterStudyProgram' => $studyProgram,
            'filterAcademicStatus' => $academicStatus,
            'filterEntryYear' => $entryYear,
            'tableHeader' => [
                [
                    'name' => 'ID',
                    'href' => $params->getSortUrl('student_id', base_url('/admin/student')),
                    'is_sorted' => $params->isSortedBy('student_id') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Student Name',
                    'href' => $params->getSortUrl('name', base_url('/admin/student')),
                    'is_sorted' => $params->isSortedBy('name') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Study Program',
                    'href' => $params->getSortUrl('study_program', base_url('/admin/student')),
                    'is_sorted' => $params->isSortedBy('study_program') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Current Semester',
                    'href' => $params->getSortUrl('current_semester', base_url('/admin/student')),
                    'is_sorted' => $params->isSortedBy('current_semester') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Status',
                    'href' => $params->getSortUrl('academic_status', base_url('/admin/student')),
                    'is_sorted' => $params->isSortedBy('academic_status') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Entry Year',
                    'href' => $params->getSortUrl('entry_year', base_url('/admin/student')),
                    'is_sorted' => $params->isSortedBy('entry_year') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'GPA',
                    'href' => $params->getSortUrl('gpa', base_url('/admin/student')),
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

    public function profile()
    {
        $parser = \Config\Services::parser();

        $user = user()->username;
        $newUser = $this->userModel->where('username', $user)->first();

        $getStudent = $this->studentModel
            ->where('user_id', $newUser->id)
            ->first();

        $student = $getStudent->toArray();
        $student['status_cell'] = view_cell('AcademicStatusCell', ['status' => $student['academic_status']]);
        $student['profile_picture'] = base_url("iconOrang.png");

        if (empty($student['high_school_diploma'])) {
            $student['high_school_diploma'] = "No Diploma Uploaded";
        } else {
            $student['high_school_diploma'] = '<a href="javascript:void(0);" onclick="viewDiploma(\'' . $student['high_school_diploma'] . '\');">View Diploma</a>';
        }

        $student['validation_errors'] = session('validation_errors') ?? '';
        $student['success'] = session('success') ?? '';
        $student['modal_error'] = session('modalError') ? 'true' : 'false';

        $data['content'] = $parser->setData($student)->render("students/v_student_profile");

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

        return redirect()->to('/admin/student');
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

        return redirect()->to('/admin/student');
    }

    public function delete($id)
    {
        $this->studentModel->delete($id);
        return redirect()->to('/admin/student');
    }

    public function uploadDiploma()
    {
        $user = user()->username;
        $newUser = $this->userModel->where('username', $user)->first();

        $student = $this->studentModel
            ->where('user_id', $newUser->id)
            ->first();

        $file = $this->request->getFile('high_school_diploma');

        $validationRules = [
            'high_school_diploma' => [
                'label' => 'Diploma',
                'rules' => 'uploaded[high_school_diploma]|mime_in[high_school_diploma,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]|max_size[high_school_diploma,5120]',
                'errors' => [
                    'uploaded' => 'Please select a file to upload.',
                    'mime_in' => 'File must be in PDF, DOC, or DOCX format.',
                    'max_size' => 'File size must not exceed 5MB.'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->with('modalError', true)
                ->with('validation_errors', $this->validator->getErrors());
        }

        // Ensure directory exists
        $uploadPath = WRITEPATH . 'uploads/diplomas/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Save file
        $newName = $student->student_id . "_" . date('Y-m-d_H-i-s') . "." . $file->getExtension();
        $file->move($uploadPath, $newName);

        $filePath = 'uploads/diplomas/' . $newName;

        $updateData =
            [
                'id' => $student->id,
                'high_school_diploma' => $filePath,
            ];
        $this->studentModel->save($updateData);

        return redirect()->back()->with('success', 'Diploma uploaded successfully.');
    }
}
