<?php

namespace DLUnire\Controllers;

use DLCore\Core\BaseController;
use DLRoute\Requests\DLOutput;
use DLUnire\Utilities\Parser;
use DLUnire\Utilities\QuerySAIME;
use DLUnire\Utilities\Storage;
use Exception;

/**
 * Permite convertir datos masivos en formato legible
 */
final class TranslateDataController extends BaseController {

    /**
     * Instancia del objeto de almacenamiento
     * 
     * @var Storage $storage
     */
    private Storage $storage;

    /**
     * Devuelve el nombre a partir del número de documento
     * 
     * @param object{document:int, type: string} $params Parámetros de la petición
     */
    public function get_name(object $params): array {
        $this->storage = new Storage();

        /** @var array<string,mixed> $data */
        $data = [];

        /**
         * Firma como nombre de archivo
         * 
         * @var string $signature
         */
        $signature = $this->get_signature($params);

        /**
         * Llave de entropía
         * 
         * @var string $entropy
         */
        $entropy = $this->get_entropy($params);

        /** @var array $data */
        $data = $this->get_raw_content($signature, $entropy);
        
        if (count($data) > 0) {
            return $data;
        }

        /** @var QuerySAIME $query */
        $query = new QuerySAIME();

        /** @var string $content */
        $content = $query->action(
            document: $params->document,
            type: $params->type
        );

        $content = trim($content);
        $data = $this->get_data($content, $signature, $entropy);

        return $data;
    }

    /**
     * Devuelve la firma de archivo
     * 
     * @param object{type: string, document: int} $params Parámetros de la petición
     * @return string
     */
    private function get_signature(object $params): string {
        return hash('sha1', "{$params->type}{$params->document}");
    }

    /**
     * Devuelve la llave de entropía con la que se cifrará el archivo.
     * 
     * @param object{type: string, document: int} $params Parámetros de la petición.
     * @return string;
     */
    private function get_entropy(object $params): string {
        return hash('sha256', $this->get_signature($params) . $params->type);
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

        /** @var array<string,string> $months */
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

    /**
     * Devuelve los datos solicitados por el cliente HTTP
     * 
     * @param string $content Contenido a ser analizar y convertido a formato JSON.
     * @param string $signature Firma de archivo.
     * @param string $entropy Llave de entropía de cifrado.
     * @return array<string,mixed>
     */
    private function get_data(string $content, string $signature, string $entropy): array {
        /** @var boolean $cache Permite decidir si quiere almacenar en caché los datos */
        $cache = boolval($this->get_input('cache'));

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

        if ($cache) {
            $this->storage->save_data($signature, DLOutput::get_json($data, true), $entropy);
        }

        return $data;
    }

    /**
     * Devuelve el contenido crudo
     * 
     * @param string $signature Firma como nombre de archivo.
     * @param string $entropy Llave de entropía con que ayudará a desbloquear el archivo.
     * 
     * @return array
     */
    private function get_raw_content(string $signature, string $entropy): array {
        
        /** @var string $content */
        $content = "";
        
        try {
            $content = $this->storage->read_storage_data($signature, $entropy);
        } catch (Exception $error) {
            return [];
        }

        return json_decode($content, true);
    }
}
