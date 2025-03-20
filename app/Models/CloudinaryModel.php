<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudinaryModel extends Model
{
    use HasFactory;

    protected $table = 'cloudinary';

    protected $guarded = [];

    public static function store($image) {}
}
