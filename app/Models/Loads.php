<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loads extends Model
{
    use HasFactory;

    protected $table = 'loads';
    protected $primaryKey = 'id_load';

    protected $fillable = [
        'load_name',
        'status'
    ];
}
