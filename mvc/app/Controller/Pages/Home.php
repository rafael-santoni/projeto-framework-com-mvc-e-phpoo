<?php

namespace App\Controller\Pages;

use App\Controller\Pages\Page as PageController;
use App\Model\Entity\Organization as OrganizationModel;
use App\Utils\View;

class Home extends PageController
{
  /**
   * Método responsável por retornar o conteúdo (View) da Home
   * @return string
   */
  public static function getHome(): string
  {
    //DADOS DA ORGANIZAÇÃO
    $objOrganization = new OrganizationModel;

    //VIEW DA HOME
    $content = View::render('pages/home', [
      'name' => $objOrganization->name,
      // 'description' => $objOrganization->description,
      // 'site' => $objOrganization->site,
    ]);

    //RETORNA A VIEW DA PÁGINA
    return parent::getPage('HOME | RS-Dev', $content);
  }
}
