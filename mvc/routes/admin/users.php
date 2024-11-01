<?php

use App\Http\Response;
use App\Controller\Admin as Controller;

//ROTA DE LISTAGEM DE USUÁRIOS
$objRouter->get('/admin/users',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request) {
    return new Response(200, Controller\User::getUsers($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO USUÁRIO
$objRouter->get('/admin/users/new',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request) {
    return new Response(200, Controller\User::getNewUser($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO USUÁRIO (POST)
$objRouter->post('/admin/users/new',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request) {
    return new Response(200, Controller\User::setNewUser($request));
  }
]);

//ROTA DE EDIÇÃO DE UM USUÁRIO
$objRouter->get('/admin/users/{id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request, $id) {
    return new Response(200, Controller\User::getEditUser($request, $id));
  }
]);

//ROTA DE EDIÇÃO DE UM USUÁRIO (POST)
$objRouter->post('/admin/users/{id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request, $id) {
    return new Response(200, Controller\User::setEditUser($request, $id));
  }
]);

//ROTA DE EXCLUSÃO DE UM USUÁRIO
$objRouter->get('/admin/users/{id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request, $id) {
    return new Response(200, Controller\User::getDeleteUser($request, $id));
  }
]);

//ROTA DE EXCLUSÃO DE UM USUÁRIO (POST)
$objRouter->post('/admin/users/{id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request, $id) {
    return new Response(200, Controller\User::setDeleteUser($request, $id));
  }
]);