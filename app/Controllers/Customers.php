<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    protected CustomerModel $customers;

    public function __construct()
    {
        $this->customers = new CustomerModel();
    }

    public function index()
    {
        return view('customers/index', [
            'title' => 'Customers',
            'customers' => $this->customers->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        $rules = [
            'full_name' => 'required|min_length[2]|max_length[120]',
            'email' => 'permit_empty|valid_email|max_length[150]',
            'phone' => 'permit_empty|max_length[30]',
        ];
        if (! $this->validate($rules)) {
            return $this->redirectBackWithError(implode(' ', $this->validator->getErrors()));
        }

        $this->customers->insert([
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email' => trim((string) $this->request->getPost('email')) ?: null,
            'phone' => trim((string) $this->request->getPost('phone')) ?: null,
        ]);

        return $this->redirectBackWithSuccess('Customer created.');
    }

    public function update(int $id)
    {
        if (! $this->customers->find($id)) {
            return $this->redirectBackWithError('Customer not found.');
        }

        $rules = [
            'full_name' => 'required|min_length[2]|max_length[120]',
            'email' => 'permit_empty|valid_email|max_length[150]',
            'phone' => 'permit_empty|max_length[30]',
        ];
        if (! $this->validate($rules)) {
            return $this->redirectBackWithError(implode(' ', $this->validator->getErrors()));
        }

        $this->customers->update($id, [
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email' => trim((string) $this->request->getPost('email')) ?: null,
            'phone' => trim((string) $this->request->getPost('phone')) ?: null,
        ]);

        return $this->redirectBackWithSuccess('Customer updated.');
    }

    public function delete(int $id)
    {
        if (! $this->customers->find($id)) {
            return $this->redirectBackWithError('Customer not found.');
        }
        $this->customers->delete($id);
        return $this->redirectBackWithSuccess('Customer deleted.');
    }
}
