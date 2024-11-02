<?php

namespace App\Http;

use App\Http\Router;
use App\Model\Entity\User as UserModel;

class Request
{
  /**
   * Instância da classe Router
   * @var Router
   */
  private Router $router;

  /**
   * Método HTTP da requisição
   * @var string
   */
  private string $httpMethod;

  /**
   * URI da página
   * @var string
   */
  private string $uri;

  /**
   * Parâmetros da URL (GET)
   * @var array
   */
  private array $queryParams = [];

  /**
   * Variáveis recebidas no POST da página
   * @var array
   */
  private array $postVars = [];

  /**
   * Cabeçalho da requisição
   * @var array
   */
  private array $headers = [];

  /**
   * Usuário autenticado
   * @var UserModel
   */
  public UserModel $user;

  /**
   * Construtor da classe
   * @param Router $router
   */
  public function __construct(Router $router)
  {
    $this->router = $router;
    $this->queryParams = $_GET ?? [];
    // $this->postVars = $_POST ?? [];
    $this->headers = getallheaders();
    $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
    // $this->uri = $_SERVER['REQUEST_URI'] ?? '';
    $this->setUri();
    $this->setPostVars();
  }

  /**
   * Método responsável por definir as variáveis do POST
   * @return boolean
   */
  private function setPostVars(): bool
  {
    //VERIFICA O MÉTODO DA RQEUISIÇÃO
    if($this->httpMethod == 'GET') return false;

    //POST PADRÃO
    $this->postVars = $_POST ?? [] ;

    //POST JSON
    $inputRaw = file_get_contents('php://input');

    $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
    return true;
  }

  /**
   * Método responsável por definir a URI
   */
  private function setUri(): void
  {
    //URI COMPLETA (COM PARÂMETROS GET)
    $this->uri = $_SERVER['REQUEST_URI'] ?? '';

    //REMOVE, SE HOUVER, OS PARÂMENTROS GET DA URI
    $explodeUri = explode('?', $this->uri);
    $this->uri = $explodeUri[0];
  }

  /**
   * Método responsável por retornar a instância da classe Router
   * @return Router
   */
  public function getRouter(): Router
  {
    return $this->router;
  }

  /**
   * Método responsável por retornar o método HTTP da requisição
   * @return string
   */
  public function getHttpMethod(): string
  {
    return $this->httpMethod;
  }

  /**
   * Método responsável por retornar a URI da requisição
   * @return string
   */
  public function getUri(): string
  {
    return $this->uri;
  }

  /**
   * Método responsável por retornar os cabeçalhos da requisição
   * @return array
   */
  public function getHeaders(): array
  {
    return $this->headers;
  }

  /**
   * Método responsável por retornar os parâmetros da URL da requisição
   * @return array
   */
  public function getQueryParams(): array
  {
    return $this->queryParams;
  }

  /**
   * Método responsável por retornar as variáveis POST da requisição
   * @return array
   */
  public function getPostVars(): array
  {
    return $this->postVars;
  }
}
