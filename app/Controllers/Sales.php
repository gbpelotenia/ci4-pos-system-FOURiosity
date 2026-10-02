<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\SaleModel;

class Sales extends BaseController
{
    protected ProductModel $products;
    protected CustomerModel $customers;
    protected SaleModel $sales;

    public function __construct()
    {
        $this->products = new ProductModel();
        $this->customers = new CustomerModel();
        $this->sales = new SaleModel();
    }

    public function index()
    {
        return view('sales/index', [
            'title' => 'Sales',
            'products' => $this->products->where('stock_quantity >', 0)->orderBy('name')->findAll(),
            'customers' => $this->customers->orderBy('full_name')->findAll(),
            'sales' => $this->sales->select('sales.*, products.name AS product_name, customers.full_name AS customer_name, users.full_name AS staff_name')
                ->join('products', 'products.id = sales.product_id')
                ->join('customers', 'customers.id = sales.customer_id', 'left')
                ->join('users', 'users.id = sales.staff_id')
                ->orderBy('sales.id', 'DESC')
                ->findAll(100),
        ]);
    }

    public function create()
    {
        $rules = [
            'product_id' => 'required|is_natural_no_zero',
            'customer_id' => 'permit_empty|is_natural',
            'quantity' => 'required|integer|greater_than[0]',
        ];
        if (! $this->validate($rules)) {
            return $this->redirectBackWithError(implode(' ', $this->validator->getErrors()));
        }

        $productId = (int) $this->request->getPost('product_id');
        $quantity = (int) $this->request->getPost('quantity');
        $customerId = $this->request->getPost('customer_id');
        $customerId = $customerId !== '' ? (int) $customerId : null;

        $product = $this->products->find($productId);
        if (! $product) {
            return $this->redirectBackWithError('Selected product does not exist.');
        }
        if ($quantity > (int) $product['stock_quantity']) {
            return $this->redirectBackWithError('Not enough stock. Available: ' . $product['stock_quantity'] . '.');
        }
        if ($customerId !== null && ! $this->customers->find($customerId)) {
            return $this->redirectBackWithError('Selected customer does not exist.');
        }

        $total = round((float) $product['price'] * $quantity, 2);
        $db = db_connect();
        $db->transStart();

        $this->sales->insert([
            'product_id' => $productId,
            'customer_id' => $customerId,
            'staff_id' => (int) session()->get('user_id'),
            'quantity' => $quantity,
            'unit_price' => (float) $product['price'],
            'total_price' => $total,
        ]);

        $this->products->update($productId, [
            'stock_quantity' => (int) $product['stock_quantity'] - $quantity,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->redirectBackWithError('The sale could not be recorded. No stock change was made.');
        }

        return $this->redirectBackWithSuccess('Sale recorded successfully. Stock was updated.');
    }
}
