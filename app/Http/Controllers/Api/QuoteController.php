<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuoteRequest;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'section' => ['nullable', Rule::in(array_keys(config('sections')))],
        ], [
            'section.in' => 'هذا القسم غير موجود.',
        ]);

        return Quote::with('user:id,name')
            ->withCount('lighters')
            ->when($request->query('section'), fn ($q, $section) => $q->inSection($section))
            ->search($request->query('q'))
            ->latest()
            ->orderBy('id')
            ->paginate(12);
    }

    public function show(Quote $quote)
    {
        return $quote->load('user:id,name')->loadCount('lighters');
    }

    public function store(QuoteRequest $request)
    {
        $quote = $request->user()->quotes()->create($request->validated());

        return response()->json($quote, 201);
    }

    public function update(QuoteRequest $request, Quote $quote)
    {
        abort_unless($quote->isOwnedBy($request->user()), 403, 'هذه الومضة ليست لك.');

        $quote->update($request->validated());

        return $quote;
    }

    public function destroy(Request $request, Quote $quote)
    {
        abort_unless($quote->isOwnedBy($request->user()), 403, 'هذه الومضة ليست لك.');

        $quote->delete();

        return response()->json(['message' => 'حُذفت الومضة.']);
    }

    public function light(Request $request, Quote $quote)
    {
        $changes = $request->user()->litQuotes()->toggle($quote->id);

        return response()->json([
            'lit' => count($changes['attached']) > 0,
            'count' => $quote->lighters()->count(),
        ]);
    }
}