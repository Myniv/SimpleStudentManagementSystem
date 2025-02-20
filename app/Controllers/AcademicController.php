<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MAcademic;
use App\Models\MStudent;
use CodeIgniter\HTTP\ResponseInterface;

class AcademicController extends BaseController
{
    private $academicModel;
    private $studentModel;
    public function __construct()
    {
        $this->academicModel = new MAcademic();
        $this->studentModel = new MStudent();
    }
    public function index()
    {
        $parser = \Config\Services::parser();
        $data['academics'] = $this->academicModel->getAcademicArray();

        $data['content'] = $parser->setData($data)
            ->render("academics/v_course_list", ['cache' => DAY, 'cache_name' => 'course_list']);


        return view("components/v_parser_layout", $data);
    }

    public function academicStatistics()
    {
        $data['academics'] = count($this->academicModel->getAcademics());
        $data['students'] = count($this->studentModel->getStudents());

        cache()->save("cache_academics_statistic_cell", $data, 86400);

        return view('academics/v_academics_statistic', $data);
    }
}
