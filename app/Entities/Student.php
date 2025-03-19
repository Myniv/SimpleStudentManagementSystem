<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Student extends Entity
{
    protected $attributes = [
        "id"=> null,
        "student_id" => null,
        "name" => null,
        "study_program" => null,
        "current_semester"=>null,
        "academic_status"=>null,
        "entry_year"=>null,
        "gpa"=>null,
        "user_id"=>null,
        "high_school_diploma" => null,
        "created_at" => null,
        "updated_at"=> null,
    ];

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'user_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
