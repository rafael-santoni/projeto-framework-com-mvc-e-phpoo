<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Http\Middleware\Queue as MiddlewareQueue;
use App\Utils\View;
use Libs\MavCodes\DotEnv\Environment;
use Libs\MavCodes\DatabaseManager\Database;

//CARREGA AS VARIÁVEIS DE AMBIENTE
Environment::load(__DIR__ . '/../');

//DEFINE AS CONFIGURAÇÕES DE BANCO DE DADOS
Database::config(
  $_ENV['DB_HOST'],
  $_ENV['DB_NAME'],
  $_ENV['DB_USER'],
  $_ENV['DB_PASS'],
  $_ENV['DB_PORT'],
);

//DEFINE A CONSTANTE DE URL
// define('URL', getenv('URL'));
define('URL', $_ENV['URL']);

//DEFINE O VALOR PADRÃO DAS VARIÁVEIS
View::init([
  'URL' => URL,
]);

//DEFINE O MAPEAMENTO DE MIDDLEWARES
MiddlewareQueue::setMap([
  'maintenance' => App\Http\Middleware\Maintenance::class,
]);

//DEFINE O MAPEAMENTO DOS MIDDLEWARES PADRÕES (EXECUTADOS EM TODAS AS ROTAS)
MiddlewareQueue::setDefault([
  'maintenance'
]);
