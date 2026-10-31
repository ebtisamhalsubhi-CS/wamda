@extends('Layout.app')

@section('title', 'هداياي · ومضة')

@section('content')

<style>
    .wm-giftrow {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 24px 0;
        border-bottom: 1px solid var(--line);
    }

    .wm-giftrow b { font-family: 'Amiri', serif; font-size: 1.4rem; font-weight: 400; }

    .wm-giftrow q {
        display: block;
        margin-top: 6px;
        font-family: 'Amiri', serif;
        font-size: 1.1rem;
        color: #a89f8c;
        quotes: none;
    }

    .wm-giftrow small {
        display: block;
        margin-top: 6px;
        font-size: .74rem;
        color: #6f6656;
    }

    .wm-giftside { display: flex; align-items: center; gap: 14px; }

    .wm-state {
        padding: 3px 12px;
        border: 1px solid #3a3226;
        border-radius: 12px;
        font-size: .78rem;
        color: var(--mute);
    }

    .wm-state.opened { border-color: var(--gold); color: var(--gold); }
</style>

<div class="wm-page" dir="rtl">
    <div class="wm-wrap">
        <section class="wm-mast">
            <em>My Gifts</em>
            <h1>هداياي</h1>
            <p>الومضات اللي أهديتها، وهل فتحها أصحابها.</p>
        </section>

        <section class="wm-sec" style="padding-top:20px">
            @forelse ($gifts as $gift)
                <div class="wm-giftrow">
                    <div>
                        <b>إلى {{ $gift->to_name }}</b>
                        <q>“{{ Str::limit($gift->quote->content, 80) }}”</q>
                        <small>أُهديت في {{ $gift->created_at->locale('ar')->translatedFormat('j F Y') }}</small>
                    </div>
                    <div class="wm-giftside">
                        @if ($gift->opened_at)
                            <span class="wm-state opened">فُتحت ✓</span>
                        @else
                            <span class="wm-state">لم تُفتح بعد</span>
                        @endif
                        <a href="{{ route('gifts.show', $gift) }}" class="wm-btn">عرض الهدية</a>
                    </div>
                </div>
            @empty
                <p class="wm-empty">ما أهديت أي ومضة بعد. من المكتبة اضغط «أهدِ» على أي ومضة.</p>
            @endforelse
        </section>
    </div>
</div>
@endsection