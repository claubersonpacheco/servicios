<?php

namespace App\Models;

use App\Enums\AdressType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'date_start' => 'date',
            'date_end' => 'date',
            'hour_start' => 'datetime:H:i',
            'hour_end' => 'datetime:H:i',
            'status' => Status::class,
            'address_type' => AdressType::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ServiceImage::class);
    }

    public function scopeVisibleFor(Builder $query, $user): Builder
    {
        return $query->when(
            ! $user?->can('services.view.all'),
            fn ($q) => $q->where('user_id', $user->id)
        );
    }
}
