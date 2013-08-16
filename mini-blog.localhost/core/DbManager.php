<?php
class DbManager
{
	protected $connections = array();
	protected $repositories = array();
	protected $repository_connection_map = array();
	
	public function setRepositoryConnectionMap($repository_name, $name)
	{
		$this->repository_connection_map[$repository_name] = $name;
	}
	
	public function getRepositoryConnectionMap($repository_name)
	{
		if(isset($this->repository_connection_map[$repository_name])){
			$name = $this->repository_connection_map[$repository_name];
			return $this->getConnection($name);
		}
		return $this->getConnection();
	}
	
	public function connect ($name, $params)
	{
		$default = array(
			'dsn' => null,
			'user' => '',
			'password' => '',
			'options' => array(),
		);
		$params = array_merge($default,$params);
		
		$con = new PDO(
			$params['dsn'],
			$params['user'],
			$params['password'],
			$params['options']
		);
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$this->connections[$name] = $con;
	}
	
	public function getConnection($name = null)
	{
		if(is_null($name)) {
			return current($this->connections);
		}
		return $this->connections[$name];
	}
	
	public function get($repository_name)
	{
		if(!isset($this->repositories[$repository_name])) {
			$repository_class_name = $repository_name . "Repository";
			$con = $this->getRepositoryConnectionMap($repository_name);
			
			$repository = new $repository_class_name($con);
			$this->repositories[$repository_name] = $repository;
		}
		return $this->repositories[$repository_name];
	}
	
	public function __destruct()
	{
		foreach ($this->repositories as $repo) {
			unset($repo);
		}
		foreach ($this->connections as $con) {
			unset($con);
		}
	}
}