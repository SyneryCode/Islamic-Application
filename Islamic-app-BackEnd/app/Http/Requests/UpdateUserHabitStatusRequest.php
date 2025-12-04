<?php

namespace App\Http\Requests;

use App\Enums\HabitStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserHabitStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'status'  => [
                'required',
                'string',
                Rule::in([
                    HabitStatus::IN_PROGRESS->value,
                    HabitStatus::COMPLETED->value,
                ]),
            ],
        ];
    }
}
