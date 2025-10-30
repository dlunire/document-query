<?php

/**
 * Copyright (c) 2025 David E Luna M
 * Licensed under the MIT License. See LICENSE file for details.
 */

namespace DLUnire\Models\DTO;

/**
 * Clase Input
 * 
 * Representa un objeto de transferencia de datos (DTO) utilizado para la definición
 * y manipulación de los atributos de un campo de entrada HTML (input).
 * 
 * Este DTO encapsula los valores típicos de un input (tipo, nombre, tamaño, placeholder, etc.)
 * y garantiza su correcta tipificación y consistencia. Es inmutable tras su construcción.
 * 
 * ### Ejemplo de uso:
 * ```php
 * use DLUnire\Models\DTO\Input;
 * 
 * $input = new Input([
 *     'name' => 'usuario',
 *     'type' => 'text',
 *     'size' => '25',
 *     'maxlength' => '50',
 *     'placeholder' => 'Ingrese su nombre',
 *     'autocomplete' => 'off'
 * ]);
 * 
 * echo $input->name; // usuario
 * echo $input->autocomplete; // off
 * ```
 * 
 * @package DLUnire\Models\DTO
 * @version v0.0.1
 * @license MIT
 * @author David E Luna M
 * @copyright Copyright (c) 2025
 */
final class HTMLInputElement {

    /**
     * Nacionalidad
     * 
     * @var string $nationality
     */
    public readonly string $nationality;

    /** 
     * Tamaño del campo de entrada.
     * 
     * @var int $size
     */
    public readonly int $size;

    /** 
     * Longitud máxima permitida del campo.
     * 
     * @var int $maxlength
     */
    public readonly int $maxlength;

    /** 
     * Texto de ayuda mostrado cuando el campo está vacío.
     * 
     * @var string|null $placeholder
     */
    public readonly ?string $placeholder;

    /** 
     * Tipo de entrada HTML (por ejemplo: text, password, email, etc.).
     * 
     * @var string $type
     */
    public readonly string $type;

    /** 
     * Clase(s) CSS asociada(s) al campo.
     * 
     * @var string|null $class
     */
    public readonly ?string $class;

    /** 
     * Control de autocompletado del campo.
     * Puede ser “on” o “off”.
     * 
     * @var string $autocomplete
     */
    public readonly string $autocomplete;

    /** 
     * Indica si el campo está deshabilitado.
     * 
     * @var bool $disabled
     */
    public readonly bool $disabled;

    /** 
     * Nombre del campo (atributo `name`).
     * 
     * @var string $name
     */
    public readonly string $name;

    /** 
     * Identificador único del campo (atributo `id`).
     * 
     * @var string $id
     */
    public readonly string $id;

    /** 
     * Valor actual del campo (atributo `value`).
     * 
     * @var string $value
     */
    public readonly string $value;

    /**
     * Campos originales recibidos para construir el DTO.
     * 
     * @var array<string, string> $attributes
     */
    public readonly array $attributes;

    /**
     * Indica si la persona ha sido marcado como fallecido por el SAIME.
     * 
     * @var boolean $deceased
     */
    public readonly bool $deceased;

    /**
     * Constructor de la clase Input.
     * 
     * Carga los campos suministrados y aplica las reglas de tipificación
     * y valores por defecto.
     * 
     * @param array<string, string> $fields Atributos del elemento `Input`
     */
    public function __construct(array $attributes) {
        $this->load_fields($attributes);
        
        if (isset($attributes['deceased'])) {
            $this->deceased = strval($attributes['deceased'] ?? false);
            return;
        }

        $this->size = intval($this->get_value('size'));
        $this->maxlength = intval($this->get_value('maxlength'));
        $this->placeholder = $this->get_value('placeholder');
        $this->type = $this->get_value('type') ?? 'text';
        $this->class = $this->get_value('class');
        $this->autocomplete = $this->get_autocomplete($this->get_value('autocomplete'));
        $this->name = strval($this->get_value('name'));
        $this->value = strval($this->get_value('value'));
        $this->disabled = $this->get_disabled($this->get_value('disabled'));
        $this->id = strval($this->get_value('id') ?? $this->name);

    }

    /**
     * Devuelve el valor asociado a un campo determinado.
     * 
     * @param string $field Nombre del campo
     * @return string|null Valor del campo o null si no existe
     */
    public function get_value(string $field): ?string {
        return $this->attributes[trim($field)] ?? null;
    }

    /**
     * Carga los datos correspondientes a los campos definidos.
     * 
     * @param array<string, string> $attributes Atributos
     * @return void
     */
    private function load_fields(array $attributes): void {
        $this->attributes = $attributes;
    }

    /**
     * Devuelve un valor normalizado para el atributo `autocomplete`.
     * 
     * @param string|null $value Valor a analizar
     * @return string Valor validado ("on" o "off")
     */
    private function get_autocomplete(?string $value): string {
        if (is_null($value)) {
            return "on";
        }

        $value = strtolower($value);
        $values = ["off", "on"];

        return in_array($value, $values, true) ? $value : "on";
    }

    /**
     * Devuelve los valores reales de disabled en boolean
     * 
     * @var string
     */
    private function get_disabled(?string $value): bool {

        if (is_null($value)) {
            return false;
        }

        /** @var string[] $values */
        $values = ["false", "0", "no"];

        return !in_array($value, $values);
    }
}
