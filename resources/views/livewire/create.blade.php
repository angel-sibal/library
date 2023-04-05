<div class="card">
    <div class="card-body">
        <h1>Add Book Form</h1>
        <form class="w-full max-w-sm">
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="title">
                    Title
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('title') is-invalid @enderror" id="title" type="text" wire:model="title">
                    @error('title')
                        <span class="text-danger">{{ $title }}</span>
                    @enderror
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="password">
                        Author
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('author') is-invalid @enderror" id="author" type="text" wire:model="author">
                    @error('author')
                        <span class="text-danger">{{ $author }}</span>
                    @enderror
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="stocks">
                        No. of Stocks
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('stocks') is-invalid @enderror" id="stocks" type="number" min="0" wire:model="stocks">
                    @error('stocks')
                        <span class="text-danger">{{ $stocks }}</span>
                    @enderror
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="cover_image">
                        Cover Image
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 @error('cover_image') is-invalid @enderror" id="cover_image" type="file" wire:model="cover_image">
                    @error('cover_image')
                        <span class="text-danger">{{ $cover_image }}</span>
                    @enderror
                </div>
                <div wire:loading wire:target="cover_image">Uploading Cover Image...</div>
            </div>
            <div class="md:flex md:items-center">
                <div class="md:w-1/3"></div>
                <div class="md:w-2/3">
                    <button wire:click.prevent="storeBook()" wire:loading.attr="disabled" wire:target="cover_image" class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">
                        Save
                    </button>
                    <button wire:click.prevent="cancelBook()" class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">
                        Cancel
                    </button>
                </div>
            </div>
          </form>
    </div>
</div>