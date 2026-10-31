<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    public function index(Request $request, ?string $section = null)
    {
        $user = Auth::user();
        $show = $request->query('show', 'all');

        $quotes = Quote::with('user')
            ->withCount('lighters')
            ->when($user, fn ($q) => $q->withExists(['lighters as lit' => fn ($q) => $q->where('lights.user_id', $user->id)]))
            ->when($section, fn ($q) => $q->inSection($section))
            ->search($request->query('q'))
            ->when(in_array($show, ['ar', 'en']), fn ($q) => $q->where('lang', $show))
            ->when($show === 'mine' && $user, fn ($q) => $q->whereBelongsTo($user))
            ->when($show === 'lit' && $user, fn ($q) => $q->whereHas('lighters', fn ($q) => $q->where('lights.user_id', $user->id)))
            ->when($show === 'top', fn ($q) => $q->orderByDesc('lighters_count'))
            ->latest()
            ->orderBy('id')
            ->simplePaginate(12)
            ->withQueryString();

        $counts = $section ? [] : collect(config('sections'))->map(fn ($s, $slug) => Quote::inSection($slug)->count());
        $day = $section ? null : $this->quoteOfTheDay();

        $myBooks = $user ? $user->books()->orderBy('title')->get() : collect();

        return view('welcome', compact('quotes', 'section', 'show', 'counts', 'day', 'myBooks'));
    }

    public function sayer(string $name)
    {
        $user = Auth::user();

        $quotes = Quote::with('user')
            ->withCount('lighters')
            ->when($user, fn ($q) => $q->withExists(['lighters as lit' => fn ($q) => $q->where('lights.user_id', $user->id)]))
            ->where('author', $name)
            ->latest()
            ->orderBy('id')
            ->get();

        abort_if($quotes->isEmpty(), 404);

        $bio = config('sayers')[$name] ?? null;
        $myBooks = $user ? $user->books()->orderBy('title')->get() : collect();

        return view('sayer', compact('name', 'quotes', 'bio', 'myBooks'));
    }

    public function stage(string $name)
    {
        $quotes = Quote::where('author', $name)->oldest()->orderBy('id')->get();

        abort_if($quotes->isEmpty(), 404);

        $bio = config('sayers')[$name] ?? null;

        return view('stage', compact('name', 'quotes', 'bio'));
    }

    public function show(Quote $quote)
    {
        $quote->load('user');

        return view('quote', compact('quote'));
    }

    public function store(QuoteRequest $request)
    {
        Auth::user()->quotes()->create($request->validated());

        return $this->backToFeed('أُضيئت ومضتك على المسرح.');
    }

    public function update(QuoteRequest $request, Quote $quote)
    {
        abort_if($quote->user_id != Auth::id(), 403, 'هذه الومضة ليست لك.');

        $quote->update($request->validated());

        return $this->backToFeed('عُدّلت ومضتك.');
    }

    public function destroy(Quote $quote)
    {
        abort_if($quote->user_id != Auth::id(), 403, 'هذه الومضة ليست لك.');

        $quote->delete();

        return $this->backToFeed('حُذفت الومضة.');
    }

    public function light(Request $request, Quote $quote)
    {
        abort_if(Auth::guest(), 403);

        $changes = Auth::user()->litQuotes()->toggle($quote->id);
        $lit = count($changes['attached']) > 0;

        if ($request->expectsJson()) {
            return response()->json(['lit' => $lit, 'count' => $quote->lighters()->count()]);
        }

        return redirect()->back();
    }

    private function backToFeed(string $status)
    {
        return redirect()->to(url()->previous() . '#wm-feed-top')->with('status', $status);
    }

    private function quoteOfTheDay(): ?Quote
    {
        $top = Quote::with('user')
            ->withCount(['lighters as recent' => fn ($q) => $q->where('lights.created_at', '>=', now()->subDay())])
            ->orderByDesc('recent')
            ->orderBy('id')
            ->first();

        if ($top && $top->recent > 0) {
            return $top;
        }

        $total = Quote::count();

        return $total ? Quote::with('user')->orderBy('id')->skip(now('Asia/Riyadh')->dayOfYear % $total)->first() : null;
    }
}
