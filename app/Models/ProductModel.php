<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'price', 'cost_price', 'stock_quantity', 'image', 'category_id', 'supplier_id'];
    protected $useTimestamps = true;
}
