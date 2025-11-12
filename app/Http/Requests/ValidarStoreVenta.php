<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\ValidacionService;

class ValidarStoreVenta extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('ventas.crear');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'metodo_pago' => 'required|in:efectivo,tarjeta_debito,tarjeta_credito,transferencia',
            'observaciones' => 'nullable|string|max:500',
            
            // Detalles de venta (productos)
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
            
            'metodo_pago.required' => 'El método de pago es obligatorio.',
            'metodo_pago.in' => 'El método de pago debe ser: efectivo, tarjeta de débito, tarjeta de crédito o transferencia.',
            
            'observaciones.max' => 'Las observaciones no pueden exceder 500 caracteres.',
            
            'detalles.required' => 'Debe agregar al menos un producto a la venta.',
            'detalles.array' => 'Los detalles de venta deben ser un arreglo.',
            'detalles.min' => 'Debe agregar al menos un producto a la venta.',
            
            'detalles.*.producto_id.required' => 'El producto es obligatorio.',
            'detalles.*.producto_id.exists' => 'El producto seleccionado no existe.',
            
            'detalles.*.cantidad.required' => 'La cantidad es obligatoria.',
            'detalles.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
            'detalles.*.cantidad.min' => 'La cantidad debe ser al menos 1.',
            
            'detalles.*.precio_unitario.required' => 'El precio unitario es obligatorio.',
            'detalles.*.precio_unitario.numeric' => 'El precio unitario debe ser un número.',
            'detalles.*.precio_unitario.min' => 'El precio unitario debe ser mayor o igual a 0.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Limpiar y preparar datos
        if ($this->has('observaciones')) {
            $this->merge([
                'observaciones' => trim($this->observaciones),
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validar que el cliente sea mayor de edad
            if ($this->filled('cliente_id')) {
                $cliente = \App\Models\Cliente::find($this->cliente_id);
                
                if ($cliente && !$cliente->es_mayor_edad) {
                    $validator->errors()->add(
                        'cliente_id', 
                        'El cliente debe ser mayor de 18 años para comprar bebidas alcohólicas.'
                    );
                }
                
                if ($cliente && $cliente->estado !== 'activo') {
                    $validator->errors()->add(
                        'cliente_id', 
                        'El cliente seleccionado está inactivo.'
                    );
                }
            }
            
            // Validar stock disponible para cada producto
            if ($this->filled('detalles') && is_array($this->detalles)) {
                foreach ($this->detalles as $index => $detalle) {
                    if (isset($detalle['producto_id']) && isset($detalle['cantidad'])) {
                        $producto = \App\Models\Producto::find($detalle['producto_id']);
                        
                        if ($producto) {
                            if ($producto->estado !== 'activo') {
                                $validator->errors()->add(
                                    "detalles.{$index}.producto_id",
                                    "El producto {$producto->nombre} está inactivo."
                                );
                            }
                            
                            if (!$producto->tieneStock($detalle['cantidad'])) {
                                $validator->errors()->add(
                                    "detalles.{$index}.cantidad",
                                    "Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stock_actual}"
                                );
                            }
                        }
                    }
                }
            }
        });
    }
}
