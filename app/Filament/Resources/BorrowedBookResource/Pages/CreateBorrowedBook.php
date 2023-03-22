<?php

namespace App\Filament\Resources\BorrowedBookResource\Pages;

use App\Filament\Resources\BorrowedBookResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBorrowedBook extends CreateRecord
{
    protected static string $resource = BorrowedBookResource::class;
}
