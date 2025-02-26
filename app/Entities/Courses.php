<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Courses extends Entity
{
    protected $attributes = [
        "id" => null,
        "code" => null,
        "name" => null,
        "credits" => null,
        "semester" => null,
        "created_at" => null,
        "updated_at" => null,
    ];
    protected $casts = [
        'id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
