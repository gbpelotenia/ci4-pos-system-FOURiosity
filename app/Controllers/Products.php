<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\SaleModel;

class Products extends BaseController
{
    protected ProductModel $products;

    public function __construct()
    {
        $this->products = new ProductModel();
    }

    public function index()
    {
        $db = db_connect();
        return view('products/index', [
            'title' => 'Products',
            'products' => $this->products->select('products.*, categories.name AS category_name, suppliers.name AS supplier_name')->join('categories', 'categories.id = products.category_id', 'left')->join('suppliers', 'suppliers.id = products.supplier_id', 'left')->orderBy('products.id', 'DESC')->findAll(),
            'categories' => $db->table('categories')->orderBy('name')->get()->getResultArray(),
            'suppliers' => $db->table('suppliers')->orderBy('name')->get()->getResultArray(),
        ]);
    }

    public function create()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[120]',
            'price' => 'required|decimal|greater_than_equal_to[0]',
            'cost_price' => 'required|decimal|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
        ];
        if (! $this->validate($rules)) {
            return $this->redirectBackWithError(implode(' ', $this->validator->getErrors()));
        }

        $image = $this->saveImage('image');
        if ($image === false) {
            return $this->redirectBackWithError('Product image upload failed. Please use JPG, JPEG, PNG, or WEBP up to 2MB.');
        }

        $this->products->insert([
            'name' => trim((string) $this->request->getPost('name')),
            'price' => (float) $this->request->getPost('price'),
            'cost_price' => (float) $this->request->getPost('cost_price'),
            'stock_quantity' => (int) $this->request->getPost('stock_quantity'),
            'category_id' => $this->nullableId('category_id'),
            'supplier_id' => $this->nullableId('supplier_id'),
            'image' => $image,
        ]);

        return $this->redirectBackWithSuccess('Product created.');
    }

    public function update(int $id)
    {
        $product = $this->products->find($id);
        if (! $product) {
            return $this->redirectBackWithError('Product not found.');
        }

        $rules = [
            'name' => 'required|min_length[2]|max_length[120]',
            'price' => 'required|decimal|greater_than_equal_to[0]',
            'cost_price' => 'required|decimal|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
        ];
        if (! $this->validate($rules)) {
            return $this->redirectBackWithError(implode(' ', $this->validator->getErrors()));
        }

        $image = $this->saveImage('image');
        if ($image === false) {
            return $this->redirectBackWithError('Product image upload failed. Please use JPG, JPEG, PNG, or WEBP up to 2MB.');
        }

        $data = [
            'name' => trim((string) $this->request->getPost('name')),
            'price' => (float) $this->request->getPost('price'),
            'cost_price' => (float) $this->request->getPost('cost_price'),
            'stock_quantity' => (int) $this->request->getPost('stock_quantity'),
            'category_id' => $this->nullableId('category_id'),
            'supplier_id' => $this->nullableId('supplier_id'),
        ];
        if ($image !== null) {
            $data['image'] = $image;
        }

        $this->products->update($id, $data);
        return $this->redirectBackWithSuccess('Product updated.');
    }

    public function delete(int $id)
    {
        if (! $this->products->find($id)) {
            return $this->redirectBackWithError('Product not found.');
        }

        $sales = new SaleModel();
        if ($sales->where('product_id', $id)->countAllResults() > 0) {
            return $this->redirectBackWithError('This product has sales history and cannot be deleted. Set its stock to 0 instead.');
        }

        $this->products->delete($id);
        return $this->redirectBackWithSuccess('Product deleted.');
    }

    private function saveImage(string $field)
    {
        $file = $this->request->getFile($field);
        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (! $file->isValid() || $file->getSizeByUnit('mb') > 2 || ! in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return false;
        }
        $dir = FCPATH . 'uploads/products';
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $name = $file->getRandomName();
        $file->move($dir, $name);
        return $name;
    }

    private function nullableId(string $field): ?int
    {
        $value = $this->request->getPost($field);
        return $value === null || $value === '' ? null : (int) $value;
    }
}
