<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ValidarStoreUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('usuarios.crear');
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder los 255 caracteres.',
            'email.required' => 'El email es obligatorio.',
            'email.string' => 'El email debe ser una cadena de texto.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.max' => 'El email no puede exceder los 255 caracteres.',
            'email.unique' => 'Ya existe un usuario con este email.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nombre del usuario',
            'email' => 'Email del usuario',
            'password' => 'Contraseña'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
            'email' => strtolower(trim($this->email))
        ]);
    }
}
