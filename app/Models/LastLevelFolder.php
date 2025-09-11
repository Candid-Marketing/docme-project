<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastLevelFolder extends Model
{
    use HasFactory;
    protected $table = 'last_level_folders';
    protected $fillable = [
        'folder_id',
        'last_folder_name',
        'last_folder_description',
        'last_folder_path',
        'last_folder_status',
        'last_folder_visibility',
        'last_folder_access',
        'last_folder_created_by',
        'last_folder_updated_by'
    ];
}
