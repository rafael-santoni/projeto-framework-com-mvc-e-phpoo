<?php

use App\Http\Response;
use App\Controller\Pages as Controller;

//ROTA HOME
$objRouter->get('/',[
  function() {
    return new Response(200, Controller\Home::getHome());
  }
]);

//ROTA SOBRE
$objRouter->get('/sobre',[
  function() {
    return new Response(200, Controller\About::getAbout());
  }
]);

//ROTA DEPOIMENTOS
$objRouter->get('/depoimentos',[
  function($request) {
    return new Response(200, Controller\Testimony::getTestimonies($request));
  }
]);

//ROTA DEPOIMENTOS (INSERT)
$objRouter->post('/depoimentos',[
  function($request) {
    return new Response(200, Controller\Testimony::insertTestimony($request));
  }
]);

// //ROTA DINÂMICA
// $objRouter->get('/pagina/{idPagina}/{acao}',[
//   function($idPagina, $acao) {
//     return new Response(200, 'Página ' . $idPagina . ' - Ação ' . $acao);
//   }
// ]);
