<?php

use App\Http\Response;
use App\Controller\Pages;

//ROTA HOME
$objRouter->get('/',[
  function() {
    return new Response(200, Pages\Home::getHome());
  }
]);

//ROTA SOBRE
$objRouter->get('/sobre',[
  function() {
    return new Response(200, Pages\About::getAbout());
  }
]);

//ROTA DINÂMICA
$objRouter->get('/pagina/{idPagina}/{acao}',[
  function($idPagina, $acao) {
    return new Response(200, 'Página ' . $idPagina . ' - Ação ' . $acao);
  }
]);
