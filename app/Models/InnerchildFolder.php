<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InnerchildFolder extends Model
{
    use HasFactory;
    protected $table = 'innerchild_folders';
    protected $fillable = [
        'folder_id',
        'innerchild_folder_name',
        'innerchild_folder_description',
        'innerchild_folder_path',
        'innerchild_folder_status',
        'innerchild_folder_visibility',
        'innerchild_folder_access',
        'innerchild_folder_created_by',
        'innerchild_folder_updated_by'
    ];
}
