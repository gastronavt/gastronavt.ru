<?php

class Controller{
	protected $data;
	protected $model;
	public $view;
	protected $params;

	public function __construct($params = array() ){
		$this->view = new View();
		$this->params = $params;
	}
	
	public function getData(){
		return $this->data;
	}

	public function getModel(){
		return $this->model;
	}
	
	public function getView(){
		return $this->view;
	}

	public function getParams(){
		return $this->params;
	}
	
}
?>