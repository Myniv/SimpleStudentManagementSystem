<?php

namespace App\Models;

use App\Entities\Academic;
use CodeIgniter\Model;

class MAcademic
{
    private $academic;

    public function __construct()
    {
        // $this->academic[] = new Academic(1, 'Mobile Programming');
        $this->academics = [
            new Academic(1, 'Mobile Programming'),
            new Academic(2, 'Web Programming'),
            new Academic(3, 'Game Development'),
            new Academic(4, 'Data Scientist'),
            new Academic(5, 'Cyber Security'),
            new Academic(6, 'Internet of Things'),
            new Academic(7, 'Java Programming'),
            new Academic(8, 'Codeigniter'),
            new Academic(9, 'JavaScript for Beginner'),
            new Academic(10, 'PHP Laravel'),
        ];
    }

    public function getAcademics()
    {
        return $this->academics;
    }
    public function getAcademicArray()
    {
        $academicsArray = [];
        foreach ($this->academics as $academic) {
            $academicsArray[] = [
                'id' => $academic->getId(),
                'name' => $academic->getName(),
            ];
        }
        return $academicsArray;
    }
}
