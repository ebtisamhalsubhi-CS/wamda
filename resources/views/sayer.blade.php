@extends('Layout.app')

@section('title', $name . ' · ومضة')

@section('content')

<style>
    .wm-sayer h1 { font-size: 3.6rem; }

    .wm-sayer .wm-about { max-width: 720px; font-size: 1.05rem; line-height: 2; }

    .wm-count { color: var(--mute); font-size: .9rem; }

    .wm-showbtn { margin-top: 26px; }
</style>

<div class="wm-page" dir="rtl">
    <div class="wm-wrap">
        <section class="wm-mast wm-sayer">
            <em>{{ $bio['role'] ?? 'قائل' }}</em>
            <h1 dir="auto">{{ $name }}</h1>
            @if ($bio)
                <p class="wm-about">{{ $bio['about'] }}</p>
            @endif
            <a href="{{ route('sayer.stage', $name) }}" class="wm-btn gold wm-showbtn">🎭 شاهد العرض</a>
        </section>

        <section class="wm-sec" id="wm-feed-top">
            <div class="wm-bar">
                <span class="wm-count">عدد الومضات: {{ $quotes->count() }}</span>
                <a href="{{ route('home') }}" class="wm-btn">رجوع للمكتبة</a>
            </div>

            @if (session('status'))
                <p class="wm-flash">{{ session('status') }}</p>
            @endif

            <div class="wm-feed">
                @foreach ($quotes as $quote)
                    <x-quote-card :quote="$quote" />
                @endforeach
            </div>
        </section>
    </div>
</div>

<x-quote-tools :books="$myBooks" />
@endsection
