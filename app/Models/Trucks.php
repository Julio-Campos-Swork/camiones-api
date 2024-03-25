<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trucks extends Model
{
    use HasFactory;

    protected $table = 'trucks';
    protected $primaryKey = 'id_truck';
    protected $fillable = [
        'truck_name',
        'status',
    ];
}
