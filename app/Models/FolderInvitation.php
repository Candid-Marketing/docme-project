<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolderInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'inviter_id',
        'guest_email',
        'message',
        'available_from',
        'available_until',
        'accepted_at',
        'token',
    ];

    public function folder()
    {
        return $this->belongsTo(UserStructureFolder::class, 'folder_id');
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

  public function files()
    {
        return $this->hasMany(File::class, 'folder_id', 'folder_id');
    }

}
