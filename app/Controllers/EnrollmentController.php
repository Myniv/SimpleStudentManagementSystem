<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MCourses;
use App\Models\MEnrollment;
use App\Models\MStudent;
use App\Models\MStudentGrades;
use CodeIgniter\HTTP\ResponseInterface;
use function PHPUnit\Framework\returnArgument;

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
    public function index()
    {
        if (!logged_in()) {
            return redirect()->to('/login'); // Ensure user is logged in
        }

        $student = $this->studentModel->where('user_id', user()->id)->first();

        if (!in_array("student", user()->getRoles())) {
            $data['enrollments'] = $this->enrollmentModel->getAllStudentCoursesAndGrades();
        } else {
            $data['enrollments'] = $this->enrollmentModel->getStudentCoursesAndGrades($student->id);
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
        if(!$this->studentGradesModel->save($studentGradesData)){
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
}
