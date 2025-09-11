<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserStructureFolder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'folder_name',
        'parent_id',
        'linked_admin_code',
    ];

    /**
     * Optional: Get the user that owns the folder.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent folder (if nested).
     */
    public function parent()
    {
        return $this->belongsTo(UserStructureFolder::class, 'parent_id');
    }

    /**
     * Get the child folders (if any).
     */
    public function children()
    {
        return $this->hasMany(UserStructureFolder::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->hasMany(UserStructureFolder::class, 'parent_id')->with('childrenRecursive');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'folder_id');
    }

}
