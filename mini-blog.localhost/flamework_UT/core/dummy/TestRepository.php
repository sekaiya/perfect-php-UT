<?php

class TestRepository
{
	protected $con;
	public function __construct($con)
	{
		$this->con = $con;
	}
	public function getConnection()
	{
		return $this->con;
	}
	
}