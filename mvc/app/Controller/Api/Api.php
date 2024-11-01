<?php

namespace App\Controller\Api;

use App\Http\Request;
use Libs\MavCodes\DatabaseManager\Pagination;

class Api
{
  /**
   * Método responsável por retornar os datelhes da API
   * @param Request $request
   * @return array
   */
  public static function getDetails(Request $request): array
  {
    return [
      'nome' => 'API - RS-Dev',
      'versao' => 'v1.0.0-alpha',
      'autor' => 'Rafael Santoni',
      'email' => 'rafasanto.dev@gmail.com',
    ];
  }

  /**
   * Método responsável por retornar os detalhes da paginação
   * @param Request $request
   * @param Pagination $objPagination
   * @return array
   */
  protected static function getPagination(Request $request, Pagination $objPagination): array
  {
    //QUERY PARAMS
    $queryParams = $request->getQueryParams();
    
    //PÁGINA
    $pages = $objPagination->getPages();

    //RETORNO
    return [
      'paginaAtual' => isset($queryParams['page']) ? (int)$queryParams['page'] : 1,
      'quantidadePaginas' => !empty($pages) ? count($pages) : 1
    ];
  }
}
