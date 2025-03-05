<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StudentGradesSeeder extends Seeder
{
    public function run()
    {
        $grades = [
            ['enrollment_id' => 1, 'course_id' => 1, 'grade_value' => 85, 'grade_letter' => 'B', 'status' => 'Passed', 'completed_at' => '2024-01-15'],
            ['enrollment_id' => 2, 'course_id' => 2, 'grade_value' => 90, 'grade_letter' => 'A', 'status' => 'Passed', 'completed_at' => '2023-05-20'],
            ['enrollment_id' => 3, 'course_id' => 3, 'grade_value' => 78, 'grade_letter' => 'C', 'status' => 'Passed', 'completed_at' => '2022-06-10'],
            ['enrollment_id' => 4, 'course_id' => 4, 'grade_value' => 88, 'grade_letter' => 'B+', 'status' => 'Passed', 'completed_at' => '2021-07-12'],
            ['enrollment_id' => 5, 'course_id' => 5, 'grade_value' => 92, 'grade_letter' => 'A', 'status' => 'Passed', 'completed_at' => '2020-08-30'],
            ['enrollment_id' => 6, 'course_id' => 6, 'grade_value' => 80, 'grade_letter' => 'B-', 'status' => 'Passed', 'completed_at' => '2019-09-25'],
            ['enrollment_id' => 7, 'course_id' => 7, 'grade_value' => 70, 'grade_letter' => 'C-', 'status' => 'Passed', 'completed_at' => '2018-11-15'],
        ];

        foreach ($grades as &$grade) {
            $grade['created_at'] = date('Y-m-d H:i:s');
            $grade['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('student_grades')->insertBatch($grades);
    }
}
