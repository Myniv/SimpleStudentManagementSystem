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
                ],
                'errors' => [
                    'uploaded' => 'Silakan pilih file gambar untuk diunggah',
                    'is_image' => 'File harus berupa gambar',
                    'mime_in' => 'File harus berformat JPG, JPEG, PNG, atau GIF',
                    'max_size' => 'Ukuran file tidak boleh melebihi 5MB'
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

}
