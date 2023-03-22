<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Models\BorrowedBook;
use App\Models\Book as Books;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Resources\BookResource\Pages\ListBooks;
use App\Filament\Resources\BorrowedBookResource\Pages\ListBorrowedBooks;

class BookStockTest extends TestCase
{
    use RefreshDatabase;

    protected $user, $admin, $super_admin;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupPermissions();

        $this->user = User::factory()->create();
        $this->user->assignRole('user');

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->super_admin = User::factory()->create();
        $this->super_admin->assignRole('super-admin');

        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }

    protected function setupPermissions()
    {
        Permission::findOrCreate('view book');
        Permission::findOrCreate('add book');
        Permission::findOrCreate('edit book');
        Permission::findOrCreate('delete book');
        Permission::findOrCreate('borrow book');
        Permission::findOrCreate('approve book request');
        Permission::findOrCreate('deny book request');

        Role::findOrCreate('super-admin');

        Role::findOrCreate('admin')
            ->givePermissionTo(['view book', 'approve book request', 'deny book request']);

        Role::findOrCreate('user')
            ->givePermissionTo(['view book', 'borrow book']);

        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }

    public function test_number_of_stocks_should_be_updated_when_a_book_is_borrowed()
    {
        $this->actingAs($this->user);
 
        $books = Books::factory()->count(1)->create([
            'stocks' => 5
        ]);
 
        Livewire::test(ListBooks::class)->callTableBulkAction('borrow', $books);
    
        foreach ($books as $book) {
            $this->assertDatabaseCount('borrowed_books', 1);
            $this->assertEquals(4, $book->stocks);
        }
    }

    public function test_number_of_stocks_should_be_updated_when_a_book_is_returned()
    {
        $this->actingAs($this->user);
 
        $books = Books::factory()->count(1)->create([
            'stocks' => 5
        ]);
 
        Livewire::test(ListBooks::class)->callTableBulkAction('borrow', $books);
    
        foreach ($books as $book) {
            $this->assertDatabaseCount('borrowed_books', 1);
            $this->assertEquals(4, $book->stocks);
        }
        
        $borrowedBooks = BorrowedBook::get();

        Livewire::test(ListBorrowedBooks::class)->callTableBulkAction('return', $borrowedBooks);
        foreach ($books as $book) {
            $book->refresh();
            $this->assertEquals(5, $book->stocks);
        }
    }
}
