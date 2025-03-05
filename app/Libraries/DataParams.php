<?php

namespace App\Libraries;

class DataParams
{
    public $search = '';

    //For filter student list
    public $study_program = '';
    public $academic_status = '';
    public $entry_year = '';

    //For filter course list
    public $credits = '';
    public $semester = '';

    public $sort = 'id';
    public $order = 'asc';
    public $page = 1;
    public $perPage = 10;
    public function __construct(array $params = [])
    {
        $this->search = $params['search'] ?? '';

        $this->study_program = $params['study_program'] ?? '';
        $this->academic_status = $params['academic_status'] ?? '';
        $this->entry_year = $params['entry_year'] ?? '';

        $this->credits = $params['credits'] ?? '';
        $this->semester = $params['semester'] ?? '';

        $this->sort = $params['sort'] ?? 'id';
        $this->order = $params['order'] ?? 'asc';
        $this->page = (int) ($params['page'] ?? 1);
        $this->perPage = (int) ($params['perPage'] ?? 10);
    }

    public function getParams()
    {
        return [
            'search' => $this->search,
            'study_program' => $this->study_program,
            'academic_status' => $this->academic_status,
            'entry_year' => $this->entry_year,

            'credits' => $this->credits,
            'semester' => $this->semester,
            
            'sort' => $this->sort,
            'order' => $this->order,
            'page_products' => $this->page,
            'perPage' => $this->perPage,
        ];
    }

    public function getSortUrl($column, $baseUrl)
    {
        $params = $this->getParams();

        $params['sort'] = $column;
        $params['order'] = ($column == $this->sort && $this->order == 'asc') ? 'desc' : 'asc';

        $queryString = http_build_query(array_filter($params));
        return $baseUrl . '?' . $queryString;
    }


    public function getResetUrl($baseUrl)
    {
        return $baseUrl;
    }

    public function isSortedBy($column)
    {
        return $this->sort === $column;
    }

    public function getSortDirection()
    {
        return $this->order;
    }

}