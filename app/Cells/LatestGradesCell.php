<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class LatestGradesCell extends Cell
{
    protected $course = [];
    protected $filter = false;
    private $latestCourse = [];

    public function mount()
    {
        if ($this->filter) {
            $this->latestCourse = array_slice($this->course, -5, 5, true);
        } else {
            $this->latestCourse = $this->course;
        }
    }

    public function getCourseProperty()
    {
        return $this->course;
    }

    public function getLatestCourseProperty()
    {
        return $this->latestCourse;
    }

    public function getFilterProperty()
    {
        return $this->filter;
    }
}
