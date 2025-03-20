<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScpInvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'scp_invoice_item';

    protected $guarded = [];
}
