<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;
    protected $table = 'downloads';

    protected $fillable = [
        'file_id',
        'file_name',
        'user_email',
        'file_type',
        'downloaded_at',
    ];
}
