<?php

use App\Http\Response;
use App\Controller\Api as Controller;

//ROTA DE LISTAGEM DE DEPOIMENTOS
$objRouter->get('/api/v1/testimonies', [
  'middlewares' => [
    'api'
  ],
  function($request) {
    return new Response(200, Controller\Testimony::getTestimonies($request), 'application/json');
  }
]);

//ROTA DE CONSULTA INDIVIDUAL DE DEPOIMENTOS
$objRouter->get('/api/v1/testimonies/{id}', [
  'middlewares' => [
    'api'
  ],
  function($request, $id) {
    return new Response(200, Controller\Testimony::getTestimony($request, $id), 'application/json');
  }
]);