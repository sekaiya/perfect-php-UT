<?php

require_once 'PHPUnit/Autoload.php';

class RouterTest extends PHPUnit_Framework_TestCase{

	public function setUp()
	{
	    $this->router = new Router($this->test);
	}
	
	private $test = array(
		'/'
			=>array('controller' => 'home', 'action' => 'index'),
		'/user/edit'
			=>array('controller' => 'user', 'action' => 'edit'),
		'/:controller'
			=>array('action' => 'index'),
		'/item/:action'
			=>array('controller' => 'item'),
	);
	
	public function test_compileRoutes()
	{		
		$keys = array_keys($this->router->routes);
		$this->assertEquals('/',$keys[0]);
		$this->assertEquals('/user/edit',$keys[1]);
		$this->assertEquals('/(?P<controller>[^/]+)',$keys[2]);
		$this->assertEquals('/item/(?P<action>[^/]+)',$keys[3]);
	}
	public function test_resolve1()
	{
		$pi = '/user/edit';
		$result = $this->router->resolve($pi);
		$this->assertEquals('user',$result['controller']);
		$this->assertEquals('edit',$result['action']);
		$this->assertEquals('/user/edit',$result[0]);
	}	
	public function test_resolve2()
	{
		$pi = '/';
		$result = $this->router->resolve($pi);
		$this->assertEquals('home',$result['controller']);
		$this->assertEquals('index',$result['action']);
		$this->assertEquals('/',$result[0]);
	}
	public function test_resolve3()
	{
		$pi = '/hoge';
		$result = $this->router->resolve($pi);
		$this->assertEquals('hoge',$result['controller']);
		$this->assertEquals('index',$result['action']);
		$this->assertEquals('/hoge',$result[0]);
	}
	public function test_resolve4()
	{
		$pi = '/item/fuga';
		$result = $this->router->resolve($pi);
		$this->assertEquals('item',$result['controller']);
		$this->assertEquals('fuga',$result['action']);
		$this->assertEquals('/item/fuga',$result[0]);
	}
	
}