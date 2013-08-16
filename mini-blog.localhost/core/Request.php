<?php
class Request {
	
	public function isPost()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			return true;
		}
		return false;
	}
	public function getGet($name, $defult = null)
	{
		if(isset($_GET[$name])){
			return $_GET[$name];
		}
		return $defult;
	}
	public function getPost($name, $defult = null)
	{
		if(isset($_POST[$name])){
			return $_POST[$name];
		}
		return $defult;
	}
	
	public function getHost(){
		if(!empty($_SERVER['HTTP_HOST'])){
			return $_SERVER['HTTP_HOST'];
		}
		return $_SERVER['SERVER_NAME'];
	}
	
	public function isSsl()
	{
		if($_SERVER['HTTPS'] === 'on'){
			return true;
		}
		return false;
	}
	
	public function gerRequestUri()
	{
		if (isset($_SERVER['REQUEST_URI'])) {
			return $_SERVER['REQUEST_URI'];
		}
		
		$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
		if (isset($_SERVER['QUERY_STRING']) && '' != $_SERVER['QUERY_STRING']) {
			$_SERVER['REQUEST_URI'].='?'.$_SERVER['QUERY_STRING'];
		}		
		return $_SERVER['REQUEST_URI'];
	}
	
	public function getBaseUrl()
	{
		$sname =  $_SERVER['SCRIPT_NAME'];
		$ru = $this->gerRequestUri();
		if(0 === strpos($ru,$sname)) {
			return $sname;
		}
		if(0 === strpos($ru,dirname($sname))) {			
			return rtrim(dirname($sname),'/');
		}
		return '';
	}
	
	public function getPathInfo()
	{
		$ru = $this->gerRequestUri();
		$bu = $this->getBaseUrl();
		$pi = substr($ru, strlen($bu));
		if($bu == $ru){
			$pi = "";
		}
		if(strpos($pi,'?')){
			$pi = substr($pi, 0, strpos($pi,'?'));
		}
		return $pi;
	}
}