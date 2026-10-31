<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShelfController extends Controller
{
    public function index(Request $request)
    {
        $books = Auth::user()->books()->withCount('quotes')->oldest()->get();

        $current = $books->firstWhere('id', $request->integer('book')) ?? $books->first();

        if ($current) {
            $current->load(['quotes' => fn ($q) => $q->with('user')
                ->withCount('lighters')
                ->withExists(['lighters as lit' => fn ($q) => $q->where('lights.user_id', Auth::id())])
                ->latest('book_quote.created_at')]);
        }

        return view('shelf', compact('books', 'current'));
    }

    public function store(BookRequest $request)
    {
        $book = Auth::user()->books()->create($request->validated());

        return redirect()->route('shelf', ['book' => $book->id])->with('status', 'أُضيف «' . $book->title . '» إلى رفّك.');
    }

    public function destroy(Book $book)
    {
        abort_if($book->user_id != Auth::id(), 403, 'هذا الكتاب ليس في رفّك.');

        $book->delete();

        return redirect()->route('shelf')->with('status', 'أُزيل الكتاب من رفّك.');
    }

    public function save(Request $request, Quote $quote)
    {
        $book = Auth::user()->books()->findOrFail($request->input('book_id'));

        $book->quotes()->syncWithoutDetaching($quote->id);

        return redirect()->to(url()->previous() . '#wm-feed-top')->with('status', 'حُفظت الومضة في «' . $book->title . '».');
    }

    public function remove(Book $book, Quote $quote)
    {
        abort_if($book->user_id != Auth::id(), 403, 'هذا الكتاب ليس في رفّك.');

        $book->quotes()->detach($quote->id);

        return redirect()->back()->with('status', 'أُزيلت الومضة من الكتاب.');
    }
}