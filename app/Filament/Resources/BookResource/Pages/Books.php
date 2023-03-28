<?php

namespace App\Filament\Resources\BookResource\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Resources\BookResource;
use Filament\Pages\Actions\CreateAction;

class Books extends Page
{
    protected static string $resource = BookResource::class;

    protected static string $view = 'filament.resources.book-resource.pages.books';

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Book')
                ->url(BookResource::getUrl('create')),
        ];
    }
}
