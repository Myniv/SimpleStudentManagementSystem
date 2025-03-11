<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;


class UsersController extends BaseController
{
    protected $userModel;
    protected $groupModel;
    protected $db;
    protected $config;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
        $this->db = \Config\Database::connect();
        $this->config = config('Auth');

        helper(['auth']);
        if (!in_groups('admin')) {
            return redirect()->to('/');
        }
    }
    public function index()
    {
        $data = [
            'title' => 'User Management',
            'users' => $this->userModel->findAll()
        ];

        return view('users/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add New User',
            'groups' => $this->groupModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('users/create', $data);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit User',
            'user' => $this->userModel->find($id),
            'groups' => $this->groupModel->findAll(),
            'userGroups' => $this->groupModel->getGroupsForUser($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['user'])) {
            return redirect()->to('/users')->with('error', 'User Not Found');
        }

        return view('users/edit', $data);
    }

    public function store()
    {
        $user = new \Myth\Auth\Entities\User();
        $user->username = $this->request->getVar('username');
        $user->email = $this->request->getVar('email');
        $user->password = $this->request->getVar('password');
        $user->active = 1;

        $this->userModel->save($user);

        $newUser = $this->userModel->where('email', $user->email)->first();
        $userId = $newUser->id;

        $groupId = $this->request->getVar('group');
        $this->groupModel->addUserToGroup($userId, $groupId);

        return redirect()->to('admin/users')->with('message', 'User Created Successfully');
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User Not Found');
        }

        $newUsername = $this->request->getVar('username');
        if ($user->username != $newUsername) {
            $existingUser = $this->userModel->where('username', $newUsername)->first();
            if ($existingUser) {
                return redirect()->to('admin/users')->with('error', 'Username already exists');
            }
        }

        $newEmail = $this->request->getVar('email');
        if ($user->email != $newEmail) {
            $existingEmail = $this->userModel->where('email', $newEmail)->first();
            if ($existingEmail) {
                return redirect()->to('admin/users')->with('error', 'Email already exists');
            }
        }

        $password = $this->request->getVar('password');
        $passConfirm = $this->request->getVar('pass_confirm');
        if (!empty($password)) {
            if ($password != $passConfirm) {
                return redirect()->to('admin/users')->with('error', 'Passwords do not match');
            }
        }

        $data = [
            'id' => $id,
            'username' => $newUsername,
            'email' => $newEmail,
            // 'password' => $password,
            'active' => $this->request->getVar('status') ? 1 : 0,
        ];

        if (!empty($password)) {
            $data['password'] = $password;
        }

        $this->userModel->save($data);

        $groupId = $this->request->getVar('group');
        if (!empty($groupId)) {
            $currentGroups = $this->groupModel->getGroupsForUser($id);

            foreach ($currentGroups as $group) {
                $this->groupModel->removeUserFromGroup($id, $group['group_id']);
            }

            $this->groupModel->addUserToGroup($id, $groupId);
        }

        return redirect()->to('admin/users')->with('message', 'User Updated Successfully');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);

        if (empty($user)) {
            return redirect()->to('admin/users')->with('error', 'User Not Found');
        }

        $this->userModel->delete($id);

        return redirect()->to('admin/users')->with('message', 'User Deleted Successfully');
    }

}