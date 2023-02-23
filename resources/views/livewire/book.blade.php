<div>
    <div class="col-md-8 mb-2">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('error') }}
            </div>
        @endif
        @if($addBook)
            @include('livewire.create')
        @endif
        @if($updateBook)
            @include('livewire.update')
        @endif
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                @if(!$addBook)
                    @can('add book')
                        <button wire:click="addBook()" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 my-2 px-4 border border-gray-400 rounded shadow">
                            Add New Book
                        </button>
                    @endcan
                @endif
                <livewire:book-table />
            </div>
        </div>
    </div>
 
</div>