<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function getFilter($addressId)
    {
        return Filter::where('address_id', $addressId)->first();
    }
}
