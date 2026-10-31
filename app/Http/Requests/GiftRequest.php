<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GiftRequest extends FormRequest
{
    protected $errorBag = 'gift';

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'to_name' => ['required', 'min:2', 'max:50'],
            'from_name' => ['required', 'min:2', 'max:50'],
            'message' => ['nullable', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'to_name.required' => 'اكتب اسم المهدى إليه.',
            'to_name.min' => 'الاسم حرفين على الأقل.',
            'to_name.max' => 'الاسم لا يتجاوز 50 حرفاً.',
            'from_name.required' => 'اكتب اسمك.',
            'from_name.min' => 'الاسم حرفين على الأقل.',
            'from_name.max' => 'الاسم لا يتجاوز 50 حرفاً.',
            'message.max' => 'الرسالة لا تتجاوز 150 حرفاً.',
        ];
    }
}