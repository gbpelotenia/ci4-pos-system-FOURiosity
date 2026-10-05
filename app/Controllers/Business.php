<?php
namespace App\Controllers;

class Business extends BaseController
{
    public function category() { return $this->createNamedRecord('categories', 'Category'); }
    public function supplier() { return $this->createNamedRecord('suppliers', 'Supplier', ['contact_person' => trim((string) $this->request->getPost('contact_person')) ?: null, 'phone' => trim((string) $this->request->getPost('phone')) ?: null]); }
    public function expense()
    {
        if (! $this->validate(['description' => 'required|min_length[2]|max_length[150]', 'amount' => 'required|decimal|greater_than[0]', 'expense_date' => 'required|valid_date[Y-m-d]'])) return $this->redirectBackWithError(implode(' ', $this->validator->getErrors()));
        db_connect()->table('expenses')->insert(['description' => trim((string) $this->request->getPost('description')), 'amount' => (float) $this->request->getPost('amount'), 'expense_date' => $this->request->getPost('expense_date'), 'created_at' => date('Y-m-d H:i:s')]);
        return $this->redirectBackWithSuccess('Expense recorded.');
    }
    public function deleteExpense(int $id) { db_connect()->table('expenses')->where('id', $id)->delete(); return $this->redirectBackWithSuccess('Expense deleted.'); }
    private function createNamedRecord(string $table, string $label, array $extra = [])
    {
        $name = trim((string) $this->request->getPost('name'));
        if (mb_strlen($name) < 2 || mb_strlen($name) > 120) return $this->redirectBackWithError("{$label} name must be between 2 and 120 characters.");
        $builder = db_connect()->table($table);
        if ($builder->where('name', $name)->countAllResults() > 0) return $this->redirectBackWithError("{$label} already exists.");
        $builder->insert(array_merge(['name' => $name, 'created_at' => date('Y-m-d H:i:s')], $extra));
        return $this->redirectBackWithSuccess("{$label} added.");
    }
}
