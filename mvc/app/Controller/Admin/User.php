<?php

namespace App\Controller\Admin;

// use App\Controller\Admin\Alert;
use App\Http\Request;
use App\Controller\Admin\Alert;
use App\Controller\Admin\Page as PageController;
use App\Model\Entity\User as UserModel;
use App\Utils\View;
use Libs\MavCodes\DatabaseManager\Pagination;

class User extends PageController
{
  /**
   * Método responsável por obter a renderização dos itens de usuários para a página
   * @param  Request    $request
   * @param  null|Pagination $objPagination
   * @return string
   */
  private static function getUserItems(Request $request, null|Pagination &$objPagination): string
  {
    //USUÁRIOS
    $itens = '';

    //QUANTIDADE TOTAL DE REGISTROS
    $quantidadeTotal = UserModel::getUsers(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

    //PÁGINA ATUAL
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;

    //INSTÂNCIA DE PAGINAÇÃO
    $objPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

    //RESULTADOS DA PÁGINA
    $results = UserModel::getUsers(null, 'id DESC', $objPagination->getLimit());

    //RENDERIZA O ITEM
    while ($objUser = $results->fetchObject(UserModel::class)) {
      $itens .= View::render('admin/modules/users/item', [
        'id' => $objUser->id,
        'nome' => $objUser->nome,
        'email' => $objUser->email,
      ]);
    }

    //RETORNA OS DEPOIMENTOS
    return $itens;
  }
  
  /**
   * Método responsável por renderizar a View de listagem de Usuários
   * @param Request $request
   * @return string
   */
  public static function getUsers(Request $request): string
  {
    //CONTEÚDO DA HOME
    $content = View::render('admin/modules/users/index', [
      'itens' => self::getUserItems($request, $objPagination),
      'pagination' => parent::getPagination($request, $objPagination),
      'status' => self::getStatus($request),
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('USUÁRIOS | RS-Dev | Painel Admin', $content, 'users');
  }

  /**
   * Método responsável por retornar o formulário de cadastro de um novo usuário
   * @param Request $request
   * @return string
   */
  public static function getNewUser(Request $request): string
  {
    //CONTEÚDO DO FORMULÁRIO
    $content = View::render('admin/modules/users/form', [
      'title' => 'Cadastrar Usuário',
      'nome' => '',
      'email' => '',
      'senha' => '',
      'status' => self::getStatus($request)
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('CADASTRAR USUÁRIO | RS-Dev | Painel Admin', $content, 'users');
  }

  /**
   * Método responsável por cadastrar um novo usuário no Banco de Dados
   * @param Request $request
   * @return string
   */
  public static function setNewUser(Request $request): string
  {
    //POST VARS
    $postVars = $request->getPostVars();
    $nome = $postVars['nome'] ?? '';
    $email = $postVars['email'] ?? '';
    $senha = $postVars['senha'] ?? '';

    //VALIDA O E-MAIL DO USUÁRIO
    $objUser = UserModel::getUserByEmail($email);
    if($objUser instanceof UserModel) {
      //REDIRECIONA O USUÁRIO
      $request->getRouter()->redirect("/admin/users/new?status=duplicated");
    }
    
    //NOVA INSTÂNCIA DE USUÁRIO
    $objUser = new UserModel;
    $objUser->nome = $nome;
    $objUser->email = $email;
    $objUser->senha = password_hash($senha, PASSWORD_DEFAULT);
    $objUser->cadastrar();

    //REDIRECIONA O USUÁRIO
    $request->getRouter()->redirect("/admin/users/{$objUser->id}/edit?status=created");
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
        return Alert::getSuccess('Usuário criado com sucesso!');
        break;
      
      case 'updated':
        return Alert::getSuccess('Usuário atualizado com sucesso!');
        break;
      
      case 'deleted':
        return Alert::getSuccess('Usuário excuído com sucesso!');
        break;
      
      case 'duplicated':
        return Alert::getError('O e-mail digitado já está sendo utilizado por outro usuário.');
        break;
    }
  }

  /**
   * Método responsável por retornar o formulário de edição de um usuário
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getEditUser(Request $request, int $id): string
  {
    //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
    $objUser = UserModel::getUserById($id);
    
    //VALIDA A INSTÂNCIA
    if(!$objUser instanceof UserModel) {
      $request->getRouter()->redirect('/admin/users');
    }

    //CONTEÚDO DO FORMULÁRIO
    $content = View::render('admin/modules/users/form', [
      'title' => 'Editar Usuário',
      'nome' => $objUser->nome,
      'email' => $objUser->email,
      'status' => self::getStatus($request)
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('EDITAR USUÁRIO | RS-Dev | Painel Admin', $content, 'users');
  }

  /**
   * Método responsável por gravar a atualização de um usuário
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setEditUser(Request $request, int $id): string
  {
    //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
    $objUser = UserModel::getUserById($id);
    
    //VALIDA A INSTÂNCIA
    if(!$objUser instanceof UserModel) {
      $request->getRouter()->redirect('/admin/users');
    }

    //POST VARS
    $postVars = $request->getPostVars();
    $nome = $postVars['nome'] ?? '';
    $email = $postVars['email'] ?? '';
    $senha = $postVars['senha'] ?? '';

    //VALIDA O E-MAIL DO USUÁRIO
    $objUserByEmail = UserModel::getUserByEmail($email);
    if($objUserByEmail instanceof UserModel && $objUserByEmail->id != $id) {
      //REDIRECIONA O USUÁRIO
      $request->getRouter()->redirect("/admin/users/{$id}/edit?status=duplicated");
    }

    //ATUALIZA A INSTÂNCIA
    $objUser->nome = $nome;
    $objUser->email = $email;
    $objUser->senha = password_hash($senha, PASSWORD_DEFAULT);
    $objUser->atualizar();

    //REDIRECIONA O USUÁRIO
    $request->getRouter()->redirect("/admin/users/{$objUser->id}/edit?status=updated");
  }

  /**
   * Método responsável por retornar o formulário de exclusão de um usuário
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getDeleteUser(Request $request, int $id): string
  {
    //OBTÉM O USUÁRIO DO BANCO DE DADOS
    $objUser = UserModel::getUserById($id);
    
    //VALIDA A INSTÂNCIA
    if(!$objUser instanceof UserModel) {
      $request->getRouter()->redirect('/admin/users');
    }

    //CONTEÚDO DO FORMULÁRIO
    $content = View::render('admin/modules/users/delete', [
      'nome' => $objUser->nome,
      'email' => $objUser->email,
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('EXCLUIR USUÁRIO | RS-Dev | Painel Admin', $content, 'users');
  }

  /**
   * Método responsável por excluir um usuário
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setDeleteUser(Request $request, int $id): string
  {
    //OBTÉM O USUÁRIO DO BANCO DE DADOS
    $objUser = UserModel::getUserById($id);
    
    //VALIDA A INSTÂNCIA
    if(!$objUser instanceof UserModel) {
      $request->getRouter()->redirect('/admin/users');
    }

    //EXCLUI O USUÁRIO
    $objUser->excluir();

    //REDIRECIONA O USUÁRIO
    $request->getRouter()->redirect('/admin/users?status=deleted');
  }
}