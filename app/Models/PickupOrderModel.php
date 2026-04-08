<?php

namespace App\Models;

use CodeIgniter\Model;

class PickupOrderModel extends Model
{
    protected $table            = 'pickup_orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    protected $allowedFields = [
        'order_code',
        'user_id',
        'customer_name',
        'pickup_address',
        'pickup_time',
        'estimated_volume',
        'category',
        'notes',
        'status',
        'assigned_mitra_id',
        'rating',
        'rated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
