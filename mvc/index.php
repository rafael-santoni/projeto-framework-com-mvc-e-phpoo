<?php

require __DIR__ . '/bootstrap/app.php';

use App\Http\Router;

//INICIA O ROUTER
$objRouter = new Router(URL);

//INCLUI AS ROTAS DE PÁGINAS
include __DIR__ . '/routes/pages.php';

//INCLUI AS ROTAS DO PAINEL
include __DIR__ . '/routes/admin.php';

//IMPRIME OU RETORNA A RESPOSTA DA ROTA
$objRouter->run()
          ->sendResponse();
