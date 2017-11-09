<?php

namespace App\Components;

use Zend\Config\Factory as ZendFactory;

/**
 * Description of ZendConfigFactory
 *
 * @author zaruba jan
 */
class ZendConfigFactory
{

  /**
   *
   * @var \Zend\Config\Config
   */
  public $config = null;

  public function create()
  {
    if (!$this->config) {
      /**
       *  zend konfigurace
       */
      $configFiles = array_merge(
              array(APP_DIR . '/config/config.php'), glob(APP_DIR . '/config/autoload/*.{php}', GLOB_BRACE)
      );

      $this->config = ZendFactory::fromFiles($configFiles, true);
      $this->config->setReadOnly();
    }
    return $this->config;
//    return new ZendConfigControl($config);
  }

}
