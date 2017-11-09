<?php


/*
 * def. adresaru
 */
define('ROOT_DIR', dirname(__DIR__));
define('APP_DIR', __DIR__);


require ROOT_DIR . '/vendor/autoload.php';




$configurator = new Nette\Configurator;
$configurator->setDebugMode(true);
//$configurator->setDebugMode('23.75.345.200'); // enable for your remote IP
$configurator->enableTracy(ROOT_DIR . '/log');

$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory(ROOT_DIR . '/temp');

$configurator->createRobotLoader()->setAutoRefresh(true)
        ->addDirectory(APP_DIR)
        ->addDirectory(APP_DIR.'/components')
        ->register();

$configurator->addConfig(APP_DIR . '/config/config.neon');
$configurator->addConfig(APP_DIR . '/config/config.local.neon');


//new App\Components\ZendConfigFactory($config);

$container = $configurator->createContainer();

return $container;
