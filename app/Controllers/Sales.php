<?php

namespace App\Controllers;

use App\Models\SaleModel;

class Sales extends BaseController
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        $saleModel = new SaleModel();

        $data = [
            'title' => 'Sales History',
            'sales' => $saleModel->getSalesHistory(),
        ];

        return view('layouts/header', $data)
            . view('sales/index', $data)
            . view('layouts/footer');
    }

    public function new()
    {
        $saleModel = new SaleModel();

        $data = [
            'title'     => 'Record Sale',
            'products'  => $saleModel->getAvailableProducts(),
            'customers' => $saleModel->getCustomers(),
        ];

        return view('layouts/header', $data)
            . view('sales/new', $data)
            . view('layouts/footer');
    }

    public function create()
    {
        $rules = [
            'product_id' => [
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required'           => 'Please select a product.',
                    'is_natural_no_zero' => 'Please select a valid product.',
                ],
            ],
            'customer_id' => [
                'rules' => 'permit_empty|is_natural_no_zero',
                'errors' => [
                    'is_natural_no_zero' => 'Please select a valid customer.',
                ],
            ],
            'quantity' => [
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required'           => 'The quantity is required.',
                    'is_natural_no_zero' => 'The quantity must be a positive whole number.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $staffId = $this->getLoggedInStaffId();

        if ($staffId === null) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Please log in before recording a sale.');
        }

        $customerInput = trim((string) $this->request->getPost('customer_id'));
        $customerId    = $customerInput === '' ? null : (int) $customerInput;

        $saleModel = new SaleModel();
        $result    = $saleModel->recordSale(
            (int) $this->request->getPost('product_id'),
            $customerId,
            $staffId,
            (int) $this->request->getPost('quantity')
        );

        if (! $result['success']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result['message']);
        }

        return redirect()->to(base_url('sales'))
            ->with('success', $result['message']);
    }

    /**
     * Supports the two session-key styles used in the earlier class projects.
     * Keep the key that matches Mendoza's authentication module.
     */
    private function getLoggedInStaffId(): ?int
    {
        foreach (['user_id', 'userId'] as $key) {
            $value = session()->get($key);

            if (is_numeric($value) && (int) $value > 0) {
                return (int) $value;
            }
        }

        return null;
    }
}
