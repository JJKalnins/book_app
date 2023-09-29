<?php

namespace App\Models;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    public function author(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_to_authors', 'book_id', 'author_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class,"book_id");
    }
}
