<?php

class Session
{
	public static $sessionStarted = false;
	public static $sessionRegenerated = false;
	
	public function __construct()
	{
		if(!self::$sessionStarted) {
			session_start();
		}
		self::$sessionStarted = true;		
	}
	
	public function get($name, $defult = null)
	{
		if(isset($_SESSION[$name])) {
			return $_SESSION[$name];
		}
		return $defult;
	}
	
	public function set($name, $value)
	{
		$_SESSION[$name] = $value;
		
	}
	public function remove($name)
	{
		unset($_SESSION[$name]);
	}

	public function clear()
	{
		$_SESSION = array();
	}

	public function regenerate()
	{
		if(!self::$sessionRegenerated) {
			session_regenerate_id(true);
		}
		self::$sessionRegenerated = true;		
	}

	public function setAuthenticated($bool)
	{
		$this->set('_authenticated', $bool);
		$this->regenerate();
	}

	public function isAuthenticated()
	{
		return $this->get('_authenticated', false);
	}
}