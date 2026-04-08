<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAssignedMitraToPickupOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pickup_orders', [
            'assigned_mitra_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'status',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pickup_orders', 'assigned_mitra_id');
    }
}
