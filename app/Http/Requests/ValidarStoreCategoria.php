<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidarStoreCategoria extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('productos.crear') || $this->user()->can('inventario.entrada');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:1000',
            'estado' => 'sometimes|in:activo,inactivo',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' => trim($this->nombre ?? ''),
        ]);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con este nombre.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'descripcion.max' => 'La descripción no puede exceder 1000 caracteres.',
            'estado.in' => 'El estado debe ser activo o inactivo.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'descripcion' => 'descripción',
            'estado' => 'estado',
        ];
    }
}
