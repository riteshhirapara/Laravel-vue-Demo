<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // all books
    public function index(Request $request)
    {
        $per_page = $request->input('listnum', 10);
        $search = $request->input('skeyword', '');
        $books = Book::query();
        $books->when($search, function ($query) use ($search) {
            $query->where('title', 'like', "%$search%")
                ->orWhere('author', 'like', "%$search%")
                ->orWhere('isbn', 'like', "%$search%")
                ->orWhere('published', 'like', "%$search%")
                ->orWhere('publisher', 'like', "%$search%")
                ->orWhere('genre', 'like', "%$search%");
        });;
        $results['total'] = $books->count();
        $results['data'] = $books ->paginate($per_page)->items();
    
        return response()->json($results);
    }

    // add book
    public function add(Request $request)
    {
        $book = new Book([
            'title' => $request->title,
            'author' => $request->author,
            'genre' => $request->genre,
            'isbn' => $request->isbn,
            'image' => 'N/A',
            'published' => $request->published,
            'publisher' => $request->publisher,
            'description' => $request->description,
        ]);
        $book->save();

        return response()->json('The book successfully added');
    }

    // edit book
    public function edit($id)
    {
        $book = Book::find($id);
        return response()->json($book);
    }

    // update book
    public function update($id, Request $request)
    {
        $book = Book::find($id);
        $book->update($request->all());

        return response()->json('The book successfully updated');
    }

    // delete book
    public function delete($id)
    {
        $book = Book::find($id);
        $book->delete();

        return response()->json('The book successfully deleted');
    }
}
