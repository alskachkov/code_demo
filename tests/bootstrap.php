<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/FunctionalTests/WebTestCase.php';
require_once __DIR__.'/../public_html/config.php';
require_once EA_LIB_DIR . 'Db.php';

$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4('Builder\\', __DIR__.DIRECTORY_SEPARATOR.'Builder', true);

$classLoader->add(null, __DIR__.'/../models/');
$classLoader->register();
