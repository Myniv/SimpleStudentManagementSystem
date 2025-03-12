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
        if (!logged_in()) {
            return redirect()->to('/login'); // Ensure user is logged in
        }

        $student = $this->studentModel->where('user_id', user()->id)->first();

        if (!in_array("student", user()->getRoles())) {
            $data['enrollments'] = $this->enrollmentModel->getAllEnrollment();
        } else {
            $data['enrollments'] = $this->enrollmentModel->getEnrollmentBasedStudent($student->id);
        }
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
}
