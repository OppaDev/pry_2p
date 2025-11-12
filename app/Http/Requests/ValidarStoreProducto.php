<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ValidarStoreProducto extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('productos.crear');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:200',
            'codigo' => 'required|string|unique:productos,codigo|max:50',
            'marca' => 'required|string|max:100',
            'presentacion' => 'required|string|max:100',
            'capacidad' => 'required|string|max:50',
            'volumen_ml' => 'required|integer|min:1',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string|max:500',
            'estado' => 'required|in:activo,inactivo',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.max' => 'El nombre del producto no puede exceder los 200 caracteres.',
            
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'El código ya está en uso.',
            'codigo.max' => 'El código no puede exceder 50 caracteres.',
            
            'marca.required' => 'La marca es obligatoria.',
            'marca.max' => 'La marca no puede exceder 100 caracteres.',
            
            'presentacion.required' => 'La presentación es obligatoria.',
            'presentacion.max' => 'La presentación no puede exceder 100 caracteres.',
            
            'capacidad.required' => 'La capacidad es obligatoria.',
            'capacidad.max' => 'La capacidad no puede exceder 50 caracteres.',
            
            'volumen_ml.required' => 'El volumen en ml es obligatorio.',
            'volumen_ml.integer' => 'El volumen debe ser un número entero.',
            'volumen_ml.min' => 'El volumen debe ser al menos 1 ml.',
            
            'stock_actual.required' => 'El stock actual es obligatorio.',
            'stock_actual.integer' => 'El stock debe ser un número entero.',
            'stock_actual.min' => 'El stock no puede ser negativo.',
            
            'stock_minimo.required' => 'El stock mínimo es obligatorio.',
            'stock_minimo.integer' => 'El stock mínimo debe ser un número entero.',
            'stock_minimo.min' => 'El stock mínimo no puede ser negativo.',
            
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio no puede ser negativo.',
            'precio.regex' => 'El precio debe tener como máximo dos decimales.',
            
            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
            
            'descripcion.max' => 'La descripción no puede exceder 500 caracteres.',
            
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser activo o inactivo.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del producto',
            'codigo' => 'código del producto',
            'marca' => 'marca',
            'presentacion' => 'presentación',
            'capacidad' => 'capacidad',
            'volumen_ml' => 'volumen en ml',
            'stock_actual' => 'stock actual',
            'stock_minimo' => 'stock mínimo',
            'precio' => 'precio',
            'categoria_id' => 'categoría',
            'descripcion' => 'descripción',
            'estado' => 'estado',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Solo limpiar espacios, sin agregar sufijo aleatorio
        $this->merge([
            'codigo' => strtoupper(trim($this->codigo)),
            'nombre' => trim($this->nombre),
            'marca' => trim($this->marca),
        ]);
    }
}
