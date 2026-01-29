<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefundBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // Можно добавить причину возврата (для диплома)
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
