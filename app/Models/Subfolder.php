<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subfolder extends Model
{
    use HasFactory;
    protected $table = 'sub_folders';
    protected $fillable = [
        'folder_id',
        'sub_folder_name',
        'sub_folder_description',
        'sub_folder_path',
        'sub_folder_status',
        'sub_folder_visibility',
        'sub_folder_access',
        'sub_folder_created_by',
        'sub_folder_updated_by'
    ];

}
