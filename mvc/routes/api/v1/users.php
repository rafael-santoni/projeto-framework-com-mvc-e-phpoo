<?php

use App\Http\Response;
use App\Controller\Api as Controller;

//ROTA DE LISTAGEM DE USUÁRIOS
$objRouter->get('/api/v1/users', [
  'middlewares' => [
    'api',
    'user-basic-auth',
    'jwt-auth'
  ],
  function($request) {
    return new Response(200, Controller\User::getUsers($request), 'application/json');
  }
]);

//ROTA DE CONSULTA DO USUÁRIO ATUAL
$objRouter->get('/api/v1/users/me', [
  'middlewares' => [
    'api',
    'user-basic-auth',
    'jwt-auth'
  ],
  function($request) {
    return new Response(200, Controller\User::getCurrentUser($request), 'application/json');
  }
]);

//ROTA DE CONSULTA INDIVIDUAL DE USUÁRIOS
$objRouter->get('/api/v1/users/{id}', [
  'middlewares' => [
    'api',
    'user-basic-auth',
    'jwt-auth'
  ],
  function($request, $id) {
    return new Response(200, Controller\User::getUser($request, $id), 'application/json');
  }
]);

//ROTA DE CADASTRO DE USUÁRIOS
$objRouter->post('/api/v1/users', [
  'middlewares' => [
    'api',
    // 'user-basic-auth',
    'jwt-auth'
  ],
  function($request) {
    return new Response(201, Controller\User::setNewUser($request), 'application/json');
  }
]);

//ROTA DE ATUALIZAÇÃO DE USUÁRIOS
$objRouter->put('/api/v1/users/{id}', [
  'middlewares' => [
    'api',
    // 'user-basic-auth',
    'jwt-auth'
  ],
  function($request, $id) {
    return new Response(200, Controller\User::setEditUser($request, $id), 'application/json');
  }
]);

//ROTA DE EXCLUSÃO DE USUÁRIOS
$objRouter->delete('/api/v1/users/{id}', [
  'middlewares' => [
    'api',
    // 'user-basic-auth',
    'jwt-auth'
  ],
  function($request, $id) {
    return new Response(200, Controller\User::setDeleteUser($request, $id), 'application/json');
  }
]);