<?php

namespace App\Filament\Resources;

use App\Models\BorrowedBook;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\BorrowedBookResource\Pages;

class BorrowedBookResource extends Resource
{
    protected static ?string $model = BorrowedBook::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
       return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('book.title')
                    ->label('Title')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('book.author')
                    ->label('Author')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),
                ImageColumn::make('book.cover_image')
                    ->label('Cover Image')
                    ->height(100),
                TextColumn::make('created_at')
                    ->label('Borrowed at')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                BulkAction::make('return')
                ->action(function (Collection $records, array $data): void {
                    foreach ($records as $record) {
                        $record->delete();
                    }

                    Notification::make() 
                        ->title('Returned book successfully')
                        ->success()
                        ->send(); 
                })
                ->label('Return Books')
                ->deselectRecordsAfterCompletion()
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBorrowedBooks::route('/'),
        ];
    }
}
