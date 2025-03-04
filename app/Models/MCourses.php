<?php

namespace App\Models;

use CodeIgniter\Model;

class MCourses extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    protected $returnType = \App\Entities\Courses::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ["code", "name", "credits", "semester"];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        "code" => 'required|is_unique[courses.code,id,{id}]|exact_length[8]',
        'name' => 'required|min_length[3]|max_length[100]',
        'credits' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[6]',
        'semester' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[8]',
    ];
    protected $validationMessages = [
        "code" => [
            "required" => "Course code is required.",
            "is_unique" => "Course code must be unique.",
            "exact_length" => "Course code must be exactly 8 characters."
        ],
        "name" => [
            "required" => "Course name is required.",
            "min_length" => "Course name must be at least 3 characters.",
            "max_length" => "Course name must not exceed 100 characters.",
        ],
        "credits" => [
            "required" => "Course credits are required.",
            "integer" => "Course credits must be a number.",
            "greater_than_equal_to" => "Course credits must be at least 1.",
            "less_than_equal_to" => "Course credits cannot exceed 6."
        ],
        "semester" => [
            "required" => "Semester is required.",
            "integer" => "Semester must be a number.",
            "greater_than_equal_to" => "Semester must be at least 1.",
            "less_than_equal_to" => "Semester cannot exceed 8."
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}
