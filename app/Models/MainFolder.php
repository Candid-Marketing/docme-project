<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainFolder extends Model
{
    use HasFactory;
    protected $table = 'main_folder';
    protected $fillable = [
        'folder_name',
        'folder_description',
        'folder_path',
        'folder_status',
        'folder_visibility',
        'folder_access',
        'folder_created_by',
        'folder_updated_by'
    ];
}
