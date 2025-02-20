<?php

namespace App\Models;

use App\Entities\Student;
use CodeIgniter\Model;
use App\Models\MAcademic; // Import MAcademic

class MStudent
{
    private $students = [];
    private $academics = [];

    public function __construct()
    {
        $academicModel = new MAcademic();
        $this->academics = $academicModel->getAcademics();

        $this->students[] = $this->createStudent(1, 'John Cena', 'Computer Science', 4, "Active", [
            ['academic' => $this->academics[0], 'grade' => 85],
            ['academic' => $this->academics[2], 'grade' => 78],
            ['academic' => $this->academics[3], 'grade' => 90],
            ['academic' => $this->academics[1], 'grade' => 35],
            ['academic' => $this->academics[5], 'grade' => 78],
            ['academic' => $this->academics[4], 'grade' => 55],
        ]);

        $this->students[] = $this->createStudent(2, 'Rey Misterio', 'Information Technology', 3, "On Leave", [
            ['academic' => $this->academics[1], 'grade' => 88],
            ['academic' => $this->academics[3], 'grade' => 92],
            ['academic' => $this->academics[5], 'grade' => 80],
            ['academic' => $this->academics[2], 'grade' => 85],
            ['academic' => $this->academics[6], 'grade' => 83],
            ['academic' => $this->academics[4], 'grade' => 54],
            ['academic' => $this->academics[0], 'grade' => 98],
            ['academic' => $this->academics[7], 'grade' => 65],
        ]);

        $this->students[] = $this->createStudent(3, 'Dwayne Johnson', 'Cyber Security', 2, "Graduated", [
            ['academic' => $this->academics[4], 'grade' => 75],
            ['academic' => $this->academics[1], 'grade' => 82],
            ['academic' => $this->academics[9], 'grade' => 70],
            ['academic' => $this->academics[5], 'grade' => 50],
            ['academic' => $this->academics[3], 'grade' => 77],
            ['academic' => $this->academics[2], 'grade' => 94],
            ['academic' => $this->academics[6], 'grade' => 65],
            ['academic' => $this->academics[8], 'grade' => 87],
            ['academic' => $this->academics[7], 'grade' => 66],
            ['academic' => $this->academics[0], 'grade' => 93],
        ]);
    }

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
