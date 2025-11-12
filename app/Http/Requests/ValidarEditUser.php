<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ValidarEditUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('usuarios.editar');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id;
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'password' => 'nullable|string|min:8|confirmed'
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
            'email.unique' => 'Ya existe otro usuario con este email.',
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
        // Para edición, limpiamos espacios y normalizamos los datos
        $data = [];
        
        if ($this->has('name')) {
            $data['name'] = trim($this->name);
        }
        
        if ($this->has('email')) {
            $data['email'] = strtolower(trim($this->email));
        }
        
        $this->merge($data);
    }
}
