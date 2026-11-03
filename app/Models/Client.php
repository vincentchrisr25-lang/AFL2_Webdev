<?php

namespace App\Models;

use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory;

    protected $fillable = [
        'client_name',
        'card_title',
        'cover_image',
        'month_date',
        'client_type',
    ];

    public function assets(): HasMany
    {
        return $this->hasMany(ClientAsset::class);
    }
}
