<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MCourses;
use App\Models\MEnrollment;
use App\Models\MStudent;
use CodeIgniter\HTTP\ResponseInterface;
use function PHPUnit\Framework\returnArgument;

class EnrollmentController extends BaseController
{
    private $enrollmentModel;
    private $studentModel;
    private $courseModel;
    public function __construct()
    {
        $this->enrollmentModel = new MEnrollment();
        $this->studentModel = new MStudent();
        $this->courseModel = new MCourses();
    }
    public function index()
    {
        $data['enrollments'] = $this->enrollmentModel
            ->select('enrollments.id, students.name AS student_name, courses.name AS course_name, enrollments.academic_year, enrollments.semester, enrollments.status')
            ->join('students', 'students.id = enrollments.student_id', 'left')
            ->join('courses', 'courses.id = enrollments.course_id', 'left')
            ->findAll();
        ;
        return view('enrollments/v_enrollment_list', $data);
    }

    public function create()
    {
        $type = $this->request->getMethod();
        if ($type == "GET") {
            $data["students"] = $this->studentModel->findAll();
            $data["courses"] = $this->courseModel->findAll();
            return view("enrollments/v_enrollment_form", $data);
        }

        $formData = [
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

    public function update($id)
    {
        $type = $this->request->getMethod();
        if ($type == "GET") {
            $data["students"] = $this->studentModel->findAll();
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
}
