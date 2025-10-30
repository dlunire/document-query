<?php

namespace DLUnire\Utilities;

use DLRoute\Http\HttpRequest;
use DLRoute\Requests\HeadersInit;
use DLRoute\Requests\RequestInit;

final class QuerySAIME extends HttpRequest {

    /** @var string $user_agent Agente de usuario */
    private string $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' .
        'AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    /**
     * Ejecuta una petición HTTP hacia el sistema SAIME.
     *
     * @param int $document Número de documento.
     * @param string $type [Opcional] Tipo de documento. El valor por defecto es `V`
     * @param RequestInit|null $init [Opcional] Opciones de la petición.
     * @return string|false
     */
    public function action(int $document, string $type = "V", ?RequestInit $init = null): string|false {
        $type = strtoupper($type);
        $type = trim($type);

        $this->set_follow_location(true);
        $this->set_max_redirect(10);

        /** @var string $origin */
        $origin = "http://controlfronterizo.saime.gob.ve";

        /** @var string $action Ruta de la petición */
        $action = "https://controlfronterizo.saime.gob.ve/index.php?r=dregistro/dregistro/cedula";

        if (!($init instanceof RequestInit)) {
            $init = new RequestInit();
        }

        $this->set_user_agent($this->user_agent);

        $headers = new HeadersInit();
        $headers->set('Referer', $origin);
        $headers->set('Origin', $origin);
        $headers->set('Content-Type', 'application/x-www-form-urlencoded');
        $headers->set('User-Agent', $this->user_agent);
        $headers->set('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8');
        $headers->set('Accept-Language', 'es-ES,es;q=0.9,en;q=0.8');
        $headers->set('Connection', 'keep-alive');
        $headers->set('Upgrade-Insecure-Requests', '1');

        /** @var array<string,string> $data */
        $data = [
            'Dregistro[letra]' => $type,
            'Dregistro[num_cedula]' => strval($document),
            'yt0' => 'CONSULTAR',
        ];

        $init->set_method("POST");
        $init->set_headers($headers);
        $init->set_body($data);

        /** @var string $content */
        $content = $this->fetch($action, $init);

        return $content;
    }
}
