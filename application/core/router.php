<?php
class Router{

	protected $uri;
	protected $controller;
	protected $action;
	protected $params;

	protected $route;
	protected $method_prefix;

	public function __construct($uri){
		$this->uri = urldecode(trim($uri, '/'));
		
		if($this->uri == 'main' || $this->uri == 'main/index'){
			header('HTTP/1.1 200 OK');
			header('Location: http://'.$_SERVER['HTTP_HOST']);
			exit();
		}

		// Get default
		$routes = array('default' => '');
		$this->route = 'template';
		$this->method_prefix = '';
		$this->controller = 'main';
		$this->action = 'index';

		$uri_parts = explode('?', $this->uri);

		//Get path like /lng/controller/action/param1/param2/.../...
		$path = $uri_parts[0];
		$path_parts = explode('/', $path);	

		if ( count($path_parts) ){
			//Get route at first element
			if ( in_array(strtolower(current($path_parts)) , array_keys($routes)) ){
				$this->route = strtolower(current($path_parts));
				$this->method_prefix = isset($routes[$this->route]) ? $routes[$this->route] : '';
				array_shift($path_parts);
			}

			// Get controller - next element of array
			if( current($path_parts) ){
				$this->controller = strtolower(current($path_parts));
				$this->controller = str_replace(" ", "", $this->controller);
				array_shift($path_parts);	
			}

			// Get action
			if( current($path_parts) ){
				$this->action = strtolower(current($path_parts));
				array_shift($path_parts);
			}
			
			//Get params
			$this->params = $path_parts;			
		}
	}


	public function getUri(){
		return $this->uri;
	}

	public function getController(){
		return $this->controller;
	}

	public function getAction(){
		return $this->action;
	}

	public function getParams(){
		return $this->params;
	}

	public function getRoute(){
		return $this->route;
	}

	public function getMethodPrefix(){
		return $this->method_prefix;
	}

}