<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use Filament\Resources\Pages\Page;

class AddBooks extends Page
{
    protected static string $resource = BookResource::class;

    protected static string $view = 'filament.resources.book-resource.pages.add-books';
}
