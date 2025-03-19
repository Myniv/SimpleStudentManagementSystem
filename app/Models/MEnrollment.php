<?php

namespace App\Models;

use CodeIgniter\Model;

class MEnrollment extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    protected $returnType = \App\Entities\Enrollment::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'student_id',
        'course_id',
        'academic_year',
        'semester',
        'status',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        //is unique column: is_unique[table_name.column_name,primary_key_column,ignore_value]
        'student_id' => 'required|integer',
        'course_id' => 'required|integer',
        'academic_year' => 'required|integer|exact_length[4]',
        'semester' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[14]',
        'status' => 'required|in_list[Pass,On Progress,Failed]',
    ];
    protected $validationMessages = [
        'student_id' => [
            'required' => 'Student ID is required.',
            'integer' => 'Student ID must be a valid number.'
        ],
        'course_id' => [
            'required' => 'Course ID is required.',
            'integer' => 'Course ID must be a valid number.'
        ],
        'academic_year' => [
            'required' => 'Academic year is required.',
            'integer' => 'Academic year must be a valid number.',
            'exact_length' => 'Entry year must be exactly 4 digits.',
        ],
        'semester' => [
            'required' => 'Semester is required.',
            'integer' => 'Semester must be a valid number.',
            'greater_than_equal_to' => 'Semester must be at least 1.',
            'less_than_equal_to' => 'Semester must not be more than 14.',
        ],
        'status' => [
            'required' => 'Status is required.',
            'in_list' => 'Status must be one of the following: Pass, On Progress, or Failed.'
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

    public function getEnrollmentBasedStudent($id)
    {
        return $this->select('enrollments.id, students.name AS student_name, courses.name AS course_name, enrollments.academic_year, enrollments.semester, enrollments.status')
            ->join('students', 'students.id = enrollments.student_id', 'left')
            ->join('courses', 'courses.id = enrollments.course_id', 'left')
            ->where('enrollments.student_id', $id)
            ->findAll();
    }

    public function getAllEnrollment()
    {
        return $this->select('enrollments.*, students.name AS student_name, courses.name AS course_name')
            ->join('students', 'students.id = enrollments.student_id', 'left')
            ->join('courses', 'courses.id = enrollments.course_id', 'left')
            ->findAll();
    }

    public function getStudentCoursesAndGrades($studentId)
    {
        return $this
            ->select('enrollments.*, students.name AS student_name, courses.name AS course_name, 
        courses.code AS course_code, courses.credits, student_grades.grade_letter AS grade_letter')
            ->join('students', 'students.id = enrollments.student_id')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->join('student_grades', 'student_grades.enrollment_id = enrollments.id', 'left')
            ->where('students.id', $studentId)
            ->findAll();
    }
    public function getAllStudentCoursesAndGrades()
    {
        return $this
            ->select('enrollments.*, students.name AS student_name, courses.name AS course_name, 
        courses.code AS course_code, courses.credits, student_grades.grade_letter AS grade_letter')
            ->join('students', 'students.id = enrollments.student_id')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->join('student_grades', 'student_grades.enrollment_id = enrollments.id', 'left')
            ->findAll();
    }


}
