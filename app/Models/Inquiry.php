<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'name',
        'partner_or_brand',
        'email',
        'whatsapp',
        'instagram',
        'session_types',
        'shoot_date',
        'location',
        'vision',
        'status',
    ];
}
