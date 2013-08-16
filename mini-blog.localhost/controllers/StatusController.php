<?php

class StatusController extends Controller
{
    protected $auth_actions = array('index', 'post');
    
	public function indexAction()
	{    	
		$user = $this->session->get('user');
		
		$repo = $this->db_manager->get('Status');
		$statuses = $repo->fetchBAllPersonalArchivesByUserId($user['id']);
		
		$token = $this->generateCsrfToken('status/post');
		
		$params = array(
			'statuses' => $statuses,
			'body' => '',
			'_token' => $token,
		);
		return $this->render($params);
	}
	
	public function postAction()
	{
		if(!$this->request->isPost()) {
			$this->forward404();
		}
		
		$token = $this->request->getPost('_token');
		if(!$this->checkCsrfToken('status/post', $token)) {
			return $token;
			return $this->redirect('/');			
		}
		$body = $this->request->getPost('body');
		
		$errors =array();		
		$errors = $this->validate('body', $body);
	
		if(count($errors) != 0) {
			$params = array(
	            'errors'    => $errors,
	            'body'    => $body,
	            '_token'    => $this->generateCsrfToken('status/post')
			);
			return $this->render($params, 'index');
		}
		
		$user = $this->session->get('user');
		$this->db_manager->get('Status')->insert($user['id'], $body);
		
		return $this->redirect('/');
	}
	
	private function validate($pattern, $value)
	{
		$errors = array();
		
		if(!strlen($value)) {
			$errors[] = 'ひとことをにゅうりょくしてください';				
		}
		if(strlen($value >200)) {
			$errors[] = '200文字以内で';				
		}
		return $errors;
	}		
}