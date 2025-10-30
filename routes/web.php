<?php

use DLRoute\Requests\DLRoute;
use DLUnire\Controllers\TranslateDataController;

DLRoute::get('/api/v1/saime/{type}/{document}', [TranslateDataController::class, 'get_name'])->filter_by_type([
    "type" => "/^(V|E|DNI)$/i",
    "document" => "integer"
]);