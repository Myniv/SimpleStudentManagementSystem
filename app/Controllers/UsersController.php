<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MStudent;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;


class UsersController extends BaseController
{
    protected $userModel;
    private $studentModel;

    protected $groupModel;
    protected $db;
    protected $config;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
        $this->studentModel = new MStudent();
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

        return view('users/v_user_list', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add New User',
            'groups' => $this->groupModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('users/v_user_create', $data);
    }

    public function createUserStudent()
    {
        $data = [
            'title' => 'Add New User',
            'groups' => $this->groupModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('users/v_user_student_register', $data);
    }
    public function storeUserStudent()
    {
        //Add user
        $user = new User();
        $user->username = $this->request->getVar('username');
        $user->email = $this->request->getVar('email');
        $user->password = $this->request->getVar('password');
        $user->active = 1;

        $checkExistingEmail = $this->userModel->where('email', $user->email)->first();
        if ($checkExistingEmail) {
            return redirect()->back()->withInput()->with('errors', "Email already exists");
        }

        $checkExistingUsername = $this->userModel->where('username', $user->username)->first();
        if ($checkExistingUsername) {
            return redirect()->back()->withInput()->with('errors', "Username already exists");
        }

        $this->userModel->save($user);

        $newUser = $this->userModel->where('email', $user->email)->first();
        $userId = $newUser->id;

        //Add Student
        $formData = [
            'student_id' => $this->request->getPost('student_id'),
            'name' => $this->request->getPost('name'),
            'study_program' => $this->request->getPost('study_program'),
            'current_semester' => $this->request->getPost('current_semester'),
            'academic_status' => $this->request->getPost('academic_status'),
            'entry_year' => $this->request->getPost('entry_year'),
            'gpa' => $this->request->getPost('gpa'),
            'user_id' => $userId
        ];

        if (!$this->studentModel->validate($formData)) {
            return redirect()->back()->withInput()->with('errors', $this->studentModel->errors());
        }

        $this->studentModel->save($formData);

        if ($user) {
            $studentGroup = $this->groupModel->where('name', 'student')->first();
            if ($studentGroup) {
                $this->groupModel->addUserToGroup($userId, $studentGroup->id);
            }
        }

        return redirect()->to('/login')->with('message', 'User Created Successfully');
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

        return view('users/v_user_edit', $data);
    }

    public function store()
    {
        $user = new \Myth\Auth\Entities\User();
        $user->username = $this->request->getVar('username');
        $user->email = $this->request->getVar('email');
        $user->password = $this->request->getVar('password');
        $user->active = 1;

        $checkExistingEmail = $this->userModel->where('email', $user->email)->first();
        $checkExistingEmailSoftDelete = $this->userModel->withDeleted()->where('email', $user->email)->first();
        if ($checkExistingEmail) {
            return redirect()->to('admin/users')->with('error', 'Email already exists');
        } elseif ($checkExistingEmailSoftDelete) {
            return redirect()->to('admin/users')->with('error', 'Email already exists as deleted user');
        }


        $checkExistingUsername = $this->userModel->where('username', $user->username)->first();
        $checkExistingUsernameSoftDelete = $this->userModel->withDeleted()->where('username', $user->username)->first();
        if ($checkExistingUsername) {
            return redirect()->to('admin/users')->with('error', 'Username already exists');
        } elseif ($checkExistingUsernameSoftDelete) {
            return redirect()->to('admin/users')->with('error', 'Username already exists as deleted user');
        }

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
            $existingUserWithSoftDelete = $this->userModel->withDeleted()->where('username', $newUsername)->first();
            if ($existingUser) {
                return redirect()->to('admin/users')->with('error', 'Username already exists');
            } elseif ($existingUserWithSoftDelete) {
                return redirect()->to('admin/users')->with('error', 'Username already exists as deleted user');
            }
        }

        $newEmail = $this->request->getVar('email');
        if ($user->email != $newEmail) {
            $existingEmail = $this->userModel->where('email', $newEmail)->first();
            $existingEmailWithSoftDelete = $this->userModel->withDeleted()->where('email', $newEmail)->first();
            if ($existingEmail) {
                return redirect()->to('admin/users')->with('error', 'Email already exists');
            } else if ($existingEmailWithSoftDelete) {
                return redirect()->to('admin/users')->with('error', 'Email already exists as deleted user');
            }
        }

        $password = $this->request->getVar('password');
        $passConfirm = $this->request->getVar('pass_confirm');
        if (!empty($password)) {
            if ($password != $passConfirm) {
                return redirect()->to('admin/users')->with('error', 'Passwords do not match');
            }
        }

        $user->username = $newUsername;
        $user->email = $newEmail;
        $user->active = $this->request->getVar('status') ? 1 : 0;

        if (!empty($password)) {
            $user->password = $password;
        }

        $this->userModel->save($user);

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