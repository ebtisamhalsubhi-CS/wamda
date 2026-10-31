<?php

namespace App\Http\Requests;

use App\Models\Quote;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class QuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'min:3', 'max:155'],
            'author' => ['nullable', 'max:60'],
            'source' => ['nullable', 'max:100'],
            'meaning' => ['nullable', 'max:300'],
            'category' => ['required', Rule::in(array_keys(Quote::CATEGORIES))],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'اكتب نص الومضة.',
            'content.min' => 'الومضة 3 أحرف على الأقل.',
            'content.max' => 'الومضة لا تتجاوز 155 حرفاً.',
            'author.max' => 'اسم القائل لا يتجاوز 60 حرفاً.',
            'source.max' => 'المصدر لا يتجاوز 100 حرف.',
            'meaning.max' => 'المعنى لا يتجاوز 300 حرف.',
            'category.required' => 'اختر التصنيف.',
            'category.in' => 'اختر تصنيفاً من القائمة.',
        ];
    }
}
