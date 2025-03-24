<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment as Alignment;
use TCPDF;

class ReportDummyController extends BaseController
{
    protected $enrollmentData;
    protected $studentsData;
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

        $this->studentsData = [
            (object) [
                'id' => 1,
                'student_id' => 2011500457,
                'name' => "Agus Setiawan",
                'study_program' => "Teknik Informatika",
                'current_semester' => 4,
                'academic_status' => "Aktif",
                'entry_year' => 2023,
                'gpa' => 3.5,
            ],
            (object) [
                'id' => 2,
                'student_id' => '182002',
                'name' => 'Budi Santoso',
                'study_program' => 'Sistem Informasi',
                'current_semester' => 4,
                'academic_status' => 'Aktif',
                'entry_year' => 2023,
                'gpa' => 3.45
            ],
            (object) [
                'id' => 3,
                'student_id' => '183003',
                'name' => 'Cindy Paramitha',
                'study_program' => 'Teknik Komputer',
                'current_semester' => 3,
                'academic_status' => 'Aktif',
                'entry_year' => 2022,
                'gpa' => 3.90
            ],
            (object) [
                'id' => 4,
                'student_id' => '184004',
                'name' => 'Deni Saputra',
                'study_program' => 'Teknik Informatika',
                'current_semester' => 6,
                'academic_status' => 'Aktif',
                'entry_year' => 2021,
                'gpa' => 3.60
            ],
            (object) [
                'id' => 5,
                'student_id' => '185005',
                'name' => 'Eko Prasetyo',
                'study_program' => 'Sistem Informasi',
                'current_semester' => 2,
                'academic_status' => 'Aktif',
                'entry_year' => 2020,
                'gpa' => 3.25
            ]
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

    public function studentsByProgramForm()
    {
        $study_programs = ['Teknik Informatika', 'Sistem Informasi', 'Teknik Komputer'];
        $entry_years = ['2021', '2022', '2023', '2024'];

        $data = [
            'title' => 'Laporan Mahasiswa Berdasarkan Program Studi',
            'study_programs' => $study_programs,
            'entry_years' => $entry_years
        ];

        return view('reports/v_report_program_study_dummy', $data);
    }

    public function studentsByProgramPdf()
    {
        $studyProgram = $this->request->getVar('study_program');
        $entryYear = $this->request->getVar('entry_year');


        $pdf = $this->initTcpdf();
        // $this->generatePdfHtmlContent($pdf, $this->studentsData, $studyProgram, $entryYear);
        $this->generatePdfContent($pdf, $this->studentsData, $studyProgram, $entryYear);

        // Output PDF
        $filename = 'laporan_mahasiswa_' . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'I');
        exit;
    }

    private function initTcpdf()
    {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator('CodeIgniter 4');
        $pdf->SetAuthor('Administrator');
        $pdf->SetTitle('Laporan Mahasiswa');
        $pdf->SetSubject('Laporan Data Mahasiswa');

        $pdf->SetHeaderData('', 0, 'UNIVERSITAS XYZ', '', [0, 0, 0], [0, 64, 128]);
        $pdf->setFooterData([0, 64, 0], [0, 64, 128]);

        $pdf->setHeaderFont(['helvetica', '', 12]);
        $pdf->setFooterFont(['helvetica', '', 8]);

        $pdf->SetMargins(15, 20, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        $pdf->SetAutoPageBreak(true, 25);

        $pdf->SetFont('helvetica', '', 10);

        $pdf->AddPage();

        return $pdf;
    }

    public function generatePdfHtmlContent($pdf, $students, $studyProgram, $entryYear)
    {
        $title = 'LAPORAN DATA MAHASISWA';

        if (!empty($studyProgram)) {
            $title .= ' - PROGRAM STUDI: ' . $studyProgram;
        }

        if (!empty($entryYear)) {
            $title .= ' - TAHUN MASUK: ' . $entryYear;
        }

        $html = '<h2 style="text-align:center;">' . $title . '</h2>
      <table border="1" cellpadding="5" cellspacing="0" style="width:100%;">
        <thead>
          <tr style="background-color:#CCCCCC; font-weight:bold; text-align:center;">
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Semester</th>
            <th>Status</th>
            <th>Tahun Masuk</th>
            <th>IPK</th>
          </tr>
         </thead>
         <tbody>';

        $no = 1;
        foreach ($students as $student) {
            $html .= '
           <tr>
            <td style="text-align:center;">' . $no . '</td>
            <td>' . $student->student_id . '</td>
            <td>' . $student->name . '</td>
            <td>' . $student->study_program . '</td>
            <td style="text-align:center;">' . $student->current_semester . '</td>
            <td style="text-align:center;">' . $student->academic_status . '</td>
            <td style="text-align:center;">' . $student->entry_year . '</td>
            <td style="text-align:center; font-weight:bold;">' . $student->gpa . '</td>
           </tr>';
            $no++;
        }

        $html .= '
               </tbody>
           </table>
           
           <p style="margin-top:30px; text-align:left;">      
               Total Mahasiswa: ' . count($students) . ' 
           </p>
   
           <p style="margin-top:30px; text-align:right;">    
               Tanggal Cetak: ' . date('d-m-Y H:i:s') . '<br> 
           </p>';
        $pdf->writeHTML($html, true, false, true, false, '');
    }

    private function generatePdfContent($pdf, $students, $studyProgram, $entryYear)
    {
        $title = 'LAPORAN DATA MAHASISWA';

        if (!empty($studyProgram)) {
            $title .= ' - PROGRAM STUDI: ' . $studyProgram;
        }

        if (!empty($entryYear)) {
            $title .= ' - TAHUN MASUK: ' . $entryYear;
        }

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, $title, 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 5, 'Tanggal Cetak: ' . date('d-m-Y H:i:s'), 0, 1, 'R');
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(220, 220, 220);

        $pdf->Cell(10, 7, 'No', 1, 0, 'C', 1);
        $pdf->Cell(30, 7, 'NIM', 1, 0, 'C', 1);
        $pdf->Cell(60, 7, 'Nama Mahasiswa', 1, 0, 'C', 1);
        $pdf->Cell(50, 7, 'Program Studi', 1, 0, 'C', 1);
        $pdf->Cell(20, 7, 'Semester', 1, 0, 'C', 1);
        $pdf->Cell(30, 7, 'Status', 1, 0, 'C', 1);
        $pdf->Cell(25, 7, 'Tahun Masuk', 1, 0, 'C', 1);
        $pdf->Cell(15, 7, 'IPK', 1, 1, 'C', 1);



        // table
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetFillColor(255, 255, 255);

        $no = 1;
        foreach ($students as $student) {
            $pdf->Cell(10, 6, $no++, 1, 0, 'C');
            $pdf->Cell(30, 6, $student->student_id, 1, 0, 'C');
            $pdf->Cell(60, 6, $student->name, 1, 0, 'L');
            $pdf->Cell(50, 6, $student->study_program, 1, 0, 'L');
            $pdf->Cell(20, 6, $student->current_semester, 1, 0, 'C');
            $pdf->Cell(30, 6, $student->academic_status, 1, 0, 'C');
            $pdf->Cell(25, 6, $student->entry_year, 1, 0, 'C');
            $pdf->Cell(15, 6, $student->gpa, 1, 1, 'C');
        }

        // Summary
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 7, 'Total Mahasiswa: ' . count($students), 0, 1, 'L');
    }












}
