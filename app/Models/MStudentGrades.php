<?php

namespace App\Models;

use App\Entities\Student_Grades;
use CodeIgniter\Model;

class MStudentGrades extends Model
{
    protected $table = 'student_grades';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = Student_Grades::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['enrollment_id', 'course_id', 'grade_value', 'grade_letter', 'status', 'completed_at'];

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
        'enrollment_id' => 'required|integer',
        'course_id' => 'required|integer',
        'grade_value' => 'permit_empty|decimal',
        'completed_at' => 'permit_empty|valid_date[Y-m-d]',
    ];

    protected $validationMessages = [
        'enrollment_id' => [
            'required' => 'Enrollment ID is required.',
            'integer' => 'Enrollment ID must be a number.',
        ],
        'course_id' => [
            'required' => 'Course ID is required.',
            'integer' => 'Course ID must be a number.',
        ],
        // 'grade_value' => [
        //     'required' => 'Grade value is required.',
        //     'decimal' => 'Grade value must be a decimal number.',
        // ],
        // 'completed_at' => [
        //     'required' => 'Completion date is required.',
        //     'valid_date' => 'Completed date must be in Y-m-d H:i:s format.',
        // ],
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

    public function getAllStudentCoursesEnrollment()
    {
        return $this->select('student_grades.*, students.name AS student_name, courses.name AS course_name')
            ->join('enrollments', 'enrollments.id = student_grades.enrollment_id', 'left')
            ->join('courses', 'courses.id = student_grades.course_id', 'left')
            ->join('students', 'students.id = enrollments.student_id', 'left')
            ->findAll();
    }

    public function getCreditDistributionByGrades($studentId)
    {
        return $this->select('student_grades.grade_letter as grade_letter, SUM(courses.credits) as total_credits')
            ->join('courses', 'courses.id = student_grades.course_id', 'left')
            ->join('enrollments', 'enrollments.id = student_grades.enrollment_id', 'left')
            ->where('enrollments.student_id', $studentId)
            ->where('student_grades.grade_letter IS NOT NULL')
            ->groupBy('student_grades.grade_letter')
            ->get() // ✅ Execute the query
            ->getResultArray(); // ✅ Fetch results as
    }

    public function getGPAPerSemester($studentId)
    {
        return $this
            ->select("SUM(courses.credits * 
        CASE 
            WHEN student_grades.grade_letter = 'A+' THEN 4.00
            WHEN student_grades.grade_letter = 'A' THEN 3.50
            WHEN student_grades.grade_letter = 'B+' THEN 3.00
            WHEN student_grades.grade_letter = 'B' THEN 2.50
            WHEN student_grades.grade_letter = 'B-' THEN 2.00
            WHEN student_grades.grade_letter = 'C' THEN 1.50
            WHEN student_grades.grade_letter = 'D' THEN 1.00
            ELSE 0.00 
        END) / SUM(courses.credits)::FLOAT AS gpa, enrollments.semester")
            ->join('enrollments', 'student_grades.enrollment_id = enrollments.id')
            ->join('courses', 'enrollments.course_id = courses.id')
            ->where('enrollments.student_id', $studentId)
            ->groupBy('enrollments.semester')
            ->get()
            ->getResultArray();
    }

}
