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
        $db = db_connect();
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

        $today = date('Y-m-d');
        $todayRow = $db->table('sales s')
            ->select('COALESCE(SUM(s.total_price), 0) AS sales, COALESCE(SUM((s.unit_price - p.cost_price) * s.quantity), 0) AS profit, COUNT(s.id) AS transactions')
            ->join('products p', 'p.id = s.product_id')
            ->where('DATE(s.created_at)', $today)
            ->get()->getRowArray();

        $chartRows = $db->query("SELECT DATE(created_at) sale_date, SUM(total_price) total FROM sales WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY DATE(created_at) ORDER BY sale_date")->getResultArray();
        $chartMap = array_column($chartRows, 'total', 'sale_date');
        $chartLabels = [];
        $chartValues = [];
        for ($daysAgo = 6; $daysAgo >= 0; $daysAgo--) {
            $date = date('Y-m-d', strtotime("-{$daysAgo} days"));
            $chartLabels[] = date('M j', strtotime($date));
            $chartValues[] = (float) ($chartMap[$date] ?? 0);
        }

        $topProducts = $db->table('sales s')
            ->select('p.name, SUM(s.quantity) AS units_sold, SUM(s.total_price) AS revenue')
            ->join('products p', 'p.id = s.product_id')
            ->groupBy('s.product_id, p.name')->orderBy('units_sold', 'DESC')->limit(5)->get()->getResultArray();

        $categories = $db->table('categories')->orderBy('name')->get()->getResultArray();
        $suppliers = $db->table('suppliers')->orderBy('name')->get()->getResultArray();
        $recentExpenses = $db->table('expenses')->orderBy('expense_date', 'DESC')->orderBy('id', 'DESC')->limit(5)->get()->getResultArray();
        $monthExpenses = (float) ($db->table('expenses')->selectSum('amount')->where('expense_date >=', date('Y-m-01'))->get()->getRowArray()['amount'] ?? 0);

        return view('dashboard/index', [
            'title' => 'Dashboard',
            'totalProducts' => $productModel->countAllResults(),
            'totalCustomers' => $customerModel->countAllResults(),
            'totalStaff' => $userModel->countAllResults(),
            'totalTransactions' => $salesModel->countAllResults(),
            'totalSales' => $totalSales,
            'recentSales' => $recentSales,
            'lowStock' => $lowStock,
            'todaySales' => (float) ($todayRow['sales'] ?? 0),
            'todayProfit' => (float) ($todayRow['profit'] ?? 0),
            'todayTransactions' => (int) ($todayRow['transactions'] ?? 0),
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
            'topProducts' => $topProducts,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'recentExpenses' => $recentExpenses,
            'monthExpenses' => $monthExpenses,
        ]);
    }
}
