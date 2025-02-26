<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Courses;
use App\Models\MCourses;
use CodeIgniter\HTTP\ResponseInterface;

class CoursesController extends BaseController
{
    private $courseModel;
    public function __construct()
    {
        $this->courseModel = new MCourses();
    }
    public function index()
    {
        $parser = \Config\Services::parser();
        $courses = $this->courseModel->findAll();

        $coursesArray = [];
        foreach ($courses as $course) {
            $courseData = $course->toArray();
            $coursesArray[] = $courseData;
        }
        $data['courses'] = $coursesArray;

        $data['content'] = $parser->setData($data)
            ->render(
                "courses/v_course_list",
                // ['cache' => 86400, 'cache_name' => 'course_list']
            );
        return view("components/v_parser_layout", $data);
    }

    public function create()
    {
        $type = $this->request->getMethod();
        if ($type == "GET") {
            return view("courses/v_course_form");
        }

        $formData = [
            'code' => $this->request->getPost('code'),
            'name' => $this->request->getPost('name'),
            'credits' => $this->request->getPost('credits'),
            'semester' => $this->request->getPost('semester'),
        ];

        if (!$this->courseModel->validate($formData)) {
            return redirect()->back()->withInput()->with('errors', $this->courseModel->errors());
        }

        $this->courseModel->save($formData);

        return redirect()->to('/courses');
    }

    public function update($id)
    {
        $type = $this->request->getMethod();
        if ($type == "GET") {
            $data["course"] = $this->courseModel->find($id);
            return view("courses/v_course_form", $data);
        }

        $formData = [
            "id" => $id,
            'code' => $this->request->getPost('code'),
            'name' => $this->request->getPost('name'),
            'credits' => $this->request->getPost('credits'),
            'semester' => $this->request->getPost('semester'),
        ];

        $this->courseModel->setValidationRule("code", "required|is_unique[courses.code,id,{$id}]");

        if (!$this->courseModel->validate($formData)) {
            return redirect()->back()->withInput()->with('errors', $this->courseModel->errors());
        }

        $this->courseModel->save($formData);

        return redirect()->to('/courses');
    }

    public function delete($id)
    {
        $this->courseModel->delete($id);
        return redirect()->to('/courses');
    }
}
