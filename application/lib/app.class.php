<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

class App{
	public static $router;
	
	public static $current_landing;
	public static $current_city;
	public static $current_organization;
	public static $current_aggregator;
	public static $current_user;
	
	public static $basket;
	
	public static $send_404;
	
	public static $resourses_folder;

	public static function getRouter(){
		return self::$router;
	}
	

	public static function run($uri, $link){
		self::$router = new Router($uri);

		self::$current_landing = BeanFactory::getBean('lngng_landings')->retrieve_by_string_fields(['link_c' => $link]);
		if (function_exists('date_default_timezone_set')){
			date_default_timezone_set(self::$current_landing->timezone_c);
		}
		self::$current_city = NFfunctions::getParentBean(self::$current_landing , 'city_cities');
		self::$current_organization = NFfunctions::getParentBean(self::$current_landing, 'orgns_organizations');
		
		session_start();
		
		//получаем пользователя
		if(!empty($_COOKIE['client_id'])){
			self::$current_user = BeanFactory::getBean('clnts_clients', $_COOKIE['client_id']);
		} elseif(!empty($_SESSION['client_id'])){
			self::$current_user = BeanFactory::getBean('clnts_clients', $_SESSION['client_id']);
		} else {
			self::$current_user =  null;
		}
		
		
		//считываем, что пользователь с мобильного приложения
		if(!empty($_GET['l']) && $_GET['l'] == 'mobile_android'){
			$_SESSION['mobile_app'] = true;
		}
		
		/*проверка не пришли ли с агрегатора*/
		if(!empty($_GET['referer'])){
			foreach(NFfunctions::getChildBeans(self::$current_landing, 'agrr_aggregator') as $agrrBean){
				if(parse_url($agrrBean->link_c)['host'] == parse_url($_GET['referer'])['host']){
					$agrrBean->link_c = $_GET['referer'];
					$_SESSION['current_aggregator'] = $agrrBean;
					break;
				}
			}
		}
		if(!empty($_SESSION['current_aggregator'])){
			self::$current_aggregator = $_SESSION['current_aggregator'];
		}
		/*END проверка не пришли ли с агрегатора*/
		
		self::$basket = new BasketController();
		$controller_file = CORE_FOLDER.'/application/controllers/'.self::$router->getController().'.controller.php';

		if(file_exists($controller_file)){
			$controller_class = ucfirst(self::$router->getController()).'Controller';
			$controller_method = strtolower(self::$router->getMethodPrefix().self::$router->getAction());
			$params = self::$router->getParams();
			$controller_object = new $controller_class($params);
		
			if(get_class($controller_object) == 'LController'){
				$params = array_merge(array($controller_method), $params);
				$controller_object = new $controller_class($params);
				$run_controller_method = $controller_object->index();
			}
			elseif(method_exists($controller_object, $controller_method)){
				$run_controller_method = $controller_object->$controller_method();
			} else {
				self::$send_404 = true;
				$controller_object =  new $controller_class();
				$run_controller_method = $controller_object->error_404();
			}
		} else {
			self::$send_404 = true;
			$controller_object = new MainController();
			$run_controller_method = $controller_object->error_404();
		}
	}		
}