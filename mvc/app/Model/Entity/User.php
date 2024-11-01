<?php

namespace App\Model\Entity;

use Libs\MavCodes\DatabaseManager\Database;
use \PDOStatement;

class User
{
  /**
   * ID do usuário
   * @var integer
   */
  public int $id;

  /**
   * Nome do usuário
   * @var string
   */
  public string $nome;

  /**
   * E-mail do usuário
   * @var string
   */
  public string $email;

  /**
   * Senha do usuário
   * @var string
   */
  public string $senha;

  /**
   * Método responsável por cadastrar a instância atual no Banco de Dados
   *
   * @return boolean
   */
  public function cadastrar(): bool
  {
    //INSERE A INSTÂNCIA NO BANCO DE DADOS
    $this->id = (new Database('usuarios'))->insert([
      'nome' => $this->nome,
      'email' => $this->email,
      'senha' => $this->senha
    ]);

    //SUCESSO
    return true;
  }

  /**
   * Método responsável por atualizar a instância atual no Banco de Dados
   * @return boolean
   */
  public function atualizar(): bool
  {
    return (new Database('usuarios'))->update("id = {$this->id}", [
      'nome' => $this->nome,
      'email' => $this->email,
      'senha' => $this->senha
    ]);
  }

  /**
   * Método responsável por excluir um usuário do Banco de Dados
   * @return boolean
   */
  public function excluir(): bool
  {
    return (new Database('usuarios'))->delete("id = {$this->id}");
  }

  /**
   * Método responsável por retornar um usuário com base no ID
   *
   * @param integer $id
   * @return boolean|User
   */
  public static function getUserById(int $id): bool|User
  {
    return self::getUsers("id = {$id}")->fetchObject(self::class);
  }

  /**
   * Método responsável por retornar um usuário com base em seu e-mail
   * @param  string $email
   * @return boolean|User
   */
  public static function getUserByEmail(string $email): bool|User
  {
    // return (new Database('usuarios'))->select('email = "' . $email . '"')->fetchObject(self::class);
    return self::getUsers('email = "' . $email . '"')->fetchObject(self::class);
  }

  
  /**
   * Método responsável por retornar Usuários
   * @param  string $where
   * @param  string $order
   * @param  string $limit
   * @param  string $field
   * @return PDOStatement
   */
  public static function getUsers(string $where = null, string $order = null, string $limit = null, string $fields = '*'): PDOStatement
  {
    return (new Database('usuarios'))->select($where, $order, $limit, $fields);
  }
}
