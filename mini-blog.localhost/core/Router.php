<?php

class Router {
	public $routes;
	
	public function __construct($definitions)
	{
		$this->routes = $this->compileRoutes($definitions);
	}
	
	public function compileRoutes($definitions)
	{
		$routes = array();
		foreach($definitions as $url => $params) {
			$tokens = explode('/',ltrim($url, '/'));
			foreach($tokens as $i => $token) {
				if(0 === strpos($token, ':')){
					$token = '(?P<'. substr($token, 1).'>[^/]+)';
				}
				$tokens[$i] = $token;
			}
			$pattern = '/'.implode('/', $tokens);
			$routes[$pattern] = $params;
		}
		return $routes;
	}
	
	public function resolve($path_info)
	{
		if('/' !== substr($path_info, 0, 1)){
			$path_info = '/'.$path_info;
		}
		foreach($this->routes as $pattern => $params) {
			
			if(preg_match("#^". $pattern. "$#", $path_info, $match)){
				$params = array_merge($params, $match);
								
				return $params;
			}
		}
		return false;
	}
}
