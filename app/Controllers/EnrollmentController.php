<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DataParams;
use App\Models\MCourses;
use App\Models\MEnrollment;
use App\Models\MStudent;
use App\Models\MStudentGrades;
use CodeIgniter\HTTP\ResponseInterface;
use function PHPUnit\Framework\returnArgument;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment as Alignment;

class EnrollmentController extends BaseController
{
    private $enrollmentModel;
    private $studentModel;
    private $studentGradesModel;
    private $courseModel;
    public function __construct()
    {
        $this->enrollmentModel = new MEnrollment();
        $this->studentModel = new MStudent();
        $this->courseModel = new MCourses();
        $this->studentGradesModel = new MStudentGrades();
    }
    // public function index()
    // {
    //     if (!logged_in()) {
    //         return redirect()->to('/login'); // Ensure user is logged in
    //     }

    //     $student = $this->studentModel->where('user_id', user()->id)->first();

    //     if (!in_array("student", user()->getRoles())) {
    //         $data['enrollments'] = $this->enrollmentModel->getAllStudentCoursesAndGrades();
    //     } else {
    //         $data['enrollments'] = $this->enrollmentModel->getStudentCoursesAndGrades($student->id);
    //     }
    //     return view('enrollments/v_enrollment_list', $data);
    // }

    public function index()
    {
        $params = new DataParams([
            "search" => $this->request->getGet("search"),

            "student_id" => $this->request->getGet("student_id"),
            "course_id" => $this->request->getGet("course_id"),
            "status" => $this->request->getGet("status"),

            "sort" => $this->request->getGet("sort"),
            "order" => $this->request->getGet("order"),
            "perPage" => $this->request->getGet("perPage"),
            "page" => $this->request->getGet("page_enrollments"),
        ]);

        $student = $this->studentModel->where('user_id', user()->id)->first();

        if (!in_array("student", user()->getRoles())) {
            $result = $this->enrollmentModel->getFilteredEnrollments($params);
        } else {
            $result = $this->enrollmentModel->getFilteredEnrollments($params, $student->id);
        }

        $data = [
            'enrollments' => $result['enrollments'],
            'pager' => $result['pager'],
            'total' => $result['total'],
            'params' => $params,
            'students' => $this->enrollmentModel->getAllStudentEnrollments(),
            'courses' => $this->enrollmentModel->getAllCoursesEnrollments(),
            'statuss' => $this->enrollmentModel->getAllStatusEnrollments(),
            'baseUrl' => base_url('enrollments'),
        ];

        return view('enrollments/v_enrollment_list', $data);
    }

    public function create()
    {
        $type = $this->request->getMethod();
        if ($type == "GET") {
            if (!logged_in()) {
                return redirect()->to('/login'); // Ensure user is logged in
            }
            if (!in_array("student", user()->getRoles())) {
                $data["students"] = $this->studentModel->findAll();
            } else {
                $data['students'] = $this->studentModel->where('user_id', user()->id)->findAll();
            }
            $data["courses"] = $this->courseModel->findAll();
            return view("enrollments/v_enrollment_form", $data);
        }

        $courseId = $this->request->getPost('course_id');
        $formData = [
            'student_id' => $this->request->getPost('student_id'),
            'course_id' => $courseId,
            "academic_year" => $this->request->getPost("academic_year"),
            "semester" => $this->request->getPost("semester"),
            "status" => $this->request->getPost("status"),
        ];

        if (!$this->enrollmentModel->validate($formData)) {
            return redirect()->back()->withInput()->with('errors', $this->enrollmentModel->errors());
        }

        $existingEnrollment = $this->enrollmentModel
            ->where('student_id', $formData['student_id'])
            ->where('course_id', $formData['course_id'])
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->withInput()->with('error', 'You are already enrolled in this course.');
        }

        $this->enrollmentModel->save($formData);
        $enrollmentsId = $this->enrollmentModel->getInsertID();

