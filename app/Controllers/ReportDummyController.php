<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment as Alignment;

class ReportDummyController extends BaseController
{
    protected $enrollmentData;
    public function __construct()
    {
        $this->enrollmentData = [
            (object) [
                'id' => 1,
                'student_id' => 2011500457,
                'name' => "Agus Setiawan",
                'study_program' => "Teknik Informatika",
                'current_semester' => 4,
                'course_code' => "IF4101",
                'course_name' => "Pemrograman Berorientasi Objek",
                'credits' => 3,
                'course_semester' => 4,
                'academic_year' => "2023/2024",
                'enrollment_semester' => "Ganjil",
                'status' => "Aktif",
            ],
            (object) [
                'id' => 2,
                'student_id' => 2011500400,
                'name' => "Sekar Amelia",
                'study_program' => "Artificial Intelligence",
                'current_semester' => 4,
                'course_code' => "AB4101",
                'course_name' => "Pemrograman Web",
                'credits' => 3,
                'course_semester' => 4,
                'academic_year' => "2023/2024",
                'enrollment_semester' => "Ganjil",
                'status' => "Aktif",
            ],
            (object) [
                'id' => 1,
                'student_id' => 2011501810,
                'name' => "Mulyana N",
                'study_program' => "Sistem Informasi",
                'current_semester' => 4,
                'course_code' => "CD4551",
                'course_name' => "Pemrograman Kecerdasan Tiruan",
                'credits' => 3,
                'course_semester' => 4,
                'academic_year' => "2023/2024",
                'enrollment_semester' => "Ganjil",
                'status' => "Aktif",
            ],
        ];
    }
    public function enrollmentForm()
    {
        $student_id = $this->request->getVar('student_id');
        $name = $this->request->getVar('name');

        $filteredData = $this->filterData($student_id, $name);

        $data = [
            'title' => 'Laporan Enrollment Mata Kuliah',
            'enrollments' => $filteredData,
            'filters' => [
                'student_id' => $student_id,
                'name' => $name
            ]
        ];

        return view('reports/v_report_enrollments_dummy', $data);
    }

    private function filterData($student_id = '', $name = '')
    {
        return $this->enrollmentData;
    }

    public function enrollmentExcel()
    {
        $student_id = $this->request->getVar('student_id');
        $name = $this->request->getVar('name');

        $enrollments = $this->filterData($student_id, $name);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Lapora Enrollment Mata Kuliah');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Filter');
        $sheet->setCellValue('B3', 'Student ID :' . ($student_id ?? 'Semua'));
        $sheet->setCellValue('D3', 'Nama:' . ($name ?? 'Semua'));
        $sheet->getStyle('A3:D3')->getFont()->setBold(true);

        $headers = [
            'A5' => 'No',
            'B5' => 'Student ID',
            'C5' => 'Nama',
            'D5' => 'Program Studi',
            'E5' => 'Semester',
            'F5' => 'Kode Mata Kuliah',
            'G5' => 'Mata Kuliah',
            'H5' => 'SKS',
            'J5' => 'Tahun Akademik',
            'L5' => 'Status',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $row = 6;
        $no = 1;
        foreach ($enrollments as $enrollment) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $enrollment->student_id);
            $sheet->setCellValue('C' . $row, $enrollment->name);
            $sheet->setCellValue('D' . $row, $enrollment->study_program);
            $sheet->setCellValue('E' . $row, $enrollment->current_semester);
            $sheet->setCellValue('F' . $row, $enrollment->course_code);
            $sheet->setCellValue('G' . $row, $enrollment->course_name);
            $sheet->setCellValue('H' . $row, $enrollment->credits);
            $sheet->setCellValue('I' . $row, $enrollment->academic_year . ' - ' . $enrollment->enrollment_semester);
            $sheet->setCellValue('J' . $row, $enrollment->status);

            $row++;
            $no++;
        }

        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A5:J' . ($row - 1))->applyFromArray($styleArray);



        $filename = 'Laporan_Mata_Kuliah_Enrol_' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

}
