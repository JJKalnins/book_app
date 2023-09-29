<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    use HasFactory;

    public function book(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_to_authors', 'author_id', 'book_id');
    }

}
