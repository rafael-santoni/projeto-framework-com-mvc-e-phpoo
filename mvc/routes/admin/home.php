<?php

use App\Http\Response;
use App\Controller\Admin as Controller;

//ROTA ADMIN
$objRouter->get('/admin',[
  'middlewares' => [
    'require-admin-login'
  ],
  function($request) {
    return new Response(200, Controller\Home::getHome($request));
  }
]);