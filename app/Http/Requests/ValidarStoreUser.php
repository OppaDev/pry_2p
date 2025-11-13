<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\ValidacionService;

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
            'cedula' => [
                'required',
                'string',
                'digits:10',
                'unique:users,cedula',
                function ($attribute, $value, $fail) {
                    // Validación de cédula ecuatoriana
                    if (!ValidacionService::validarCedulaEcuatoriana($value)) {
                        $fail('La cédula ingresada no es válida.');
                    }
                },
            ],
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
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.digits' => 'La cédula debe tener exactamente 10 dígitos.',
            'cedula.unique' => 'Ya existe un usuario con esta cédula.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre del usuario',
            'email' => 'email del usuario',
            'cedula' => 'cédula',
            'password' => 'contraseña'
        ];
    }

    protected function prepareForValidation(): void
    {
        $data = [];
        
        if ($this->has('name')) {
            $data['name'] = trim($this->name);
        }
        
        if ($this->has('email')) {
            $data['email'] = strtolower(trim($this->email));
        }
        
        if ($this->has('cedula')) {
            // Limpiar cédula: solo números
            $data['cedula'] = preg_replace('/[^0-9]/', '', $this->cedula);
        }
        
        $this->merge($data);
    }
}
