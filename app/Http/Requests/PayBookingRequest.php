<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // Заглушка: имя держателя и последние 4 цифры
            'card_holder' => ['nullable', 'string', 'max:100'],
            'card_last4'  => ['nullable', 'digits:4'],

            // outcome: success / failed / timeout
            'outcome'     => ['required', 'in:success,failed,timeout'],
        ];
    }
}
