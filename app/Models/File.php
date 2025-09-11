<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $table = 'files';

    protected $fillable = [
        'folder_id',
        'file_name',
        'file_path',
        'file_size',
        'file_type',
        'folder_name',
        'created_by',
        'updated_by',
    ];

    public function main()
    {
        return $this->belongsTo(MainFolder::class, 'main_id');
    }

    public function sub()
    {
        return $this->belongsTo(SubFolder::class, 'sub_id');
    }

    public function inner()
    {
        return $this->belongsTo(InnerFolder::class, 'inner_id');
    }

    public function child()
    {
        return $this->belongsTo(ChildFolder::class, 'child_id');
    }

    public function innerChild()
    {
        return $this->belongsTo(InnerchildFolder::class, 'innerchild_id');
    }

    public function lastChild()
    {
        return $this->belongsTo(LastLevelFolder::class, 'lastchild_id');
    }

    public function folder()
    {
        return $this->belongsTo(UserStructureFolder::class, 'folder_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'created_by', 'email'); // or whatever column
    }

}
