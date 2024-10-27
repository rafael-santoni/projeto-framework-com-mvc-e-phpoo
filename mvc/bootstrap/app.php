<?php

require __DIR__ . '/../vendor/autoload.php';

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
