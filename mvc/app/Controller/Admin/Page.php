<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Utils\View;
use Libs\MavCodes\DatabaseManager\Pagination;

abstract class Page
{
  /**
   * Módulos disponíveis no Painel
   * @var array
   */
  private static array $modules = [
    'home' => [
      'label' => 'Home',
      'link' => URL . '/admin'
    ],
    'testimonies' => [
      'label' => 'Depoimentos',
      'link' => URL . '/admin/testimonies'
    ],
    'users' => [
      'label' => 'Usuários',
      'link' => URL . '/admin/users'
    ],
  ];

  /**
   * Método responsável por retornar o conteúdo (view) da estrutura genérica da Pagina do Painel
   * @param  string $title
   * @param  string $content
   * @return string
   */
  public static function getPage(string $title, string $content): string
  {
    return View::render('admin/page', [
      'title' => $title,
      'content' => $content
    ]);
  }

  /**
   * Método responsável por renderizar a View do Menu do Painel
   * @param string $currentModule
   * @return string
   */
  private static function getMenu(string $currentModule): string
  {
    //LINKS DO MENU
    $links = '';

    //ITERA OS MÓDULOS
    foreach (self::$modules as $hash => $module) {
      $links .= View::render('admin/menu/link', [
        'label' => $module['label'],
        'link' => $module['link'],
        'current' => ($hash == $currentModule) ? 'text-danger' : ''
      ]);
    }

    //RETORNA A RENDERIZAÇÃO DO MENU
    return View::render('admin/menu/box', [
      'links' => $links
    ]);
  }

  /**
   * Método responsável por renderizar a View do Painel com conteúdos dinâmicos
   * @param string $title
   * @param string $content
   * @param string $currentModule
   * @return string
   */
  public static function getPanel(string $title, string $content, string $currentModule): string
  {
    //RENDERIZA A VIEW DO PAINEL
    $contentPanel = View::render('admin/panel', [
      'menu' => self::getMenu($currentModule),
      'content' => $content
    ]);

    //RETORNA A PÁGINA RENDERIZADA
    return self::getPage($title, $contentPanel);
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
      $links .= View::render('admin/pagination/link', [
        'page' => $page['page'],
        'link' => $link,
        'active' => $page['current'] ? 'active' : '',
      ]);
    }

    //RENDERIZA A 'BOX' DE PAGINAÇÃO
    return View::render('admin/pagination/box', [
      'links' => $links,
    ]);
  }
}
