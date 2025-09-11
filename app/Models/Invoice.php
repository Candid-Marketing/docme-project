<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    protected $fillable = [
        'user_id',
        'invoice_id',
        'invoice_file',
        'invoice_date',
        'due_date',
        'amount',
        'invoice_path',
    ];
}
