<?php

namespace App\Models;

use App\Libraries\DataParams;
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

    public function getFilteredEnrollments(DataParams $params, $studentId = null)
    {
        if (!in_array("student", user()->getRoles()) || empty($studentId)) {
            $this
                ->select('enrollments.*, students.name AS student_name, courses.name AS course_name, 
            courses.code AS course_code, courses.credits, student_grades.grade_letter AS grade_letter')
                ->join('students', 'students.id = enrollments.student_id')
                ->join('courses', 'courses.id = enrollments.course_id')
                ->join('student_grades', 'student_grades.enrollment_id = enrollments.id', 'left');
        } else {
            $this
                ->select('enrollments.*, students.name AS student_name, courses.name AS course_name, 
        courses.code AS course_code, courses.credits, student_grades.grade_letter AS grade_letter')
                ->join('students', 'students.id = enrollments.student_id')
                ->join('courses', 'courses.id = enrollments.course_id')
                ->join('student_grades', 'student_grades.enrollment_id = enrollments.id', 'left')
                ->where('students.id', $studentId);
        }


        if (!empty($params->search)) {
            $this->groupStart()
                ->like('students.name', $params->search, 'both', null, true)
                ->orLike('courses.name', $params->search, 'both', null, true)
                ->orLike('courses.code', $params->search, 'both', null, true)
                ->orLike('student_grades.grade_letter', $params->search, 'both', null, true)
                ->orLike('enrollments.status', $params->search, 'both', null, true); // Searching status in enrollments

            if (is_numeric($params->search)) {
                $this->orWhere('CAST (enrollments.student_id AS TEXT) LIKE', "%$params->search%")
                    ->orWhere('CAST (enrollments.academic_year AS TEXT) LIKE', "%$params->search%")
                    ->orWhere('CAST (enrollments.semester AS TEXT) LIKE', "%$params->search%")
                    ->orWhere('CAST (courses.credits AS TEXT) LIKE', "%$params->search%");
            }
            $this->groupEnd();
        }

        if (!empty(($params->student_id))) {
            $this->where("enrollments.student_id", $params->student_id);
        }

        if (!empty($params->course_id)) {
            $this->where("enrollments.course_id", $params->course_id);
        }

        if (!empty($params->status)) {
            $this->where("enrollments.status", $params->status);
        }

        $allowedSortColumns = [
            'id',               // Enrollment ID
            'students.name',    // Student name
            'courses.name',     // Course name
            'enrollments.academic_year', // Academic Year
            'enrollments.semester', // Semester
            'enrollments.status', // Enrollment status
            'student_grades.grade_letter' // Grades
        ];
        $sort = in_array($params->sort, $allowedSortColumns) ? $params->sort : 'id';
        $order = ($params->order == 'asc') ? 'asc' : 'desc';

        $this->orderBy($sort, $order);
        $result = [
            'enrollments' => $this->paginate($params->perPage, 'enrollments', $params->page),
            'pager' => $this->pager,
            'total' => $this->countAllResults(false),
        ];

        return $result;
    }

    public function getEnrollmentBasedStudent($id)
    {
        return $this->select('enrollments.id,enrollments.semester as enrollment_semester, enrollments.student_id as student_id, students.study_program as study_program, students.name AS student_name,courses.code as course_code, courses.name AS course_name, courses.credits as credits, enrollments.academic_year as academic_year, enrollments.semester as current_semester, enrollments.status as status')
            ->join('students', 'students.id = enrollments.student_id', 'left')
            ->join('courses', 'courses.id = enrollments.course_id', 'left')
            ->where('enrollments.student_id', $id)
            ->findAll();
    }

    public function getAllEnrollment()
    {
        return $this->select('enrollments.id,enrollments.semester as enrollment_semester, enrollments.student_id as student_id, students.study_program as study_program, students.name AS student_name,courses.code as course_code, courses.name AS course_name, courses.credits as credits, enrollments.academic_year as academic_year, enrollments.semester as current_semester, enrollments.status as status')
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

    public function getStudentCredits($studentId)
    {
        return $this
            ->select('students.id, students.name, enrollments.semester as semester, SUM(courses.credits) as total_credits')
            ->join('courses', 'enrollments.course_id = courses.id')
            ->join('students', 'enrollments.student_id = students.id')
            ->where('students.id', $studentId)
            ->where('enrollments.status', 'Pass')
            ->groupBy('students.id, students.name, enrollments.semester')
            ->get() // ✅ Execute the query
            ->getResultArray(); // ✅ Fetch results as
    }

    public function getAllStudentEnrollments()
    {
        $students = $this->select('enrollments.student_id as student_id, students.name AS student_name') // Fixed 'students_id'
            ->join('students', 'students.id = enrollments.student_id', 'left') // Ensure correct column name
            ->distinct()
            ->findAll();

        // return array_column($students, 'student_name'); // Fix return value key
        return $students;
    }

    public function getAllCoursesEnrollments()
    {
        $courses = $this->select('enrollments.course_id as course_id, courses.name AS course_name') // Fixed 'courses_id'
            ->join('courses', 'courses.id = enrollments.course_id', 'left')
            ->distinct()
            ->findAll();

        // return array_column($courses, 'course_name'); // Fix return value key
        return $courses;
    }

    public function getAllStatusEnrollments()
    {
        $status = $this->select('enrollments.status')
            ->distinct()
            ->findAll();

        // return array_column($status, 'status');
        return $status;
    }


}
