<?php

namespace App\Controllers;

use CodeIgniter\Files\File;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;

class Home extends BaseController
{
    private $userModel;
    private $groupModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
    }
    public function index(): string
    {
        return view('welcome_message');
    }
    public function dashboard(): string
    {
        if (!logged_in()) {
            return redirect()->to('/login'); // Ensure user is logged in
        }

        // $this->testEmailSend();
        // $this->testEmailWithTemplate();

        $data = [
            'name' => user()->username,
            'role' => implode(', ', user()->getRoles())
        ];
        return view('/layouts/dashboard', $data);

    }

    private function testEmailSend()
    {
        $email = service('email');

        $email->setFrom('mulyanan@solecode.id');
        $email->setTo('mulyanancr@gmail.com');
        $email->setCC('');
        $email->setBCC('');

        $email->setSubject('Email Test');
        $email->setMessage('Testing the email class');

        $email->send();
    }

    public function testEmailWithTemplate()
    {
        $email = service('email');

        $email->setFrom('mulyanan@solecode.id');
        $email->setTo('mulyanancr@gmail.com');
        $email->setSubject('Email Test With HTML Template');

        $data = [
            'title' => 'Important Notification',
            'name' => 'John Doe',
            'content' => 'This is the content of the email that will be sended.',
            'features' => [
                'Feature 1 : Important Information',
                'Feature 2 : Detail Product',
                'Feature 3 : How to use the product'
            ],
        ];

        $email->setMessage(view('email/email_template', $data));

        $filePath = ROOTPATH . 'public/uploads/Mini Project 6.pdf';
        $excelPath = ROOTPATH . 'public/uploads/Skala Likert.xlsx';
        $imagePath = ROOTPATH . 'public/uploads/iconOrang.png';

        //Send multiple recipients can be like this
        // $email->setTo('mulyanancr2@gmail.com, mulyanancr1@gmail.com');

        //Send multiple recipients or can be like this
        // $ccList = [
        //     'mulyanancr2@gmail.com',
        //     'mulyanancr1@gmail.com',
        // ];
        // $email->setCC($ccList);

        if (file_exists($filePath)) {
            $email->attach($filePath);
        }

        if (file_exists($excelPath)) {
            $email->attach($excelPath);
        }

        if (file_exists($imagePath)) {
            $email->attach($imagePath);
        }

        if ($email->send()) {
            echo 'Email sent successfully';
        }
    }

    public function testUploadFiles()
    {
        $type = $this->request->getMethod();
        if ($type == "GET") {
            return view('upload_form/upload_form_test');
        }

        //Validation rules for doc
        // $validationRules = [
        //     'userfile' => [
        //         'label' => 'Dokumen',
        //         'rules' => [
        //             'uploaded[userfile]',
        //             'mime_in[userfile,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]',
        //             'max_size[userfile,5120]', // 5MB dalam KB (5 * 1024)
        //         ],
        //         'errors' => [
        //             'uploaded' => 'Silakan pilih file untuk diunggah',
        //             'mime_in' => 'File harus berformat PDF, DOC, atau DOCX',
        //             'max_size' => 'Ukuran file tidak boleh melebihi 5MB'
        //         ]
        //     ]
        // ];

        //Validation rules for image
        $validationRules = [
            'userfile' => [
                'label' => 'Gambar',
                'rules' => [
                    'uploaded[userfile]',
                    'is_image[userfile]',
                    'mime_in[userfile,image/jpg,image/jpeg,image/png,image/gif]',
                    'max_size[userfile,5120]', // 5MB dalam KB (5 * 1024)
                    'min_dims[userfile,600,600]',
                ],
                'errors' => [
                    'uploaded' => 'Please choose file tu uploaded.',
                    'is_image' => 'File must be an image.',
                    'mime_in' => 'File must be JPG, JPEG, PNG, atau GIF',
                    'max_size' => 'File size must be less than 5MB',
                    'min_dims' => 'Image must be at least 600x600'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            return view('upload_form/upload_form_test', ['errors' => $this->validator->getErrors()]);
        }


        $userFile = $this->request->getFile('userfile');
        // if (!$userFile->isValid()) {
        //     return view('upload_form/upload_form_test', ['errors' => $userFile->getErrorString()]);
        // }

        $newName = $userFile->getRandomName();
        // $newName = $userFile->getName();
        // $userFile->move(WRITEPATH . 'uploads', $newName);
        // $filePath = WRITEPATH . 'uploads/' . $newName;

        $userFile->move(WRITEPATH . 'uploads/original', "original_" . $newName);
        $filePath = WRITEPATH . 'uploads/original/' . "original_" . $newName;

        $this->createImageVersions($filePath, $newName);



        $data = ['uploaded_fileinfo' => new File($filePath)];
        return view('upload_form/upload_success_page', $data);
    }

    private function createImageVersions($filePath, $fileName)
    {

        $image = service('image');

        $image->withFile($filePath)
            ->fit(100, 100, 'center')
            ->save(WRITEPATH . 'uploads/thumbnail/' . "thumbnail_" . $fileName);


        // $image->withFile($filePath)
        //     ->fit(300, 300, 'center')
        //     ->save(WRITEPATH . 'uploads/medium/' . $fileName);

        // Jika ingin menggunakan resize (mempertahankan ratio) daripada fit:
        $image->withFile($filePath)
            ->resize(300, 300, true, 'height')
            ->save(WRITEPATH . 'uploads/medium/' . "medium_" . $fileName);

        $image->withFile($filePath)
            ->text('Copyright 2017 My Photo Co', [
                'color' => '#fff',
                'opacity' => 0.5,
                'withShadow' => true,
                'hAlign' => 'center',
                'vAlign' => 'bottom',
                'fontSize' => 50,
            ])
            ->save(WRITEPATH . 'uploads/watermark/' . "watermark_" . $fileName);
    }

    public function dashboardStudentDummy()
    {
        //Pie Chart : 
        $creditsByGrade = $this->getCreditsByGrade();

        //Bar Chart :
        $creditComparison = $this->getCreditComparison();

        //Line Chart :
        $gpaData = $this->getGpaPerSemester();

        $data['creditsByGrade'] = json_encode($creditsByGrade);
        $data['creditComparison'] = json_encode($creditComparison);
        $data['gpaData'] = json_encode($gpaData);
        return view('dashboard/v_dashboard_student', $data);
    }

    //Pie Chart
    private function getCreditsByGrade()
    {
        $dummyGradeCredits = [
            ['grade_letter' => 'A', 'credits' => 45],
            ['grade_letter' => 'B+', 'credits' => 20],
            ['grade_letter' => 'B', 'credits' => 32],
            ['grade_letter' => 'C+', 'credits' => 8],
            ['grade_letter' => 'C', 'credits' => 18],
            ['grade_letter' => 'D', 'credits' => 6],

        ];

        $backgroundColors = [
            'A' => 'rgb(54, 162, 235)', // Biru 
            'B+' => 'rgb(75, 192, 192)', // Cyan 
            'B' => 'rgb(153, 102, 255)', // Ungu 
            'C+' => 'rgb(255, 205, 86)', // Kuning
            'C' => 'rgb(255, 159, 64)', // Oranye 
            'D' => 'rgb(255, 99, 132)' // Merah
        ];

        foreach ($dummyGradeCredits as $row) {
            $gradeLabels[] = $row['grade_letter'] . '=' . $row['credits'] . ' Credits';
            $creditCounts[] = (int) $row['credits'];
            $colors[] = $backgroundColors[$row['grade_letter']];
        }

        return [
            'labels' => $gradeLabels,
            'datasets' => [
                [
                    'label' => 'Credits By Grade',
                    'data' => $creditCounts,
                    'backgroundColor' => $colors,
                    'hoverOffset' => 4
                ]
            ]
        ];
    }

    //Bar Chart
    private function getCreditComparison()
    {
        $dummyCredits = [
            ['semester' => 1, 'credits_taken' => 20, 'credits_required' => 20],
            ['semester' => 2, 'credits_taken' => 19, 'credits_required' => 22],
            ['semester' => 3, 'credits_taken' => 22, 'credits_required' => 24],
            ['semester' => 4, 'credits_taken' => 20, 'credits_required' => 22],
            ['semester' => 5, 'credits_taken' => 18, 'credits_required' => 20],
            ['semester' => 6, 'credits_taken' => 16, 'credits_required' => 18]
        ];

        foreach ($dummyCredits as $row) {
            $labels[] = 'Semester ' . $row['semester'];
            $creditsTaken[] = (int) $row['credits_taken'];
            $creditsRequired[] = (int) $row['credits_required'];
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Credits Taken',
                    'data' => $creditsTaken,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1
                ],
                [
                    'label' => 'Credits Required',
                    'data' => $creditsRequired,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'borderWidth' => 1
                ]
            ]
        ];
    }

    //Line Chart
    private function getGpaPerSemester()
    {
        $dummyGpaData = [
            ['semester' => 1, 'semester_gpa' => 3.45],
            ['semester' => 2, 'semester_gpa' => 2.52],
            ['semester' => 3, 'semester_gpa' => 3.21],
            ['semester' => 4, 'semester_gpa' => 2.68],
            ['semester' => 5, 'semester_gpa' => 3.75],
            ['semester' => 6, 'semester_gpa' => 2.82],
            ['semester' => 7, 'semester_gpa' => 3.41],
            ['semester' => 8, 'semester_gpa' => 2.95],
        ];
        foreach ($dummyGpaData as $row) {
            $semesters[] = 'Semester ' . $row['semester'];
            $gpaData[] = round($row['semester_gpa'], 2);
        }

        return [
            'labels' => $semesters,
            'datasets' => [
                [
                    'label' => 'GPA',
                    'data' => $gpaData,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'tension' => 0.1,
                    'fill' => false
                ]
            ]
        ];
    }

}
