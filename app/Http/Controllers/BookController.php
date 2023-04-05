<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        request()->validate([
            'cover_image' => 'image|mimes:jpeg,png|max:1024',
        ]);

        $validated = $request->validated();
        $filename = '';

        if ($request->hasFile('cover_image')) {
            $filename = time() . '.' . $request->cover_image->getClientOriginalExtension();
            $request->cover_image->storeAs('public/images', $filename);
            $validated['cover_image'] = $filename;
        }

        Book::create($validated);

        return redirect()
            ->route('filament.resources.books.index')
            ->with('success', 'Book created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, Book $book)
    {
        request()->validate([
            'cover_image' => 'image|mimes:jpeg,png|max:1024',
        ]);

        $validated = $request->validated();
        $filename = '';

        if ($request->hasFile('cover_image')) {
            $filename = time() . '.' . $request->cover_image->getClientOriginalExtension();
            $request->cover_image->storeAs('public/images', $filename);
            if ($book->cover_image) {
                Storage::delete('public/images/' . $book->cover_image);
            }
        } else {
            $filename = $book->cover_image;
        }

        $validated['cover_image'] = $filename;

        $book->update($validated);

        return redirect()
            ->route('filament.resources.books.index')
            ->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!is_null($request->ids)) {
            foreach ($request->ids as $id) {
                $book = Book::find($id);
                
                if (Storage::disk('public')->exists($book->cover_image)) {
                    Storage::disk('public')->delete($book->cover_image);
                }
        
                $book->delete();
            }

            $request->session()->flash('success', 'Book deleted successfully.');
            return response()->json(['status' => 'success']);
        }
    }
}
