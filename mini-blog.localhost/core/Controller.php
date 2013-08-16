<?php

abstract class Controller
{
	protected $controller_name;
	protected $action_name;
	protected $apprication;
	protected $request;
	protected $response;
	protected $session;
	protected $db_manager;
	protected $auth_actions;
	
	
	public function __construct($apprication)
	{
		$this->controller_name = strtolower(substr(get_class($this), 0, -10));
		$this->apprication = $apprication;
		$this->request = $apprication->getRequest();
		$this->response = $apprication->getResponse();
		$this->session = $apprication->getSession();
		$this->db_manager = $apprication->getDbManager();
	}
	
	public function run($action, $params = array())
	{
        $this->action_name = $action;
        
		$action_method_name = $action. 'Action';
		if(!method_exists($this, $action_method_name)) {
			$this->forward404();
		}
		
		if ($this->needsAuthentication($action) && !$this->session->isAuthenticated()) {
			throw new UnauthorizedActionException();
		}		
		return $this->$action_method_name($params);
	}
	
	protected function forward404()
    {
        throw new HttpNotFoundException('Forwarded 404 page from '
            . $this->controller_name . '/' . $this->action_name);
    }
    
	protected function needsAuthentication($action)
	{
		if($this->auth_actions === true) {
			return true;
		}
		if(!is_array($this->auth_actions)) {
			return false;
		}
		if(in_array($action, $this->auth_actions)) {
			return true;
		}
		return false;
	}
	
	public function render($variables = array(), $template = null, $layout = 'layout')
	{
		$defaults = array(
			'request' => $this->request,
			'base_url' => $this->request->getBaseUrl(),
			'session' => $this->session,
		);		
		$view = new View($this->apprication->getViewDir(), $defaults);
		
		if (is_null($template)) {
			$template = $this->action_name;
		}
		$path = $this->controller_name. '/'. $template;
		return $view->render($path, $variables, $layout);
	}
	public function redirect($url)
	{
		if (!preg_match('#https?://#', $url)) {
			$protcol = $this->request->isSsl()? 'https://' :'http://';
			$host = $this->request->getHost();
			$base_url = $this->request->getBaseUrl();
			
			$url = $protcol. $host. $base_url. $url;
		}
		
		$this->response->setStatusCode(302, 'Found');
		$this->response->setHttpHeader('Location', $url);
	}
	
	public function generateCsrfToken($form_name)
	{
		$key = 'csrf_tokens/'. $form_name;
		$tokens = $this->session->get($key);
		if (count($tokens) >= 10) {
			array_shift($tokens);
		}
		$token = sha1($form_name. session_id(). microtime());
 		$tokens[] = $token;
		$this->session->set($key, $tokens);
		
		return $token;
	}
	
	public function checkCsrfToken($form_name, $token)
	{
		$key = 'csrf_tokens/'. $form_name;
		$tokens = $this->session->get($key);
		$pos = array_search($token, $tokens, true);
		
		if (false === $pos) {
			return false;
		}
		unset($tokens[$pos]);
		$this->session->set($key, $tokens);
		return true;
	}
}
