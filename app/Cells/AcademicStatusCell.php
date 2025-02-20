<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class AcademicStatusCell extends Cell
{
    protected $status;
    private $color;

    public function mount()
    {
        if ($this->status == 'active' || $this->status == 'Active') {
            $this->color = "badge bg-success";
        } elseif ($this->status == "graduated" || $this->status == "Graduated") {
            $this->color = "badge bg-primary";
        } else {
            $this->color = "badge bg-danger";
        }

        cache()->save("cache_academic_status_cell", $this->status, 86400);
    }

    public function getStatusProperty()
    {
        return $this->status;
    }

    public function getColorProperty()
    {
        return $this->color;
    }
}
