<?php

require_once 'PHPUnit/Autoload.php';

class RequestTest extends PHPUnit_Framework_TestCase{
	
	public function test_getBaseUrl_getPathInfo_1()
	{
		$req = new Request;
		$_SERVER['REQUEST_URI'] = "/foo/bar/list";
		$_SERVER['SCRIPT_NAME'] = "/foo/bar/index.php";		
		$this->assertSame("/foo/bar", $req->getBaseUrl());
		$this->assertSame("/list", $req->getPathInfo());
	}
	public function test_getBaseUrl_getPathInfo_2()
	{
		$req = new Request;
		$_SERVER['REQUEST_URI'] = "/index.php/list?foo=bar";
		$_SERVER['SCRIPT_NAME'] = "/index.php";
		$this->assertSame("/index.php", $req->getBaseUrl());
		$this->assertSame("/list", $req->getPathInfo());
	}
	public function test_getBaseUrl_getPathInfo_3()
	{
		$req = new Request;
		$_SERVER['REQUEST_URI'] = "/";
		$_SERVER['SCRIPT_NAME'] = "/index.php";			
		$this->assertSame("", $req->getBaseUrl());
		$this->assertSame("/", $req->getPathInfo());
	}
	
	//TODO: サーバー上でunitテストを実行する方法
	//Undefined index: REQUEST_METHOD
	
	public function test_isPost()
	{	
		$req = new Request;
		//$this->assertEquals(false, $req->isPost());
	}
}