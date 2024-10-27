<?php

namespace Libs\MavCodes\DatabaseManager;

use \PDO;
use \PDOException;
use \PDOStatement;

class Database {

	/**
	 * Host para conexão com o BD
	 * @var string
	 */
	private static string $host;

	/**
	 * Porta do Host para conexão com o BD
	 * @var string
	 */
	private static string $port;

	/**
	 * Nome do Bando de Dados
	 * @var string
	 */
	private static string $dbName;

	/**
	 * Usuário para conectar no BD
	 * @var string
	 */
	private static string $user;

	/**
	 * Senha de acesso do Usuário para conectar no BD
	 * @var string
	 */
	private static string $pass;

	/**
	 * Nome da tabela que a ser manipulada
	 * @var string
	 */
	public string $table;

	/**
	 * Instância de conexão com o Banco de Dados
	 * @var PDO
	 */
	public PDO $connection;

	/**
	 * Instancia a conexão
	 * @param string $table
	 */
	public function __construct(string $table=null)
  {
		$this->table = $table;
		$this->setConnection();
	}

	/**
	 * Método responsável por criar a conexão com o BD
	 */
	private function setConnection(): void
  {
		try{

			// $this->connection = new PDO('mysql:host='.self::HOST.';dbname='.self::NAME, self::USER, self::PASS);
			$this->connection = new PDO('mysql:host='.self::$host.':'.self::$port.';dbname='.self::$dbName, self::$user, self::$pass);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch(PDOException $e){
			die('Erro: '.$e->getMessage());
		}
	}

	/**
	 * Método responsável por executar QUERIES no Banco de Dados
	 * @param string $query
	 * @param array $params [ field => value ]
	 * @return PDOStatement
	 */
	public function execute(string $query, array $params=[]): PDOStatement
  {
		try{

			$statement = $this->connection->prepare($query);
			$statement->execute($params);

			return $statement;

		} catch(PDOException $e){
			die('Erro: '.$e->getMessage());
		}
	}

	/**
	 * Método responsável por inserir dados no BD
	 * @param array $values [ field => value ]
	 * @return integer (ID inserido)
	 */
	public function insert(array $values): int
  {
		//Dados da QUERY
		$fields = array_keys($values);
		$binds  = array_pad([],count($fields),'?');

		//Cria a QUERY
		$query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';

		//Executa a QUERY
		$var =
		$this->execute($query,array_values($values));

		//retorna o ID inserido
		return $this->connection->lastInsertId();
	}

	/**
	 * Método para obter dados do BD
	 * @param string $where
	 * @param string $order
	 * @param string $limit
	 * @param string $fields
	 * @return PDOStatement
	 */
	public function select(string $where=null, string $order=null, string $limit=null, string $fields='*'): PDOStatement
  {
		//Trata os dados para a QUERY

		$where = (!is_null($where)) ? 'WHERE '.$where : '';
		$order = (!is_null($order)) ? 'ORDER BY '.$order : '';
		$limit = (!is_null($limit)) ? 'LIMIT '.$limit : '';

		$query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;

		return $this->execute($query);
	}

	/**
	 * Método para atualizar dados no Banco de Dados
	 * @param string $where
	 * @param array $values [ field => values ]
	 * @return bool
	 */
	public function update(string $where, array $values): bool
  {
		//Trata os campos e dados da QUERY
		$fields = array_keys($values);
		$dados = array_values($values);

		$query = 'UPDATE '.$this->table.' SET '.implode('=?,',$fields).'=? WHERE '.$where;

		$this->execute($query,$dados);

		return true;
	}

	/**
	 * Método para excluir dados no Banco de Dados
	 * @param string $where
	 * @return bool
	 */
	public function delete(string $where): bool
  {
		$query = 'DELETE FROM '.$this->table.' WHERE '.$where;

		$this->execute($query);

		return true;
	}

	/**
	 * Método responsável por adicionar as configurações de conexão com o banco de dados
	 * @param string $host
	 * @param string $dbName
	 * @param string $user
	 * @param string $pass
	 * @param integer $port
	 */
	public static function config(string $host, string $dbName, string $user, string $pass, int $port):void
  {
		self::$host = $host;
		self::$dbName = $dbName;
		self::$user = $user;
		self::$pass = $pass;
		self::$port = $port;
	}
}
