<?php

use App\Http\Response;
use App\Controller\Api as ApiController;

//ROTA AUTORIZAÇÃO DA API
$objRouter->post('/api/v1/auth', [
  'middlewares' => [
    'api'
  ],
  function($request) {
    return new Response(201, ApiController\Auth::generateToken($request), 'application/json');
  }
]);