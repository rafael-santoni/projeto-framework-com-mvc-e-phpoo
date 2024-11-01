<?php

use App\Http\Response;
use App\Controller\Admin as Controller;

//ROTA DE LISTAGEM DE DEPOIMENTOS
$objRouter->get('/admin/testimonies',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request) {
    return new Response(200, Controller\Testimony::getTestimonies($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO DEPOIMENTO
$objRouter->get('/admin/testimonies/new',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request) {
    return new Response(200, Controller\Testimony::getNewTestimony($request));
  }
]);

//ROTA DE CADASTRO DE UM NOVO DEPOIMENTO (POST)
$objRouter->post('/admin/testimonies/new',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request) {
    return new Response(200, Controller\Testimony::setNewTestimony($request));
  }
]);

//ROTA DE EDIÇÃO DE UM DEPOIMENTO
$objRouter->get('/admin/testimonies/{id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request, $id) {
    return new Response(200, Controller\Testimony::getEditTestimony($request, $id));
  }
]);

//ROTA DE EDIÇÃO DE UM DEPOIMENTO (POST)
$objRouter->post('/admin/testimonies/{id}/edit',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request, $id) {
    return new Response(200, Controller\Testimony::setEditTestimony($request, $id));
  }
]);

//ROTA DE EXCLUSÃO DE UM DEPOIMENTO
$objRouter->get('/admin/testimonies/{id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request, $id) {
    return new Response(200, Controller\Testimony::getDeleteTestimony($request, $id));
  }
]);

//ROTA DE EXCLUSÃO DE UM DEPOIMENTO (POST)
$objRouter->post('/admin/testimonies/{id}/delete',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request, $id) {
    return new Response(200, Controller\Testimony::setDeleteTestimony($request, $id));
  }
]);