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
use TCPDF;


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
            'exportUrl' => base_url('/admin/student/report') . '?' . http_build_query([
                'study_program' => $params->study_program,
                'entry_year' => $params->entry_year,
            ]),
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
        $data['gpaData'] = json_encode($this->getGpaPerSemester($student->id));
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
                    break;
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

    private function getGpaPerSemester($studentId)
    {
        $data = $this->studentGradesModel->getGPAPerSemester($studentId);
        // dd($data);

        $semesters = [];
        $gpaData = [];
        foreach ($data as $row) {
            $semesters[] = 'Semester ' . $row['semester'];
            $gpaData[] = round($row['gpa'], 2);
            // $gpaData[] = $row['gpa'];
        }

        return [
            'labels' => $semesters,
            'datasets' => [
                [
                    'label' => 'GPA',
                    'data' => $gpaData,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'tension' => 0.1,
                    'fill' => false
                ]
            ]
        ];
    }

    public function viewStudentReportsPdf()
    {
        $studyProgram = $this->request->getVar('study_program');
        $entryYear = $this->request->getVar('entry_year');

        if (!empty($studyProgram) && !empty($entryYear)) {
            $student = $this->studentModel->where('study_program', $studyProgram)->where('entry_year', $entryYear)->findAll();
        } else if (!empty($studyProgram)) {
            $student = $this->studentModel->where('study_program', $studyProgram)->findAll();
        } else if (!empty($entryYear)) {
            $student = $this->studentModel->where('entry_year', $entryYear)->findAll();
        } else {
            $student = $this->studentModel->findAll();
        }

        $data['students'] = $student;
        $data['study_program'] = $this->studentModel->getAllStudyPrograms();
        $data['entry_year'] = $this->studentModel->getAllEntryYear();
        $data['entry_year_selected'] = $entryYear;
        $data['study_program_selected'] = $studyProgram;

        return view('reports/v_report_students', $data);
    }

    public function studentReportsPdf()
    {
        $studyProgram = $this->request->getVar('study_program');
        $entryYear = $this->request->getVar('entry_year');

        $pdf = $this->initTcpdf();

        if (!empty($studyProgram) && !empty($entryYear)) {
            $student = $this->studentModel->where('study_program', $studyProgram)->where('entry_year', $entryYear)->findAll();
        } else if (!empty($studyProgram)) {
            $student = $this->studentModel->where('study_program', $studyProgram)->findAll();
        } else if (!empty($entryYear)) {
            $student = $this->studentModel->where('entry_year', $entryYear)->findAll();
        } else {
            $student = $this->studentModel->findAll();
        }

        $this->generatePdfHtmlContent($pdf, $student, $studyProgram, $entryYear);
        // $this->generatePdfContent($pdf, $student, $studyProgram, $entryYear);

        // Output PDF
        $filename = 'laporan_mahasiswa_' . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'I');
        exit;
    }

    private function initTcpdf()
    {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator('CodeIgniter 4');
        $pdf->SetAuthor('Administrator');
        $pdf->SetTitle('Laporan Mahasiswa');
        $pdf->SetSubject('Laporan Data Mahasiswa');

        //To set the image in pdf, 
        //set this : define ('K_PATH_IMAGES', FCPATH. '/');
        //in this path : vendor/tecnickcom/tcpdf/config/tcpdf_config:
        $pdf->SetHeaderData('iconOrang.png', 10, 'UNIVERSITAS XYZ', '', [0, 0, 0], [0, 64, 128]);
        $pdf->setFooterData([0, 64, 0], [0, 64, 128]);

        $pdf->setHeaderFont(['helvetica', '', 12]);
        $pdf->setFooterFont(['helvetica', '', 8]);

        $pdf->SetMargins(15, 20, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        $pdf->SetAutoPageBreak(true, 25);

        $pdf->SetFont('helvetica', '', 10);

        $pdf->AddPage();

        return $pdf;
    }

    public function generatePdfHtmlContent($pdf, $students, $studyProgram, $entryYear)
    {
        // $image_file = K_PATH_IMAGES . 'iconOrang.png';
        // $pdf->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $title = 'LAPORAN DATA MAHASISWA';

        if (!empty($studyProgram)) {
            $title .= ' - PROGRAM STUDI: ' . $studyProgram;
        }

        if (!empty($entryYear)) {
            $title .= ' - TAHUN MASUK: ' . $entryYear;
        }

        $html = '<h2 style="text-align:center;">' . $title . '</h2>
      <table border="1" cellpadding="5" cellspacing="0" style="width:100%;">
        <thead>
          <tr style="background-color:#CCCCCC; font-weight:bold; text-align:center;">
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Semester</th>
            <th>Status</th>
            <th>Tahun Masuk</th>
            <th>IPK</th>
          </tr>
         </thead>
         <tbody>';

        $no = 1;
        foreach ($students as $student) {
            $html .= '
           <tr>
            <td style="text-align:center;">' . $no . '</td>
            <td>' . $student->student_id . '</td>
            <td>' . $student->name . '</td>
            <td>' . $student->study_program . '</td>
            <td style="text-align:center;">' . $student->current_semester . '</td>
            <td style="text-align:center;">' . $student->academic_status . '</td>
            <td style="text-align:center;">' . $student->entry_year . '</td>
            <td style="text-align:center; font-weight:bold;">' . $student->gpa . '</td>
           </tr>';
            $no++;
        }

        $html .= '
               </tbody>
           </table>
           
           <p style="margin-top:30px; text-align:left;">      
               Total Mahasiswa: ' . count($students) . ' 
           </p>
   
           <p style="margin-top:30px; text-align:right;">    
               Tanggal Cetak: ' . date('d-m-Y H:i:s') . '<br> 
           </p>';
        $pdf->writeHTML($html, true, false, true, false, '');
    }

}


