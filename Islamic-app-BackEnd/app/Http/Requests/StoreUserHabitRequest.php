<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserHabitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'       => ['required', 'exists:users,id'],
            // إما habit_id من القوالب أو اسم جديد
            'habit_id'      => ['nullable', 'exists:habits,id', 'required_without:name'],
            'name'          => ['nullable', 'string', 'max:255', 'required_without:habit_id'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'start_date'    => ['nullable', 'date'],
        ];
    }
}
