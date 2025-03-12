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

        $data = [
            'name' => user()->username,
            'role' => implode(', ', user()->getRoles())
        ];
        return view('/layouts/dashboard', $data);
    }
}
