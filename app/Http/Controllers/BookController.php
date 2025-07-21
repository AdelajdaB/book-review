<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index() {

        $books = Book::select('id', 'title', 'author', 'description', 'rating', 'cover_image')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $books->getCollection()->transform(function ($book) {
            $book->append(['cover_url', 'has_cover']);
            return $book;
        });

        return Inertia::render('Books/Index', [
            'books' => $books,
        ]);
    }
}
