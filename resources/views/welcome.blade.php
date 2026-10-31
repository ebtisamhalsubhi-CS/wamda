@extends('Layout.app')

@section('title', 'ومضة')

@section('content')

<style>
    .wm-stagewrap { position: relative; overflow: hidden; }

    .wm-stagewrap::before, .wm-stagewrap::after {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        width: 12%;
        background: repeating-linear-gradient(90deg, #3a0f14 0, #4d151b 14px, #2f0b10 28px);
        pointer-events: none;
    }

    .wm-stagewrap::before { right: 0; mask-image: linear-gradient(270deg, #000 25%, transparent); }

    .wm-stagewrap::after { left: 0; mask-image: linear-gradient(90deg, #000 25%, transparent); }

    .wm-hero {
        position: relative;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 56px;
        align-items: center;
        min-height: 80vh;
        padding: 50px 0;
    }

    .wm-hero h1 {
        margin: 0 0 34px;
        font-family: 'Amiri', serif;
        font-size: 4.2rem;
        font-weight: 400;
        line-height: 1.45;
    }

    .wm-cta { display: flex; gap: 12px; }

    .wm-stage {
        position: relative;
        isolation: isolate;
        width: 440px;
        height: 500px;
        margin: 0 auto;
    }

    .wm-word {
        display: flex;
        justify-content: center;
        font-family: 'Amiri', serif;
        font-size: 7.5rem;
        font-weight: 700;
        line-height: 1.2;
        color: #f3ecda;
        text-shadow: 0 0 18px rgba(243, 236, 218, .35);
    }

    .wm-word span {
        position: relative;
        animation: letter 8s infinite;
    }

    .wm-word span::before {
        content: "";
        position: absolute;
        top: 80px;
        left: 50%;
        z-index: -1;
        width: 2px;
        height: 110px;
        margin-left: -1px;
        background: #6d6354;
    }

    .wm-word i {
        position: absolute;
        top: 190px;
        left: 50%;
        width: 22px;
        height: 34px;
        margin-left: -11px;
        border: 2px solid #6d6354;
        border-radius: 5px;
        background: #0a0806;
        transform-origin: top center;
    }

    .wm-word i::after {
        content: "";
        position: absolute;
        bottom: -5px;
        left: 0;
        right: 0;
        height: 8px;
        border-radius: 50%;
        background: #3a3226;
        animation: lens 8s infinite;
    }

    .wm-word i::before {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        width: 240px;
        height: 205px;
        margin-left: -120px;
        clip-path: polygon(46% 0, 54% 0, 100% 100%, 0 100%);
        background: linear-gradient(rgba(240, 217, 166, .45), rgba(240, 217, 166, .04));
        opacity: 0;
        animation: on 8s infinite;
    }

    .wm-word span:nth-child(1) i { transform: rotate(22deg); }

    .wm-word span:nth-child(2) i { transform: rotate(10deg); }

    .wm-word span:nth-child(3) i { transform: rotate(-6deg); }

    .wm-word span:nth-child(4) i { transform: rotate(-22deg); }

    .wm-word span:nth-child(1) i::before,
    .wm-word span:nth-child(4) i::before { height: 225px; }

    .wm-lines {
        position: absolute;
        top: 420px;
        left: 0;
        right: 0;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .wm-lines li {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-family: 'Amiri', serif;
        font-size: 1.6rem;
        color: #f3ecda;
        text-shadow: 0 0 20px rgba(240, 217, 166, .6);
        opacity: 0;
        animation: on 8s infinite;
    }

    .wm-word span:nth-child(2),
    .wm-word span:nth-child(2) i::before,
    .wm-word span:nth-child(2) i::after,
    .wm-lines li:nth-child(2) { animation-delay: 2s; }

    .wm-word span:nth-child(3),
    .wm-word span:nth-child(3) i::before,
    .wm-word span:nth-child(3) i::after,
    .wm-lines li:nth-child(3) { animation-delay: 4s; }

    .wm-word span:nth-child(4),
    .wm-word span:nth-child(4) i::before,
    .wm-word span:nth-child(4) i::after,
    .wm-lines li:nth-child(4) { animation-delay: 6s; }

    @keyframes on {
        0% { opacity: 0; }
        4%, 21% { opacity: 1; }
        25%, 100% { opacity: 0; }
    }

    @keyframes lens {
        0%, 23% { background: #f0d9a6; box-shadow: 0 0 14px #f0d9a6; }
        25%, 100% { background: #3a3226; box-shadow: none; }
    }

    @keyframes letter {
        0%, 23% { color: var(--gold); text-shadow: 0 0 26px rgba(240, 217, 166, .6); }
        25%, 100% { color: #f3ecda; text-shadow: 0 0 18px rgba(243, 236, 218, .35); }
    }

    .wm-tiles {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0 48px;
        padding: 70px 0 20px;
    }

    .wm-tile { padding: 26px 0 34px; border-top: 2px solid var(--line); }

    .wm-tile:hover { border-top-color: var(--gold); }

    .wm-tile h3 {
        margin: 0;
        font-family: 'Amiri', serif;
        font-size: 2.1rem;
        font-weight: 400;
    }

    .wm-tile:hover h3 { color: var(--gold); }

    .wm-tile em {
        display: block;
        margin-bottom: 12px;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.2rem;
        color: var(--mute);
    }

    .wm-tile p { color: #a89f8c; font-size: .92rem; line-height: 1.9; }

    .wm-tile small { color: var(--mute); }

    .wm-day {
        max-width: 880px;
        margin: 90px auto 0;
        padding: 56px 0;
        border-top: 1px solid var(--line);
        border-bottom: 1px solid var(--line);
        text-align: center;
    }

    .wm-day small { display: block; margin-bottom: 26px; color: var(--gold); }

    .wm-day q {
        display: block;
        margin-bottom: 24px;
        font-family: 'Amiri', serif;
        font-size: 2.4rem;
        line-height: 1.8;
        quotes: none;
    }

    .wm-day cite { font-style: normal; color: var(--mute); }

    .wm-day cite a { font-weight: 700; color: var(--gold); }

    .wm-day cite a::after { content: '←'; display: inline-block; margin-inline-start: 6px; transition: transform .2s; }

    .wm-day cite a:hover::after { transform: translateX(-4px); }

    .wm-filters { display: flex; flex-wrap: wrap; gap: 22px; }

    .wm-filters a { font-size: .86rem; color: var(--mute); }

    .wm-filters a.on { color: var(--gold); border-bottom: 1px solid var(--gold); }

    .wm-find {
        width: 260px;
        max-width: 100%;
        padding: 8px 2px;
        border: 0;
        border-bottom: 1px solid #3a3226;
        background: transparent;
        color: var(--ink);
        font: inherit;
        outline: none;
    }

    .wm-more { display: flex; justify-content: center; gap: 12px; }

    .wm-bottom {
        padding: 40px 0 50px;
        border-top: 1px solid var(--line);
        font-size: .84rem;
        color: var(--mute);
    }

    .wm-bottom .wm-wrap {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 20px;
    }

    .wm-bottom nav { display: flex; flex-wrap: wrap; gap: 24px; }

    @media (max-width: 900px) {
        .wm-hero { grid-template-columns: 1fr; min-height: 0; }

        .wm-hero h1 { font-size: 3rem; }

        .wm-tiles { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 560px) {
        .wm-tiles { grid-template-columns: 1fr; }

        .wm-stage { zoom: .75; }
    }
</style>

@php
    $sections = config('sections');

    $filters = ['all' => 'الكل', 'ar' => 'العربية', 'en' => 'English', 'top' => 'الأكثر إضاءة'];
    if (auth()->check()) {
        $filters['mine'] = 'ومضاتي';
        $filters['lit'] = 'ما أضأته';
    }
@endphp

<div class="wm-page" dir="rtl">

    @if (!$section)
        <div class="wm-stagewrap">
            <div class="wm-wrap">
                <section class="wm-hero">
                    <div>
                        <h1>مكتبة للكلمات التي تستحق الضوء.</h1>
                        <div class="wm-cta">
                            <a href="#wm-sections" class="wm-btn solid">تصفّح المكتبة</a>
                            @auth
                                <button type="button" class="wm-btn" data-open>اعتلِ المسرح</button>
                            @else
                                <a href="{{ route('signup') }}" class="wm-btn">اعتلِ المسرح</a>
                            @endauth
                        </div>
                    </div>

                    <div class="wm-stage">
                        <div class="wm-word">
                            <span>و<i></i></span>
                            <span>م&zwj;<i></i></span>
                            <span>&zwj;ض&zwj;<i></i></span>
                            <span>&zwj;ة<i></i></span>
                        </div>

                        <ul class="wm-lines">
                            <li>وحيٌ يضيء العقول</li>
                            <li>معرفةٌ تفتح الآفاق</li>
                            <li>ضياءٌ ينير الظلمات</li>
                            <li>همسةٌ بليغة في الأثر</li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>

        <div class="wm-wrap">
            <section class="wm-tiles" id="wm-sections">
                @foreach ($sections as $slug => $s)
                    <a class="wm-tile" href="{{ route('explore', $slug) }}">
                        <h3>{{ $s['title'] }}</h3>
                        <em>{{ $s['title_en'] }}</em>
                        <p>{{ $s['desc'] }}</p>
                        <small>عدد الومضات: {{ $counts[$slug] }}</small>
                    </a>
                @endforeach
            </section>

            @if ($day)
                <section class="wm-day">
                    <small>ومضة اليوم</small>
                    <q dir="{{ $day->lang === 'en' ? 'ltr' : 'rtl' }}">“{{ $day->content }}”</q>
                    <cite>
                        @if ($day->author)
                            <a href="{{ route('sayer', $day->author) }}">{{ $day->author }}</a>
                        @else
                            {{ $day->user->name }}
                        @endif
                        @if ($day->source)
                            / {{ $day->source }}
                        @endif
                    </cite>
                </section>
            @endif
        </div>
    @else
        <div class="wm-wrap">
            <section class="wm-mast">
                <em>{{ $sections[$section]['title_en'] }}</em>
                <h1>{{ $sections[$section]['title'] }}</h1>
                <p>{{ $sections[$section]['desc'] }}</p>
            </section>
        </div>
    @endif

    <div class="wm-wrap">
        <section class="wm-sec" id="wm-feed-top">
            <div class="wm-bar">
                <nav class="wm-filters">
                    @foreach ($filters as $key => $label)
                        <a href="{{ request()->fullUrlWithQuery(['show' => $key, 'page' => null]) }}#wm-feed-top" class="{{ $show === $key ? 'on' : '' }}">{{ $label }}</a>
                    @endforeach
                </nav>

                <form method="GET" action="{{ url()->current() }}#wm-feed-top">
                    <input type="hidden" name="show" value="{{ $show }}">
                    <input class="wm-find" type="search" name="q" value="{{ request('q') }}" placeholder="ابحث بالنص أو القائل أو المصدر">
                </form>

                @auth
                    <button type="button" class="wm-btn" data-open>إضافة ومضة</button>
                @else
                    <a href="{{ route('login') }}" class="wm-btn">إضافة ومضة</a>
                @endauth
            </div>

            @if (session('status'))
                <p class="wm-flash">{{ session('status') }}</p>
            @endif

            <div class="wm-feed">
                @forelse ($quotes as $quote)
                    <x-quote-card :quote="$quote" />
                @empty
                    <p class="wm-empty">لا توجد ومضات هنا.</p>
                @endforelse
            </div>

            <div class="wm-more">
                @if (!$quotes->onFirstPage())
                    <a class="wm-btn" href="{{ $quotes->previousPageUrl() }}#wm-feed-top">السابق</a>
                @endif
                @if ($quotes->hasMorePages())
                    <a class="wm-btn" href="{{ $quotes->nextPageUrl() }}#wm-feed-top">التالي</a>
                @endif
            </div>
        </section>
    </div>

    <footer class="wm-bottom">
        <div class="wm-wrap">
            <span>ومضة. مكتبة ومسرح للكلمات.</span>
            <nav>
                @foreach ($sections as $slug => $s)
                    <a href="{{ route('explore', $slug) }}">{{ $s['title'] }}</a>
                @endforeach
            </nav>
        </div>
    </footer>
</div>

<x-quote-tools :books="$myBooks" />
@endsection
