<?php

namespace App\Controller\Admin;

// use App\Controller\Admin\Alert;
use App\Http\Request;
use App\Controller\Admin\Alert;
use App\Controller\Admin\Page as PageController;
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
    $objPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

    //RESULTADOS DA PÁGINA
    $results = TestimonyModel::getTestimonies(null, 'id DESC', $objPagination->getLimit());

    //RENDERIZA O ITEM
    while ($objTestimony = $results->fetchObject(TestimonyModel::class)) {
      $itens .= View::render('admin/modules/testimonies/item', [
        'id' => $objTestimony->id,
        'nome' => $objTestimony->nome,
        'mensagem' => $objTestimony->mensagem,
        'data' => date('d/m/Y H:i:s', strtotime($objTestimony->data)),
      ]);
    }

    //RETORNA OS DEPOIMENTOS
    return $itens;
  }
  
  /**
   * Método responsável por renderizar a View de listagem de Depoimentos
   * @param Request $request
   * @return string
   */
  public static function getTestimonies(Request $request): string
  {
    //CONTEÚDO DA HOME
    $content = View::render('admin/modules/testimonies/index', [
      'itens' => self::getTestimonyItems($request, $objPagination),
      'pagination' => parent::getPagination($request, $objPagination),
      'status' => self::getStatus($request),
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('DEPOIMENTOS | RS-Dev | Painel Admin', $content, 'testimonies');
  }

  /**
   * Método responsável por retornar o formulário de cadastro de um novo depoimento
   * @param Request $request
   * @return string
   */
  public static function getNewTestimony(Request $request): string
  {
    //CONTEÚDO DO FORMULÁRIO
    $content = View::render('admin/modules/testimonies/form', [
      'title' => 'Cadastrar Depoimento',
      'nome' => '',
      'mensagem' => '',
      'status' => ''
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('CADASTRAR DEPOIMENTO | RS-Dev | Painel Admin', $content, 'testimonies');
  }

  /**
   * Método responsável por cadastrar um novo depoimento no Banco de Dados
   * @param Request $request
   * @return string
   */
  public static function setNewTestimony(Request $request): string
  {
    //POST VARS
    $postVars = $request->getPostVars();
    
    //NOVA INSTÂNCIA DE DEPOIMENTO
    $objTestimony = new TestimonyModel;
    $objTestimony->nome = $postVars['nome'] ?? '';
    $objTestimony->mensagem = $postVars['mensagem'] ?? '';
    $objTestimony->cadastrar();

    //REDIRECIONA O USUÁRIO
    $request->getRouter()->redirect("/admin/testimonies/{$objTestimony->id}/edit?status=created");
  }

  /**
   * Método responsável por retornar a mensagem de status
   * @param Request $request
   * @return string
   */
  private static function getStatus(Request $request): string
  {
    //QUERY PARAMS
    $queryParams = $request->getQueryParams();
    
    //STATUS
    if(!isset($queryParams['status'])) return '';
    
    //MENSAGENS DE STATUS
    switch ($queryParams['status']) {
      case 'created':
        return Alert::getSuccess('Depoimento criado com sucesso!');
        break;
      
      case 'updated':
        return Alert::getSuccess('Depoimento atualizado com sucesso!');
        break;
      
      case 'deleted':
        return Alert::getSuccess('Depoimento excuído com sucesso!');
        break;
    }
  }

  /**
   * Método responsável por retornar o formulário de edição de um depoimento
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getEditTestimony(Request $request, int $id): string
  {
    //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
    $objTestimony = TestimonyModel::getTestimonyById($id);
    
    //VALIDA A INSTÂNCIA
    if(!$objTestimony instanceof TestimonyModel) {
      $request->getRouter()->redirect('/admin/testimonies');
    }

    //CONTEÚDO DO FORMULÁRIO
    $content = View::render('admin/modules/testimonies/form', [
      'title' => 'Editar Depoimento',
      'nome' => $objTestimony->nome,
      'mensagem' => $objTestimony->mensagem,
      'status' => self::getStatus($request)
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('EDITAR DEPOIMENTO | RS-Dev | Painel Admin', $content, 'testimonies');
  }

  /**
   * Método responsável por gravar a atualização de um depoimento
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setEditTestimony(Request $request, int $id): string
  {
    //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
    $objTestimony = TestimonyModel::getTestimonyById($id);
    
    //VALIDA A INSTÂNCIA
    if(!$objTestimony instanceof TestimonyModel) {
      $request->getRouter()->redirect('/admin/testimonies');
    }

    //POST VARS
    $postVars = $request->getPostVars();

    //ATUALIZA A INSTÂNCIA
    $objTestimony->nome = $postVars['nome'] ?? $objTestimony->nome;
    $objTestimony->mensagem = $postVars['mensagem'] ?? $objTestimony->mensagem;
    $objTestimony->atualizar();

    //REDIRECIONA O USUÁRIO
    $request->getRouter()->redirect("/admin/testimonies/{$objTestimony->id}/edit?status=updated");
  }

  /**
   * Método responsável por retornar o formulário de exclusão de um depoimento
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getDeleteTestimony(Request $request, int $id): string
  {
    //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
    $objTestimony = TestimonyModel::getTestimonyById($id);
    
    //VALIDA A INSTÂNCIA
    if(!$objTestimony instanceof TestimonyModel) {
      $request->getRouter()->redirect('/admin/testimonies');
    }

    //CONTEÚDO DO FORMULÁRIO
    $content = View::render('admin/modules/testimonies/delete', [
      'nome' => $objTestimony->nome,
      'mensagem' => $objTestimony->mensagem,
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('EXCLUIR DEPOIMENTO | RS-Dev | Painel Admin', $content, 'testimonies');
  }

  /**
   * Método responsável por excluir um depoimento
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setDeleteTestimony(Request $request, int $id): string
  {
    //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
    $objTestimony = TestimonyModel::getTestimonyById($id);
    
    //VALIDA A INSTÂNCIA
    if(!$objTestimony instanceof TestimonyModel) {
      $request->getRouter()->redirect('/admin/testimonies');
    }

    //EXCLUI O DEPOIMENTO
    $objTestimony->excluir();

    //REDIRECIONA O USUÁRIO
    $request->getRouter()->redirect('/admin/testimonies?status=deleted');
  }
}