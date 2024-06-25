<?php

namespace App\Models;

use CodeIgniter\Model;

class Products extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'product_name',
        'slug',
        'unit_price',
        'unit_type',
        'stock_level',
        'category_id',
        'ordering'
    ];

}
