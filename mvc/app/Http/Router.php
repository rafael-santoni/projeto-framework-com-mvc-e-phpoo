<?php

namespace App\Http;

use App\Http\Request;
use App\Http\Response;
use App\Http\Middleware\Queue as MiddlewareQueue;
use \Closure;
use \Exception;
use \ReflectionFunction;

class Router
{
  /**
   * URL completa do projeto (raiz)
   * @var string
   */
  private string $url = '';

  /**
   * Prefixo de todas as rotas
   * @var string
   */
  private string $prefix = '';

  /**
   * Índice de rotas
   * @var array
   */
  private array $routes = [];

  /**
   * Instância da classe Request
   * @var Request
   */
  private Request $request;

  /**
   * Método responsável por iniciar a classe
   * @param string $url
   */
  public function __construct(string $url)
  {
    $this->url = $url;
    $this->request = new Request($this);
    $this->setPrefix();
  }

  /**
   * Método responsável por definir o prefixo das rotas
   */
  private function setPrefix(): void
  {
    //INFORMAÇÕES DA URL ATUAL
    $parseUrl = parse_url($this->url);

    //DEFINE O PREFIXO
    $this->prefix = $parseUrl['path'] ?? '';
  }

  /**
   * Método responsável por adicionar uma rota na classe
   * @param string $method
   * @param string $route
   * @param array  $params
   */
  private function addRoute(string $method, string $route, array $params = []): void
  {
    //VALIDAÇÃO DOS PARÂMETROS
    foreach ($params as $key => $value) {
      if($value instanceof Closure) {
        $params['controller'] = $value;
        unset($params[$key]);
        continue;
      }
    }

    //MIDDLEWARES DA ROTA
    $params['middlewares'] = $params['middlewares'] ?? [];

    //VARIÁVEIS DA ROTA
    $params['variables'] = [];

    //PADRÃO DE VALIDAÇÃO DAS VARIÁVEIS DAS ROTAS
    $patternVariable = '/{(.*?)}/';
    if(preg_match_all($patternVariable, $route, $matches)) {
      $route = preg_replace($patternVariable, '(.*?)', $route);
      $params['variables'] = $matches[1];
    }

    //PADRÃO DE VALIDAÇÃO DA URL
    $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

    //ADICIONA A ROTA DENTRO DA CLASSE
    $this->routes[$patternRoute][$method] = $params;
  }

  /**
 * Método responsável por definir uma rota do tipo GET
   * @param  string $route
   * @param  array  $params
   * @return null|string
   */
  public function get(string $route, array $params =[]): ?string
  {
    return $this->addRoute('GET', $route, $params);
  }

  /**
 * Método responsável por definir uma rota do tipo POST
   * @param  string $route
   * @param  array  $params
   * @return null|string
   */
  public function post(string $route, array $params =[]): ?string
  {
    return $this->addRoute('POST', $route, $params);
  }

  /**
 * Método responsável por definir uma rota do tipo PUT
   * @param  string $route
   * @param  array  $params
   * @return null|string
   */
  public function put(string $route, array $params =[]): ?string
  {
    return $this->addRoute('PUT', $route, $params);
  }

  /**
 * Método responsável por definir uma rota do tipo DELETE
   * @param  string $route
   * @param  array  $params
   * @return null|string
   */
  public function delete(string $route, array $params =[]): ?string
  {
    return $this->addRoute('DELETE', $route, $params);
  }

  /**
   * Método responsável por retornar a URI desconsiderando o prefixo
   * @return string
   */
  private function getUri(): string
  {
    //URI DA REQUISIÇÃO
    $uri = $this->request->getUri();

    //SEPARA A URI DO PREFIXO
    $explodeUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

    //RETORNA A URI SEM PREFIXO
    return end($explodeUri);
  }

  /**
   * Método responsável por retornar os dados da rota atual
   * @return array
   */
  private function getRoute(): array
  {
    //URI
    $uri = $this->getUri();

    //MÉTODO DA REQUISIÇÃO
    $httpMethod = $this->request->getHttpMethod();

    //VALIDA AS ROTAS
    foreach ($this->routes as $patternRoute => $methods) {
      //VERIFICA SE A URI BATE COM O PADRÃO
      if(preg_match($patternRoute, $uri, $matches)) {
        //VERIFICA O MÉTODO
        if(isset($methods[$httpMethod])) {
          //REMOVE A PRIMEIRA POSIÇÃO
          unset($matches[0]);

          //VARIÁVEIS PROCESSADAS
          $keys = $methods[$httpMethod]['variables'];
          $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
          $methods[$httpMethod]['variables']['request'] = $this->request;

          //RETORNO DOS PARÂMETROS DA ROTA
          return $methods[$httpMethod];
        }

        //MÉTODO NÃO PERMITIDO/DEFINIDO
        throw new Exception("Método não permitido", 405);
      }
    }

    //URL NÃO ENCONTRADA
    throw new Exception("URL não encontrada", 404);
  }

  /**
   * Método responsável por executar a rota atual
   * @return Response
   */
  public function run(): Response
  {
    try {

      //OBTÉM A ROTA ATUAL
      $route = $this->getRoute();

      //VERIFICA O CONTROLADOR
      if(!isset($route['controller'])) {
        throw new Exception("A URL não pôde ser processada", 500);
      }

      //ARGUMENTOS DA FUNÇÃO
      $args = [];

      //REFLECTION
      $reflection = new ReflectionFunction($route['controller']);
      foreach ($reflection->getParameters() as $parameter) {
        $name = $parameter->getName();
        $args[$name] = $route['variables'][$name] ?? '';
      }

      // //RETORNA A EXECUÇÃO DA FUNÇÃO
      // return call_user_func_array($route['controller'], $args);

      //RETORNA A EXECUÇÃO DA FILA DE MIDDLEWARES
      return (new MiddlewareQueue($route['middlewares'], $route['controller'], $args))->next($this->request);

    } catch (Exception $e) {
      return new Response($e->getCode(), $e->getMessage());
    }
  }

  /**
   * Método responsável por retornar a URL atual
   * @return string
   */
  public function getCurrentUrl(): string
  {
    return $this->url . $this->getUri();
  }

  /**
   * Método responsável por redirecionar a URL
   * @param string $route
   */
  public function redirect(string $route): void
  {
    //URL
    $url = $this->url . $route;

    //EXECUTA O REDIRECT
    header("Location: {$url}");
    exit;
  }
}
