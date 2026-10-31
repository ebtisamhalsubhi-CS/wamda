@extends('Layout.app')

@php
    $sayer = $quote->author ?? $quote->user->name;
@endphp

@section('title', $sayer . ' · ومضة')

@push('head')
    <meta property="og:site_name" content="ومضة">
    <meta property="og:title" content="“{{ Str::limit($quote->content, 90) }}” — {{ $sayer }}">
    <meta property="og:description" content="{{ $quote->meaning ?? 'مكتبة المقولات على المسرح' }}">
@endpush

@section('content')

<style>
    .wm-qstage {
        position: relative;
        display: grid;
        place-items: center;
        min-height: calc(100vh - 115px);
        overflow: hidden;
        padding: 150px 20px 80px;
        text-align: center;
    }

    .wm-qcur {
        position: absolute;
        top: 0;
        bottom: 0;
        z-index: 3;
        width: 51%;
        background: repeating-linear-gradient(90deg, #3a0f14 0, #5a1820 18px, #2f0b10 36px);
        animation: 2s ease-in-out .4s forwards;
    }

    .wm-qcur.r { right: 0; animation-name: qopenR; }

    .wm-qcur.l { left: 0; animation-name: qopenL; }

    .wm-qlamp {
        position: absolute;
        top: 0;
        left: 50%;
        width: 26px;
        margin-left: -13px;
    }

    .wm-qlamp::before {
        content: "";
        display: block;
        width: 2px;
        height: 70px;
        margin: 0 auto;
        background: #6d6354;
    }

    .wm-qcan {
        position: relative;
        height: 36px;
        border: 2px solid #6d6354;
        border-radius: 5px;
        background: #0a0806;
    }

    .wm-qcan::after {
        content: "";
        position: absolute;
        bottom: -5px;
        left: 0;
        right: 0;
        height: 8px;
        border-radius: 50%;
        background: #3a3226;
        animation: qglow .8s ease 2.2s forwards;
    }

    .wm-qbeam {
        position: absolute;
        top: 100%;
        left: 50%;
        width: 760px;
        height: 760px;
        transform: translateX(-50%);
        clip-path: polygon(47% 0, 53% 0, 100% 100%, 0 100%);
        background: linear-gradient(rgba(240, 217, 166, .28), rgba(240, 217, 166, .02));
        opacity: 0;
        animation: qshow 1.2s ease 2.2s forwards;
    }

    .wm-qbody { position: relative; max-width: 780px; }

    .wm-qbody > * { opacity: 0; animation: qrise 1s ease forwards; }

    .wm-q-tag { display: block; color: var(--gold); animation-delay: 2.6s; }

    .wm-q-text {
        display: block;
        margin: 24px 0 20px;
        font-family: 'Amiri', serif;
        font-size: 2.6rem;
        line-height: 1.8;
        color: #f3ecda;
        text-shadow: 0 0 24px rgba(240, 217, 166, .35);
        quotes: none;
        animation-delay: 3s;
    }

    .wm-q-text.en { font-family: 'Cormorant Garamond', serif; font-style: italic; line-height: 1.45; }

    .wm-q-by { display: block; font-style: normal; color: var(--mute); animation-delay: 3.8s; }

    .wm-q-by a { font-weight: 700; color: var(--gold); }

    .wm-q-by a::after { content: '←'; display: inline-block; margin-inline-start: 6px; }

    .wm-q-mean {
        max-width: 600px;
        margin: 34px auto 0;
        padding-top: 22px;
        border-top: 1px solid #3a3226;
        animation-delay: 4.4s;
    }

    .wm-q-mean b { display: block; margin-bottom: 8px; font-size: .85rem; font-weight: 500; color: var(--gold); }

    .wm-q-mean p { margin: 0; line-height: 2; color: #cfc4ad; }

    .wm-q-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
        margin-top: 44px;
        animation-delay: 5s;
    }

    @keyframes qopenR {
        to { transform: translateX(100%); }
    }

    @keyframes qopenL {
        to { transform: translateX(-100%); }
    }

    @keyframes qshow {
        to { opacity: 1; }
    }

    @keyframes qglow {
        to { background: #f0d9a6; box-shadow: 0 0 16px #f0d9a6; }
    }

    @keyframes qrise {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: none; }
    }

    @media (max-width: 600px) {
        .wm-q-text { font-size: 1.9rem; }
    }
</style>

<section class="wm-qstage" dir="rtl">
    <div class="wm-qcur l"></div>
    <div class="wm-qcur r"></div>

    <div class="wm-qlamp">
        <div class="wm-qcan"><span class="wm-qbeam"></span></div>
    </div>

    <div class="wm-qbody">
        <small class="wm-q-tag">{{ \App\Models\Quote::CATEGORIES[$quote->category] }}</small>

        <q class="wm-q-text {{ $quote->lang === 'en' ? 'en' : '' }}" dir="{{ $quote->lang === 'en' ? 'ltr' : 'rtl' }}">“{{ $quote->content }}”</q>

        <cite class="wm-q-by">
            @if ($quote->author)
                <a href="{{ route('sayer', $quote->author) }}">{{ $quote->author }}</a>
            @else
                {{ $quote->user->name }}
            @endif
            @if ($quote->source)
                / {{ $quote->source }}
            @endif
        </cite>

        @if ($quote->meaning)
            <div class="wm-q-mean">
                <b>المعنى</b>
                <p>{{ $quote->meaning }}</p>
            </div>
        @endif

        <div class="wm-q-actions">
            @if ($quote->author)
                <a href="{{ route('sayer.stage', $quote->author) }}" class="wm-btn gold">🎭 شاهد عرض {{ $quote->author }}</a>
            @endif
            <a href="{{ route('home') }}" class="wm-btn">تصفّح مكتبة ومضة</a>
        </div>
    </div>
</section>
@endsection
