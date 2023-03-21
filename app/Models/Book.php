<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'author', 'stocks', 'cover_image_filepath'
    ];
    
    public $timestamps = true;

    public function borrowedBooks(): HasMany
    {
        return $this->hasMany(BorrowedBook::class);
    }
}
