<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;


class Address extends Model
{
    use HasFactory;

    protected $table = "addresses";

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

}
