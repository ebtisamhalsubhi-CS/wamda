@extends('Layout.app')

@section('title', 'إنشاء حساب')

@section('content')

<div class="wm-auth" dir="rtl">
    <div class="wm-cone"></div>
    <div class="wm-panel">
        <h1>انضم إلى المسرح</h1>
        <p class="sub">أنشئ حسابك وابدأ بتسليط الضوء على أفكارك.</p>

        <form action="{{ route('signup') }}" method="POST">
            @csrf

            <div class="wm-field">
                <label for="name">اسم المستخدم</label>
                <input class="wm-input" id="name" type="text" name="name" value="{{ old('name') }}" minlength="2" maxlength="50" required autocomplete="name">
                <p class="wm-hint">من 2 إلى 50 حرفاً، حروف فقط</p>
                @error('name') <p class="wm-err">{{ $message }}</p> @enderror
            </div>

            <div class="wm-field">
                <label for="email">البريد الإلكتروني</label>
                <input class="wm-input" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                <p class="wm-hint">مثال: name@example.com</p>
                @error('email') <p class="wm-err">{{ $message }}</p> @enderror
            </div>

            <div class="wm-field">
                <label for="password">كلمة المرور</label>
                <input class="wm-input" id="password" type="password" name="password" minlength="8" required autocomplete="new-password">
                <p class="wm-hint">8 أحرف على الأقل</p>
                @error('password') <p class="wm-err">{{ $message }}</p> @enderror
            </div>

            <button class="wm-btn gold full" type="submit">إنشاء الحساب</button>
        </form>

        <p class="wm-foot">لديك حساب؟ <a href="{{ route('login') }}">سجّل الدخول</a></p>
    </div>
</div>
@endsection
