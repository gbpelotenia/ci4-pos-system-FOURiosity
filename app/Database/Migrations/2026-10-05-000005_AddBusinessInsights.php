<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddBusinessInsights extends Migration
{
    public function up()
    {
        $this->forge->addField(['id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true], 'name' => ['type' => 'VARCHAR', 'constraint' => 120], 'created_at' => ['type' => 'DATETIME', 'null' => true]]);
        $this->forge->addKey('id', true); $this->forge->addUniqueKey('name'); $this->forge->createTable('categories', true);
        $this->forge->addField(['id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true], 'name' => ['type' => 'VARCHAR', 'constraint' => 120], 'contact_person' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true], 'phone' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true], 'created_at' => ['type' => 'DATETIME', 'null' => true]]);
        $this->forge->addKey('id', true); $this->forge->addUniqueKey('name'); $this->forge->createTable('suppliers', true);
        $this->forge->addField(['id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true], 'description' => ['type' => 'VARCHAR', 'constraint' => 150], 'amount' => ['type' => 'DECIMAL', 'constraint' => '12,2'], 'expense_date' => ['type' => 'DATE'], 'created_at' => ['type' => 'DATETIME', 'null' => true]]);
        $this->forge->addKey('id', true); $this->forge->createTable('expenses', true);
        $this->forge->addColumn('products', ['cost_price' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0, 'after' => 'price'], 'category_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'cost_price'], 'supplier_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'category_id']]);
    }
    public function down()
    {
        $this->forge->dropColumn('products', ['cost_price', 'category_id', 'supplier_id']);
        $this->forge->dropTable('expenses', true); $this->forge->dropTable('suppliers', true); $this->forge->dropTable('categories', true);
    }
}
