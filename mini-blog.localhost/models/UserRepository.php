<?php

class UserRepository extends DBRepository
{
	public function insert($user_name, $password)
	{
		$password = $this->hashPassword($password);
		$now = new DateTime();
		$sql = "INSERT INTO USER(user_name, password, created_at)
				VALUES(:user_name, :password, :created_at)";
		$params = array(
			':user_name' => $user_name,
			':password' => $password,
			':created_at' => $now->format('Y-m-d H:i:s'));
		
		$this->execute($sql, $params);
	}
	public function fetchByUserName($user_name)
	{
		$sql = "SELECT * FROM USER WHERE user_name = :user_name";
		$params = array(
			':user_name' => $user_name);
		return $this->fetch($sql, $params);
	}
	
	public function isUniqueUserName($user_name)
	{
		$sql = "SELECT * FROM USER WHERE user_name = :user_name";
		$params = array(
			':user_name' => $user_name);
		$result = $this->fetchAll($sql, $params);
		if(!$result){
			return true;
		}
		if(count($result)== 0) {
			return true;
		}
		return false;
		
	}
	
	public function hashPassword($password)
	{
		return sha1($password, 'SecletKey');
	}
}