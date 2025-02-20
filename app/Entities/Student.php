<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Student 
{
    private $id;
    private $name;
    private $program;
    private $course = [];
    private $semester;
    private $gpa;
    private $status;

    public function __construct($id, $name, $program, array $course, $semester, $gpa, $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->program = $program;
        $this->course = $course;
        $this->semester = $semester;
        $this->gpa = $gpa;
        $this->status = $status;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getProgram()
    {
        return $this->program;
    }
    public function getCourse()
    {
        return $this->course;
    }
    public function getSemester()
    {
        return $this->semester;
    }
    public function getGpa()
    {
        return $this->gpa;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
