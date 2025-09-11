<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InnerFolder extends Model
{
    use HasFactory;
    protected $table = 'inner_folders';
    protected $fillable = [
        'folder_id',
        'inner_folder_name',
        'inner_folder_description',
        'inner_folder_path',
        'inner_folder_status',
        'inner_folder_visibility',
        'inner_folder_access',
        'inner_folder_created_by',
        'inner_folder_updated_by'
    ];
}
