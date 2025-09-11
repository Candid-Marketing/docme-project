<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_type',
        'property_type',
        'property_address',
        'property_usage',
        'number_of_people',
        'title',
        'first_name',
        'last_name',
        'employment',
        'trust_account',
        'share_account',
        'liabilities',
        'loan_types',
    ];

    // Optionally, you can define the casts for JSON columns
    protected $casts = [
        'liabilities' => 'array',
        'loan_types' => 'array',
    ];
}

