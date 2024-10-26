<?php

namespace App\Model\Entity;

class Organization
{
  /**
   * ID da organização
   * @var integer
   */
  public int $id = 1;

  /**
   * Nome da organização
   * @var string
   */
  public string $name = 'RS-Dev => Canal MavCodes';

  /**
   * Site da organização
   * @var string
   */
  public string $site = 'https://www.youtube.com/@MavCodes';

  /**
   * Decrição da organização
   * @var string
   */
  public string $description = 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Consectetur ullam quos doloremque esse architecto velit iure voluptate, vel in nisi error illo dignissimos excepturi assumenda numquam quis repudiandae? Omnis, veritatis. Iusto eveniet rem eligendi officiis, facere adipisci porro obcaecati voluptas exercitationem nisi. Adipisci nesciunt accusantium iste quasi officiis.';
}
