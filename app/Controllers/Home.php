<?php

namespace App\Controllers;

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
        $this->testEmailWithTemplate();

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
}
