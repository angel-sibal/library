<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Book as Books;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Book extends Component
{
    use WithFileUploads;

    public $books, $title, $author, $stocks, $cover_image_filepath, $bookId, $updateBook = false, $addBook = false;

    protected $listeners = ['deleteBook', 'editBook'];

    protected $rules = [
        'title' => 'required',
        'author' => 'required',
        'stocks' => 'required',
        'cover_image_filepath' => 'image|max:1024',
    ];

    public function resetFields(){
        $this->title = '';
        $this->author = '';
        $this->stocks = 0;
        $this->cover_image_filepath = '';
    }

    public function render()
    {
        $this->books = Books::select('id', 'title', 'author', 'stocks', 'cover_image_filepath')->get();
        return view('livewire.book');
    }

    public function addBook()
    {
        $this->resetFields();
        $this->addBook = true;
        $this->updateBook = false;
    }

    public function storeBook()
    {
        $this->validate();

        $filepath = null;

        if ($this->cover_image_filepath) {
            $filepath = $this->cover_image_filepath->storeAs('images', $this->cover_image_filepath->getClientOriginalName(), 'public');
        }

        try {
            Books::create([
                'title' => $this->title,
                'author' => $this->author,
                'stocks' => $this->stocks,
                'cover_image_filepath' => $filepath,
            ]);
            session()->flash('success', 'Book Created Successfully!!');
            $this->resetFields();
            $this->addBook = false;
        } catch (\Exception $ex) {
            session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function editBook($id){
        try {
            $book = Books::findOrFail($id);

            if (!$book) {
                session()->flash('error', 'Book not found');
            } else {
                $this->title = $book->title;
                $this->author = $book->author;
                $this->stocks = $book->stocks;
                $this->bookId = $book->id;
                $this->updateBook = true;
                $this->addBook = false;
            }
        } catch (\Exception $ex) {
            session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function updateBook()
    {
        $this->validate([
            'title' => ['required', Rule::unique('books')->ignore($this->bookId)]
        ]);
      
        try {
            $deleteExistingCoverImage = false;

            $formData = [
                'title' => $this->title,
                'author' => $this->author,
                'stocks' => $this->stocks,
            ];

            if ($this->cover_image_filepath) {
                $deleteExistingCoverImage = true;
                $filepath = $this->cover_image_filepath->storeAs('images', $this->cover_image_filepath->getClientOriginalName(), 'public');
                $formData['cover_image_filepath'] = $filepath;
            }

            $book = Books::find($this->bookId);

            if ($deleteExistingCoverImage) {
                if(Storage::disk('public')->exists($book->cover_image_filepath)){
                    Storage::disk('public')->delete($book->cover_image_filepath);
                }
            }

            $book->update($formData);

            session()->flash('success','Book Updated Successfully!!');
            $this->resetFields();
            $this->updateBook = false;
        } catch (\Exception $ex) {
            dd($ex);
            session()->flash('success','Something goes wrong!!');
        }
    }

    public function cancelBook()
    {
        $this->addBook = false;
        $this->updateBook = false;
        $this->resetFields();
    }

    public function deleteBook($id)
    {
        try {
            $book = Books::find($id);

            if (Storage::disk('public')->exists($book->cover_image_filepath)) {
                Storage::disk('public')->delete($book->cover_image_filepath);
            }

            $book->delete();

            session()->flash('success',"Book Deleted Successfully!!");
        } catch(\Exception $ex) {
            session()->flash('error',"Something goes wrong!!");
        }
    }
}
