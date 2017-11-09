<?php

// zatim nepouzito

namespace App\Components;

/**
 * Description of ZendConfigControl
 * 
 * @author zaruba jan
 */
class ZendConfigControl extends \Nette\Application\UI\Control
{
  /*
   * @var \Zend\Config\Config
   */

  private $config;

  public function __construct(\Zend\Config\Config $config)
  {
    $this->config = $config;
  }

  public function getConfig()
  {
    return $this->config;
  }

}
