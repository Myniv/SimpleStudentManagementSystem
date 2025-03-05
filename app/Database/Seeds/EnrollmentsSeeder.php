<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EnrollmentsSeeder extends Seeder
{
    public function run()
    {
        $enrollments = [
            ['student_id' => 1, 'course_id' => 1, 'academic_year' => 2024, 'semester' => 1, 'status' => 'Enrolled'],
            ['student_id' => 2, 'course_id' => 2, 'academic_year' => 2023, 'semester' => 2, 'status' => 'Completed'],
            ['student_id' => 3, 'course_id' => 3, 'academic_year' => 2022, 'semester' => 3, 'status' => 'Enrolled'],
            ['student_id' => 4, 'course_id' => 4, 'academic_year' => 2021, 'semester' => 4, 'status' => 'Completed'],
            ['student_id' => 5, 'course_id' => 5, 'academic_year' => 2020, 'semester' => 5, 'status' => 'Enrolled'],
            ['student_id' => 6, 'course_id' => 6, 'academic_year' => 2019, 'semester' => 6, 'status' => 'Completed'],
            ['student_id' => 7, 'course_id' => 7, 'academic_year' => 2018, 'semester' => 7, 'status' => 'Enrolled'],
        ];

        foreach ($enrollments as &$enrollment) {
            $enrollment['created_at'] = date('Y-m-d H:i:s');
            $enrollment['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('enrollments')->insertBatch($enrollments);
    }
}
