<?php

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

class SessionTest extends PHPUnit_Extensions_OutputTestCase
{
	/**
     * @runInSeparateProcess
     */
	public function test_construct(){
		$this->assertSame(Session::$sessionStarted, false);
		$session = new Session;
		$this->assertSame(Session::$sessionStarted, true);
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_get1(){
		$session = new Session;
		$this->assertSame($session->get('hoge'), null);
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_get2(){
		$session = new Session;
		$session->set('hoge', 'hugahuga');
		$session->get('hoge');
		$this->assertSame($_SESSION['hoge'], 'hugahuga');
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_set(){
		$session = new Session;
		$session->set('hoge', 'huga');
		$this->assertSame($_SESSION['hoge'], 'huga');
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_remove(){
		$session = new Session;
		$session->set('hoge', 'huga');
		$this->assertSame($_SESSION['hoge'], 'huga');
		$session->remove('hoge');
		$this->assertSame($session->get('hoge'), null);
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_clear(){
		$session = new Session;
		$session->set('hoge', 'huga');
		$this->assertSame(count($_SESSION), 1);
		$session->clear();
		$this->assertSame(count($_SESSION), 0);
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_regenerate1(){
		$session = new Session;
		$oldID = session_id();
		$session->regenerate();
		$this->assertNotSame(session_id(), $oldID);
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_regenerate2(){
		$session = new Session;
		
		$session->regenerate();
		$new1ID = session_id();
		
		$session->regenerate();
		$new2ID = session_id();
		
		$this->assertSame($new1ID, $new2ID);
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setAuthenticated1(){
		$session = new Session;
		$session->setAuthenticated(true);
		$this->assertSame($_SESSION['_authenticated'], true);
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setAuthenticated2(){
		$session = new Session;
		$oldID = session_id();
		$session->setAuthenticated(true);
		$this->assertNotSame(session_id(), $oldID);
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_isAuthenticated(){
		$session = new Session;
		$this->assertSame($session->isAuthenticated(), false);
		$session->setAuthenticated(true);
		$this->assertSame($session->isAuthenticated(), true);
	}
	
}