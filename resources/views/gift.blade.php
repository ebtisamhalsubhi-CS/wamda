@extends('Layout.app')

@section('title', 'ومضة مهداة إلى ' . $gift->to_name)

@section('content')

<style>
    .wm-giftshare {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        margin: 30px 0 0;
        padding: 24px 26px;
        border: 1px solid #3a3226;
        border-radius: 4px;
        background: #0f0c09;
    }

    .wm-giftshare h3 {
        margin: 0 0 4px;
        font-family: 'Amiri', serif;
        font-size: 1.5rem;
        font-weight: 400;
    }

    .wm-giftshare p { margin: 0; font-size: .86rem; color: var(--mute); }

    .wm-linkrow { display: flex; flex-wrap: wrap; gap: 10px; }

    .wm-linkrow .wm-input {
        width: 300px;
        max-width: 100%;
        padding: 9px 12px;
        font-size: .8rem;
    }

    .wm-giftstage {
        position: relative;
        display: grid;
        place-items: center;
        min-height: calc(100vh - 115px);
        overflow: hidden;
        padding: 150px 20px 80px;
        text-align: center;
    }

    .wm-cur {
        position: absolute;
        top: 0;
        bottom: 0;
        z-index: 3;
        width: 51%;
        background: repeating-linear-gradient(90deg, #3a0f14 0, #5a1820 18px, #2f0b10 36px);
        animation: 2.4s ease-in-out .6s forwards;
    }

    .wm-cur.r { right: 0; animation-name: openR; }

    .wm-cur.l { left: 0; animation-name: openL; }

    .wm-glamp {
        position: absolute;
        top: 0;
        left: 50%;
        width: 26px;
        margin-left: -13px;
    }

    .wm-glamp::before {
        content: "";
        display: block;
        width: 2px;
        height: 70px;
        margin: 0 auto;
        background: #6d6354;
    }

    .wm-gcan {
        position: relative;
        height: 36px;
        border: 2px solid #6d6354;
        border-radius: 5px;
        background: #0a0806;
    }

    .wm-gcan::after {
        content: "";
        position: absolute;
        bottom: -5px;
        left: 0;
        right: 0;
        height: 8px;
        border-radius: 50%;
        background: #3a3226;
        animation: glow .8s ease 2.8s forwards;
    }

    .wm-gbeam {
        position: absolute;
        top: 100%;
        left: 50%;
        width: 760px;
        height: 680px;
        transform: translateX(-50%);
        clip-path: polygon(47% 0, 53% 0, 100% 100%, 0 100%);
        background: linear-gradient(rgba(240, 217, 166, .30), rgba(240, 217, 166, .02));
        opacity: 0;
        animation: show 1.2s ease 2.8s forwards;
    }

    .wm-giftbody { position: relative; max-width: 760px; }

    .wm-giftbody > * { opacity: 0; animation: rise 1s ease forwards; }

    .wm-g-to { margin: 0; color: var(--gold); animation-delay: 3.4s; }

    .wm-g-to b {
        font-family: 'Amiri', serif;
        font-size: 1.7rem;
        font-weight: 700;
        color: #f3ecda;
    }

    .wm-g-quote {
        display: block;
        margin: 28px 0 20px;
        font-family: 'Amiri', serif;
        font-size: 2.6rem;
        line-height: 1.8;
        color: #f3ecda;
        text-shadow: 0 0 24px rgba(240, 217, 166, .35);
        quotes: none;
        animation-delay: 4.2s;
    }

    .wm-g-quote.en { font-family: 'Cormorant Garamond', serif; font-style: italic; }

    .wm-g-by {
        display: block;
        font-style: normal;
        color: var(--mute);
        animation-delay: 5.2s;
    }

    .wm-g-from {
        margin: 36px 0 8px;
        font-family: 'Amiri', serif;
        font-size: 1.5rem;
        animation-delay: 6s;
    }

    .wm-g-msg {
        max-width: 520px;
        margin: 0 auto;
        color: #a89f8c;
        line-height: 1.9;
        animation-delay: 6.6s;
    }

    .wm-g-actions { margin-top: 44px; animation-delay: 7.2s; }

    @keyframes openR {
        to { transform: translateX(100%); }
    }

    @keyframes openL {
        to { transform: translateX(-100%); }
    }

    @keyframes show {
        to { opacity: 1; }
    }

    @keyframes glow {
        to { background: #f0d9a6; box-shadow: 0 0 16px #f0d9a6; }
    }

    @keyframes rise {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: none; }
    }

    @media (max-width: 600px) {
        .wm-g-quote { font-size: 1.9rem; }
    }
</style>

@php
    $quote = $gift->quote;
    $link = route('gifts.show', $gift);
@endphp

<div dir="rtl">
    @if ($isSender)
        <div class="wm-wrap">
            <section class="wm-giftshare">
                <div>
                    <h3>هديتك جاهزة 🎁</h3>
                    <p>أرسل الرابط إلى {{ $gift->to_name }}. وتحت تشوف كيف بتظهر له.</p>
                    <p class="wm-hint">
                        الحالة:
                        @if ($gift->opened_at)
                            فُتحت ✓ في {{ $gift->opened_at->locale('ar')->translatedFormat('j F Y') }}
                        @else
                            لم تُفتح بعد
                        @endif
                    </p>
                </div>
                <div class="wm-linkrow">
                    <input class="wm-input" id="wm-giftlink" value="{{ $link }}" readonly dir="ltr">
                    <button type="button" class="wm-btn" id="wm-copylink">نسخ الرابط</button>
                    <a class="wm-btn gold" target="_blank" rel="noopener" href="https://wa.me/?text={{ rawurlencode('أهديتك ومضة 🎁 ' . $link) }}">أرسلها بالواتساب</a>
                </div>
            </section>
        </div>
    @endif

    <section class="wm-giftstage">
        <div class="wm-cur l"></div>
        <div class="wm-cur r"></div>

        <div class="wm-glamp">
            <div class="wm-gcan"><span class="wm-gbeam"></span></div>
        </div>

        <div class="wm-giftbody">
            <p class="wm-g-to">ومضة مهداة إلى <b>{{ $gift->to_name }}</b></p>

            <q class="wm-g-quote {{ $quote->lang === 'en' ? 'en' : '' }}" dir="{{ $quote->lang === 'en' ? 'ltr' : 'rtl' }}">“{{ $quote->content }}”</q>

            <cite class="wm-g-by">
                {{ $quote->author ?? $quote->user->name }}
                @if ($quote->source)
                    / {{ $quote->source }}
                @endif
            </cite>

            <p class="wm-g-from">من {{ $gift->from_name }}</p>

            @if ($gift->message)
                <p class="wm-g-msg">{{ $gift->message }}</p>
            @endif

            <div class="wm-g-actions">
                <a href="{{ route('home') }}" class="wm-btn">تصفّح مكتبة ومضة</a>
            </div>
        </div>
    </section>
</div>

@if ($isSender)
<script>
    const copyBtn = document.getElementById('wm-copylink');
    copyBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(document.getElementById('wm-giftlink').value).then(() => {
            copyBtn.textContent = 'تم النسخ';
            setTimeout(() => copyBtn.textContent = 'نسخ الرابط', 1500);
        });
    });
</script>
@endif
@endsection