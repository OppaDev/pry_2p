<?php

namespace App\Services;

class ValidacionService
{
    /**
     * Validar cédula ecuatoriana usando el algoritmo módulo 10
     * 
     * @param string $cedula
     * @return bool
     */
    public static function validarCedulaEcuatoriana(string $cedula): bool
    {
        // Limpiar la cédula
        $cedula = preg_replace('/[^0-9]/', '', $cedula);
        
        // Verificar que tenga 10 dígitos
        if (strlen($cedula) != 10) {
            return false;
        }
        
        // Validar que los dos primeros dígitos correspondan a una provincia (01-24)
        $provincia = intval(substr($cedula, 0, 2));
        if ($provincia < 1 || $provincia > 24) {
            return false;
        }
        
        // El tercer dígito debe ser menor a 6 (0-5) para personas naturales
        $tercerDigito = intval(substr($cedula, 2, 1));
        if ($tercerDigito > 5) {
            return false;
        }
        
        // Algoritmo módulo 10
        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $suma = 0;
        
        for ($i = 0; $i < 9; $i++) {
            $valor = intval($cedula[$i]) * $coeficientes[$i];
            $suma += ($valor > 9) ? ($valor - 9) : $valor;
        }
        
        $verificador = ($suma % 10 == 0) ? 0 : (10 - ($suma % 10));
        
        return $verificador == intval($cedula[9]);
    }
    
    /**
     * Validar RUC ecuatoriano
     * 
     * @param string $ruc
     * @return bool
     */
    public static function validarRUCEcuatoriano(string $ruc): bool
    {
        // Limpiar el RUC
        $ruc = preg_replace('/[^0-9]/', '', $ruc);
        
        // Verificar que tenga 13 dígitos
        if (strlen($ruc) != 13) {
            return false;
        }
        
        // Los últimos 3 dígitos deben ser 001 para personas naturales
        $ultimosTres = substr($ruc, -3);
        if ($ultimosTres !== '001') {
            return false;
        }
        
        // Validar los primeros 10 dígitos como cédula
        $cedula = substr($ruc, 0, 10);
        return self::validarCedulaEcuatoriana($cedula);
    }
    
    /**
     * Validar que una persona sea mayor de edad (18 años)
     * 
     * @param string $fechaNacimiento (formato Y-m-d)
     * @return bool
     */
    public static function esMayorDeEdad(string $fechaNacimiento): bool
    {
        $fecha = \Carbon\Carbon::parse($fechaNacimiento);
        return $fecha->age >= 18;
    }
    
    /**
     * Validar formato de teléfono ecuatoriano
     * 
     * @param string $telefono
     * @return bool
     */
    public static function validarTelefonoEcuatoriano(string $telefono): bool
    {
        // Limpiar el teléfono
        $telefono = preg_replace('/[^0-9]/', '', $telefono);
        
        // Teléfonos convencionales: 9 dígitos (02XXXXXXX)
        // Celulares: 10 dígitos (09XXXXXXXX)
        $longitud = strlen($telefono);
        
        if ($longitud == 9) {
            // Teléfono convencional debe empezar con 0
            return substr($telefono, 0, 1) == '0';
        }
        
        if ($longitud == 10) {
            // Celular debe empezar con 09
            return substr($telefono, 0, 2) == '09';
        }
        
        return false;
    }
}
