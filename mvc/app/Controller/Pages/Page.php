<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Utils\View;
use Libs\MavCodes\DatabaseManager\Pagination;

abstract class Page
{
  /**
   * Método responsável por renderizar o topo da página genérica
   * @return string
   */
  private static function getHeader(): string
  {
    return View::render('pages/header');
  }

  /**
   * Método responsável por renderizar o rodapé da página genérica
   * @return string
   */
  private static function getFooter(): string
  {
    return View::render('pages/footer');
  }

  /**
   * Método responsável por renderizar o layout de paginação
   * @param  Request    $request
   * @param  Pagination $objPagination
   * @return string
   */
  public static function getPagination(Request $request, Pagination $objPagination): string
  {
    //PÁGINAS
    $pages = $objPagination->getPages();

    //VERIFICA A QUANTIDADE DE PÁGINAS
    if(count($pages) <= 1) return '';

    //LINKS
    $links = '';

    //URL ATUAL SEM PARÂMETROS (GETS)
    $url = $request->getRouter()->getCurrentUrl();

    //GET
    $queryParams = $request->getQueryParams();

    //RENDERIZA OS LINKS
    foreach ($pages as $page) {
      //ALTERA A PÁGINA
      $queryParams['page'] = $page['page'];

      //LINK
      $link = $url . '?' . http_build_query($queryParams);

      //VIEW
      $links .= View::render('pages/pagination/link', [
        'page' => $page['page'],
        'link' => $link,
        'active' => $page['current'] ? 'active' : '',
      ]);
    }

    //RENDERIZA A 'BOX' DE PAGINAÇÃO
    return View::render('pages/pagination/box', [
      'links' => $links,
    ]);
  }

  /**
   * Método responsável por retornar o conteúdo (View) da Página Genérica
   * @param  string $title
   * @param  string $content
   * @return string
   */
  public static function getPage(string $title, string $content): string
  {
    return View::render('pages/page', [
      'title' => $title,
      'header' => self::getHeader(),
      'content' => $content,
      'footer' => self::getFooter(),
    ]);
  }
}
