<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneTimePasscode extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'one_time_codes';
}
