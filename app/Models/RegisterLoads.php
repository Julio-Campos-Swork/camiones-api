<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterLoads extends Model
{
    use HasFactory;

    protected $table = 'load_registers';
    protected $primaryKey = 'id_load_register';
    protected $fillable = [
        'id_load',
        'id_register_type',
        'id_truck',
        'id_user',
        'filename',
        'status',
        'register_date'
    ];
    public $timestamps = true;

}
