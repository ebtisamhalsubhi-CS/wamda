<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['quote_id', 'to_name', 'from_name', 'message'])]
class Gift extends Model
{
    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Gift $gift) {
            $gift->code = Str::random(10);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }
}