<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Student_Grades;
use App\Models\MEnrollment;
use App\Models\MStudentGrades;
use CodeIgniter\HTTP\ResponseInterface;

class StudentGradesController extends BaseController
{
    private $enrollmentModel;
    private $studentGradesModel;
    public function __construct()
    {
        $this->enrollmentModel = new MEnrollment();
        $this->studentGradesModel = new MStudentGrades();
    }

    public function index()
    {
        if (!logged_in()) {
            return redirect()->to('/login'); // Ensure user is logged in
        }
        $student_grades = $this->studentGradesModel->getAllStudentCoursesEnrollment();
        $data = [
            'student_grades' => $student_grades,
        ];
        return view('student_grades/v_student_grades_list', $data);
    }

    public function create()
    {
        $type = $this->request->getMethod();
        if ($type == "GET") {
            $data['enrollments'] = $this->enrollmentModel->getAllEnrollment();
            return view('student_grades/v_student_grades_form', $data);
        }

        $enrollments = explode(",", $this->request->getPost('enrollments'));

        $formData = [
            'grade_value' => $this->request->getPost('grade_value'),
            'completed_at' => $this->request->getPost('completed_at'),
            'enrollment_id' => $enrollments[0],
            'course_id' => $enrollments[1]
        ];

        if (!$this->enrollmentModel->validate($formData)) {
            return redirect()->back()->withInput()->with('errors', $this->enrollmentModel->errors());
        }
        $studentGrade = new Student_Grades($formData);


        $this->studentGradesModel->save($studentGrade);
        return redirect()->to('/lecturer/student-grades');
    }

    public function update($id)
    {
        $type = $this->request->getMethod();
        if ($type == "GET") {
            $data['enrollments'] = $this->enrollmentModel->getAllEnrollment();
            $data['student_grades'] = $this->studentGradesModel->find($id);
            return view('student_grades/v_student_grades_form', $data);
        }

        // $enrollments = explode(",", $this->request->getPost('enrollments'));
        $formData = [
            'id' => $id,
            'grade_value' => $this->request->getPost('grade_value'),
            'completed_at' => $this->request->getPost('completed_at'),
            // 'enrollment_id' => $enrollments[0],
            // 'course_id' => $enrollments[1]
        ];

        if (!$this->enrollmentModel->validate($formData)) {
            return redirect()->back()->withInput()->with('errors', $this->enrollmentModel->errors());
        }
        $studentGrade = new Student_Grades($formData);


        $this->studentGradesModel->save($studentGrade);
        return redirect()->to('/lecturer/student-grades');
    }

    public function delete($id)
    {
        $this->studentGradesModel->delete($id);
        return redirect()->to('/lecturer/student-grades');
    }

}
