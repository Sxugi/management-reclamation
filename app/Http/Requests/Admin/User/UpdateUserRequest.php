<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')->user_id;

        return [
            'username' => [
                'required',
                'string',
                'min:3',
                'max: 20',
                Rule::unique('users', 'username')->ignore($userId, 'user_id'),
                'regex:/^[a-z0-9._]+$/',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId, 'user_id'),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s()]*$/',
            ],
            'password' => [
                'nullable',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed',
            ],
            'role' => [
                'required',
                Rule::in(['admin', 'user']),
            ],
            'status' => [
                'required',
                Rule::in(['active', 'inactive', 'suspended']),
            ],
            'avatar' => [
                'nullable',
                'image',
                Rule::imageFile()
                    ->types(['jpeg', 'jpg', 'png'])
                    ->max(2048),
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'username' => 'username',
            'name' => 'nama lengkap',
            'email' => 'email',
            'phone' => 'nomor telepon',
            'password' => 'password',
            'password_confirmation' => 'konfirmasi password',
            'role' => 'role',
            'status' => 'status',
            'avatar' => 'foto profil',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Username
            'username.required' => 'Username wajib diisi.',
            'username.string' => 'Username harus berupa teks.',
            'username. min' => 'Username minimal : min karakter.',
            'username.max' => 'Username maksimal :max karakter.',
            'username.unique' => 'Username sudah digunakan, silakan pilih username lain.',
            'username.regex' => 'Username hanya boleh berisi huruf kecil, angka, titik (.) dan underscore (_).',

            // Name
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.string' => 'Nama lengkap harus berupa teks.',
            'name.max' => 'Nama lengkap maksimal :max karakter.',

            // Email
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal :max karakter.',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain.',

            // Phone
            'phone.string' => 'Nomor telepon harus berupa teks.',
            'phone.max' => 'Nomor telepon maksimal :max karakter.',
            'phone.regex' => 'Format nomor telepon tidak valid.',

            // Password
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal :min karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.letters' => 'Password harus mengandung huruf.',
            'password. mixed_case' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.symbols' => 'Password harus mengandung simbol.',
            'password.uncompromised' => 'Password ini pernah bocor dalam data breach. Silakan gunakan password lain.',

            // Role
            'role.required' => 'Role wajib dipilih.',
            'role. in' => 'Role yang dipilih tidak valid.',

            // Status
            'status.required' => 'Status wajib dipilih.',
            'status. in' => 'Status yang dipilih tidak valid.',

            // Avatar
            'avatar.image' => 'File harus berupa gambar.',
            'avatar. mimes' => 'Format gambar harus jpg, jpeg, atau png.',
            'avatar.max' => 'Ukuran gambar maksimal :max KB (2MB).',
        ];
    }

    /**
     * Prepare the data for validation. 
     */
    protected function prepareForValidation(): void
    {
        // Otomatis lowercase username
        if ($this->has('username')) {
            $this->merge([
                'username' => strtolower($this->username),
            ]);
        }
    }
}