<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BookRequest extends FormRequest
{
    protected $errorBag = 'book';

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'min:2', 'max:100'],
            'author' => ['nullable', 'max:60'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'اكتب عنوان الكتاب.',
            'title.min' => 'العنوان حرفين على الأقل.',
            'title.max' => 'العنوان لا يتجاوز 100 حرف.',
            'author.max' => 'اسم الكاتب لا يتجاوز 60 حرفاً.',
        ];
    }
}