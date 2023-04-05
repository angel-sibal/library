<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BorrowedBook;
use App\Models\Book as Books;
use Illuminate\Contracts\View\View;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Concerns\InteractsWithTable;

class Book extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery(): Builder 
    {
        return Books::query();
    } 
    
    public function render(): View
    {
        return view('livewire.book');
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('title')
                ->limit(50)
                ->sortable()
                ->searchable(),
            TextColumn::make('author')
                ->limit(50)
                ->sortable()
                ->searchable(),
            ImageColumn::make('cover_image')
                ->label('Cover Image')
                ->height(100),
            TextColumn::make('stocks')
                ->sortable(),
            TextColumn::make('created_at')
                ->sortable(),
            TextColumn::make('updated_at')
                ->sortable(),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            BulkAction::make('borrow')
                ->visible(fn (Books $record): bool => auth()->user()->can('borrow', $record))
                ->action(function (Collection $records, array $data): void {
                    foreach ($records as $record) {
                        BorrowedBook::firstOrCreate([
                            'book_id' => $record->id,
                            'user_id' => auth()->user()->id,
                        ]);
                    }

                    Notification::make() 
                        ->title('Borrowed book successfully')
                        ->success()
                        ->send(); 
                })
                ->label('Borrow Books')
                ->deselectRecordsAfterCompletion(),
            DeleteBulkAction::make()
                ->visible(fn (Books $record): bool => auth()->user()->can('delete', $record)),
        ];
    } 

    protected function getTableActions(): array
    {
        return [
            EditAction::make('edit')
                ->visible(auth()->user()->can('edit'))
                ->url(fn (Books $record): string => route('filament.resources.books.edit', $record)),
            DeleteAction::make()
                ->visible(auth()->user()->can('delete')),
        ];
    }
}
