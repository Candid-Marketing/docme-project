<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildFolder extends Model
{
    use HasFactory;
    protected $table = 'child_folders';
    protected $fillable = [
        'folder_id',
        'child_folder_name',
        'child_folder_description',
        'child_folder_path',
        'child_folder_status',
        'child_folder_visibility',
        'child_folder_access',
        'child_folder_created_by',
        'child_folder_updated_by'
    ];

}
