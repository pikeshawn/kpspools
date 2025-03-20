<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScpInvoice extends Model
{
    use HasFactory;

    protected $table = 'scp_invoice';

    protected $guarded = [];
}
