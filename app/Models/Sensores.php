<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Sensores extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'sensores';
    protected $primaryKey = '_id'; // Nombre del campo ID

    
    protected $fillable = [
        'tipo',
        'nSensor',
        'valor',
        'fecha',
    ];

    public $timestamps = false;


}
