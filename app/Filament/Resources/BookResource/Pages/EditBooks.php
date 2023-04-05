<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use App\Models\Book;
use Filament\Resources\Pages\Page;

class EditBooks extends Page
{
    protected static string $resource = BookResource::class;

    protected static string $view = 'filament.resources.book-resource.pages.edit-books';

    public $book;
 
    public function mount($bookId)
    {
        $this->book = Book::find($bookId);
    }
}
