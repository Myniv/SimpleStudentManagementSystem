<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\StudentDb;
use App\Libraries\DataParams;
use App\Models\MEnrollment;
use App\Models\MStudent;
use App\Models\MStudentGrades;
use CodeIgniter\HTTP\ResponseInterface;
use Myth\Auth\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment as Alignment;


class StudentController extends BaseController
{
    private $studentModel;
    private $enrollmentModel;
    private $studentGradesModel;
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->studentModel = new MStudent();
        $this->enrollmentModel = new MEnrollment();
        $this->studentGradesModel = new MStudentGrades();
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
            'exportUrl' => base_url('/admin/student/reports'),
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

        if (empty($student['high_school_diploma'])) {
            $student['high_school_diploma'] = "No Diploma Uploaded";
        } else {
            $student['high_school_diploma'] = '<a href="' . base_url('student/profile/view-diploma?file=' . $student['high_school_diploma']) . '" target="_blank">View Diploma</a>';
        }

        $student['button_upload_diploma'] = '';
        $student['success'] = '';

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
            $student['high_school_diploma'] = '<a href="' . base_url('student/profile/view-diploma?file=' . $student['high_school_diploma']) . '" target="_blank">View Diploma</a>';
        }
        $student['button_upload_diploma'] = '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#uploadDiplomaModal">
                Upload Diploma
            </button>';

        $student['validation_errors'] = session('validation_errors') ? implode('<br>', session('validation_errors')) : '';
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

    public function viewDiploma()
    {
        $filePath = $this->request->getGet('file');
        $filePath = basename($filePath);


        $fullPath = WRITEPATH . 'uploads/diplomas/' . $filePath;

        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filePath . '"')
            ->setBody(file_get_contents($fullPath));
    }

    public function dashboardStudent()
    {
        $user = user()->username;
        $newUser = $this->userModel->where('username', $user)->first();

        $student = $this->studentModel
            ->where('user_id', $newUser->id)
            ->first();

        $data['creditComparison'] = json_encode($this->getStudentCreditsCompariosn($student->id));
        $data['creditsByGrade'] = json_encode($this->getCreditsByGrade($student->id));
        // print_r($data['creditComparison']);


        return view('dashboard/v_dashboard_student', $data);
    }

    private function getCreditsByGrade($studentId)
    {
        $data = [];
        $grades = $this->studentGradesModel->getCreditDistributionByGrades($studentId);
        // dd($grades);

        foreach ($grades as $grade) {
            $data[] = [
                'grade_letter' => $grade['grade_letter'],
                'credits' => $grade['total_credits']
            ];
        }

        $backgroundColors = [
            'A' => 'rgb(54, 162, 235)', // Biru 
            'B+' => 'rgb(75, 192, 192)', // Cyan 
            'B' => 'rgb(153, 102, 255)', // Ungu 
            'C+' => 'rgb(255, 205, 86)', // Kuning
            'C' => 'rgb(255, 159, 64)', // Oranye 
            'D' => 'rgb(255, 99, 132)' // Merah
        ];

        $gradeLabels = [];
        $creditCounts = [];
        $colors = [];
        foreach ($data as $row) {
            $gradeLabels[] = $row['grade_letter'] . '=' . $row['credits'] . ' Credits';
            $creditCounts[] = (int) $row['credits'];
            $colors[] = $backgroundColors[$row['grade_letter']];
        }

        return [
            'labels' => $gradeLabels,
            'datasets' => [
                [
                    'label' => 'Credits By Grade',
                    'data' => $creditCounts,
                    'backgroundColor' => $colors,
                    'hoverOffset' => 4
                ]
            ]
        ];
    }

    private function getStudentCreditsCompariosn($studentId)
    {
        $credits = $this->enrollmentModel->getStudentCredits($studentId);

        $creditsRequired = [
            ['semester' => 1, 'credits_required' => 20],
            ['semester' => 2, 'credits_required' => 24],
            ['semester' => 3, 'credits_required' => 18],
            ['semester' => 4, 'credits_required' => 20],
            ['semester' => 5, 'credits_required' => 18],
            ['semester' => 6, 'credits_required' => 16],
            ['semester' => 7, 'credits_required' => 18],
            ['semester' => 8, 'credits_required' => 18],
        ];

        foreach ($credits as $row) {
            $tempCreditsRequired = 0;
            foreach ($creditsRequired as $value) {
                if ($value['semester'] == $row['semester']) {
                    $tempCreditsRequired = $value['credits_required'];
                }
            }
            $data[] = [
                'semester' => $row['semester'],
                'credits_taken' => $row['total_credits'],
                'credits_required' => $tempCreditsRequired
            ];
        }

        $labels = [];
        $creditsTaken = [];
        $creditsRequired = [];
        foreach ($data as $row) {
            $labels[] = 'Semester ' . $row['semester'];
            $creditsTaken[] = (int) $row['credits_taken'];
            $creditsRequired[] = (int) $row['credits_required'];
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Credits Taken',
                    'data' => $creditsTaken,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1
                ],
                [
                    'label' => 'Credits Required',
                    'data' => $creditsRequired,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'borderWidth' => 1
                ]
            ]
        ];
    }

    public function reportStudentExcel()
    {
        $params = new DataParams([
            "search" => $this->request->getGet("search"),
            "study_program" => $this->request->getGet("study_program"),
            "academic_status" => $this->request->getGet("academic_status"),
            "entry_year" => $this->request->getGet("entry_year"),
        ]);
        $enrollments = $this->studentModel->getFilteredExcels($params);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Lapora Enrollment Mata Kuliah');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Filter');
        $sheet->setCellValue('B3', 'Student ID :' . ($student_id ?? 'Semua'));
        $sheet->setCellValue('D3', 'Nama:' . ($name ?? 'Semua'));
        $sheet->getStyle('A3:D3')->getFont()->setBold(true);

        $headers = [
            'A5' => 'No',
            'B5' => 'Student ID',
            'C5' => 'Nama',
            'D5' => 'Program Studi',
            'E5' => 'Semester',
            'F5' => 'Kode Mata Kuliah',
            'G5' => 'Mata Kuliah',
            'H5' => 'SKS',
            'J5' => 'Tahun Akademik',
            'L5' => 'Status',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $row = 6;
        $no = 1;
        foreach ($enrollments as $enrollment) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $enrollment->student_id);
            $sheet->setCellValue('C' . $row, $enrollment->name);
            $sheet->setCellValue('D' . $row, $enrollment->study_program);
            $sheet->setCellValue('E' . $row, $enrollment->current_semester);
            // $sheet->setCellValue('F' . $row, $enrollment->course_code);
            // $sheet->setCellValue('G' . $row, $enrollment->course_name);
            // $sheet->setCellValue('H' . $row, $enrollment->credits);
            // $sheet->setCellValue('I' . $row, $enrollment->academic_year . ' - ' . $enrollment->enrollment_semester);
            // $sheet->setCellValue('J' . $row, $enrollment->status);

            $row++;
            $no++;
        }

        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A5:J' . ($row - 1))->applyFromArray($styleArray);



        $filename = 'Laporan_Mata_Kuliah_Enrol_' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

}


