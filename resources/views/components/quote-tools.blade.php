@props(['books' => collect()])

@php
    $editing = old('editing');
    $giftQuote = old('gift_quote') ? \App\Models\Quote::find(old('gift_quote')) : null;
@endphp

<style>
    .wm-modal {
        position: fixed;
        inset: 0;
        z-index: 60;
        display: none;
        grid-template-columns: minmax(0, 1fr);
        place-items: center;
        padding: 20px;
        background: rgba(0, 0, 0, .8);
    }

    .wm-modal.open { display: grid; }

    .wm-curtain {
        position: relative;
        width: 540px;
        max-width: 100%;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
        padding: 36px;
        border: 1px solid #3a2a22;
        border-radius: 4px;
        background: linear-gradient(90deg, #3a0f14, #0f0c09 18%, #0f0c09 82%, #3a0f14);
    }

    .wm-curtain h3 {
        margin: 0 0 20px;
        font-family: 'Amiri', serif;
        font-size: 1.6rem;
        font-weight: 400;
    }

    .wm-curtain textarea { min-height: 110px; resize: vertical; }

    .wm-close {
        position: absolute;
        top: 14px;
        left: 16px;
        border: 0;
        background: transparent;
        font-size: 1.3rem;
        color: var(--mute);
    }

    .wm-storybox { width: 420px; text-align: center; }

    .wm-storybox img {
        display: block;
        height: 50vh;
        max-width: 100%;
        margin: 0 auto 20px;
        border: 1px solid var(--line);
        border-radius: 4px;
    }

    .wm-storylink { display: flex; gap: 8px; margin-bottom: 10px; }

    .wm-storylink .wm-input { flex: 1; min-width: 0; padding: 9px 12px; font-size: .8rem; }

    .wm-storyhint { margin: 0 0 16px; font-size: .8rem; line-height: 1.8; color: var(--mute); text-align: right; }

    .wm-storyhint.warn { color: #d98a7c; }

    .wm-preview {
        margin: 0 0 22px;
        padding: 14px 16px;
        border-right: 2px solid var(--gold);
        background: rgba(194, 160, 104, .06);
        font-family: 'Amiri', serif;
        font-size: 1.15rem;
        line-height: 1.9;
    }
</style>

@auth
<div id="wm-modal" class="wm-modal {{ $errors->any() ? 'open' : '' }}" dir="rtl">
    <div class="wm-curtain">
        <button type="button" class="wm-close">✕</button>
        <h3 id="wm-title">{{ $editing ? 'عدّل ومضتك' : 'أضف ومضتك' }}</h3>

        <form id="wm-form" method="POST" action="{{ $editing ? route('quotes.update', $editing) : route('quotes.store') }}">
            @csrf
            <input type="hidden" name="_method" id="wm-method" value="{{ $editing ? 'PUT' : 'POST' }}">
            <input type="hidden" name="editing" id="wm-editing" value="{{ $editing }}">

            <div class="wm-field">
                <label for="wm-content">النص</label>
                <textarea class="wm-input" id="wm-content" name="content" minlength="3" maxlength="155" required>{{ old('content') }}</textarea>
                <p class="wm-hint"><span>من 3 إلى 155 حرفاً</span><span id="wm-content-count">0 / 155</span></p>
                @error('content') <p class="wm-err">{{ $message }}</p> @enderror
            </div>

            <div class="wm-field">
                <label for="wm-author">القائل (اختياري)</label>
                <input class="wm-input" id="wm-author" name="author" maxlength="60" value="{{ old('author') }}">
                <p class="wm-hint">حتى 60 حرفاً. إذا تركته فارغاً يظهر اسمك</p>
                @error('author') <p class="wm-err">{{ $message }}</p> @enderror
            </div>

            <div class="wm-field">
                <label for="wm-source">المصدر (اختياري)</label>
                <input class="wm-input" id="wm-source" name="source" maxlength="100" value="{{ old('source') }}">
                <p class="wm-hint">كتاب أو قصيدة أو مسرحية، حتى 100 حرف</p>
                @error('source') <p class="wm-err">{{ $message }}</p> @enderror
            </div>

            <div class="wm-field">
                <label for="wm-meaning">المعنى (اختياري)</label>
                <textarea class="wm-input" id="wm-meaning" name="meaning" maxlength="300">{{ old('meaning') }}</textarea>
                <p class="wm-hint"><span>اشرح معنى الومضة بكلماتك، حتى 300 حرف</span><span id="wm-meaning-count">0 / 300</span></p>
                @error('meaning') <p class="wm-err">{{ $message }}</p> @enderror
            </div>

            <div class="wm-field">
                <label for="wm-category">التصنيف</label>
                <select class="wm-input" id="wm-category" name="category">
                    @foreach (\App\Models\Quote::CATEGORIES as $key => $label)
                        <option value="{{ $key }}" @selected(old('category', 'literature') == $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="wm-hint">اختر نوع الومضة</p>
                @error('category') <p class="wm-err">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="wm-btn gold full">إضاءة الومضة</button>
        </form>
    </div>
</div>

<div id="wm-gift" class="wm-modal {{ $errors->gift->any() ? 'open' : '' }}" dir="rtl">
    <div class="wm-curtain">
        <button type="button" class="wm-close">✕</button>
        <h3>أهدِ هذه الومضة</h3>
        <p class="wm-preview" id="wm-gift-text">@if ($giftQuote)“{{ $giftQuote->content }}”@endif</p>

        <form id="wm-gift-form" method="POST" action="{{ $giftQuote ? route('gifts.store', $giftQuote) : '' }}">
            @csrf
            <input type="hidden" name="gift_quote" id="wm-gift-quote" value="{{ $giftQuote?->id }}">

            <div class="wm-field">
                <label for="wm-to">إلى</label>
                <input class="wm-input" id="wm-to" name="to_name" minlength="2" maxlength="50" required value="{{ old('to_name') }}">
                <p class="wm-hint">اسم من تهديه، من 2 إلى 50 حرفاً</p>
                @error('to_name', 'gift') <p class="wm-err">{{ $message }}</p> @enderror
            </div>

            <div class="wm-field">
                <label for="wm-from">من</label>
                <input class="wm-input" id="wm-from" name="from_name" minlength="2" maxlength="50" required value="{{ old('from_name', auth()->user()->name) }}">
                <p class="wm-hint">اسمك كما سيظهر في الإهداء</p>
                @error('from_name', 'gift') <p class="wm-err">{{ $message }}</p> @enderror
            </div>

            <div class="wm-field">
                <label for="wm-msg">رسالة (اختيارية)</label>
                <textarea class="wm-input" id="wm-msg" name="message" maxlength="150">{{ old('message') }}</textarea>
                <p class="wm-hint"><span>كلمة قصيرة تظهر تحت الومضة، حتى 150 حرفاً</span><span id="wm-msg-count">0 / 150</span></p>
                @error('message', 'gift') <p class="wm-err">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="wm-btn gold full">جهّز الهدية</button>
        </form>
    </div>
</div>

<div id="wm-save" class="wm-modal" dir="rtl">
    <div class="wm-curtain">
        <button type="button" class="wm-close">✕</button>
        <h3>احفظ في رفّي</h3>

        @if ($books->isEmpty())
            <p class="wm-empty">رفّك فاضي. أضف أول كتاب قرأته، وبعدها احفظ فيه الومضات.</p>
            <a href="{{ route('shelf') }}" class="wm-btn gold full">اذهب إلى رفّي</a>
        @else
            <form id="wm-save-form" method="POST" action="">
                @csrf
                <div class="wm-field">
                    <label for="wm-book">الكتاب</label>
                    <select class="wm-input" id="wm-book" name="book_id">
                        @foreach ($books as $b)
                            <option value="{{ $b->id }}">{{ $b->title }}</option>
                        @endforeach
                    </select>
                    <p class="wm-hint">اختر الكتاب اللي تبي تحفظ الومضة تحته</p>
                </div>
                <button type="submit" class="wm-btn gold full">احفظ</button>
            </form>
        @endif
    </div>
</div>
@endauth

<div id="wm-story" class="wm-modal" dir="rtl">
    <div class="wm-curtain wm-storybox">
        <button type="button" class="wm-close">✕</button>
        <h3>بطاقة ستوري</h3>
        <img id="wm-story-img" alt="بطاقة الومضة">
        <div class="wm-storylink">
            <input class="wm-input" id="wm-story-url" readonly dir="ltr">
            <button type="button" class="wm-btn" id="wm-story-copy">نسخ الرابط</button>
        </div>
        <p class="wm-storyhint">في الإنستقرام أضف ملصق «الرابط» 🔗 والصق الرابط، وكل من يضغط عليه يدخل على الومضة في ومضة.</p>
        <p class="wm-storyhint warn" id="wm-story-local" hidden>أنت فاتح الموقع من localhost، فالرابط ما يفتح عند غيرك. افتح الموقع من رابطه على الإنترنت ثم سوّ الستوري.</p>
        <button type="button" class="wm-btn gold full" id="wm-story-save">حفظ الصورة</button>
    </div>
</div>

<script>
    document.querySelectorAll('[data-copy]').forEach(btn => {
        btn.addEventListener('click', () => {
            navigator.clipboard.writeText(btn.dataset.copy);
            btn.textContent = 'تم النسخ';
            setTimeout(() => btn.textContent = 'نسخ', 1500);
        });
    });

    document.querySelectorAll('.wm-light').forEach(form => {
        form.addEventListener('submit', e => {
            e.preventDefault();

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'Accept': 'application/json' },
            })
                .then(res => res.json())
                .then(data => {
                    form.querySelector('span').textContent = data.count;
                    form.closest('.wm-card').classList.toggle('lit', data.lit);
                });
        });
    });

    document.querySelectorAll('[data-explain]').forEach(btn => {
        btn.addEventListener('click', () => {
            const meaning = btn.closest('.wm-card').querySelector('.wm-meaning');
            meaning.hidden = !meaning.hidden;
            btn.classList.toggle('on', !meaning.hidden);
        });
    });

    const quoteBox = document.getElementById('wm-modal');
    const giftBox = document.getElementById('wm-gift');
    const saveBox = document.getElementById('wm-save');

    function openBox(box) {
        box.querySelectorAll('.wm-err').forEach(err => err.remove());
        box.classList.add('open');
    }

    function closeBoxes() {
        document.querySelectorAll('.wm-modal').forEach(box => box.classList.remove('open'));
    }

    document.querySelectorAll('.wm-close').forEach(btn => btn.addEventListener('click', closeBoxes));
    document.querySelectorAll('.wm-modal').forEach(box => {
        box.addEventListener('click', e => {
            if (e.target === box) closeBoxes();
        });
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeBoxes();
    });

    const storyBox = document.getElementById('wm-story');
    const storyImg = document.getElementById('wm-story-img');
    const storyUrl = document.getElementById('wm-story-url');
    const storyCopy = document.getElementById('wm-story-copy');
    const storySave = document.getElementById('wm-story-save');
    const phone = matchMedia('(pointer: coarse)').matches;
    let storyFile = null;

    function wrapLines(ctx, text, width) {
        const lines = [];
        let line = '';

        text.split(' ').forEach(word => {
            const test = line ? line + ' ' + word : word;

            if (line && ctx.measureText(test).width > width) {
                lines.push(line);
                line = word;
            } else {
                line = test;
            }
        });

        lines.push(line);
        return lines;
    }

    async function drawStory(data) {
        const en = data.lang === 'en';
        const quoteFont = en ? 'italic 500 66px "Cormorant Garamond"' : '400 62px Amiri';

        await Promise.all([
            document.fonts.load(quoteFont),
            document.fonts.load('700 120px Amiri'),
            document.fonts.load('500 40px "IBM Plex Sans Arabic"'),
        ]).catch(() => {});

        const canvas = document.createElement('canvas');
        canvas.width = 1080;
        canvas.height = 1920;
        const ctx = canvas.getContext('2d');

        ctx.fillStyle = '#0c0a08';
        ctx.fillRect(0, 0, 1080, 1920);

        const beam = ctx.createLinearGradient(0, 260, 0, 1500);
        beam.addColorStop(0, 'rgba(240, 217, 166, .26)');
        beam.addColorStop(1, 'rgba(240, 217, 166, 0)');
        ctx.fillStyle = beam;
        ctx.beginPath();
        ctx.moveTo(515, 262);
        ctx.lineTo(565, 262);
        ctx.lineTo(1010, 1500);
        ctx.lineTo(70, 1500);
        ctx.fill();

        const stripes = ['#3a0f14', '#5a1820', '#2f0b10'];
        for (let i = 0; i < 7; i++) {
            ctx.fillStyle = stripes[i % 3];
            ctx.fillRect(i * 18, 0, 18, 1920);
            ctx.fillRect(1080 - (i + 1) * 18, 0, 18, 1920);
        }

        ctx.strokeStyle = '#6d6354';
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.moveTo(540, 0);
        ctx.lineTo(540, 190);
        ctx.stroke();

        ctx.fillStyle = '#0a0806';
        ctx.lineWidth = 4;
        ctx.beginPath();
        ctx.roundRect(512, 190, 56, 68, 8);
        ctx.fill();
        ctx.stroke();

        ctx.shadowColor = '#f0d9a6';
        ctx.shadowBlur = 40;
        ctx.fillStyle = '#f0d9a6';
        ctx.beginPath();
        ctx.ellipse(540, 260, 26, 9, 0, 0, Math.PI * 2);
        ctx.fill();
        ctx.shadowBlur = 0;

        ctx.textAlign = 'center';
        ctx.direction = 'rtl';
        ctx.fillStyle = '#c2a068';
        ctx.font = '400 34px "IBM Plex Sans Arabic"';
        ctx.fillText(data.tag, 540, 560);

        ctx.direction = en ? 'ltr' : 'rtl';
        ctx.font = quoteFont;
        const lines = wrapLines(ctx, '“' + data.text + '”', 820);
        const lineHeight = en ? 92 : 112;
        let y = 980 - (lines.length - 1) * lineHeight / 2;

        ctx.fillStyle = '#f3ecda';
        ctx.shadowColor = 'rgba(240, 217, 166, .35)';
        ctx.shadowBlur = 24;
        lines.forEach(line => {
            ctx.fillText(line, 540, y);
            y += lineHeight;
        });
        ctx.shadowBlur = 0;

        ctx.direction = 'rtl';
        ctx.font = '500 40px "IBM Plex Sans Arabic"';
        ctx.fillStyle = '#c2a068';
        ctx.fillText(data.who, 540, y + 30);

        if (data.source) {
            ctx.font = '300 32px "IBM Plex Sans Arabic"';
            ctx.fillStyle = '#8d8372';
            ctx.fillText(data.source, 540, y + 90);
        }

        ctx.font = '700 120px Amiri';
        ctx.fillStyle = '#ece3cf';
        ctx.fillText('ومضة', 540, 1760);

        ctx.font = '300 30px "IBM Plex Sans Arabic"';
        ctx.fillStyle = '#8d8372';
        ctx.fillText('مكتبة المقولات على المسرح', 540, 1820);

        return new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
    }

    document.querySelectorAll('[data-story]').forEach(btn => {
        btn.addEventListener('click', async () => {
            const blob = await drawStory(btn.dataset);

            if (storyImg.src) URL.revokeObjectURL(storyImg.src);
            storyFile = new File([blob], 'wamda-story.png', { type: 'image/png' });
            storyImg.src = URL.createObjectURL(blob);
            storyUrl.value = btn.dataset.url;
            storyCopy.textContent = 'نسخ الرابط';
            storySave.textContent = phone ? 'شارك في الستوري' : 'حفظ الصورة';
            document.getElementById('wm-story-local').hidden = !['localhost', '127.0.0.1'].includes(location.hostname);
            openBox(storyBox);
        });
    });

    function copyStoryLink() {
        navigator.clipboard.writeText(storyUrl.value).catch(() => {});
        storyCopy.textContent = 'تم النسخ';
    }

    storyCopy.addEventListener('click', copyStoryLink);

    storySave.addEventListener('click', () => {
        copyStoryLink();

        if (phone && navigator.canShare && navigator.canShare({ files: [storyFile] })) {
            navigator.share({ files: [storyFile] }).catch(() => {});
            return;
        }

        const link = document.createElement('a');
        link.href = storyImg.src;
        link.download = storyFile.name;
        link.click();
    });

    if (quoteBox) {
        const content = document.getElementById('wm-content');
        const meaning = document.getElementById('wm-meaning');
        const message = document.getElementById('wm-msg');

        function updateCounters() {
            document.getElementById('wm-content-count').textContent = content.value.length + ' / 155';
            document.getElementById('wm-meaning-count').textContent = meaning.value.length + ' / 300';
            document.getElementById('wm-msg-count').textContent = message.value.length + ' / 150';
        }

        content.addEventListener('input', updateCounters);
        meaning.addEventListener('input', updateCounters);
        message.addEventListener('input', updateCounters);
        updateCounters();

        function openQuote(title, action, method, data) {
            document.getElementById('wm-title').textContent = title;
            document.getElementById('wm-form').action = action;
            document.getElementById('wm-method').value = method;
            document.getElementById('wm-editing').value = data.edit || '';
            content.value = data.content || '';
            document.getElementById('wm-author').value = data.author || '';
            document.getElementById('wm-source').value = data.source || '';
            meaning.value = data.meaning || '';
            document.getElementById('wm-category').value = data.category || 'literature';
            updateCounters();
            openBox(quoteBox);
            content.focus();
        }

        document.querySelectorAll('[data-open]').forEach(btn => {
            btn.addEventListener('click', () => openQuote('أضف ومضتك', '{{ route('quotes.store') }}', 'POST', {}));
        });

        document.querySelectorAll('[data-edit]').forEach(btn => {
            btn.addEventListener('click', () => openQuote('عدّل ومضتك', btn.dataset.action, 'PUT', btn.dataset));
        });

        document.querySelectorAll('[data-gift]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('wm-gift-form').action = btn.dataset.gift;
                document.getElementById('wm-gift-quote').value = btn.dataset.id;
                document.getElementById('wm-gift-text').textContent = '“' + btn.dataset.text + '”';
                document.getElementById('wm-to').value = '';
                message.value = '';
                updateCounters();
                openBox(giftBox);
                document.getElementById('wm-to').focus();
            });
        });

        document.querySelectorAll('[data-save]').forEach(btn => {
            btn.addEventListener('click', () => {
                const saveForm = document.getElementById('wm-save-form');
                if (saveForm) saveForm.action = btn.dataset.save;
                openBox(saveBox);
            });
        });
    }
</script>
