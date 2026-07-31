<?php

class LController extends Controller
{
	public function __construct($params = array())
	{
		parent::__construct($params);
		
		$this->model = new Main();
	}
	
	public function index()
	{	
		$source = $this->params[0];
		$get = !empty($_GET) ? '&'.http_build_query($_GET) : '' ;

		header('HTTP/1.1 200 OK');
		header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'?l='.$source.$get);
		exit();
	}
}