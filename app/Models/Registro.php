<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'registros';
}
