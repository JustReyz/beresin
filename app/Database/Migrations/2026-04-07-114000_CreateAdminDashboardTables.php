<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminDashboardTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'customer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'aktif',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('order_code');
        $this->forge->createTable('pickup_orders', true);

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'today_completed' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'today_active' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'availability_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'standby',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('couriers', true);
    }

    public function down()
    {
        $this->forge->dropTable('couriers', true);
        $this->forge->dropTable('pickup_orders', true);
    }
}
