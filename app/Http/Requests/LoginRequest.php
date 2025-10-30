<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'max:255',
                Rule::email(),
            ],
            'password' => [
                'required',
                'string',
                'max:255',
                Password::min(8),
            ],
        ];
    }

    public function findUser(): ?User
    {
        if (! $this->filled('email')) {
            return null;
        }

        return User::query()->where('email', $this->input('email'))->first();
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $user = $this->findUser();

            /** @var string $password */
            $password = $this->input('password');

            if ($this->filled('email') && blank($user)) {
                $validator->errors()->add('email', __('auth.failed'));
            }

            if ($user) {
                /** @var string $hashPassword */
                $hashPassword = $user->password;

                if (! Hash::check($password, $hashPassword)) {
                    $validator->errors()->add('password', __('auth.password'));
                }
            }
        });
    }
}
