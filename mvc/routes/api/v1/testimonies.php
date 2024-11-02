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

//ROTA DE CADASTRO DE DEPOIMENTOS
$objRouter->post('/api/v1/testimonies', [
  'middlewares' => [
    'api',
    'user-basic-auth'
  ],
  function($request) {
    return new Response(201, Controller\Testimony::setNewTestimony($request), 'application/json');
  }
]);

//ROTA DE ATUALIZAÇÃO DE DEPOIMENTOS
$objRouter->put('/api/v1/testimonies/{id}', [
  'middlewares' => [
    'api',
    'user-basic-auth'
  ],
  function($request, $id) {
    return new Response(200, Controller\Testimony::setEditTestimony($request, $id), 'application/json');
  }
]);

//ROTA DE EXCLUSÃO DE DEPOIMENTOS
$objRouter->delete('/api/v1/testimonies/{id}', [
  'middlewares' => [
    'api',
    'user-basic-auth'
  ],
  function($request, $id) {
    return new Response(200, Controller\Testimony::setDeleteTestimony($request, $id), 'application/json');
  }
]);