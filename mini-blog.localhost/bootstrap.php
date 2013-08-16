<?php

require 'core/ClassLoader.php';

$classLoader = new ClassLoader;
$classLoader->registerDir(dirname(__FILE__)."\core");
$classLoader->registerDir(dirname(__FILE__)."\models");
$classLoader->register();