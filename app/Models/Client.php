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

    public function videos(): HasMany
    {
        return $this->hasMany(ClientAsset::class)
            ->where('file_type', 'video')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ClientAsset::class)
            ->where('file_type', 'photo')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function getCoverUrlAttribute(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        return asset('storage/'.$this->cover_image);
    }

    public function getDisplayTitleAttribute(): string
    {
        return $this->card_title ?: $this->client_name;
    }
}
