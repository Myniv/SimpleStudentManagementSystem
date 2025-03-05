<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Courses;
use App\Libraries\DataParams;
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
        $params = new DataParams([
            "search" => $this->request->getGet("search"),
            "credits" => $this->request->getGet("credits"),
            "semester" => $this->request->getGet("semester"),
            "perPage" => $this->request->getGet("perPage"),
            "sort" => $this->request->getGet("sort"),
            "order" => $this->request->getGet("order"),
            "page" => $this->request->getGet("page_courses"),
        ]);
        $result = $this->courseModel->getFilteredProducts($params);

        $data = [
            'courses' => $result['courses'],
            'pager' => $result['pager']->links('courses', 'custom_pager'),
            'total' => $result['total'],
            'search' => $params->search,
            'reset' => $params->getResetUrl(base_url('/courses')),
            'order' => $params->order,
            'sort' => $params->sort,
            'page' => $params->page,
            'perPageOptions' => [
                ['value' => 2, 'selected' => ($params->perPage == 2) ? 'selected' : ''],
                ['value' => 25, 'selected' => ($params->perPage == 25) ? 'selected' : ''],
                ['value' => 50, 'selected' => ($params->perPage == 50) ? 'selected' : ''],
            ],
            'tableHeader' => [
                [
                    'name' => 'ID',
                    'href' => $params->getSortUrl('id', base_url('/courses')),
                    'is_sorted' => $params->isSortedBy('id') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Course Name',
                    'href' => $params->getSortUrl('name', base_url('/courses')),
                    'is_sorted' => $params->isSortedBy('name') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Code',
                    'href' => $params->getSortUrl('code', base_url('/courses')),
                    'is_sorted' => $params->isSortedBy('code') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Credits',
                    'href' => $params->getSortUrl('credits', base_url('/courses')),
                    'is_sorted' => $params->isSortedBy('credits') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
                [
                    'name' => 'Semester',
                    'href' => $params->getSortUrl('semester', base_url('/courses')),
                    'is_sorted' => $params->isSortedBy('semester') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : ''
                ],
            ],
            'baseUrl' => base_url('/courses'),
            'filterCredits' => [
                ['name' => '1', 'value' => 1, 'selected' => ($params->credits == 1) ? 'selected' : ''],
                ['name' => '2', 'value' => 2, 'selected' => ($params->credits == 2) ? 'selected' : ''],
                ['name' => '3', 'value' => 3, 'selected' => ($params->credits == 3) ? 'selected' : ''],
                ['name' => '4', 'value' => 4, 'selected' => ($params->credits == 4) ? 'selected' : ''],
                ['name' => '5', 'value' => 5, 'selected' => ($params->credits == 5) ? 'selected' : ''],
                ['name' => '6', 'value' => 6, 'selected' => ($params->credits == 6) ? 'selected' : ''],

            ],
            'filterSemester' => [

                ['name' => '1', 'value' => 1, 'selected' => ($params->semester == 1) ? 'selected' : ''],
                ['name' => '2', 'value' => 2, 'selected' => ($params->semester == 2) ? 'selected' : ''],
                ['name' => '3', 'value' => 3, 'selected' => ($params->semester == 3) ? 'selected' : ''],
                ['name' => '4', 'value' => 4, 'selected' => ($params->semester == 4) ? 'selected' : ''],
                ['name' => '5', 'value' => 5, 'selected' => ($params->semester == 5) ? 'selected' : ''],
                ['name' => '6', 'value' => 6, 'selected' => ($params->semester == 6) ? 'selected' : ''],
                ['name' => '7', 'value' => 7, 'selected' => ($params->semester == 8) ? 'selected' : ''],
                ['name' => '8', 'value' => 8, 'selected' => ($params->semester == 8) ? 'selected' : ''],

            ],
        ];

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
