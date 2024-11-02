<?php

namespace App\Controller\Api;

use Exception;
// use App\Utils\View;
use App\Http\Request;
use App\Controller\Api\Api as ApiController;
use Libs\MavCodes\DatabaseManager\Pagination;
use App\Model\Entity\User as UserModel;

class User extends ApiController
{
  /**
   * Método responsável por obter a renderização dos itens de usuários para a página
   * @param  Request    $request
   * @param  null|Pagination $objPagination
   * @return array
   */
  private static function getUserItems(Request $request, null|Pagination &$objPagination): array
  {
    //USUÁRIOS
    $itens = [];

    //QUANTIDADE TOTAL DE REGISTROS
    $quantidadeTotal = UserModel::getUsers(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

    //PÁGINA ATUAL
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;

    //INSTÂNCIA DE PAGINAÇÃO
    $objPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

    //RESULTADOS DA PÁGINA
    $results = UserModel::getUsers(null, 'id ASC', $objPagination->getLimit());

    //RENDERIZA O ITEM
    while ($objUser = $results->fetchObject(UserModel::class)) {
      $itens[] = [
        'id' => (int)$objUser->id,
        'nome' => $objUser->nome,
        'email' => $objUser->email,
      ];
    }

    //RETORNA OS USUÁRIOS
    return $itens;
  }

  /**
   * Método responsável por retornar os usuários cadastrados
   * @param Request $request
   * @return array
   */
  public static function getUsers(Request $request): array
  {
    return [
      'usuarios' => self::getUserItems($request, $objPagination),
      'paginacao' => parent::getPagination($request, $objPagination),
    ];
  }

  /**
   * Método responsável por retornar os detalhes de um usuário
   * @param Request $request
   * @param integer $id
   * @return array
   */
  public static function getUser(Request $request, int $id): array
  {
    //VALIDA O ID DO USUÁRIO
    if(!is_numeric($id)) {
      throw new Exception("O id '{$id}' não é válido", 400);
    }

    //BUSCA USUÁRIO
    $objUser = UserModel::getUserById($id);

    //VALIDA SE O USUÁRIO EXISTE
    if(!$objUser instanceof UserModel) {
      throw new Exception("O usuário id: {$id} não foi encontrado", 404);
    }

    //RETORNA OS DETALHES DO USUÁRIO
    return [
      'id' => (int)$objUser->id,
      'nome' => $objUser->nome,
      'email' => $objUser->email
    ];
  }

  /**
   * Método responsável por cadastrar um novo usuário
   *
   * @param Request $request
   * @return array
   */
  public static function setNewUser(Request $request): array
  {
    //POST VARS
    $postVars = $request->getPostVars();
    
    //VALIDA OS CAMPOS OBRIGATÓRIOS
    if(!isset($postVars['nome']) || !isset($postVars['email']) || !isset($postVars['senha'])) {
      throw new Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios", 400);
    }

    //VALIDA A DUPLICAÇÃO DE USUÁRIOS
    $objUser = UserModel::getUserByEmail($postVars['email']);
    if($objUser instanceof UserModel) {
      //RETORNA O ERRO
      throw new Exception("O e-mail '{$postVars['email']}' já está em uso.", 400);
    }

    //NOVO USUÁRIO
    $objUser = new UserModel;
    $objUser->nome = $postVars['nome'];
    $objUser->email = $postVars['email'];
    $objUser->senha = password_hash($postVars['senha'], PASSWORD_DEFAULT);
    $objUser->cadastrar();

    //RETORNA OS DETALHES DO USUÁRIO CADASTRADO
    return [
      'id' => (int)$objUser->id,
      'nome' => $objUser->nome,
      'email' => $objUser->email,
    ];
  }

  /**
   * Método responsável por atualizar um usuário
   * @param Request $request
   * @param integer $id
   * @return array
   */
  public static function setEditUser(Request $request, int $id): array
  {
    //POST VARS
    $postVars = $request->getPostVars();
    
    //VALIDA OS CAMPOS OBRIGATÓRIOS
    if(!isset($postVars['nome']) || !isset($postVars['email']) || !isset($postVars['senha'])) {
      throw new Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios", 400);
    }

    //BUSCA O USUÁRIO NO BANCO DE DADOS
    $objUser = UserModel::getUserById($id);

    //VALIDA SE O USUÁRIO EXISTE
    if(!$objUser instanceof UserModel) {
      throw new Exception("O usuário id: {$id} não foi encontrado", 404);
    }

    //VALIDA A DUPLICAÇÃO DE USUÁRIOS
    $objUserEmail = UserModel::getUserByEmail($postVars['email']);
    if($objUserEmail instanceof UserModel && $objUserEmail->id != $objUser->id) {
      //RETORNA O ERRO
      throw new Exception("O e-mail '{$postVars['email']}' já está em uso.", 400);
    }

    //ATUALIZA O USUÁRIO
    $objUser->nome = $postVars['nome'];
    $objUser->email = $postVars['email'];
    $objUser->senha = password_hash($postVars['senha'], PASSWORD_DEFAULT);
    $objUser->atualizar();

    //RETORNA OS DETALHES DO USUÁRIO ATUALIZADO
    return [
      'id' => (int)$objUser->id,
      'nome' => $objUser->nome,
      'email' => $objUser->email
    ];
  }

  /**
   * Método responsável por excluir um usuário
   * @param Request $request
   * @param integer $id
   * @return array
   */
  public static function setDeleteUser(Request $request, int $id): array
  {
    //BUSCA O USUÁRIO NO BANCO DE DADOS
    $objUser = UserModel::getUserById($id);

    //VALIDA A INSTÂNCIA
    if(!$objUser instanceof UserModel) {
      throw new Exception("O usuário id: {$id} não foi encontrado", 404);
    }

    //IMPEDE A EXCLUSÃO DO PRÓPRIO CADASTRO
    if($objUser->id == $request->user->id) {
      throw new Exception("Não é possível excluir o cadastro atualmente conectado.", 400);
    }

    //EXCLUI O USUÁRIO
    $objUser->excluir();

    //RETORNA O SUCESSO DA EXCLUSÃO
    return [
      'sucesso' => true
    ];
  }
}
