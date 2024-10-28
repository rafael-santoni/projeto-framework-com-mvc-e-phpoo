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
   * Método responsável por retornar um usuário com base em seu e-mail
   * @param  string $email
   * @return boolean|User
   */
  public static function getUserByEmail(string $email): bool|User
  {
    return (new Database('usuarios'))->select('email = "' . $email . '"')->fetchObject(self::class);
  }
}
