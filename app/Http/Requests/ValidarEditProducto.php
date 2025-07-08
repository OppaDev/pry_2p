<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ValidarEditProducto extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // if(Auth::user() -> email === 'test@example.com')
        // {
        //     return true;
        // }
        // return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productoId = $this->route('producto')->id;

        return [
            'nombre' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('productos', 'nombre')->ignore($productoId)
            ],
            'codigo' => [
                'required',
                'string',
                'max:10',
                Rule::unique('productos', 'codigo')->ignore($productoId)
            ],
            'cantidad' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/'
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.max' => 'El nombre del producto no puede exceder los 50 caracteres.',
            'nombre.string' => 'El nombre del producto debe ser una cadena de texto.',
            'nombre.unique' => 'Ya existe otro producto con este nombre.',
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'El código ya está en uso por otro producto.',
            'codigo.max' => 'El código no puede exceder los 10 caracteres.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad no puede ser negativa.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio no puede ser negativo.',
            'precio.regex' => 'El precio debe tener como máximo dos decimales.'
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'Nombre del producto',
            'codigo' => 'Código del producto',
            'cantidad' => 'Stock del producto',
            'precio' => 'Precio del producto'
        ];
    }

    protected function prepareForValidation(): void
    {
        // Para edición, limpiamos espacios y normalizamos los datos
        $data = [];

        if ($this->has('nombre')) {
            $data['nombre'] = trim($this->nombre);
        }

        if ($this->has('codigo')) {
            $data['codigo'] = strtoupper(trim($this->codigo));
        }

        $this->merge($data);
    }
}
