<?php

require __DIR__.'/includes/app.php';

use \App\Http\Router;

// Inicia o Router
$obRouter = new Router(URL);

// Incluir as rotas de páginas
require __DIR__ . '/routes/site.php';

// Incluir as rotas do painel
require __DIR__ . '/routes/admin.php';

// Incluir as rotas de API
require __DIR__ . '/routes/api.php';

$obRouter->run()->sendResponse();