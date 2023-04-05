<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'title', 'author', 'stocks', 'cover_image'
    ];
    
    public $timestamps = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function borrowedBooks(): HasMany
    {
        return $this->hasMany(BorrowedBook::class);
    }

    protected function stocks(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value - count($this->borrowedBooks),
        );
    }
}
