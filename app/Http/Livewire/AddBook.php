<?php

namespace App\Http\Livewire;

use Filament\Forms;
use App\Models\Book;
use Livewire\Component;
use Filament\Forms\Components\Card;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class AddBook extends Component implements Forms\Contracts\HasForms 
{
    use Forms\Concerns\InteractsWithForms; 
    
    public Book $book;
 
    public $title = '';
    public $author = '';
    public $cover_image = '';
    public $stocks = 0;
 
    public function mount(): void
    {
        $this->form->fill();
    }
 
    protected function getFormSchema(): array 
    {
        return [
            Card::make()->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(125),
                TextInput::make('author')
                    ->required()
                    ->maxLength(125),
                FileUpload::make('cover_image')
                    ->image(),
                TextInput::make('stocks')
                    ->required()
                    ->numeric()
                    ->minValue(1),
            ])
        ];
    }
 
    public function submit(): void
    {
        Book::create($this->form->getState());
    }
 
    public function render(): View
    {
        return view('livewire.add-book');
    }
}
