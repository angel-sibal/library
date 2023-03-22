<?php

namespace App\Filament\Resources\BorrowedBookResource\Pages;

use App\Filament\Resources\BorrowedBookResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBorrowedBook extends EditRecord
{
    protected static string $resource = BorrowedBookResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
