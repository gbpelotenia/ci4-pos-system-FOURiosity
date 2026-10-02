<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    protected $helpers = ['form'];

    public function index()
    {
        $userModel = new UserModel();

        return view('users/index', [
            'users' => $userModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function new()
    {
        return view('users/create');
    }

    public function create()
    {
        $rules = [
            'username'         => 'required|min_length[3]|max_length[100]',
            'full_name'        => 'required|max_length[150]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
            'avatar'           => 'permit_empty|is_image[avatar]|max_size[avatar,2048]|mime_in[avatar,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $avatarName = $this->uploadAvatar();

        $userModel = new UserModel();

        $userModel->insert([
            'username'  => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
            'password'  => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'avatar'    => $avatarName,
        ]);

        return redirect()->to(site_url('users'))
            ->with('success', 'Staff member added successfully.');
    }

    public function edit($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('users/edit', [
            'user' => $user,
        ]);
    }

    public function update($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'username'         => 'required|min_length[3]|max_length[100]',
            'full_name'        => 'required|max_length[150]',
            'password_confirm' => 'permit_empty|matches[password]',
            'avatar'           => 'permit_empty|is_image[avatar]|max_size[avatar,2048]|mime_in[avatar,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username'  => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
        ];

        $password = $this->request->getPost('password');

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $avatarName = $this->uploadAvatar();

        if ($avatarName) {
            $data['avatar'] = $avatarName;
        }

        $userModel->update($id, $data);

        return redirect()->to(site_url('users'))
            ->with('success', 'Staff member updated successfully.');
    }

    public function delete($id)
    {
        $userModel = new UserModel();
        $userModel->delete($id);

        return redirect()->to(site_url('users'))
            ->with('success', 'Staff member deleted successfully.');
    }

    private function uploadAvatar()
    {
        $avatar = $this->request->getFile('avatar');

        if (!$avatar || !$avatar->isValid() || $avatar->hasMoved()) {
            return null;
        }

        $uploadPath = FCPATH . 'uploads/avatars/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $avatarName = $avatar->getRandomName();
        $avatar->move($uploadPath, $avatarName);

        return $avatarName;
    }
}