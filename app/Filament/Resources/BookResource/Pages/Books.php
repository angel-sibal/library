<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Models\Book;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\BookResource;
use Filament\Pages\Actions\CreateAction;

class Books extends Page
{
    protected static string $resource = BookResource::class;

    protected static string $view = 'filament.resources.book-resource.pages.books';

    public $books;
 
    public function mount()
    {
        $this->books = Book::select('id', 'title', 'author', 'stocks', 'cover_image')->get();
    }
}
