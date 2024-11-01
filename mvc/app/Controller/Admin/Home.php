<?php

namespace App\Controller\Admin;

use App\Utils\View;
use App\Http\Request;
// use App\Model\Entity\User as UserModel;
// use App\Session\Admin\Login as LoginSession;
use App\Controller\Admin\Page as PageController;
use App\Controller\Admin\Alert;

class Home extends PageController
{
  /**
   * Método responsável por renderizar a View de Home do Painel
   * @param Request $request
   * @return string
   */
  public static function getHome(Request $request): string
  {
    //CONTEÚDO DA HOME
    $content = View::render('admin/modules/home/index', [

    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('HOME | RS-Dev | ADMIN', $content, 'home');
  }
}
