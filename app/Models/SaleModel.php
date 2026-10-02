<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table            = 'sales';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'customer_id',
        'sold_by',
        'quantity',
        'total_price',
        'created_at',
    ];

    protected $useTimestamps = false;

    /**
     * Products that can currently be sold.
     */
    public function getAvailableProducts(): array
    {
        return $this->db->table('products')
            ->select('id, name, price, stock_quantity, image')
            ->where('stock_quantity >', 0)
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Customers shown in the optional customer selector.
     */
    public function getCustomers(): array
    {
        return $this->db->table('customers')
            ->select('id, full_name, email')
            ->orderBy('full_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Sales history with the names needed by the view.
     */
    public function getSalesHistory(?int $limit = null): array
    {
        $builder = $this->db->table('sales');
        $builder->select(
            "sales.id, sales.quantity, sales.total_price, sales.created_at,
             COALESCE(products.name, 'Unavailable Product') AS product_name,
             COALESCE(customers.full_name, 'Walk-in Customer') AS customer_name,
             COALESCE(users.full_name, 'Unavailable Staff') AS staff_name",
            false
        );
        $builder->join('products', 'products.id = sales.product_id', 'left');
        $builder->join('customers', 'customers.id = sales.customer_id', 'left');
        $builder->join('users', 'users.id = sales.sold_by', 'left');
        $builder->orderBy('sales.created_at', 'DESC');
        $builder->orderBy('sales.id', 'DESC');

        if ($limit !== null) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Records one sale and decreases stock as one database transaction.
     *
     * The product price and staff ID are never accepted from the form.
     */
    public function recordSale(
        int $productId,
        ?int $customerId,
        int $soldBy,
        int $quantity
    ): array {
        if ($productId < 1 || $soldBy < 1 || $quantity < 1) {
            return [
                'success' => false,
                'message' => 'The sale information is invalid.',
            ];
        }

        $product = $this->db->table('products')
            ->select('id, name, price, stock_quantity')
            ->where('id', $productId)
            ->get()
            ->getRowArray();

        if (! $product) {
            return [
                'success' => false,
                'message' => 'The selected product does not exist.',
            ];
        }

        if ((int) $product['stock_quantity'] < $quantity) {
            return [
                'success' => false,
                'message' => 'The requested quantity exceeds the available stock.',
            ];
        }

        if ($customerId !== null) {
            $customerExists = $this->db->table('customers')
                ->where('id', $customerId)
                ->countAllResults() === 1;

            if (! $customerExists) {
                return [
                    'success' => false,
                    'message' => 'The selected customer does not exist.',
                ];
            }
        }

        $staffExists = $this->db->table('users')
            ->where('id', $soldBy)
            ->countAllResults() === 1;

        if (! $staffExists) {
            return [
                'success' => false,
                'message' => 'The logged-in staff account could not be found.',
            ];
        }

        $totalPrice = round((float) $product['price'] * $quantity, 2);

        $this->db->transBegin();

        try {
            // The stock condition prevents two simultaneous requests from overselling.
            $stockUpdated = $this->db->table('products')
                ->set('stock_quantity', 'stock_quantity - ' . $quantity, false)
                ->where('id', $productId)
                ->where('stock_quantity >=', $quantity)
                ->update();

            if (! $stockUpdated || $this->db->affectedRows() !== 1) {
                $this->db->transRollback();

                return [
                    'success' => false,
                    'message' => 'The requested quantity is no longer available.',
                ];
            }

            $saleInserted = $this->db->table('sales')->insert([
                'product_id'  => $productId,
                'customer_id' => $customerId,
                'sold_by'     => $soldBy,
                'quantity'    => $quantity,
                'total_price' => $totalPrice,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);

            if (! $saleInserted || $this->db->transStatus() === false) {
                $this->db->transRollback();

                return [
                    'success' => false,
                    'message' => 'The sale could not be recorded. No stock was changed.',
                ];
            }

            $saleId = $this->db->insertID();
            $this->db->transCommit();

            return [
                'success'     => true,
                'message'     => 'Sale recorded successfully.',
                'sale_id'     => $saleId,
                'total_price' => $totalPrice,
            ];
        } catch (\Throwable $exception) {
            $this->db->transRollback();
            log_message('error', 'Sale transaction failed: {message}', [
                'message' => $exception->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An unexpected error occurred. No sale was recorded.',
            ];
        }
    }

    /**
     * Summary information used on the dashboard.
     */
    public function getDashboardData(): array
    {
        $revenue = $this->db->table('sales')
            ->selectSum('total_price', 'total_revenue')
            ->get()
            ->getRowArray();

        return [
            'total_products'     => $this->db->table('products')->countAllResults(),
            'total_customers'    => $this->db->table('customers')->countAllResults(),
            'total_staff'        => $this->db->table('users')->countAllResults(),
            'total_transactions' => $this->db->table('sales')->countAllResults(),
            'total_revenue'      => (float) ($revenue['total_revenue'] ?? 0),
            'low_stock_products' => $this->db->table('products')
                ->select('id, name, stock_quantity')
                ->where('stock_quantity <=', 5)
                ->orderBy('stock_quantity', 'ASC')
                ->orderBy('name', 'ASC')
                ->get()
                ->getResultArray(),
            'recent_sales' => $this->getSalesHistory(5),
        ];
    }
}
