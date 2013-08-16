<?php

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

class ResponseTest extends PHPUnit_Extensions_OutputTestCase{

	/**
     * @runInSeparateProcess
     */
	public function test_send1(){
		$res = new Response();
		
		$this->expectOutputString('');
		$res->send();
	}
	/**
     * @runInSeparateProcess
     */
	public function test_send2(){
		$res = new Response();
		$res->setContent('hoho');
		$this->expectOutputString('hoho');
		$res->send();
	}
}