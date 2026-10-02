<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Products extends BaseController
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        return view('products/index', [
            'title' => 'Products',
            'products' => (new ProductModel())->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function new()
    {
        return view('products/new', ['title' => 'Add Product']);
    }

    public function create()
    {
        $rules = [
            'name' => 'required|max_length[150]',
            'price' => 'required|decimal',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'image' => 'permit_empty|is_image[image]|max_size[image,2048]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $image = $this->request->getFile('image');
        $imageName = null;
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $imageName);
        }

        (new ProductModel())->insert([
            'name' => trim($this->request->getPost('name')),
            'price' => $this->request->getPost('price'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'image' => $imageName,
        ]);

        return redirect()->to('/products')->with('message', 'Product added successfully.');
    }

    public function edit(int $id)
    {
        $product = (new ProductModel())->find($id);
        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Product not found');
        }
        return view('products/edit', ['title' => 'Edit Product', 'product' => $product]);
    }

    public function update(int $id)
    {
        $model = new ProductModel();
        if (!$model->find($id)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Product not found');
        }

        $rules = [
            'name' => 'required|max_length[150]',
            'price' => 'required|decimal',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'image' => 'permit_empty|is_image[image]|max_size[image,2048]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => trim($this->request->getPost('name')),
            'price' => $this->request->getPost('price'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
        ];
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $data['image'] = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $data['image']);
        }
        $model->update($id, $data);

        return redirect()->to('/products')->with('message', 'Product updated successfully.');
    }

    public function delete(int $id)
    {
        (new ProductModel())->delete($id);
        return redirect()->to('/products')->with('message', 'Product deleted successfully.');
    }
}
