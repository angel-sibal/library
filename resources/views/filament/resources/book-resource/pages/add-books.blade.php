<x-app-layout>
    <div class="font-sans antialiased">
        <div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0">
            <div class="mb-4">
                <h1 class="text-3xl font-bold">
                    Add Book
                </h1>
            </div>
            <div class="w-full px-16 py-20 mt-6 overflow-hidden bg-white rounded-lg lg:max-w-4xl">
                <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label class="block">
                            <span class="text-gray-700 @error('title') text-red-500 @enderror">Title</span>
                            <input type="text" name="title"
                                class="block @error('title') border-red-500 bg-red-100 text-red-900 @enderror w-full mt-1 rounded-md"
                                placeholder="" value="{{ old('title') }}" />
                        </label>
                        @error('title')
                            <div class="flex items-center text-sm text-red-600">
                                {{ $message }}

                            </div>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block">
                            <span class="text-gray-700 @error('author') text-red-500 @enderror">Author</span>
                            <input type="text" name="author"
                                class="block @error('author') border-red-500 bg-red-100 text-red-900 @enderror w-full mt-1 rounded-md"
                                placeholder="" value="{{ old('author') }}" />
                        </label>
                        @error('author')
                            <div class="flex items-center text-sm text-red-600">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block">
                            <span class="text-gray-700 @error('stocks') text-red-500 @enderror">Stocks</span>
                            <input type="number"
                                class="block @error('stocks') border-red-500  bg-red-100 text-red-900 @enderror w-full mt-1 rounded-md"
                                name="stocks" value="{{ old('stocks') }}" />
                        </label>
                        @error('stocks')
                            <div class="flex items-center text-sm text-red-600">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block">
                            <span class="sr-only">Choose File</span>
                            <input type="file" name="cover_image"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        </label>
                        @error('cover_image')
                            <div class="flex items-center text-sm text-red-600">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="text-white bg-blue-600  rounded text-sm px-5 py-2.5">Submit</button>
                    <button type="button" class="text-black bg-gray-200 rounded text-sm px-5 py-2.5" onclick="window.location='{{ route('filament.resources.books.index') }}'">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
