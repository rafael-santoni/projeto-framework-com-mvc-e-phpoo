<?php

use App\Http\Response;
use App\Controller\Api as ApiController;

//ROTA RAIZ DA API
$objRouter->get('/api/v1', [
  'middlewares' => [
    'api'
  ],
  function($request) {
    return new Response(200, ApiController\Api::getDetails($request), 'application/json');
  }
]);