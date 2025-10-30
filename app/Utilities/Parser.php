<?php

namespace DLUnire\Utilities;

use DLUnire\Models\DTO\HTMLInputElement;

final class Parser {

    /**
     * Contenido a ser analizado para ser analizado sintácticamente
     * 
     * @var string $content
     */
    private string $content;


    /**
     * Carga el contenido durante la instancia de Parser
     * 
     * @param string $content Contenido a ser analizado
     */
    public function __construct(string $content) {
        $this->content = $content;
    }

    /**
     * Devuelve el valor en función de los datos existentes.
     * 
     * @param string $field Campo a ser consultado
     * @return HTMLInputElement|null
     */
    public function get_value(string $field = ""): ?HTMLInputElement {
        return $this->get_inputs()[$field] ?? null;
    }

    /**
     * Devuelve inputs para ser posteriormente analizados
     * 
     * @return array<int, HTMLInputElement>
     */
    public function get_inputs(): array {

        /** @var string $pattern */
        $pattern = "/<input(.*?)>/i";

        preg_match_all($pattern, $this->content, $matches);

        /** @var array<string,HTMLInputElement> $inputs */
        $inputs = [];

        foreach ($matches[0] ?? [] as $input) {
            /** @var HTMLInputElement $attributes */
            $attributes = new HTMLInputElement($this->get_attributes($input));

            /** @var string $field */
            $field = $attributes->name;

            $inputs[$field] = $attributes;
        }
        
        $inputs['deceased'] = new HTMLInputElement(["deceased" => $this->has_deceased()]);

        return $inputs;
    }

    /**
     * Devuelve los atributos de un campo
     * 
     * @param string $input Entrada a ser analizada
     * @return array<string,string>
     */
    private function get_attributes(string $input): array {

        /** @var string $pattern */
        $pattern = "/[a-z]+=\"(.*?)\"|[a-z]+=\'(.*?)\'|([a-z]+=[\d\w\-]+)/i";

        preg_match_all($pattern, $input, $matches);
        
        /** @var string[] $attributes */
        $attributes = $matches[0] ?? [];

        /** @var array<string,string> $values */
        $values = $this->get_values($attributes);

        return $values;
    }

    /**
     * Devuelve todos los valores con sus atributos con sus respectivos valores
     * 
     * @param array $attributes
     * @return array<string,string>
     */
    private function get_values(array $attributes): array {

        /** @var array<string,string> $values */
        $values = [];

        foreach ($attributes as $attribute) {
            /** @var string[] $data */
            $data = explode("=", $attribute);

            if (count($data) < 2) continue;

            /** @var string $key */
            $key = $data[0];

            /** @var string $value */
            $value = $data[1];
            $value = trim($value, "'");
            $value = trim($value, '"');
            $value = trim($value);

            $values[$key] = $value;
        }

        return $values;
    }

    /**
     * Indica si la persona consulta ha sido marcado como fallecido por el SAIME.
     * 
     * @return bool
     */
    private function has_deceased(): bool {
        return boolval(preg_match("/fallecido/i", $this->content));
    }
}