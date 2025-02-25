<?php

namespace App\Models;

use App\Entities\Student;
use CodeIgniter\Model;
use App\Models\MAcademic; // Import MAcademic

class MStudent extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    // protected $returnType = 'array';
    protected $returnType = \App\Entities\Student::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ["student_id", "name", "study_program", "current_semester", "academic_status", "entry_year", "gpa"];

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
    protected $validationRules = [];
    protected $validationMessages = [];
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

    private function createStudent($id, $name, $program, $semester, $status, $courses, )
    {
        return new Student(
            $id,
            $name,
            $program,
            $courses,
            $semester,
            $this->calculateGPA($courses),
            $status,
        );
    }

    public function getStudents()
    {
        return $this->students;
    }
    public function getStudentsArray()
    {
        $studentArray = [];
        foreach ($this->students as $student) {
            $studentArray[] = [
                'id' => $student->getId(),
                'name' => $student->getName(),
                'program' => $student->getProgram(),
                'semester' => $student->getSemester(),
                'gpa' => number_format($student->getGpa(), 2),
                'courses' => $this->formatCourseToArray($student->getCourse(), false),
                'status' => $student->getStatus(),
            ];
        }

        return $studentArray;
    }

    public function getStudentById($id)
    {
        return $this->students[$id];
    }

    public function getStudentByIdArray($id)
    {
        foreach ($this->students as $student) {
            if ($student->getId() == $id) {
                return [
                    'id' => $student->getId(),
                    'name' => $student->getName(),
                    'program' => $student->getProgram(),
                    'semester' => $student->getSemester(),
                    'gpa' => number_format($student->getGpa(), 2),
                    'courses' => $this->formatCourseToArray($student->getCourse(), false),
                    'status' => $student->getStatus(),
                ];
            }
        }

        return "Student Not Found";
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

    public function formatCourseToArray($courses, $filter)
    {
        $course = $courses;
        if ($filter == true) {
            $course = array_slice($courses, -5, 5, true);
        }
        $courseArray = [];

        foreach ($course as $value) {
            $courseArray[] = [
                'course_id' => $value['academic']->getId(),
                'course_name' => $value['academic']->getName(),
                'course_grade' => $value['grade'],
            ];
        }
        return $courseArray;
    }

}
