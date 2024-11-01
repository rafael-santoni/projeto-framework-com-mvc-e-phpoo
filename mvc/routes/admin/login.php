<?php
use App\Http\Response;
use App\Controller\Admin as Controller;

//ROTA LOGIN
$objRouter->get('/admin/login',[
  'middlewares' => [
    'require-admin-logout',
  ],
  function($request) {
    return new Response(200, Controller\Login::getLogin($request));
  }
]);

//ROTA LOGIN (POST)
$objRouter->post('/admin/login',[
  'middlewares' => [
    'require-admin-logout'
  ],
  function($request) {
    return new Response(200, Controller\Login::setLogin($request));
  }
]);


//ROTA LOGOUT
$objRouter->get('/admin/logout',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request) {
    return new Response(200, Controller\Login::setLogout($request));
  }
]);