<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterType extends Model
{
    use HasFactory;

    protected $table = 'register_type';

    protected $primaryKey = 'id_register_type';

    protected $fillable = [
        'register_name',
        'status',
    ];

}
