@extends('Layout.app')

@section('title', 'رفّي · ومضة')

@section('content')

<style>
    .wm-addbook {
        display: grid;
        grid-template-columns: 2fr 1.4fr auto;
        gap: 16px;
        align-items: start;
        margin: 44px 0 10px;
    }

    .wm-addbook .wm-btn { margin-top: 25px; padding: 13px 22px; }

    .wm-shelf { margin-top: 50px; padding-top: 40px; background: radial-gradient(ellipse 45% 100% at 50% 0, rgba(194, 160, 104, .13), transparent); }

    .wm-books {
        display: flex;
        align-items: flex-end;
        gap: 6px;
        min-height: 250px;
        padding: 20px 24px 0;
        overflow-x: auto;
    }

    .wm-spine {
        position: relative;
        flex: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        width: 52px;
        padding: 18px 0 12px;
        border-radius: 3px 3px 0 0;
        color: #f3ecda;
        box-shadow: inset -7px 0 12px rgba(0, 0, 0, .4), inset 2px 0 0 rgba(255, 255, 255, .07);
        transition: transform .3s;
    }

    .wm-spine::before, .wm-spine::after {
        content: "";
        position: absolute;
        left: 8px;
        right: 8px;
        height: 1px;
        background: rgba(194, 160, 104, .7);
    }

    .wm-spine::before { top: 9px; }

    .wm-spine::after { bottom: 32px; }

    .wm-spine span {
        max-height: calc(100% - 40px);
        overflow: hidden;
        writing-mode: vertical-rl;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-family: 'Amiri', serif;
        font-size: 1rem;
    }

    .wm-spine small { font-size: .7rem; color: rgba(243, 236, 218, .6); }

    .wm-spine:hover { transform: translateY(-8px); }

    .wm-spine.on { transform: translateY(-18px); outline: 1px solid var(--gold); }

    .wm-spine.c0 { background: #5c1a20; }

    .wm-spine.c1 { background: #1f3a2e; }

    .wm-spine.c2 { background: #1d2a44; }

    .wm-spine.c3 { background: #6b4a1f; }

    .wm-spine.c4 { background: #3b2618; }

    .wm-spine.c5 { background: #2b2620; }

    .wm-plank {
        height: 16px;
        border-radius: 2px;
        background: linear-gradient(#5a3b22, #2e1d10);
        box-shadow: 0 14px 26px rgba(0, 0, 0, .6);
    }

    .wm-bookhead h2 {
        margin: 0;
        font-family: 'Amiri', serif;
        font-size: 2.2rem;
        font-weight: 400;
    }

    .wm-bookhead p { margin: 4px 0 0; font-size: .86rem; color: var(--mute); }

    @media (max-width: 700px) {
        .wm-addbook { grid-template-columns: 1fr; }

        .wm-addbook .wm-btn { margin-top: 0; }
    }
</style>

<div class="wm-page" dir="rtl">
    <div class="wm-wrap">
        <section class="wm-mast">
            <em>My Shelf</em>
            <h1>رفّي</h1>
            <p>الكتب اللي قرأتها، وتحت كل كتاب الومضات اللي علقت معك منه.</p>
        </section>

        <form class="wm-addbook" method="POST" action="{{ route('shelf.store') }}">
            @csrf
            <div class="wm-field">
                <label for="title">عنوان الكتاب</label>
                <input class="wm-input" id="title" name="title" minlength="2" maxlength="100" required value="{{ old('title') }}">
                <p class="wm-hint">من 2 إلى 100 حرف</p>
                @error('title', 'book') <p class="wm-err">{{ $message }}</p> @enderror
            </div>
            <div class="wm-field">
                <label for="book-author">الكاتب (اختياري)</label>
                <input class="wm-input" id="book-author" name="author" maxlength="60" value="{{ old('author') }}">
                <p class="wm-hint">حتى 60 حرفاً</p>
                @error('author', 'book') <p class="wm-err">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="wm-btn gold">أضف إلى الرف</button>
        </form>

        @if (session('status'))
            <p class="wm-flash" style="margin-top:20px">{{ session('status') }}</p>
        @endif

        @if ($books->isEmpty())
            <p class="wm-empty">رفّك فاضي. أضف أول كتاب قرأته، وبعدها من المكتبة اضغط «احفظ» على أي ومضة.</p>
        @else
            <div class="wm-shelf">
                <div class="wm-books">
                    @foreach ($books as $b)
                        <a href="{{ route('shelf', ['book' => $b->id]) }}"
                            class="wm-spine c{{ $b->id % 6 }} {{ $current->id === $b->id ? 'on' : '' }}"
                            style="height: {{ 160 + ($b->id * 37) % 70 }}px"
                            title="{{ $b->title }}">
                            <span>{{ $b->title }}</span>
                            <small>{{ $b->quotes_count }}</small>
                        </a>
                    @endforeach
                </div>
                <div class="wm-plank"></div>
            </div>

            <section class="wm-sec">
                <div class="wm-bar">
                    <div class="wm-bookhead">
                        <h2>{{ $current->title }}</h2>
                        <p>
                            @if ($current->author) {{ $current->author }} · @endif
                            عدد الومضات: {{ $current->quotes->count() }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('shelf.destroy', $current) }}" onsubmit="return confirm('إزالة هذا الكتاب من رفّك؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="wm-btn">إزالة الكتاب</button>
                    </form>
                </div>

                <div class="wm-feed">
                    @forelse ($current->quotes as $quote)
                        <x-quote-card :quote="$quote" :book="$current" />
                    @empty
                        <p class="wm-empty">ما حفظت ومضات في هذا الكتاب بعد. من المكتبة اضغط «احفظ» على أي ومضة.</p>
                    @endforelse
                </div>
            </section>
        @endif
    </div>
</div>

<x-quote-tools :books="$books" />
@endsection