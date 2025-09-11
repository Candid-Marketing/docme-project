<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminFolderTemplate extends Model
{
    use HasFactory;
    protected $table = 'admin_folder_templates';
    protected $fillable = ['name', 'unique_code', 'parent_code', 'description', 'sort_order', 'created_by'];

    public function parent()
    {
        return $this->belongsTo(AdminFolderTemplate::class, 'parent_code', 'unique_code');
    }

    public function children()
    {
        return $this->hasMany(AdminFolderTemplate::class, 'parent_code', 'unique_code');
    }

}
