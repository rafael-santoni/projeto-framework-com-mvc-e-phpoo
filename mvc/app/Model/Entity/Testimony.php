<?php

namespace App\Model\Entity;

use Libs\MavCodes\DatabaseManager\Database;
use \PDOStatement;

class Testimony
{
  /**
   * ID do depoimento
   * @var integer
   */
  public int $id;

  /**
   * Nome do usuário que fez o depoimento
   * @var string
   */
  public string $nome;

  /**
   * Mensagem do depoimento
   * @var string
   */
  public string $mensagem;

  /**
   * Data de publicação do depoimento
   * @var string
   */
  public string $data;

  /**
   * Método responsável por cadastrar a instância atual no Banco de Dados
   * @return boolean
   */
  public function cadastrar(): bool
  {
    //DEFINE A DATA
    $this->data = date('Y-m-d H:i:s');

    //INSERE O DEPOIMENTO NO BANCO DE DADOS
    $this->id = (new Database('depoimentos'))->insert([
      'nome' => $this->nome,
      'mensagem' => $this->mensagem,
      'data' => $this->data,
    ]);

    //SUCESSO
    return true;
  }

  /**
   * Método responsável por retornar Depoimentos
   * @param  string $where
   * @param  string $order
   * @param  string $limit
   * @param  string $field
   * @return PDOStatement
   */
  public static function getTestimonies(string $where = null, string $order = null, string $limit = null, string $fields = '*'): PDOStatement
  {
    return (new Database('depoimentos'))->select($where, $order, $limit, $fields);
  }
}
