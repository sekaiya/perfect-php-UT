<?php

class SampleApplication extends Application
{	
	private $test = array(
		'/item/:action'
			=>array('controller' => 'item'),
	);
	
	public function getRootDir()
	{
		return dirname(__FILE__);
	}
	
	public function registerRoutes()
	{
		return $this->test;
	}
}