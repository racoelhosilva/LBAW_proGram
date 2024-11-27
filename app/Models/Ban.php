<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ban extends Model
{
    use HasFactory;

    protected $table = 'ban';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'administrator_id',
        'start',
        'reason',
        'duration',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function administrator(): BelongsTo
    {
        return $this->belongsTo(Administrator::class);
    }

    public function isPermanent(): bool
    {
        return $this->duration === '00:00:00';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->where(function ($query) {
            $query->whereRaw('start + duration > NOW()')->orWhere('duration', 0);
        });
    }

    public function isActive(): bool
    {
        return $this->is_active && ($this->isPermanent() || $this->end()->isFuture());
    }

    public function end(): ?Carbon
    {
        if ($this->isPermanent()) {
            return null;
        }

        $start = Carbon::parse($this->start);
        $duration = CarbonInterval::make($this->duration);

        return $start->add($duration);
    }
}
