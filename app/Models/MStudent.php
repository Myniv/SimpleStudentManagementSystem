<?php

namespace App\Models;

use App\Entities\Student;
use App\Libraries\DataParams;
use CodeIgniter\Model;
use App\Models\MAcademic; // Import MAcademic

class MStudent extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    // protected $returnType = 'array';
    protected $returnType = Student::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ["student_id", "name", "study_program", "current_semester", "academic_status", "entry_year", "gpa", "user_id", "high_school_diploma"];

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
        'student_id' => 'required|is_unique[students.student_id,id,{id}]',
        'name' => 'required|min_length[3]|max_length[100]',
        'study_program' => 'required',
        'current_semester' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[14]',
        'academic_status' => 'required|in_list[Active,On Leave,Graduated]',
        'entry_year' => 'required|integer|exact_length[4]',
        'gpa' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[4.00]',
    ];
    protected $validationMessages = [
        'student_id' => [
            'required' => 'Student ID is required.',
            'is_unique' => 'Student ID must be unique.',
        ],
        'name' => [
            'required' => 'Student name is required.',
            'min_length' => 'Name must be at least 3 characters.',
            'max_length' => 'Name must not exceed 100 characters.',
        ],
        'study_program' => [
            'required' => 'Study program is required.',
        ],
        'current_semester' => [
            'required' => 'Current semester is required.',
            'integer' => 'Semester must be a number.',
            'greater_than_equal_to' => 'Semester must be at least 1.',
            'less_than_equal_to' => 'Semester must not be more than 14.',
        ],
        'academic_status' => [
            'required' => 'Academic status is required.',
            'in_list' => 'Academic status must be one of: active, on leave, or graduated.',
        ],
        'entry_year' => [
            'required' => 'Entry year is required.',
            'integer' => 'Entry year must be a valid number.',
            'exact_length' => 'Entry year must be exactly 4 digits.',
        ],
        'gpa' => [
            'required' => 'GPA is required.',
            'decimal' => 'GPA must be a decimal number.',
            'greater_than_equal_to' => 'GPA cannot be less than 0.',
            'less_than_equal_to' => 'GPA cannot be greater than 4.00.',
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

    public function getFilteredProducts(DataParams $params)
    {
        if (!empty($params->search)) {
            $this->groupStart()
                ->like('name', $params->search, 'both', null, true)
                ->orLike('study_program', $params->search, 'both', null, true)
                ->orLike('academic_status', $params->search, 'both', null, true);

            if (is_numeric($params->search)) {
                $this->orWhere('CAST (student_id AS TEXT) LIKE', "%$params->search%")
                    ->orWhere('CAST (current_semester AS TEXT) LIKE', "%$params->search%")
                    ->orWhere('CAST (gpa AS TEXT) LIKE', "%$params->search%")
                    ->orWhere('CAST (entry_year AS TEXT) LIKE', "%$params->search%");
            }
            $this->groupEnd();
        }

        if (!empty(($params->study_program))) {
            $this->where("study_program", $params->study_program);
        }

        if (!empty($params->academic_status)) {
            $this->where("academic_status", $params->academic_status);
        }

        if (!empty($params->entry_year)) {
            $this->where("entry_year", $params->entry_year);
        }

        $allowedSortColumns = ['student_id', 'name', 'study_program', 'current_semester', 'academic_status', 'entry_year', 'gpa'];
        $sort = in_array($params->sort, $allowedSortColumns) ? $params->sort : 'student_id';
        $order = ($params->order == 'asc') ? 'asc' : 'desc';

        $this->orderBy($sort, $order);
        $result = [
            'students' => $this->paginate($params->perPage, 'students', $params->page),
            'pager' => $this->pager,
            'total' => $this->countAllResults(false),
        ];

        return $result;
    }

    public function getAllStudyPrograms()
    {
        return $this->select('study_program')->distinct()->findAll();
    }

    public function getAllAcademicStatuses()
    {
        return $this->select('academic_status')->distinct()->findAll();
    }

    public function getAllEntryYear()
    {
        return $this->select('entry_year')->orderBy('entry_year', 'asc')->distinct()->findAll();
    }

    private function calculateGPA($courses)
    {
        if (empty($courses)) {
            return 0.0;
        }

        $totalGrades = array_sum(array_column($courses, 'grade'));
        $averageGrade = $totalGrades / count($courses);

        return $this->convertToGPA($averageGrade);
    }

    private function convertToGPA($averageGrade)
    {
        if ($averageGrade >= 90)
            return 4.0;
        if ($averageGrade >= 80)
            return 3.5;
        if ($averageGrade >= 70)
            return 3.0;
        if ($averageGrade >= 60)
            return 2.5;
        if ($averageGrade >= 50)
            return 2.0;
        return 1.0; // Below 50 is a failing grade
    }

    public function getStudentCredits($studentId)
    {
        return $this->db->table('enrollments')
            ->select('students.id, students.name, enrollments.semester as semester, SUM(courses.credits) as total_credits')
            ->join('courses', 'enrollments.course_id = courses.id')
            ->join('students', 'enrollments.student_id = students.id')
            ->where('students.id', $studentId)
            ->where('enrollments.status', 'Pass')
            ->groupBy('students.id, students.name, enrollments.semester')
            ->get() // ✅ Execute the query
            ->getResultArray(); // ✅ Fetch results as
    }

}
