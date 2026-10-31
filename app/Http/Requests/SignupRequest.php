<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SignupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guest();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:50', 'regex:/^[\pL\pM\s]+$/u'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اكتب اسم المستخدم.',
            'name.min' => 'اسم المستخدم حرفين على الأقل.',
            'name.max' => 'اسم المستخدم لا يتجاوز 50 حرفاً.',
            'name.regex' => 'اسم المستخدم حروف فقط، بدون أرقام أو رموز.',
            'email.required' => 'اكتب البريد الإلكتروني.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.max' => 'البريد الإلكتروني طويل جداً.',
            'email.unique' => 'هذا البريد مسجّل مسبقاً.',
            'password.required' => 'اكتب كلمة المرور.',
            'password.min' => 'كلمة المرور 8 أحرف على الأقل.',
        ];
    }
}
