<?php

namespace App\Presenters;

use Nette;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter implements IPresenter
{

  /**
   * @var \App\Components\ZendConfigFactor
   */
  private $zendConfigFactory;

  public function injectZendConfigFactory(\App\Components\ZendConfigFactory $factory)
  {
    $this->zendConfigFactory = $factory;
  }

  public function getConfig()
  {
    return $this->zendConfigFactory->create();
  }

  public function bashColorToHtml($string)
  {
    $colors = [
        '/\[0;30m(.*?)\[0m/s' => '<span class="black">$1</span>',
        '/\[0;31m(.*?)\[0m/s' => '<span class="red">$1</span>',
        '/\[0;32m(.*?)\[0m/s' => '<span class="green">$1</span>',
        '/\[0;33m(.*?)\[0m/s' => '<span class="brown">$1</span>',
        '/\[0;34m(.*?)\[0m/s' => '<span class="blue">$1</span>',
        '/\[0;35m(.*?)\[0m/s' => '<span class="purple">$1</span>',
        '/\[0;36m(.*?)\[0m/s' => '<span class="cyan">$1</span>',
        '/\[0;37m(.*?)\[0m/s' => '<span class="light-gray">$1</span>',
        '/\[1;30m(.*?)\[0m/s' => '<span class="dark-gray">$1</span>',
        '/\[1;31m(.*?)\[0m/s' => '<span class="light-red">$1</span>',
        '/\[1;32m(.*?)\[0m/s' => '<span class="light-green">$1</span>',
        '/\[1;33m(.*?)\[0m/s' => '<span class="yellow">$1</span>',
        '/\[1;34m(.*?)\[0m/s' => '<span class="light-blue">$1</span>',
        '/\[1;35m(.*?)\[0m/s' => '<span class="light-purple">$1</span>',
        '/\[1;36m(.*?)\[0m/s' => '<span class="light-cyan">$1</span>',
        '/\[1;37m(.*?)\[0m/s' => '<span class="white">$1</span>',
    ];

    return preg_replace(array_keys($colors), $colors, $string);
  }

}
