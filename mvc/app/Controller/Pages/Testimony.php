<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Controller\Pages\Page as PageController;
use App\Model\Entity\Testimony as TestimonyModel;
use App\Utils\View;
use Libs\MavCodes\DatabaseManager\Pagination;

class Testimony extends PageController
{
  /**
   * Método responsável por obter a renderização dos itens de depoimentos para a página
   * @param  Request    $request
   * @param  null|Pagination $objPagination
   * @return string
   */
  private static function getTestimonyItems(Request $request, null|Pagination &$objPagination): string
  {
    //DEPOIMENTOS
    $itens = '';

    //QUANTIDADE TOTAL DE REGISTROS
    $quantidadeTotal = TestimonyModel::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

    //PÁGINA ATUAL
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;

    //INSTÂNCIA DE PAGINAÇÃO
    $objPagination = new Pagination($quantidadeTotal, $paginaAtual, 3);

    //RESULTADOS DA PÁGINA
    $results = TestimonyModel::getTestimonies(null, 'id DESC', $objPagination->getLimit());

    //RENDERIZA O ITEM
    while ($objTestimony = $results->fetchObject(TestimonyModel::class)) {
      $itens .= View::render('pages/testimony/item', [
        'nome' => $objTestimony->nome,
        'mensagem' => $objTestimony->mensagem,
        'data' => date('d/m/Y H:i:s', strtotime($objTestimony->data)),
      ]);
    }

    //RETORNA OS DEPOIMENTOS
    return $itens;
  }

  /**
   * Método responsável por retornar o conteúdo (View) de Depoimentos
   * @param Request $request
   * @return string
   */
  public static function getTestimonies(Request $request): string
  {
    //VIEW DA HOME
    $content = View::render('pages/testimonies', [
      'itens' => self::getTestimonyItems($request, $objPagination),
      'pagination' => parent::getPagination($request, $objPagination)
    ]);

    //RETORNA A VIEW DA PÁGINA
    return parent::getPage('DEPOIMENTOS | RS-Dev', $content);
  }

  /**
   * Método responsável por cadastrar um depoimento
   * @param  Request $request
   * @return string
   */
  public static function insertTestimony(Request $request): string
  {
    //DADOS DO POST
    $postVars = $request->getPostVars();

    //NOVA INSTÂNCIA DE DEPOIMENTO
    $objTestimony = new TestimonyModel;
    $objTestimony->nome = $postVars['nome'];
    $objTestimony->mensagem = $postVars['mensagem'];
    $objTestimony->cadastrar();

    //RETORNA A PÁGINA DE LISTAGEM DE DEPOIMENTOS
    return self::getTestimonies($request);
  }
}
