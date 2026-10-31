@extends('Layout.app')

@section('title', 'عرض الليلة · ' . $name)

@section('content')

<style>
    .wm-show {
        position: fixed;
        inset: 0;
        z-index: 70;
        overflow: hidden;
        background: #070605;
        text-align: center;
    }

    .wm-drape {
        position: absolute;
        top: 0;
        bottom: 0;
        z-index: 4;
        width: 51%;
        background: repeating-linear-gradient(90deg, #3a0f14 0, #5a1820 18px, #2f0b10 36px);
        transition: transform 2.2s ease-in-out;
    }

    .wm-drape.l { left: 0; }

    .wm-drape.r { right: 0; }

    .wm-show.open .wm-drape.l { transform: translateX(-100%); }

    .wm-show.open .wm-drape.r { transform: translateX(100%); }

    .wm-slamp {
        position: absolute;
        top: 0;
        left: 50%;
        z-index: 3;
        width: 26px;
        margin-left: -13px;
    }

    .wm-slamp::before {
        content: "";
        display: block;
        width: 2px;
        height: 60px;
        margin: 0 auto;
        background: #6d6354;
    }

    .wm-scan {
        position: relative;
        height: 36px;
        border: 2px solid #6d6354;
        border-radius: 5px;
        background: #0a0806;
    }

    .wm-scan::after {
        content: "";
        position: absolute;
        bottom: -5px;
        left: 0;
        right: 0;
        height: 8px;
        border-radius: 50%;
        background: #3a3226;
        transition: background .8s 1.6s, box-shadow .8s 1.6s;
    }

    .wm-show.open .wm-scan::after { background: #f0d9a6; box-shadow: 0 0 16px #f0d9a6; }

    .wm-sbeam {
        position: absolute;
        top: 100%;
        left: 50%;
        width: 900px;
        height: 100vh;
        transform: translateX(-50%);
        clip-path: polygon(47% 0, 53% 0, 100% 100%, 0 100%);
        background: linear-gradient(rgba(240, 217, 166, .24), rgba(240, 217, 166, .02));
        opacity: 0;
        transition: opacity 1.2s 1.6s;
    }

    .wm-show.open .wm-sbeam { opacity: 1; }

    .wm-act {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: grid;
        place-content: safe center;
        justify-items: center;
        padding: 140px 24px 110px;
        overflow-y: auto;
        opacity: 0;
        visibility: hidden;
        transition: opacity .8s, visibility .8s;
    }

    .wm-act.on { opacity: 1; visibility: visible; }

    .wm-act small { margin-bottom: 22px; font-size: .85rem; color: var(--gold); }

    .wm-intro h1 {
        margin: 0 0 12px;
        font-family: 'Amiri', serif;
        font-size: 4.4rem;
        font-weight: 400;
        color: #f3ecda;
        text-shadow: 0 0 30px rgba(240, 217, 166, .35);
    }

    .wm-intro em { font-family: 'Cormorant Garamond', serif; font-size: 1.3rem; color: var(--mute); }

    .wm-intro p { max-width: 640px; margin: 20px 0 0; line-height: 2; color: #a89f8c; }

    .wm-words {
        display: block;
        max-width: 900px;
        font-family: 'Amiri', serif;
        font-size: 2.7rem;
        line-height: 1.8;
        color: #f3ecda;
        text-shadow: 0 0 24px rgba(240, 217, 166, .35);
        quotes: none;
    }

    .wm-words.en {
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
        font-size: 2.9rem;
        line-height: 1.45;
    }

    .wm-words span {
        display: inline-block;
        opacity: 0;
        filter: blur(6px);
        transform: translateY(10px);
        transition: opacity .7s, filter .7s, transform .7s;
    }

    .wm-act.on .wm-words span { opacity: 1; filter: none; transform: none; }

    .wm-act cite,
    .wm-mean {
        opacity: 0;
        transition: opacity 1s;
    }

    .wm-act.on cite,
    .wm-act.on .wm-mean { opacity: 1; }

    .wm-act cite { display: block; margin-top: 22px; font-style: normal; color: var(--gold); }

    .wm-act cite span { color: var(--mute); }

    .wm-mean {
        max-width: 620px;
        margin-top: 34px;
        padding-top: 22px;
        border-top: 1px solid #3a3226;
    }

    .wm-mean b { display: block; margin-bottom: 8px; font-size: .85rem; font-weight: 500; color: var(--gold); }

    .wm-mean p { margin: 0; line-height: 2; color: #cfc4ad; }

    .wm-outro { z-index: 5; }

    .wm-outro h2 {
        margin: 0 0 8px;
        font-family: 'Amiri', serif;
        font-size: 3.4rem;
        font-weight: 400;
        color: #f3ecda;
    }

    .wm-outro p { margin: 0 0 30px; color: #e3c9c0; }

    .wm-outro div { display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; }

    .wm-outro .wm-btn { background: rgba(12, 10, 8, .7); }

    .wm-outro .wm-btn.gold { background: var(--gold); }

    .wm-controls {
        position: absolute;
        bottom: 22px;
        left: 0;
        right: 0;
        z-index: 6;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 10px;
        padding: 0 16px;
    }

    .wm-controls span { min-width: 60px; font-size: .85rem; color: var(--mute); }

    .wm-controls .wm-btn { padding: 8px 16px; background: rgba(12, 10, 8, .7); }

    .wm-show.ended .wm-controls { display: none; }

    .wm-exit {
        position: absolute;
        top: 16px;
        left: 22px;
        z-index: 6;
        font-size: 1.3rem;
        color: var(--mute);
    }

    .wm-exit:hover { color: var(--gold); }

    @media (max-width: 600px) {
        .wm-intro h1 { font-size: 3rem; }

        .wm-words { font-size: 1.8rem; }

        .wm-words.en { font-size: 2rem; }

        .wm-act { padding: 110px 18px 120px; }

        #wm-full { display: none; }
    }
</style>

<div class="wm-show" dir="rtl">
    <div class="wm-drape l"></div>
    <div class="wm-drape r"></div>

    <div class="wm-slamp">
        <div class="wm-scan"><span class="wm-sbeam"></span></div>
    </div>

    <a href="{{ route('sayer', $name) }}" class="wm-exit" id="wm-exit">✕</a>

    <section class="wm-act wm-intro" data-time="6000">
        <small>عرض الليلة</small>
        <h1 dir="auto">{{ $name }}</h1>
        @if ($bio)
            <em>{{ $bio['role'] }}</em>
            <p>{{ $bio['about'] }}</p>
        @endif
    </section>

    @foreach ($quotes as $quote)
        <section class="wm-act">
            <small>الومضة {{ $loop->iteration }} من {{ $quotes->count() }}</small>
            <q class="wm-words {{ $quote->lang === 'en' ? 'en' : '' }}" dir="{{ $quote->lang === 'en' ? 'ltr' : 'rtl' }}">{{ $quote->content }}</q>
            <cite>
                {{ $name }}
                @if ($quote->source)
                    <span>/ {{ $quote->source }}</span>
                @endif
            </cite>
            @if ($quote->meaning)
                <div class="wm-mean">
                    <b>المعنى</b>
                    <p>{{ $quote->meaning }}</p>
                </div>
            @endif
        </section>
    @endforeach

    <section class="wm-act wm-outro">
        <h2>انتهى العرض</h2>
        <p>شكراً لحضوركم</p>
        <div>
            <button type="button" class="wm-btn gold" id="wm-again">أعد العرض</button>
            <a href="{{ route('sayer', $name) }}" class="wm-btn">ارجع لصفحة {{ $name }}</a>
        </div>
    </section>

    <div class="wm-controls">
        <button type="button" class="wm-btn" id="wm-prev">السابق</button>
        <button type="button" class="wm-btn" id="wm-pause">إيقاف مؤقت</button>
        <button type="button" class="wm-btn" id="wm-next">التالي</button>
        <span id="wm-step"></span>
        <button type="button" class="wm-btn" id="wm-full">ملء الشاشة</button>
    </div>
</div>

<script>
    const show = document.querySelector('.wm-show');
    const acts = [...document.querySelectorAll('.wm-act')];
    const step = document.getElementById('wm-step');
    const pauseBtn = document.getElementById('wm-pause');
    const last = acts.length - 1;
    let current = -1;
    let timer = null;
    let playing = true;

    acts.forEach(act => {
        const words = act.querySelector('.wm-words');
        if (!words) return;

        const parts = ('“' + words.textContent.trim() + '”').split(' ');
        words.textContent = '';

        parts.forEach((part, i) => {
            const span = document.createElement('span');
            span.textContent = part;
            span.style.transitionDelay = (i * 0.22) + 's';
            words.append(span, ' ');
        });

        const after = parts.length * 0.22 + 0.4;
        act.querySelector('cite').style.transitionDelay = after + 's';

        const meaning = act.querySelector('.wm-mean');
        let time = (after + 3) * 1000;

        if (meaning) {
            meaning.style.transitionDelay = (after + 1) + 's';
            time += 1000 + meaning.textContent.trim().length * 55;
        }

        act.dataset.time = Math.round(time);
    });

    function play(index) {
        clearTimeout(timer);
        current = index;

        acts.forEach((act, i) => act.classList.toggle('on', i === index));
        show.classList.toggle('open', index !== last);
        show.classList.toggle('ended', index === last);
        step.textContent = (index + 1) + ' / ' + last;

        if (playing && index !== last) {
            timer = setTimeout(() => play(index + 1), Number(acts[index].dataset.time));
        }
    }

    function start() {
        playing = true;
        pauseBtn.textContent = 'إيقاف مؤقت';
        acts.forEach(act => act.classList.remove('on'));
        show.classList.remove('ended');
        setTimeout(() => show.classList.add('open'), 400);
        timer = setTimeout(() => play(0), 2200);
    }

    document.getElementById('wm-next').addEventListener('click', () => play(Math.min(current + 1, last)));
    document.getElementById('wm-prev').addEventListener('click', () => play(Math.max(current - 1, 0)));
    document.getElementById('wm-again').addEventListener('click', start);

    pauseBtn.addEventListener('click', () => {
        playing = !playing;
        pauseBtn.textContent = playing ? 'إيقاف مؤقت' : 'تشغيل';
        play(Math.max(current, 0));
    });

    document.getElementById('wm-full').addEventListener('click', () => {
        if (document.fullscreenElement) {
            document.exitFullscreen();
        } else if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
        }
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowLeft' || e.key === ' ') {
            e.preventDefault();
            play(Math.min(current + 1, last));
        }

        if (e.key === 'ArrowRight') {
            play(Math.max(current - 1, 0));
        }

        if (e.key === 'Escape' && !document.fullscreenElement) {
            location.href = document.getElementById('wm-exit').href;
        }
    });

    start();
</script>
@endsection
