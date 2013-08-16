<?php
require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__). '/../../core/ClassLoader.php';

class ClassLoaderTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
	    $this->classLoader = new ClassLoader;
		$this->classLoader->registerDir(dirname(__FILE__)."\dummy");
	}
	
	public function test_registerDir()
	{
		$result = (array)$this->classLoader;
		$this->assertSame(1, count($result["\0*\0dirs"]));
		$this->assertSame(dirname(__FILE__)."\dummy", $result["\0*\0dirs"][0]);
	}	
	
	public function test_register()
	{
		$this->assertSame(false, class_exists("TestClassForClassLoader", false));
		$this->classLoader->register();
		$this->assertSame(false, class_exists("TestClassForClassLoader", false));
		$this->test1 = new TestClassForClassLoader();
		$this->assertSame(true, class_exists("TestClassForClassLoader", false));
	}	
}