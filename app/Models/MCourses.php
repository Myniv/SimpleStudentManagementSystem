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
        "code" => 'required|is_unique[courses.code,id,{id}]',
        'name' => 'required|min_length[3]|max_length[100]',
        'credits' => 'required|integer|min_length[1]|max_length[3]',
        'semester' => 'required|integer|min_length[1]|max_length[2]',
    ];
    protected $validationMessages = [
        'code' => [
            'required' => 'Course code is required.',
            'is_unique' => 'Course code must be unique.',
        ],
        'name' => [
            'required' => 'Course name is required.',
            'min_length' => 'Course name must be at least 3 characters long.',
            'max_length' => 'Course name must not exceed 100 characters.',
        ],
        'credits' => [
            'required' => 'Credits are required.',
            'integer' => 'Credits must be a valid number.',
            'min_length' => 'Credits must be at least 1 digit.',
            'max_length' => 'Credits must not exceed 3 digits.',
        ],
        'semester' => [
            'required' => 'Semester is required.',
            'integer' => 'Semester must be a valid number.',
            'min_length' => 'Semester must be at least 1 digit.',
            'max_length' => 'Semester must not exceed 2 digits.',
        ],
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
