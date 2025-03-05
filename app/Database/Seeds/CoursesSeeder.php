<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            ['code' => 'CS101', 'name' => 'Introduction to Computer Science', 'credits' => 3, 'semester' => 1],
            ['code' => 'CS102', 'name' => 'Data Structures', 'credits' => 4, 'semester' => 2],
            ['code' => 'CS103', 'name' => 'Database Systems', 'credits' => 3, 'semester' => 3],
            ['code' => 'CS104', 'name' => 'Operating Systems', 'credits' => 3, 'semester' => 4],
            ['code' => 'CS105', 'name' => 'Artificial Intelligence', 'credits' => 4, 'semester' => 5],
            ['code' => 'CS106', 'name' => 'Cyber Security', 'credits' => 3, 'semester' => 6],
            ['code' => 'CS107', 'name' => 'Cloud Computing', 'credits' => 3, 'semester' => 7],
        ];

        foreach ($courses as &$course) {
            $course['created_at'] = date('Y-m-d H:i:s');
            $course['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('courses')->insertBatch($courses);
    }
}
