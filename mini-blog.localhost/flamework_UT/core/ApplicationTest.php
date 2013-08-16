<?php

require_once 'PHPUnit/Extensions/OutputTestCase.php';

class ApplicationTest extends PHPUnit_Extensions_OutputTestCase
{
	private $expected =<<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>404</title>
</head>
<body>
	XXMSGXX
</body>
</html>
EOF;

	/**
     * @runInSeparateProcess
     */
	public function test_setDebugMode1()
	{
		$test = new SampleApplication();
		$this->assertEquals(ini_get('display_errors'), 0);
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setDebugMode2()
	{
		$test = new SampleApplication(true);
		$this->assertEquals(ini_get('display_errors'), 1);
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_getViewDir()
	{
		$test = new SampleApplication(true);
		$this->assertEquals('C:\xampp\htdocs\mini-blog.localhost\flamework_UT\core\dummy/views', $test->getViewDir());
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_router()
	{
		$test = (array)new SampleApplication(true);
		$result = $test["\0*\0router"]->resolve('item/hoge');
		$this->assertSame('item',$result['controller']);
		$this->assertSame('hoge',$result['action']);
		$this->assertSame('/item/hoge',$result[0]);
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_render404Page1()
	{
		$test = new SampleApplication(true);		
		$method = new ReflectionMethod('Application','render404Page');
		$method->setAccessible(true);
		$method->invoke($test, new HttpNotFoundException());
		$result = (array)$test->getResponse();
		$expected = str_replace('XXMSGXX', '',$this->expected);
		$this->assertSame($expected, $result["\0*\0content"]);
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_render404Page2()
	{
		$test = new SampleApplication();		
		$method = new ReflectionMethod('Application','render404Page');
		$method->setAccessible(true);
		$method->invoke($test, new HttpNotFoundException());
		$result = (array)$test->getResponse();
		$expected = str_replace('XXMSGXX', 'Page not found.',$this->expected);
		$this->assertSame($expected, $result["\0*\0content"]);
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_render404Page3()
	{
		$test = new SampleApplication(true);		
		$method = new ReflectionMethod('Application','render404Page');
		$method->setAccessible(true);
		$method->invoke($test, new HttpNotFoundException("'& &"));
		$result = (array)$test->getResponse();
		$expected = str_replace('XXMSGXX', '&#039;&amp; &amp;',$this->expected);
		$this->assertSame($expected, $result["\0*\0content"]);
	}
}