<?php

require dirname(__FILE__). '/core/ClassLoader.php';

$classLoader = new ClassLoader;

$classLoader->registerDir(dirname(__FILE__)."/core");
$classLoader->registerDir(dirname(__FILE__)."/models");

$classLoader->registerDir(dirname(__FILE__)."/flamework_UT/core");
$classLoader->registerDir(dirname(__FILE__)."/flamework_UT/core/dummy");
$classLoader->registerDir(dirname(__FILE__)."/mini_blog_UT/models");

$classLoader->register();