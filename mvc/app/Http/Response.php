<?php

namespace App\Http;

class Response
{
  /**
   * Código do Status HTTP
   * @var integer
   */
  private int $httpCode = 200;

  /**
   * Cabeçalho da resposta
   * @var array
   */
  private array $headers = [];

  /**
   * Tipo do conteúdo da resposta
   * @var string
   */
  private string $contentType = 'text/html';

  /**
   * Conteúdo da resposta
   * @var mixed
   */
  private mixed $content;

  /**
   * Método responsável por iniciar a classe e definir os valores
   * @param int    $httpCode
   * @param mixed  $content
   * @param string $contentType
   */
  public function __construct(int $httpCode, mixed $content, string $contentType = 'text/html')
  {
    $this->httpCode = $httpCode;
    $this->content = $content;
    $this->setContentType($contentType);
  }

  /**
   * Método responsável por alterar o Content-Type da resposta
   * @param string $contentType
   */
  public function setContentType(string $contentType): void
  {
    $this->contentType = $contentType;
    $this->addHeader('Content-Type', $contentType);
  }

  /**
   * Método responsável por adicionar um registro no cabeçalho da resposta
   * @param string $key
   * @param string $value
   */
  public function addHeader(string $key, string $value): void
  {
    $this->headers[$key] = $value;
  }

  /**
   * Método responsável por enviar os cabeçalhos para o navegador
   */
  private function sendHeaders(): void
  {
    //STATUS
    http_response_code($this->httpCode);

    //ENVIAR CABEÇALHOS
    foreach ($this->headers as $key => $value) {
      header("{$key}: {$value}");
    }
  }

  /**
   * Método responsável por enviar a resposta para o usuário
   */
  public function sendResponse(): void
  {
    //ENVIA OS CABEÇALHOS
    $this->sendHeaders();

    //IMPRIME OU RETORNA O CONTEÚDO
    switch ($this->contentType) {
      case 'text/html':
        echo $this->content;
        exit;
      case 'application/json':
        echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
  }
}
