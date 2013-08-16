<?php

class StatusRepository extends DBRepository
{
	public function insert($user_id, $body)
	{
		$now = new DateTime();
		
		$sql = "INSERT INTO STATUS(user_id, body, created_at)
				VALUES(:user_id, :body, :created_at)";
		
		$params = array(
			':user_id' => $user_id,
			':body' => $body,
			':created_at' => $now->format('Y-m-d H:i:s'));
		
		$this->execute($sql, $params);
	}
	public function fetchBAllPersonalArchivesByUserId($user_id)
	{
		$sql = "SELECT a.*, u.user_name 
			FROM status a
				LEFT JOIN USER u ON a.user_id = u.id
			WHERE u.id = :user_id
			ORDER BY a.created_at DESC";
		
		$params = array(
			':user_id' => $user_id);
		return $this->fetchAll($sql, $params);
	}
}