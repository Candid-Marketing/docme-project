<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;


class LinkedAccount extends Model
{
    use HasFactory;
    protected $table = 'linked_accounts';
    protected $fillable = [
        'user_id', // The main logged-in user
        'linked_user_id', // The account they can switch to
    ];

   public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function linkedUser()
    {
        return $this->belongsTo(User::class, 'linked_user_id');
    }



}
