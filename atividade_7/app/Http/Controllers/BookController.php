<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Exibir livros com paginação
    public function index()
    {
        // Carregar os livros com autores usando eager loading e paginação
        $books = Book::with('author')->paginate(20);

        return view('books.index', compact('books'));
    }

    // Exibir detalhes de um livro
    public function show(Book $book)
    {
        $book->load(['author', 'publisher', 'category']);

        $users = User::all();

        return view('books.show', compact('book', 'users'));
    }

    public function edit(Book $book)
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.edit', compact('book', 'publishers', 'authors', 'categories'));
    }

    public function createWithId()
    { 
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.create-id', compact('publishers', 'authors', 'categories'));
    }

    public function createWithSelect()
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.create-select', compact('publishers', 'authors', 'categories'));
    }

    
    public function storeWithId(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

       
        $coverImagePath = $request->file('cover_image') 
            ? $request->file('cover_image')->store('cover_images', 'public') 
            : null;

       
        Book::create(array_merge(
            $request->all(),
            ['cover_image' => $coverImagePath]
        ));

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    
    public function storeWithSelect(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('cover_images', 'public'); 
        } else {
            $path = 'cover_images/default_cover.jpg'; 
        }

        
        Book::create([
            'title' => $request->input('title'),
            'author_id' => $request->input('author_id'),
            'category_id' => $request->input('category_id'),
            'publisher_id' => $request->input('publisher_id'),
            'cover_image' => $path, 
        ]);

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        
        if ($request->hasFile('cover_image')) {
          
            if ($book->cover_image && file_exists(storage_path('app/public/' . $book->cover_image))) {
                unlink(storage_path('app/public/' . $book->cover_image));
            }
            
            $path = $request->file('cover_image')->store('cover_images', 'public'); 
        } else {
            $path = $book->cover_image; 
        }

        $book->update([
            'title' => $request->input('title'),
            'author_id' => $request->input('author_id'),
            'category_id' => $request->input('category_id'),
            'publisher_id' => $request->input('publisher_id'),
            'cover_image' => $path, 
        ]);

        return redirect()->route('books.index')->with('success', 'Livro atualizado com sucesso.');
    }

    public function destroy(Book $book)
    {
        
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livro deletado com sucesso.');
    }
}

