<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['content', 'author', 'source', 'meaning', 'category'])]
#[Hidden(['search'])]
class Quote extends Model
{
    public const CATEGORIES = [
        'quotations' => 'مقولات',
        'literature' => 'أدب',
        'poetry' => 'شعر',
        'theater' => 'مسرح',
    ];

    protected static function booted(): void
    {
        static::saving(function (Quote $quote) {
            $quote->lang = preg_match('/\p{Arabic}/u', $quote->content) ? 'ar' : 'en';
            $quote->search = static::normalize($quote->content . ' ' . $quote->author . ' ' . $quote->source);
        });
    }

    public static function normalize(?string $text): string
    {
        $text = mb_strtolower(trim((string) $text));
        $text = preg_replace('/[\x{064B}-\x{065F}\x{0670}\x{0640}]/u', '', $text);

        return strtr($text, ['أ' => 'ا', 'إ' => 'ا', 'آ' => 'ا', 'ٱ' => 'ا', 'ة' => 'ه', 'ى' => 'ي', '’' => "'", '‘' => "'"]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lighters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lights')->withTimestamps();
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class)->withTimestamps();
    }

    public function gifts(): HasMany
    {
        return $this->hasMany(Gift::class);
    }

    public function scopeInSection(Builder $query, string $slug): void
    {
        $section = config("sections.$slug");

        $query->whereIn('category', $section['types'])
            ->when($section['lang'], fn ($q, $lang) => $q->where('lang', $lang));
    }

    public function scopeSearch(Builder $query, ?string $term): void
    {
        $term = static::normalize($term);

        if ($term !== '') {
            $query->where('search', 'like', '%' . addcslashes($term, '%_\\') . '%');
        }
    }

    public function isOwnedBy(?User $user): bool
    {
        return $user !== null && $user->id === $this->user_id;
    }
}
