@extends('Layout.app')

@section('title', 'تسجيل الدخول')

@section('content')

<div class="wm-auth" dir="rtl">
    <div class="wm-cone"></div>
    <div class="wm-panel">
        <h1>مرحباً بعودتك</h1>
        <p class="sub">سجّل دخولك لتكمل إضاءة ومضاتك.</p>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="wm-field">
                <label for="email">البريد الإلكتروني</label>
                <input class="wm-input" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                <p class="wm-hint">البريد اللي سجّلت فيه</p>
                @error('email') <p class="wm-err">{{ $message }}</p> @enderror
            </div>

            <div class="wm-field">
                <label for="password">كلمة المرور</label>
                <input class="wm-input" id="password" type="password" name="password" minlength="8" required autocomplete="current-password">
                <p class="wm-hint">8 أحرف على الأقل</p>
                @error('password') <p class="wm-err">{{ $message }}</p> @enderror
            </div>

            <button class="wm-btn gold full" type="submit">تسجيل الدخول</button>
        </form>

        <p class="wm-foot">ليس لديك حساب؟ <a href="{{ route('signup') }}">أنشئ حساباً</a></p>
    </div>
</div>
@endsection