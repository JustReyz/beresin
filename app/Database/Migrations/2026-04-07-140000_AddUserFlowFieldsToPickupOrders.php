<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserFlowFieldsToPickupOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pickup_orders', [
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'order_code',
            ],
            'pickup_address' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'customer_name',
            ],
            'pickup_time' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'pickup_address',
            ],
            'estimated_volume' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
                'after' => 'pickup_time',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'category',
            ],
            'rating' => [
                'type' => 'INT',
                'constraint' => 1,
                'null' => true,
                'after' => 'assigned_mitra_id',
            ],
            'rated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'rating',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pickup_orders', 'user_id');
        $this->forge->dropColumn('pickup_orders', 'pickup_address');
        $this->forge->dropColumn('pickup_orders', 'pickup_time');
        $this->forge->dropColumn('pickup_orders', 'estimated_volume');
        $this->forge->dropColumn('pickup_orders', 'notes');
        $this->forge->dropColumn('pickup_orders', 'rating');
        $this->forge->dropColumn('pickup_orders', 'rated_at');
    }
}
