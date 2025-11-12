<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\ValidacionService;
use Carbon\Carbon;

class ValidarStoreCliente extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('clientes.crear');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipo_identificacion' => 'required|in:cedula,ruc,pasaporte',
            'identificacion' => [
                'required',
                'string',
                'max:20',
                'unique:clientes,identificacion',
                function ($attribute, $value, $fail) {
                    if ($this->tipo_identificacion === 'cedula') {
                        if (!ValidacionService::validarCedulaEcuatoriana($value)) {
                            $fail('La cédula ingresada no es válida.');
                        }
                    } elseif ($this->tipo_identificacion === 'ruc') {
                        if (!ValidacionService::validarRUCEcuatoriano($value)) {
                            $fail('El RUC ingresado no es válido.');
                        }
                    }
                },
            ],
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) {
                    $edad = Carbon::parse($value)->age;
                    if ($edad < 18) {
                        $fail('El cliente debe ser mayor de 18 años. Edad actual: ' . $edad . ' años.');
                    }
                    if ($edad > 120) {
                        $fail('La fecha de nacimiento no es válida.');
                    }
                },
            ],
            'direccion' => 'nullable|string|max:500',
            'telefono' => [
                'nullable',
                'string',
                'max:15',
                function ($attribute, $value, $fail) {
                    if ($value && !ValidacionService::validarTelefonoEcuatoriano($value)) {
                        $fail('El número de teléfono no tiene un formato válido ecuatoriano.');
                    }
                },
            ],
            'correo' => 'nullable|email|max:255',
            'estado' => 'sometimes|in:activo,inactivo',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombres' => trim($this->nombres ?? ''),
            'apellidos' => trim($this->apellidos ?? ''),
            'identificacion' => preg_replace('/[^0-9]/', '', $this->identificacion ?? ''),
            'correo' => $this->correo ? strtolower(trim($this->correo)) : null,
        ]);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tipo_identificacion.required' => 'Debe seleccionar el tipo de identificación.',
            'tipo_identificacion.in' => 'El tipo de identificación debe ser cédula, RUC o pasaporte.',
            'identificacion.required' => 'La identificación es obligatoria.',
            'identificacion.unique' => 'Ya existe un cliente registrado con esta identificación.',
            'nombres.required' => 'Los nombres son obligatorios.',
            'nombres.max' => 'Los nombres no pueden exceder 255 caracteres.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.max' => 'Los apellidos no pueden exceder 255 caracteres.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'correo.email' => 'El correo electrónico debe tener un formato válido.',
            'telefono.max' => 'El teléfono no puede exceder 15 caracteres.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'tipo_identificacion' => 'tipo de identificación',
            'identificacion' => 'identificación',
            'nombres' => 'nombres',
            'apellidos' => 'apellidos',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'direccion' => 'dirección',
            'telefono' => 'teléfono',
            'correo' => 'correo electrónico',
            'estado' => 'estado',
        ];
    }
}
