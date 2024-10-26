<?php

require __DIR__ . '/vendor/autoload.php';

use App\Http\Router;
use App\Utils\View;

define('URL', 'http://rs-dev.test/mvc');

// $objRequest = new App\Http\Request;
// $objResponse = new App\Http\Response(200, 'Hello World!! :)');
// $objRouter = new Router('');
//
// // var_dump($objRequest);
// // var_dump($objResponse);
// // var_dump($objRouter);
// // $objResponse->sendResponse();
//
// exit;

//DEFINE O VALOR PADRÃO DAS VARIÁVEIS
View::init([
  'URL' => URL,
]);

//INICIA O ROUTER
$objRouter = new Router(URL);

//INCLUI AS ROTAS DE PÁGINAS
include __DIR__ . '/routes/pages.php';

// echo "<pre>";
// print_r($objRouter);
// echo "</pre>"; exit;
//
// echo Home::getHome();

//IMPRIME OU RETORNA A RESPOSTA DA ROTA
$objRouter->run()
          ->sendResponse();
