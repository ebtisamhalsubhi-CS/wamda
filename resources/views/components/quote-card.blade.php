@props(['quote', 'book' => null])

@php
    $isMine = auth()->id() === $quote->user_id;
    $sayer = $quote->author ?? $quote->user->name;
@endphp

<div class="wm-card {{ $quote->lang == 'en' ? 'en' : '' }} {{ $quote->lit ? 'lit' : '' }} {{ $isMine ? 'mine' : '' }}" dir="{{ $quote->lang == 'en' ? 'ltr' : 'rtl' }}">
    <span class="wm-tag">
        {{ \App\Models\Quote::CATEGORIES[$quote->category] }}
        @if ($isMine)
            <em>ومضتك</em>
        @endif
    </span>

    <p class="wm-text">“{{ $quote->content }}”</p>

    <div class="wm-who">
        @if ($quote->author)
            <a href="{{ route('sayer', $quote->author) }}">{{ $quote->author }}</a>
        @else
            <b>{{ $sayer }}</b>
        @endif
        @if ($quote->source)
            <span>{{ $quote->source }}</span>
        @endif
    </div>

    @if ($quote->meaning)
        <p class="wm-meaning" dir="rtl" hidden>{{ $quote->meaning }}</p>
    @endif

    <p class="wm-pub" dir="rtl">نشرها {{ $quote->user->name }} · {{ $quote->created_at->locale('ar')->translatedFormat('j F Y') }}</p>

    <div class="wm-tools" dir="rtl">
        @auth
            <form class="wm-light" method="POST" action="{{ route('quotes.light', $quote) }}">
                @csrf
                <button type="submit" class="wm-tool">إضاءة <span>{{ $quote->lighters_count }}</span></button>
            </form>
        @else
            <a href="{{ route('login') }}" class="wm-tool">إضاءة <span>{{ $quote->lighters_count }}</span></a>
        @endauth

        <button type="button" class="wm-tool" data-copy="“{{ $quote->content }}” — {{ $sayer }}">نسخ</button>

        @if ($quote->meaning)
            <button type="button" class="wm-tool" data-explain>المعنى</button>
        @endif

        <button type="button" class="wm-tool"
            data-story
            data-url="{{ route('quotes.show', $quote) }}"
            data-text="{{ $quote->content }}"
            data-who="{{ $sayer }}"
            data-source="{{ $quote->source }}"
            data-lang="{{ $quote->lang }}"
            data-tag="{{ \App\Models\Quote::CATEGORIES[$quote->category] }}">ستوري</button>

        @auth
            <button type="button" class="wm-tool" data-gift="{{ route('gifts.store', $quote) }}" data-id="{{ $quote->id }}" data-text="{{ $quote->content }}">أهدِ</button>

            @if ($book)
                <form method="POST" action="{{ route('shelf.remove', [$book, $quote]) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="wm-tool">إزالة من الكتاب</button>
                </form>
            @else
                <button type="button" class="wm-tool" data-save="{{ route('shelf.save', $quote) }}">احفظ</button>
            @endif
        @else
            <a href="{{ route('login') }}" class="wm-tool">أهدِ</a>
        @endauth

        @if ($isMine)
            <button type="button" class="wm-tool"
                data-edit="{{ $quote->id }}"
                data-action="{{ route('quotes.update', $quote) }}"
                data-content="{{ $quote->content }}"
                data-author="{{ $quote->author }}"
                data-source="{{ $quote->source }}"
                data-meaning="{{ $quote->meaning }}"
                data-category="{{ $quote->category }}">تعديل</button>

            <form method="POST" action="{{ route('quotes.destroy', $quote) }}" onsubmit="return confirm('حذف هذه الومضة؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="wm-tool">حذف</button>
            </form>
        @endif
    </div>
</div>
