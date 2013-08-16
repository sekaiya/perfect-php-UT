<?php

class AccountController extends Controller
{
	public function signupAction()
	{
		$token = $this->generateCsrfToken('account/signup');
		
		return $this->render(array(
	            'user_name' => '',
	            'password'  => '',
	            '_token' => $token));
	}
	
	public function registerAction()
	{
		if(!$this->request->isPost()) {
			$this->forward404();
		}
		
		$token = $this->request->getPost('_token');
		if(!$this->checkCsrfToken('account/signup', $token)) {
			return $token;
			return $this->redirect('/account/signup');			
		}
		
		$user_name = $this->request->getPost('user_name');
		$password = $this->request->getPost('password');
		
		$errors =array();
		
		$errors = $this->validate('user_name', $user_name);
		$errors = array_merge($errors, $this->validate('password', $password));
		
		if(count($errors) != 0) {
			$params = array(
	            'errors'    => $errors,
	            'user_name' => $user_name,
	            'password'  => $password,
	            '_token'    => $this->generateCsrfToken('account/signup')
			);
			return $this->render($params, 'signup');
		}
		
		$repo = $this->db_manager->get('User');
		$repo->insert($user_name, $password);
		$this->session->setAuthenticated(true);
		
		$user = $repo->fetchByUserName($user_name);
		$this->session->set('user', $user);
		
		return $this->redirect('/');
		
	}
	private function validate($pattern, $value)
	{
		$errors = array();
		
		if(!strlen($value)) {
			$errors[] = 'please enter  '. $pattern;				
		}
		if($pattern == 'user_name') {
			
			if (!preg_match("/^[a-zA-Z0-9]+$/", $value)) {
				$errors[] = 'please use hankaku eisuji ';
			}
			if(strlen($value) < 3 || strlen($value) > 20) {
				$errors[] = 'user_name, please enter 3 ~ 20';				
			}
			if (!$this->db_manager->get('User')->isUniqueUserName($value)) {
	            $errors[] = 'user ID has already used';
	        }
		}	
		if($pattern == 'password') {
			if(strlen($value) < 4 || strlen($value)> 30) {
				$errors[] = 'password, please enter 4 ~ 30';							
			}
		}
		return $errors;
	}
}