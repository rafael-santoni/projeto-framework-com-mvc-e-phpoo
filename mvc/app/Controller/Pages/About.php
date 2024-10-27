<?php

namespace App\Controller\Pages;

use App\Controller\Pages\Page as PageController;
use App\Model\Entity\Organization as OrganizationModel;
use App\Utils\View;

class About extends PageController
{
  /**
   * Método responsável por retornar o conteúdo (View) da Página de Sobre o Canal
   * @return string
   */
  public static function getAbout(): string
  {
    //DADOS DA ORGANIZAÇÃO
    $objOrganization = new OrganizationModel;

    //VIEW DA HOME
    $content = View::render('pages/about', [
      'name' => $objOrganization->name,
      'description' => $objOrganization->description,
      'site' => $objOrganization->site,
    ]);

    //RETORNA A VIEW DA PÁGINA
    return parent::getPage('SOBRE | RS-Dev', $content);
  }
}
