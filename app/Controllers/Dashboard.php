<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\SaleModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $salesModel = new SaleModel();
        $productModel = new ProductModel();
        $customerModel = new CustomerModel();
        $userModel = new UserModel();

        $recentSales = $salesModel->select('sales.*, products.name AS product_name, customers.full_name AS customer_name, users.full_name AS staff_name')
            ->join('products', 'products.id = sales.product_id')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->join('users', 'users.id = sales.staff_id')
            ->orderBy('sales.id', 'DESC')
            ->findAll(8);

        $totalSales = (float) ($salesModel->selectSum('total_price')->first()['total_price'] ?? 0);
        $lowStock = $productModel->where('stock_quantity <=', 5)->orderBy('stock_quantity', 'ASC')->findAll(10);

        return view('dashboard/index', [
            'title' => 'Dashboard',
            'totalProducts' => $productModel->countAllResults(),
            'totalCustomers' => $customerModel->countAllResults(),
            'totalStaff' => $userModel->countAllResults(),
            'totalTransactions' => $salesModel->countAllResults(),
            'totalSales' => $totalSales,
            'recentSales' => $recentSales,
            'lowStock' => $lowStock,
        ]);
    }
}
