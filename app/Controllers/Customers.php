<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        return view('customers/index', [
            'title' => 'Customers',
            'customers' => (new CustomerModel())->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function new()
    {
        return view('customers/new', ['title' => 'Add Customer']);
    }

    public function create()
    {
        $rules = [
            'full_name' => 'required|max_length[150]',
            'email' => 'required|valid_email|max_length[150]',
            'phone' => 'required|max_length[30]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new CustomerModel())->insert([
            'full_name' => trim($this->request->getPost('full_name')),
            'email' => trim($this->request->getPost('email')),
            'phone' => trim($this->request->getPost('phone')),
        ]);

        return redirect()->to('/customers')->with('message', 'Customer added successfully.');
    }

    public function edit(int $id)
    {
        $customer = (new CustomerModel())->find($id);
        if (!$customer) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Customer not found');
        }
        return view('customers/edit', ['title' => 'Edit Customer', 'customer' => $customer]);
    }

    public function update(int $id)
    {
        $model = new CustomerModel();
        if (!$model->find($id)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Customer not found');
        }

        $rules = [
            'full_name' => 'required|max_length[150]',
            'email' => 'required|valid_email|max_length[150]',
            'phone' => 'required|max_length[30]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->update($id, [
            'full_name' => trim($this->request->getPost('full_name')),
            'email' => trim($this->request->getPost('email')),
            'phone' => trim($this->request->getPost('phone')),
        ]);

        return redirect()->to('/customers')->with('message', 'Customer updated successfully.');
    }

    public function delete(int $id)
    {
        (new CustomerModel())->delete($id);
        return redirect()->to('/customers')->with('message', 'Customer deleted successfully.');
    }
}
