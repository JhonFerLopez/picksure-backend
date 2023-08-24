<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagesPautas extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'imageproducts_id',
        'pautasuser_id',
    ];
}
