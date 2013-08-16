<?php

require_once 'PHPUnit/Autoload.php';

class DbManagerTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{			
		$this->dbManager = new DbManager;
		$this->dbManager->connect('master', array(
			'dsn' => 'mysql:dbname=test; host=localhost',
			'user' => 'user',
			'password' => 'user',
		));
		$this->dbManager->connect('testcon', array(
			'dsn' => 'mysql:dbname=test; host=localhost',
			'user' => 'syuser',
			'password' => 'syuser',
		));
	}
	
	//指定がなければ最初のPDOインスタンスを返却
	public function test1()
	{
		$dbm = $this->dbManager;
		$this->assertSame($dbm->getConnection('master') ,$dbm->getConnection());
	}	
	
	//get時、一番最初に作成したconnectionのみ設定できる
	public function test2()
	{
		$dbm = $this->dbManager;
		$test_rp = $dbm->get('Test');
		$this->assertSame($test_rp->getConnection(), $dbm->getConnection('master'));
	}

	//setRepositoryConnectionMap をget前に実行すると任意のconnectionが設定できる
	public function test3()
	{
		$dbm = $this->dbManager;				
		$dbm->setRepositoryConnectionMap('Test', 'testcon');
		$test_rp = $dbm->get('Test');
		$this->assertSame($test_rp->getConnection(), $dbm->getConnection('testcon'));
	}
}
	