        $studentGradesData = [
            'enrollment_id' => $enrollmentsId,
            'course_id' => $formData['course_id'],
            'grade_value' => null,
            'grade_letter' => null,
            'status' => null,
        ];
        // dd($studentGradesData);
        if (!$this->studentGradesModel->save($studentGradesData)) {
            return redirect()->back()->withInput()->with('errorGrades', $this->studentGradesModel->errors());
        }

        if (in_array("student", user()->getRoles())) {
            $student = $this->studentModel->where('user_id', user()->id)->first();
            $course = $this->courseModel->getCourseBasedOnEnrollmentId($enrollmentsId);
            $enrollments = $this->enrollmentModel->find($enrollmentsId);

            //send email
            $email = service('email');
            $email->setFrom('mulyanan@solecode.id');
            $email->setTo(user()->email);
            $email->setSubject('Course Registration Notification');
            $data = [
                'title' => 'Course Registration',
                'name' => $student->name . ' (' . $student->student_id . ')',
                'content' => '',
                'features_title' => 'Course Details',
                'features' => [
                    'Courses : ' . $course->name . ' (' . $course->code . ')',
                    'Credits Hours : ' . $course->credits,
                    'Registration At : ' . $enrollments->created_at
                ],
            ];
            $email->setMessage(view('email/email_template', $data));
            $email->send();
        }

        return redirect()->to('/enrollments');
    }

    public function update($id)
    {
        $type = $this->request->getMethod();
        if ($type == "GET") {
            if (!in_array("student", user()->getRoles())) {
                $data["students"] = $this->studentModel->findAll();
            } else {
                $data['students'] = $this->studentModel->where('user_id', user()->id)->findAll();
            }
            $data["courses"] = $this->courseModel->findAll();

            $data["enrollment"] = $this->enrollmentModel->find($id);
            return view("enrollments/v_enrollment_form", $data);
        }

        $formData = [
            'id' => $id,
            'student_id' => $this->request->getPost('student_id'),
            'course_id' => $this->request->getPost('course_id'),
            "academic_year" => $this->request->getPost("academic_year"),
            "semester" => $this->request->getPost("semester"),
            "status" => $this->request->getPost("status"),
        ];

        if (!$this->enrollmentModel->validate($formData)) {
            return redirect()->back()->withInput()->with('errors', $this->enrollmentModel->errors());
        }

        $this->enrollmentModel->save($formData);
        return redirect()->to('/enrollments');
    }

    public function delete($id)
    {
        $this->enrollmentModel->delete($id);
        return redirect()->to('/enrollments');
    }

    public function getViewReportStudentExcel(){
        $search = $this->request->getGet("search");
        $enrollments = $this->enrollmentModel->getEnrollmentBasedStudent($search);

        $data['enrollments'] = $enrollments;
        $data['search'] = $search;
        return view('reports/v_report_enrollments', $data);
    }

    public function reportStudentExcel()
    {
        $search = $this->request->getGet("search");
        $enrollments = $this->enrollmentModel->getEnrollmentBasedStudent($search);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Lapora Enrollment Mata Kuliah');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Filter');
        // $sheet->setCellValue('B3', 'Student ID :' . ($student_id ?? 'Semua'));
        // $sheet->setCellValue('D3', 'Nama:' . ($name ?? 'Semua'));
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
            'I5' => 'Tahun Akademik',
            'J5' => 'Status',
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
            $sheet->setCellValue('C' . $row, $enrollment->student_name);
            $sheet->setCellValue('D' . $row, $enrollment->study_program);
            $sheet->setCellValue('E' . $row, $enrollment->current_semester);
            $sheet->setCellValue('F' . $row, $enrollment->course_code);
            $sheet->setCellValue('G' . $row, $enrollment->course_name);
            $sheet->setCellValue('H' . $row, $enrollment->credits);
            $sheet->setCellValue('I' . $row, $enrollment->academic_year . ' - ' . $enrollment->enrollment_semester);
            $sheet->setCellValue('J' . $row, $enrollment->status);

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
