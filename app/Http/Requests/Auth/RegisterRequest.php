<?php

namespace App\Http\Requests\Auth;

use App\Enums\LearnerProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'last_name'    => ['required', 'string', 'max:100'],
            'first_name'   => ['required', 'string', 'max:100'],
            'profile_type' => ['required', Rule::enum(LearnerProfile::class)],
            'intitule'     => ['nullable', 'string', 'max:200'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'        => ['required', 'string', 'max:30'],
            'password'     => ['required', 'confirmed', Password::defaults()],
            'country'      => ['required', 'string', 'max:100'],
            'city'         => ['required', 'string', 'max:100'],
            'birth_date'   => ['nullable', 'date', 'before:today'],
            'gender'       => ['nullable', 'string', 'in:homme,femme,autre'],
        ];
    }
}
