<?php

namespace App\Models;

use CodeIgniter\Model;

class CourierModel extends Model
{
    protected $table            = 'couriers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    protected $allowedFields = [
        'name',
        'today_completed',
        'today_active',
        'availability_status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
