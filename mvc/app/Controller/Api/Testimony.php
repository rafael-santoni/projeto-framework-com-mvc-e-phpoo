<?php

namespace App\Controller\Api;

use Exception;
use App\Utils\View;
use App\Http\Request;
use App\Controller\Api\Api as ApiController;
use Libs\MavCodes\DatabaseManager\Pagination;
use App\Model\Entity\Testimony as TestimonyModel;

class Testimony extends ApiController
{
  /**
   * Método responsável por obter a renderização dos itens de depoimentos para a página
   * @param  Request    $request
   * @param  null|Pagination $objPagination
   * @return array
   */
  private static function getTestimonyItems(Request $request, null|Pagination &$objPagination): array
  {
    //DEPOIMENTOS
    $itens = [];

    //QUANTIDADE TOTAL DE REGISTROS
    $quantidadeTotal = TestimonyModel::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

    //PÁGINA ATUAL
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;

    //INSTÂNCIA DE PAGINAÇÃO
    $objPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

    //RESULTADOS DA PÁGINA
    $results = TestimonyModel::getTestimonies(null, 'id DESC', $objPagination->getLimit());

    //RENDERIZA O ITEM
    while ($objTestimony = $results->fetchObject(TestimonyModel::class)) {
      $itens[] = [
        'id' => (int)$objTestimony->id,
        'nome' => $objTestimony->nome,
        'mensagem' => $objTestimony->mensagem,
        'data' => $objTestimony->data,
      ];
    }

    //RETORNA OS DEPOIMENTOS
    return $itens;
  }

  /**
   * Método responsável por retornar os depoimentos cadastrados
   * @param Request $request
   * @return array
   */
  public static function getTestimonies(Request $request): array
  {
    return [
      'depoimentos' => self::getTestimonyItems($request, $objPagination),
      'pagination' => parent::getPagination($request, $objPagination),
    ];
  }

  /**
   * Método responsável por retornar os detalhes de um depoimento
   * @param Request $request
   * @param integer $id
   * @return array
   */
  public static function getTestimony(Request $request, int $id): array
  {
    //VALIDA O ID DO DEPOIMENTO
    if(!is_numeric($id)) {
      throw new Exception("O id '{$id}' não é válido", 400);
    }

    //BUSCA DEPOIMENTO
    $objTestimony = TestimonyModel::getTestimonyById($id);

    //VALIDA SE O DEPOIMENTO EXISTE
    if(!$objTestimony instanceof TestimonyModel) {
      throw new Exception("O depoimento id: {$id} não foi encontrado", 404);
    }

    //RETORNA OS DETALHES DO DEPOIMENTO
    return [
      'id' => (int)$objTestimony->id,
      'nome' => $objTestimony->nome,
      'mensagem' => $objTestimony->mensagem,
      'data' => $objTestimony->data,
    ];
  }
}
