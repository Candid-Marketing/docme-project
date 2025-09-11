<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;
    protected $table = 'invitations';

    protected $fillable = [
        'file_id',
        'inviter_id',
        'guest_email',
        'message',
        'available_from',
        'available_until',
        'accepted_at',
        'token',
    ];


    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

}
