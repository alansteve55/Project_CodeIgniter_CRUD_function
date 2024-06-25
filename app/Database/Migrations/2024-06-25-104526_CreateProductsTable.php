<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'product_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'unit_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'stock_level' => [
                'type' => 'INT',
            ],
            'category_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'ordering' => [
                'type'       => 'INT',
                'constraint'   => 11,
                'default'      =>10000 
            ],
            'created_at timestamp default current_timestamp',
            'updated_at timestamp default current_timestamp on update current_timestamp'
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
