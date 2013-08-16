<?php

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__).'/../../MiniBlogApplication.php';

class UserRepositoryTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{			
		$this->dbManager = new DbManager;
		$this->dbManager->connect('master', array(
			'dsn' => 'mysql:dbname=mini_blog; host=localhost',
			'user' => 'user',
			'password' => 'user',
		));
	}
	/**
     * @runInSeparateProcess
     */
	public function test_insert()
	{	
		$repo = $this->dbManager->get('User');
		$now = new DateTime();
		$time = $now->format('is');
		$user_name = "suzuki".$time;
		$repo->insert($user_name, "password");
		
		$result = $repo->fetchByUserName($user_name);
		$this->assertSame($user_name, $result['user_name']);
		
	}
	/**
     * @runInSeparateProcess
     */
	public function test_fetchByUserName()
	{	
		$repo = $this->dbManager->get('User');
		$result = $repo->fetchByUserName("inai");
		$this->assertSame(false, $result);
	}
	/**
     * @runInSeparateProcess
     */
	public function test_isUniqueUserName1()
	{	
		$repo = $this->dbManager->get('User');
		$result = $repo->isUniqueUserName("inai");
		$this->assertSame(true, $result);
	}
	/**
     * @runInSeparateProcess
     */
	public function test_isUniqueUserName2()
	{	
		$repo = $this->dbManager->get('User');
		if($repo->isUniqueUserName("iru")) {
			$repo->insert("iru", "password");
		}
		
		$result = $repo->isUniqueUserName("iru");
		$this->assertSame(false, $result);
	}
	
 public function __sleep()
  {
    return array();
  }
}