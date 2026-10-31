<?php

namespace App\Http\Controllers;

use App\Http\Requests\GiftRequest;
use App\Models\Gift;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;

class GiftController extends Controller
{
    public function index()
    {
        $gifts = Auth::user()->gifts()->with('quote')->latest()->get();

        return view('gifts', compact('gifts'));
    }

    public function store(GiftRequest $request, Quote $quote)
    {
        $gift = Auth::user()->gifts()->create([...$request->validated(), 'quote_id' => $quote->id]);

        return redirect()->route('gifts.show', $gift);
    }

    public function show(Gift $gift)
    {
        $isSender = Auth::id() === $gift->user_id;

        if (! $isSender && ! $gift->opened_at) {
            $gift->opened_at = now();
            $gift->save();
        }

        $gift->load('quote.user');

        return view('gift', compact('gift', 'isSender'));
    }
}