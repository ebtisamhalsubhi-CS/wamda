@php
    $activeSection = request()->route('section');
    $onHome = request()->routeIs('home') && !$activeSection;
@endphp

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cormorant+Garamond:ital,wght@0,500;1,400;1,500&family=IBM+Plex+Sans+Arabic:wght@300;400;500&display=swap">

<style>
    :root {
        --bg: #0c0a08;
        --ink: #ece3cf;
        --mute: #8d8372;
        --line: #2a241c;
        --gold: #c2a068;
    }

    * { box-sizing: border-box; }

    html, body { background: var(--bg) !important; background-image: none !important; }

    body {
        margin: 0;
        color: var(--ink);
        font-family: 'IBM Plex Sans Arabic', sans-serif;
        font-weight: 300;
    }

    a { color: inherit; text-decoration: none; }

    button { font: inherit; color: inherit; cursor: pointer; }

    .wm-wrap { max-width: 1180px; margin: 0 auto; padding: 0 28px; }

    .wm-nav {
        position: sticky;
        top: 0;
        z-index: 50;
        background: rgba(12, 10, 8, .94);
        border-bottom: 1px solid var(--line);
    }

    .wm-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 68px;
    }

    .wm-brand { font-family: 'Amiri', serif; font-size: 2.3rem; font-weight: 700; }

    .wm-actions { display: flex; align-items: center; gap: 10px; }

    .wm-me { display: flex; align-items: center; gap: 8px; font-size: .86rem; color: var(--mute); }

    .wm-avatar {
        display: grid;
        place-items: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--gold);
        color: #000;
        font-weight: 500;
    }

    .wm-sub {
        display: flex;
        gap: 30px;
        overflow-x: auto;
        border-top: 1px solid var(--line);
    }

    .wm-sub a {
        padding: 13px 0;
        font-size: .88rem;
        color: var(--mute);
        white-space: nowrap;
    }

    .wm-sub a:hover { color: var(--ink); }

    .wm-sub a.on { color: var(--gold); border-bottom: 1px solid var(--gold); }

    .wm-sub .wm-mine { margin-right: auto; }

    .wm-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 9px 20px;
        border: 1px solid #3a3226;
        border-radius: 3px;
        background: transparent;
        font-size: .86rem;
        font-weight: 400;
    }

    .wm-btn:hover { border-color: var(--gold); color: var(--gold); }

    .wm-btn.solid { background: var(--ink); border-color: var(--ink); color: #000; }

    .wm-btn.gold {
        background: var(--gold);
        border-color: var(--gold);
        color: #000;
        font-weight: 500;
    }

    .wm-btn.full { width: 100%; padding: 14px; }

    #wm-cursor {
        position: fixed;
        left: 0;
        top: 0;
        z-index: 30;
        width: 460px;
        height: 460px;
        margin: -230px 0 0 -230px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(194, 160, 104, .10), transparent 68%);
        pointer-events: none;
        opacity: 0;
        transition: opacity .4s;
    }

    #wm-dim {
        position: fixed;
        inset: 0;
        z-index: 40;
        background: radial-gradient(circle 240px at var(--mx, 50%) var(--my, 50%), transparent, rgba(0, 0, 0, .95));
        pointer-events: none;
        opacity: 0;
        transition: opacity .7s;
    }

    #wm-dim.on { opacity: 1; }

    .wm-auth {
        position: relative;
        display: grid;
        place-items: center;
        min-height: calc(100vh - 115px);
        padding: 40px 20px;
        overflow: hidden;
    }

    .wm-cone {
        position: absolute;
        top: 0;
        left: 50%;
        width: 640px;
        max-width: 100%;
        height: 420px;
        transform: translateX(-50%);
        clip-path: polygon(44% 0, 56% 0, 100% 100%, 0 100%);
        background: linear-gradient(rgba(194, 160, 104, .16), transparent);
    }

    .wm-panel {
        position: relative;
        width: 420px;
        max-width: 100%;
        padding: 38px 32px;
        border: 1px solid var(--line);
        border-radius: 4px;
        background: #0f0c09;
    }

    .wm-panel h1 {
        margin: 0 0 6px;
        font-family: 'Amiri', serif;
        font-size: 2.1rem;
        font-weight: 400;
    }

    .wm-panel .sub { margin: 0 0 26px; font-size: .9rem; color: var(--mute); }

    .wm-field { margin-bottom: 16px; }

    .wm-field label {
        display: block;
        margin-bottom: 6px;
        font-size: .8rem;
        color: var(--mute);
    }

    .wm-input {
        width: 100%;
        padding: 13px 14px;
        border: 1px solid #30291f;
        border-radius: 3px;
        background: #0a0806;
        color: var(--ink);
        font: inherit;
        outline: none;
    }

    .wm-input:focus { border-color: var(--gold); }

    .wm-hint {
        display: flex;
        justify-content: space-between;
        margin: 6px 0 0;
        font-size: .74rem;
        color: #6f6656;
    }

    .wm-err { margin: 6px 0 0; font-size: .78rem; color: #d98a7c; }

    .wm-foot {
        margin-top: 20px;
        text-align: center;
        font-size: .88rem;
        color: var(--mute);
    }

    .wm-foot a { color: var(--gold); }

    .wm-mast { padding: 70px 0 44px; border-bottom: 1px solid var(--line); }

    .wm-mast em {
        display: block;
        margin-bottom: 12px;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.2rem;
        color: var(--mute);
    }

    .wm-mast h1 {
        margin: 0 0 16px;
        font-family: 'Amiri', serif;
        font-size: 4rem;
        font-weight: 400;
    }

    .wm-mast p { color: #a89f8c; }

    .wm-sec { padding: 56px 0 100px; scroll-margin-top: 96px; }

    .wm-bar {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 44px;
        padding-bottom: 18px;
        border-bottom: 1px solid var(--line);
    }

    .wm-feed { column-width: 320px; column-gap: 56px; }

    .wm-flash { margin: -24px 0 32px; color: var(--gold); }

    .wm-empty { padding: 40px 0; color: var(--mute); }

    .wm-card {
        break-inside: avoid;
        margin-bottom: 46px;
        padding-top: 22px;
        border-top: 1px solid var(--line);
    }

    .wm-card.mine { padding: 22px 16px 8px; border-top: 2px solid #7a2a31; background: linear-gradient(rgba(92, 26, 32, .22), transparent); }

    .wm-card.lit { border-top: 2px solid var(--gold); background: linear-gradient(rgba(194, 160, 104, .12), transparent); }

    .wm-tag {
        display: block;
        margin-bottom: 14px;
        font-size: .75rem;
        color: var(--gold);
    }

    .wm-tag em {
        margin-right: 6px;
        padding: 1px 9px;
        border: 1px solid #7a2a31;
        border-radius: 10px;
        font-style: normal;
        color: #dba9ad;
    }

    .wm-text {
        margin: 0 0 18px;
        font-family: 'Amiri', serif;
        font-size: 1.55rem;
        line-height: 2.1;
    }

    .wm-card.en .wm-text {
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
        font-size: 1.7rem;
        line-height: 1.55;
    }

    .wm-who { display: flex; flex-direction: column; font-size: .86rem; }

    .wm-who a { align-self: flex-start; font-weight: 700; color: var(--gold); }

    .wm-who a::after { content: '←'; display: inline-block; margin-inline-start: 6px; transition: transform .2s; }

    .wm-card.en .wm-who a::after { content: '→'; }

    .wm-who a:hover::after { transform: translateX(-4px); }

    .wm-card.en .wm-who a:hover::after { transform: translateX(4px); }

    .wm-who span { font-size: .76rem; color: var(--mute); }

    .wm-meaning {
        margin: 14px 0 6px;
        padding: 10px 14px;
        border-right: 2px solid var(--gold);
        background: rgba(194, 160, 104, .06);
        font-size: .9rem;
        line-height: 1.9;
        color: #cfc4ad;
    }

    .wm-pub { margin: 8px 0 6px; font-size: .74rem; color: #6f6656; }

    .wm-tools {
        display: flex;
        flex-wrap: wrap;
        gap: 2px;
        margin: 0 -8px;
    }

    .wm-tools form { margin: 0; }

    .wm-tool {
        padding: 6px 8px;
        border: 0;
        background: transparent;
        font-size: .78rem;
        color: var(--mute);
    }

    .wm-tool:hover, .wm-tool.on, .wm-card.lit .wm-light .wm-tool { color: var(--gold); }

    @media (max-width: 768px) {
        #wm-cursor, #wm-dim, #wm-theater { display: none; }
    }

    @media (max-width: 480px) {
        .wm-wrap { padding: 0 16px; }

        .wm-brand { font-size: 1.8rem; }

        .wm-actions .wm-btn { padding: 8px 10px; font-size: .8rem; }

        .wm-me .wm-name { display: none; }

        .wm-mast h1 { font-size: 3rem; }
    }
</style>

<div id="wm-cursor"></div>
<div id="wm-dim"></div>

<header class="wm-nav" dir="rtl">
    <div class="wm-wrap">
        <div class="wm-top">
            <a href="{{ route('home') }}" class="wm-brand">ومضة</a>

            <div class="wm-actions">
                <button id="wm-theater" type="button" class="wm-btn">💡 <span>تعتيم القاعة</span></button>

                @auth
                    <span class="wm-me">
                        <span class="wm-avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                        <span class="wm-name">{{ auth()->user()->name }}</span>
                    </span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="wm-btn">تسجيل الخروج</button>
                    </form>
                @else
                    @if (!request()->routeIs('login'))
                        <a href="{{ route('login') }}" class="wm-btn">تسجيل الدخول</a>
                    @endif
                    @if (!request()->routeIs('signup'))
                        <a href="{{ route('signup') }}" class="wm-btn solid">إنشاء حساب</a>
                    @endif
                @endauth
            </div>
        </div>

        <nav class="wm-sub">
            <a href="{{ route('home') }}" class="{{ $onHome ? 'on' : '' }}">الرئيسية</a>
            @foreach (config('sections') as $slug => $s)
                <a href="{{ route('explore', $slug) }}" class="{{ $activeSection == $slug ? 'on' : '' }}">{{ $s['title'] }}</a>
            @endforeach

            @auth
                <a href="{{ route('shelf') }}" class="wm-mine {{ request()->routeIs('shelf') ? 'on' : '' }}">رفّي</a>
                <a href="{{ route('gifts.index') }}" class="{{ request()->routeIs('gifts.index') ? 'on' : '' }}">هداياي</a>
            @endauth
        </nav>
    </div>
</header>

<script>
    const cursorLight = document.getElementById('wm-cursor');
    const dim = document.getElementById('wm-dim');
    const theaterBtn = document.getElementById('wm-theater');

    function aimDim(x, y) {
        dim.style.setProperty('--mx', x + 'px');
        dim.style.setProperty('--my', y + 'px');
    }

    document.addEventListener('mousemove', e => {
        cursorLight.style.opacity = 1;
        cursorLight.style.transform = 'translate(' + e.clientX + 'px, ' + e.clientY + 'px)';
        aimDim(e.clientX, e.clientY);
    });

    document.addEventListener('touchstart', e => aimDim(e.touches[0].clientX, e.touches[0].clientY), { passive: true });
    document.addEventListener('touchmove', e => aimDim(e.touches[0].clientX, e.touches[0].clientY), { passive: true });

    function setDim(on) {
        dim.classList.toggle('on', on);
        theaterBtn.querySelector('span').textContent = on ? 'إنارة القاعة' : 'تعتيم القاعة';
        localStorage.setItem('dim', on ? '1' : '0');
    }

    theaterBtn.addEventListener('click', () => setDim(!dim.classList.contains('on')));

    if (localStorage.getItem('dim') === '1') {
        setDim(true);
    }
</script>
