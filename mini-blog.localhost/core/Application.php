<?php

abstract class Application
{
	protected $isDebug = false;
	protected $request;
	protected $response;
	protected $session;
	protected $db_manager;
	protected $router;
	protected $login_action = array();	
	
	public function __construct($isDebug = false)
	{
		$this->setDebugMode($isDebug);
		$this->initialize();
		$this->configure();
	}
	
	public function run()
	{
		$pi = $this->request->getPathInfo();
		$params = $this->router->resolve($pi);
		try {
			if ($params == null) {
				throw new HttpNotFoundException('No route found for '. $pi);			
			}			
			$controller = $params['controller'];
			$action = $params['action'];
			
			$this->runAction($controller, $action, $params);			
		} catch (HttpNotFoundException $e) {
			$this->render404Page($e);
		} catch (UnauthorizedActionException $e) {
			list($controller, $action) = $this->login_action;
			$this->runAction($controller, $action);			
		}
		$this->response->send();
	}
	
	protected function render404Page($e)
	{
		$this->response->setStatusCode('404', 'not found');
		$message = $this->isDebugMode() ? $e->getMessage() : 'Page not found.';
		$message = htmlspecialchars($message, ENT_QUOTES,'UTF-8');
		$content = $this->response->setContent(<<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>404</title>
</head>
<body>
	{$message}
</body>
</html>
EOF
		);
	}
	
	public function runAction($controller, $action, $params = array())
	{
		$controller_class_name = ucwords($controller). 'Controller';
		$controller_class = $this->findController($controller_class_name);
		
		if ($controller_class == false) {
			throw new HttpNotFoundException($controller_class_name. ' controller is not found.');
		}
		$content = $controller_class->run($action, $params);
		$this->response->setContent($content);
	}
	
	public function findController($controller_class_name)
	{
		if (!class_exists($controller_class_name)) {
			$controller_file = $this->getControllerDir(). '/'. $controller_class_name. '.php';
			
			if(!is_readable($controller_file)) {
				return false;
			}
			require_once $controller_file;
			
			if(!class_exists($controller_class_name)) {
				return false;				
			}
		}
		return new $controller_class_name($this);
	}
	
	protected function setDebugMode($isDebug)
	{
		if($isDebug) {
			ini_set('display_errors', 1);
			error_reporting(-1);
		} else {
			ini_set('display_errors', 0);			
		}
		$this->isDebug = $isDebug;
	}
	protected function initialize()
	{
		$this->request = new Request();
		$this->response = new Response();
		$this->session = new Session();
		$this->db_manager = new DbManager();
		$this->router = new Router($this->registerRoutes());	
	}
	protected function configure()
	{		
		//NOP
	}
	
	abstract public function getRootDir();
	abstract protected function registerRoutes();
	
	public function isDebugMode()
	{
		return $this->isDebug;
	}
	
	public function getRequest()
	{
		return $this->request;		
	}	
	
	public function getResponse()
	{
		return $this->response;	
	}
	
	public function getSession()
	{
		return $this->session;	
	}
	
	public function getDbManager()
	{
		return $this->db_manager;	
	}
	
	public function getControllerDir()
	{
		return $this->getRootDir().'/controllers';
	}
	
	public function getViewDir()
	{
		return $this->getRootDir().'/views';
	}
	
	public function getModelDir()
	{
		return $this->getRootDir().'/models';		
	}
	
	public function getWebDir()
	{
		return $this->getRootDir().'/webs';		
	}
}