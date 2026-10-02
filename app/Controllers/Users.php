<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    protected UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function index()
    {
        return view('users/index', [
            'title' => 'Staff Management',
            'users' => $this->users->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'full_name' => 'required|min_length[2]|max_length[100]',
            'password' => 'required|min_length[6]',
            'password_confirmation' => 'required|matches[password]',
        ];
        if (! $this->validate($rules)) {
            return $this->redirectBackWithError(implode(' ', $this->validator->getErrors()));
        }

        $avatar = $this->saveAvatar('avatar');
        if ($avatar === false) {
            return $this->redirectBackWithError('Avatar upload failed. Please use JPG, JPEG, PNG, or WEBP up to 2MB.');
        }

        $this->users->insert([
            'username' => trim((string) $this->request->getPost('username')),
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'avatar' => $avatar,
            'role' => 'staff',
        ]);

        return $this->redirectBackWithSuccess('Staff account created.');
    }

    public function update(int $id)
    {
        $user = $this->users->find($id);
        if (! $user) {
            return $this->redirectBackWithError('Staff account not found.');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,$id]",
            'full_name' => 'required|min_length[2]|max_length[100]',
            'password_confirmation' => 'permit_empty|matches[password]',
        ];
        if (! $this->validate($rules)) {
            return $this->redirectBackWithError(implode(' ', $this->validator->getErrors()));
        }

        $avatar = $this->saveAvatar('avatar');
        if ($avatar === false) {
            return $this->redirectBackWithError('Avatar upload failed. Please use JPG, JPEG, PNG, or WEBP up to 2MB.');
        }

        $data = [
            'username' => trim((string) $this->request->getPost('username')),
            'full_name' => trim((string) $this->request->getPost('full_name')),
        ];
        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }
        if ($avatar !== null) {
            $data['avatar'] = $avatar;
        }

        $this->users->update($id, $data);
        return $this->redirectBackWithSuccess('Staff account updated.');
    }

    public function delete(int $id)
    {
        if ($id === (int) session()->get('user_id')) {
            return $this->redirectBackWithError('You cannot delete the account you are currently using.');
        }
        if (! $this->users->find($id)) {
            return $this->redirectBackWithError('Staff account not found.');
        }
        $this->users->delete($id);
        return $this->redirectBackWithSuccess('Staff account deleted.');
    }

    private function saveAvatar(string $field)
    {
        $file = $this->request->getFile($field);
        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (! $file->isValid() || $file->getSizeByUnit('mb') > 2 || ! in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return false;
        }
        $dir = FCPATH . 'uploads/avatars';
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $name = $file->getRandomName();
        $file->move($dir, $name);
        return $name;
    }
}
