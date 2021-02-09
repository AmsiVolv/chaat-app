<?php

use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/** @var ClassLoader $loader */
$loader = require dirname(__DIR__) . '/vendor/autoload.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);
return $loader;
