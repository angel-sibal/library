<?php

namespace App\Filament\Resources;

use App\Models\Book;
use App\Models\BorrowedBook;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\BookResource\Pages;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function canCreate(): bool
    {
       return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('title')->limit(50)->sortable()->searchable(),
                TextColumn::make('author')->limit(50)->sortable()->searchable(),
                ImageColumn::make('cover_image_filepath')->label('Cover Image')->height(100),
                TextColumn::make('stocks')->sortable(),
                TextColumn::make('created_at')->sortable(),
                TextColumn::make('updated_at')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                BulkAction::make('borrow')
                ->action(function (Collection $records, array $data): void {
                    foreach ($records as $record) {
                        BorrowedBook::create([
                            'book_id' => $record->id,
                            'user_id' => auth()->user()->id,
                        ]);
                    }

                    Notification::make() 
                    ->title('Borrowed book successfully')
                    ->success()
                    ->send(); 
                })
                ->label('Borrow Book')
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
            'index' => Pages\ListBooks::route('/'),
        ];
    }
}
