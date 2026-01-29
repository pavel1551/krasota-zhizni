<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'hall_id'   => ['required', 'integer', 'exists:halls,id'],
            'start_at'  => ['required', 'date', 'after:now'],
            'end_at'    => ['required', 'date', 'after:start_at'],

            // equipment — массив вида equipment[ID] = qty
            'equipment' => ['nullable', 'array'],
            'equipment.*' => ['nullable', 'integer', 'min:0', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'start_at.after' => 'Дата/время начала должны быть в будущем.',
            'end_at.after'   => 'Время окончания должно быть позже начала.',
        ];
    }
}
