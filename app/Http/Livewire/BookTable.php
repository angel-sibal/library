<?php

namespace App\Http\Livewire;

use App\Models\Book;
use Illuminate\Support\Facades\URL;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;

class BookTable extends DataTableComponent
{
    protected $model = Book::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        $columns = [
            Column::make("Id", "id"),
            Column::make("Title", "title")
                ->sortable()
                ->searchable(),
            Column::make("Author", "author")
                ->sortable()
                ->searchable(),
            Column::make("Image", "cover_image")
                ->hideIf(true),
            ImageColumn::make('Cover Image')
                ->location(
                    fn($row) => ($row->cover_image) ? URL::asset('/storage/' . $row->cover_image) : URL::asset('/storage/images/book-default-cover.jpg')
                )
                ->attributes(fn($row) => [
                    'width' => '100px',
                ]),
            Column::make("Stocks", "stocks")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];

        if (auth()->user()->roles->pluck("name")->first() === 'Super Admin') {
            array_push($columns,
                Column::make('Actions')
                ->label(
                    function ($row) {
                        $delete = '<button wire:click="$emit(' . "'deleteBook'" . ', ' . $row->id . ')" class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" style="margin-left:2px;">Delete</button>';
                        $edit = '<button wire:click="$emit(' . "'editBook'" . ', ' . $row->id . ')" class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">Edit</button>';
                        return $edit . $delete;
                    }
                )->html()
            );
        }
        
        return $columns;
    }
}
