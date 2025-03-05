<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StudentsSeeder extends Seeder
{
    public function run()
    {
        $students = [
            [
                'student_id' => 1001,
                'name' => 'John Doe',
                'study_program' => 'Computer Science',
                'current_semester' => 4,
                'academic_status' => 'Active',
                'entry_year' => 2022,
                'gpa' => 3.5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'student_id' => 1002,
                'name' => 'Jane Smith',
                'study_program' => 'Information Technology',
                'current_semester' => 2,
                'academic_status' => 'Active',
                'entry_year' => 2023,
                'gpa' => 3.8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'student_id' => 1003,
                'name' => 'Michael Brown',
                'study_program' => 'Software Engineering',
                'current_semester' => 6,
                'academic_status' => 'Inactive',
                'entry_year' => 2021,
                'gpa' => 2.9,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'student_id' => 1004,
                'name' => 'Emily Johnson',
                'study_program' => 'Cyber Security',
                'current_semester' => 3,
                'academic_status' => 'Active',
                'entry_year' => 2023,
                'gpa' => 3.2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'student_id' => 1005,
                'name' => 'William Davis',
                'study_program' => 'Artificial Intelligence',
                'current_semester' => 5,
                'academic_status' => 'Active',
                'entry_year' => 2022,
                'gpa' => 3.9,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'student_id' => 1006,
                'name' => 'Sophia Martinez',
                'study_program' => 'Data Science',
                'current_semester' => 7,
                'academic_status' => 'Active',
                'entry_year' => 2021,
                'gpa' => 3.7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'student_id' => 1007,
                'name' => 'James Wilson',
                'study_program' => 'Cloud Computing',
                'current_semester' => 1,
                'academic_status' => 'Active',
                'entry_year' => 2024,
                'gpa' => 3.4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert data into the students table
        $this->db->table('students')->insertBatch($students);
    }
}
