<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Student_Grades extends Entity
{
    protected $attributes = [
        "id" => null,
        "enrollment_id" => null,
        "course_id" => null,
        "grade_value" => null,
        "grade_letter" => null,
        "status" => null,
        "completed_at" => null,
        "created_at" => null,
        "updated_at" => null,
    ];

    protected $casts = [
        'id' => 'integer',
        'enrollment_id' => 'integer',
        'course_id' => 'integer',
        'grade_value' => 'integer',
        'grade_letter' => 'string',
        'status' => 'string',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function setGradeLetter()
    {
        if (!isset($this->attributes['grade_value'])) {
            return null; // Prevent errors if grade_value is not set
        }

        $grade = $this->attributes['grade_value'];

        if ($grade >= 90) {
            $this->attributes['grade_letter'] = 'A+';
        } elseif ($grade >= 80) {
            $this->attributes['grade_letter'] = 'A';
        } elseif ($grade >= 70) {
            $this->attributes['grade_letter'] = 'B';
        } elseif ($grade >= 60) {
            $this->attributes['grade_letter'] = 'C';
        } elseif ($grade >= 50) {
            $this->attributes['grade_letter'] = 'D';
        } else {
            $this->attributes['grade_letter'] = 'F';
        }

        return $this->attributes['grade_letter'];
    }

    public function setGradeValue($value)
    {
        $this->attributes['grade_value'] = $value;
        $this->setGradeLetter();
        $this->setStatus();
        return $this;
    }

    protected $beforeInsert = ['updateGradeDetails'];
    protected $beforeUpdate = ['updateGradeDetails'];

    protected function updateGradeDetails(array $data)
    {
        if (isset($data['data']['grade_value'])) {
            $this->setGradeValue($data['data']['grade_value']);
        }
        return $data;
    }


    public function setStatus()
    {
        if ($this->attributes['grade_value'] >= 60) {
            $this->attributes['status'] = 'Pass';
        } elseif ($this->attributes['grade_value'] < 60) {
            $this->attributes['status'] = 'Failed';
        } else {
            $this->attributes['status'] = "On Progress";
        }
    }

}
