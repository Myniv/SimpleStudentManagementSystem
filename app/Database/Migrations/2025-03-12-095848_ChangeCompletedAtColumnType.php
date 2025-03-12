<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeCompletedAtColumnType extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE student_grades 
                          ALTER COLUMN completed_at 
                          TYPE TIMESTAMP WITHOUT TIME ZONE 
                          USING completed_at::timestamp;");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE student_grades 
                          ALTER COLUMN completed_at 
                          TYPE VARCHAR(255);");
    }
}
