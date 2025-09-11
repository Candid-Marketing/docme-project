<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    protected $fillable = [
        'user_id', 'plan_name', 'product_id', 'storage_limit', 'share_limit', 'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
