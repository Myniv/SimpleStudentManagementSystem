<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHighSchoolDiplomaToStudents extends Migration
{
    public function up()
    {
        $fields = [
            'high_school_diploma' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ];
        
        $this->forge->addColumn('students', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('students', 'high_school_diploma');
    }
}
