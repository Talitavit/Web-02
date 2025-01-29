<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Storage;


class BookController extends Controller
{
    public function createWithId()
    {
        return view('books.create-id');
    }

    public function storeWithId(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $book = Book::create($request->except('cover'));

        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public'); // Armazenando a imagem no diretório 'covers' dentro de 'public/storage'
    
            // Opcional: Você pode armazenar o caminho da capa em uma variável de sessão para ser acessada na próxima página
            session()->flash('cover_url', Storage::url($coverPath));
        }

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }
    public function createWithSelect()
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.create-select', compact('publishers', 'authors', 'categories'));
    }
    public function storeWithSelect(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $book = Book::create($request->except('cover'));

        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public'); // Armazenando a imagem no diretório 'images' dentro de 'public/storage'
    
            // Opcional: Você pode armazenar o caminho da capa em uma variável de sessão para ser acessada na próxima página
            session()->flash('cover_url', Storage::url($coverPath));
        }

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }
        public function edit(Book $book)
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.edit', compact('book', 'publishers', 'authors', 'categories'));
    }
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book->update($request->all());

        return redirect()->route('books.index')->with('success', 'Livro atualizado com sucesso.');
    }

      public function show($id)
{
    $book = Book::findOrFail($id);
    $users = User::all();  // Ou qualquer lógica para obter os usuários

    return view('books.show', compact('book', 'users'));  // Passando a variável $users
}
    public function index()
{
    // Carregar os livros com autores usando eager loading e paginação
    $books = Book::with('author')->paginate(20);

    return view('books.index', compact('books'));

}

    public function destroy (Book $book){
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livro excluído com sucesso.');
    }

}
