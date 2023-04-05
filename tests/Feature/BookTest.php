<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\Book;
use App\Models\BorrowedBook;
use App\Models\Book as Books;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Filament\Resources\BookResource\Pages\ListBooks;
use App\Filament\Resources\BorrowedBookResource\Pages\ListBorrowedBooks;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    protected $user, $admin, $super_admin;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupPermissions();

        $this->user = User::factory()->create();
        $this->user->assignRole('User');

        $this->admin = User::factory()->create();
        $this->admin->assignRole('Admin');

        $this->super_admin = User::factory()->create();
        $this->super_admin->assignRole('Super Admin');

        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }

    protected function setupPermissions()
    {
        Permission::findOrCreate('View Books');
        Permission::findOrCreate('Create Books');
        Permission::findOrCreate('Edit Books');
        Permission::findOrCreate('Delete Books');
        Permission::findOrCreate('Borrow Books');
        Permission::findOrCreate('Approve Book Requests');
        Permission::findOrCreate('Deny Book Requests');

        Role::findOrCreate('Super Admin');

        Role::findOrCreate('Admin')
            ->givePermissionTo(['View Books', 'Approve Book Requests', 'Deny Book Requests']);

        Role::findOrCreate('User')
            ->givePermissionTo(['View Books', 'Borrow Books']);

        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }

    public function test_users_can_see_books_table_in_the_dashboard()
    {
        $this->actingAs($this->user)
            ->get('/dashboard')
            ->assertSeeLivewire('book-table');
    }

    public function test_users_that_are_not_super_admins_cannot_see_add_book_button_in_the_dashboard()
    {
        $this->actingAs($this->admin)
            ->get('/dashboard')
            ->assertDontSee('Add New Book');
    }

    public function test_super_admins_can_see_add_book_form_after_clicking_add_book_button()
    {
        Livewire::actingAs($this->super_admin);
 
        Livewire::test(Book::class)
            ->call('addBook')
            ->assertSee('Add Book Form');
    }

    public function test_super_admins_can_add_a_book()
    {
        $this->actingAs($this->super_admin);
        Storage::fake('public');
 
        Livewire::test(Book::class)
            ->set('title', 'First Book Title')
            ->set('author', 'First Book Author')
            ->set('stocks', 5)
            ->set('cover_image', UploadedFile::fake()->image('test-image.png'))
            ->call('storeBook');
 
        Storage::disk('public')->assertExists('images/test-image.png');
        $this->assertTrue(Books::whereTitle('First Book Title')->exists());
    }

    public function test_users_that_are_not_super_admins_cannot_see_actions_column_when_books_table_is_not_empty()
    {
        $this->actingAs($this->admin);

        Books::factory()->create([
            'title' => 'First Book Title',
        ]);

        $this->assertTrue(Books::whereTitle('First Book Title')->exists());
        $this->get('/dashboard')
        ->assertDontSee('Edit')
        ->assertDontSee('Delete');
    }

    public function test_super_admins_can_see_edit_book_form_after_clicking_edit_book_button()
    {
        $this->actingAs($this->admin);

        $book = Books::factory()->create([
            'title' => 'First Book Title',
        ]);

        $this->assertTrue(Books::whereTitle('First Book Title')->exists());
        $this->get('/dashboard');
 
        Livewire::test(Book::class)
            ->call('editBook', $book->id)
            ->assertSee('Edit Book Form');
    }

    public function test_super_admins_can_edit_a_book()
    {
        $this->actingAs($this->super_admin);
        Storage::fake('public');

        $book = Books::factory()->create([
            'title' => 'First Book Title',
            'author' => 'First Book Author',
            'stocks' => 5,
            'cover_image' => 'images/test-image.png',
        ]);

        $this->assertTrue(Books::whereTitle('First Book Title')->exists());
 
        Livewire::test(Book::class)
            ->set('bookId', $book->id)
            ->set('title', 'Updated Book Title')
            ->set('author', 'Updated Book Author')
            ->set('stocks', 6)
            ->set('cover_image', UploadedFile::fake()->image('test-image-2.png'))
            ->call('updateBook');
 
        Storage::disk('public')->assertExists('images/test-image-2.png');
        $this->assertTrue(Books::whereTitle('Updated Book Title')->exists());
    }

    public function test_add_book_form_will_be_hidden_and_input_fields_will_be_cleared_upon_clicking_cancel_button()
    {
        Livewire::actingAs($this->super_admin);

        Livewire::test(Book::class)
            ->call('addBook')
            ->assertSee('Add Book Form')
            ->call('cancelBook')
            ->assertDontSee('Add Book Form')
            ->assertSet('addBook', false)
            ->assertSet('title', '')
            ->assertSet('author', '')
            ->assertSet('stocks', 0)
            ->assertSet('cover_image', '');
    }

    public function test_edit_book_form_will_be_hidden_and_input_fields_will_be_cleared_upon_clicking_cancel_button()
    {
        $this->actingAs($this->super_admin);

        $book = Books::factory()->create([
            'title' => 'First Book Title',
        ]);

        $this->assertTrue(Books::whereTitle('First Book Title')->exists());

        Livewire::test(Book::class)
            ->call('editBook', $book->id)
            ->assertSee('Edit Book Form')
            ->call('cancelBook')
            ->assertDontSee('Edit Book Form')
            ->assertSet('updateBook', false)
            ->assertSet('title', '')
            ->assertSet('author', '')
            ->assertSet('stocks', 0)
            ->assertSet('cover_image', '');
    }

    public function test_super_admins_can_delete_a_book()
    {
        $this->actingAs($this->super_admin);

        $book = Books::factory()->create([
            'title' => 'First Book Title',
            'author' => 'First Book Author',
            'stocks' => 5,
            'cover_image' => 'images/test-image.png',
        ]);

        $this->assertTrue(Books::whereTitle('First Book Title')->exists());
 
        Livewire::test(Book::class)->call('deleteBook', $book->id);

        $this->assertSoftDeleted('books', ['id' => $book->id]);
    }

    public function test_admin_users_cannot_access_admin_panel()
    {
        $response = $this->actingAs($this->super_admin)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_users_can_see_books_in_admin_panel()
    {
        $response = $this->actingAs($this->user)->get('/admin');
        $response->assertStatus(200)->assertSee('Books');
    }

    public function test_users_can_see_book_list_in_admin_panel()
    {
        $this->actingAs($this->user);

        $books = Books::factory()->count(10)->create();

        Livewire::test(ListBooks::class)->assertCanSeeTableRecords($books);
    }

    public function test_users_can_see_title_column_in_book_list()
    {
        $this->actingAs($this->user);

        Books::factory()->count(10)->create();
 
        Livewire::test(ListBooks::class)->assertCanRenderTableColumn('title');
    }

    public function test_users_can_borrow_a_book()
    {
        $this->actingAs($this->user);
 
        $books = Books::factory()->count(1)->create();
 
        Livewire::test(ListBooks::class)->callTableBulkAction('borrow', $books);
    
        foreach ($books as $book) {
            $this->assertDatabaseHas('borrowed_books', [
                'book_id' => $book->id,
                'user_id' => $this->user->id
            ]);
        }
    }

    public function test_users_can_see_borrowed_books_in_admin_panel()
    {
        $this->actingAs($this->user);

        $books = Books::factory()->count(1)->create();
 
        Livewire::test(ListBooks::class)->callTableBulkAction('borrow', $books);

        $borrowedBooks = BorrowedBook::where('user_id', $this->user->id)->get();

        Livewire::test(ListBorrowedBooks::class)->assertCanSeeTableRecords($borrowedBooks);
    }

    public function test_users_cannot_borrow_the_same_book_twice()
    {
        $this->actingAs($this->user);
 
        $books = Books::factory()->count(1)->create();
 
        Livewire::test(ListBooks::class)->callTableBulkAction('borrow', $books);
    
        foreach ($books as $book) {
            $this->assertDatabaseHas('borrowed_books', [
                'book_id' => $book->id,
                'user_id' => $this->user->id
            ]);
        }

        Livewire::test(ListBooks::class)->callTableBulkAction('borrow', $books);

        $this->assertDatabaseCount('borrowed_books', 1);
    }

    public function test_users_can_return_a_borrowed_book()
    {
        $this->actingAs($this->user);
 
        $books = Books::factory()->count(1)->create();
 
        Livewire::test(ListBooks::class)->callTableBulkAction('borrow', $books);
    
        $this->assertDatabaseCount('borrowed_books', 1);
        
        $borrowedBooks = BorrowedBook::get();

        Livewire::test(ListBorrowedBooks::class)->callTableBulkAction('return', $borrowedBooks);

        foreach ($borrowedBooks as $borrowedBook) {
            $this->assertSoftDeleted('borrowed_books', ['id' => $borrowedBook->id]);
        }
    }
}
