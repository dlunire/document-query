<?php

namespace DLUnire\Controllers;

use DLCore\Core\BaseController;
use DLUnire\Utilities\Parser;
use DLUnire\Utilities\QuerySAIME;

/**
 * Permite convertir datos masivos en formato legible
 */
final class TranslateDataController extends BaseController {

    /**
     * Devuelve el nombre a partir del número de documento
     * 
     * @param object{document:int, type: string} $params Parámetros de la petición
     */
    public function get_name(object $params): array {

        /** @var QuerySAIME $query */
        $query = new QuerySAIME();

        /** @var string $content */
        $content = $query->action(
            document: $params->document,
            type: $params->type
        );

        $content = trim($content);

        /** @var Parser $parser */
        $parser = new Parser($content);
        
        /** @var array<string,mixed> $data */
        $data = [
            "nationality" => $parser->get_value('Dregistro[letra]')?->value ?? "-",
            "document" => $parser->get_value('Dregistro[num_cedula]')?->value ?? "-",
            "firstname" => $parser->get_value('Dregistro[primernombre]')?->value ?? "-",
            "middlename" => $parser->get_value('Dregistro[segundonombre]')?->value ?? "-",
            "first_surname" => $parser->get_value('Dregistro[primerapellido]')?->value ?? "-",
            "second_surname" => $parser->get_value('Dregistro[segundoapellido]')?->value ?? "-",
            "birthdate" => $this->get_birth_date($parser->get_value('Dregistro[fecha_nac]')?->value ?? "-"),
            "gender" => $this->get_gender($parser->get_value('Dregistro[sexo]')?->value ?? '-'),
            "deceased" => $parser->get_value('deceased')?->deceased ?? false,
        ];

        return $data;
    }

    /**
     * Devuelve el nombre completo del género
     * 
     * @param string $gender
     * @return string
     */
    private function get_gender(string $gender): string {
        /** @var string $gender */
        $gender = strtoupper($gender);

        /** @var array<string,string> $genders */
        $genders = [
            "M" => "Hombre",
            "F" => "Mujer"
        ];

        return $genders[$gender] ?? "-";
    }

    /**
     * Devuelve la fecha de nacimiento de la persona consultada
     * 
     * @param string $date Fecha a ser analizada.
     * @return string
     */
    private function get_birth_date(string $date): string {
        /** @var string[] $parts */
        $parts = preg_split("/[-\/]+/", $date);

        if (count($parts) < 3) return "-";

        /** @var string $day */
        $day = strval($parts[0]);

        /** @var string $month */
        $month = strval($parts[1]);

        /** @var string $year */
        $year = strval($parts[2]);

        $months = [
            "01" => "enero",
            "02" => "febrero",
            "03" => "marzo",
            "04" => "abril",
            "05" => "mayo",
            "06" => "junio",
            "07" => "julio",
            "08" => "agosto",
            "09" => "septiembre",
            "10" => "octubre",
            "11" => "noviembre",
            "12" => "diciembre"
        ];

        /** @var string current_month */
        $current_month = $months[$month] ?? "enero";

        return "{$day} de {$current_month} de {$year}";
    }
}
