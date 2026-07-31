<?php

class telegramController extends Controller
{
	public function __construct($params  = array())
	{
		parent::__construct($params);
		
		$this->model = new Main();

		$this->model->getTitle();
		$this->model->getDescription();
	}
	
	public function index()
	{
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		
		$data = $this->model->getData();

		$this->view->generateWithTemplate(
			'main_view.php', 
			'template_main_telegram_view.php',
			$data
		);
	}
	
	public function iframe()
	{
		$this->view->generate(
			'telegram/iframe_view.php',
			$this->model->getData()
		);
	}
	
	public function menu()
	{
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		
		$data = $this->model->getData();
		$this->view->generateWithTemplate(
			'menu_view.php', 
			'template_main_telegram_view.php',
			$data
		);
	}
	
	public function captcha(){
		$randomnr = rand(1000, 9999);
		$_SESSION['job_captcha'] = md5($randomnr);
	 
		$im = imagecreatetruecolor(100, 38);
	 
		$white = imagecolorallocate($im, 255, 255, 255);
		$grey = imagecolorallocate($im, 150, 150, 150);
		$black = imagecolorallocate($im, 0, 0, 0);
	 
		imagefilledrectangle($im, 0, 0, 200, 35, $black);
		
		$font = '/var/www/domains_nginx/landings_style2_core/assets_new/fonts/Vergilia/Vergilia.ttf';

		imagettftext($im, 20, 4, 22, 30, $grey, $font, $randomnr);
	 
		imagettftext($im, 20, 4, 15, 32, $white, $font, $randomnr);
		
		//prevent caching on client side:
		header("Expires: Wed, 1 Jan 1997 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	 
		header ("Content-type: image/gif");
		imagegif($im);
		imagedestroy($im);
	}
	
	public function interior()
	{
		$this->model->getTitle('Интерьер '.App::$current_organization->name_rus_c.' в городе '.App::$current_city->name);
		$this->model->getDescription('Ознакомьтесь с интерьером нашего заведения с помощью нашей фото-галереи');
		$this->model->getCustomSEO('interior');
		
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		
		$data = $this->model->getData();
		$this->view->generateWithTemplate(
			'interior_view.php', 
			'template_main_telegram_view.php',
			$data
		);
	}

	public function error_404()
	{	
		header('HTTP/1.1 404 OK');
		
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		$this->model->getTitle('Страница не найдена.');
		
		$data = $this->model->getData();
		$this->view->generateWithTemplate(
			'404_view.php', 
			'template_main_telegram_view.php', 
			$data
		);
	}
	
	public function lead()
	{	
		if(!empty($_SESSION['current_order']->CUSTOM_products)){
			$this->model->getCurrentProductGroups();
			$this->model->getCurrentBranchs();
			$this->model->getCurrentAreas('show_order_c');
			$this->model->getLandings();
			$this->model->getOrderProducts($_SESSION['current_order']);
			$this->model->getCurrentStreets();
			$this->model->getTitle('Оформление доставки продукции '.App::$current_organization->name_rus_c.' в городе '.App::$current_city->name);
			$this->model->getDescription('На этой странице Вы можете оформить свой заказ в городе '.App::$current_city->name.'. Наши диспетчеры свяжутся с Вами сразу после оформления.');
			$data = $this->model->getData();
			
			$this->view->generateWithTemplate(
				'lead_view.php', 
				'template_main_telegram_view.php', 
				$data
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram');
			exit();
		}
	}
	
	public function pwa()
	{	
		$this->model->getTitle('Страница - '.App::$current_organization->name_rus_c);
		$this->view->generate(
			'telegram/pwa_view.php',
			$this->model->getData()
		);
	}
	
	public function login()
	{	
		if(empty($_COOKIE['client_id'])){
			$this->model->getTitle('Страница авторизации - '.App::$current_organization->name_rus_c);
			$this->model->getDescription('Авторизуйтесь на сайте '.App::$current_organization->name_rus_c.' ,указав свой номер телефона. После авторизации вы сможете отслеживать статус выполнения заказа.');
			$this->view->generate(
				'telegram/login_view.php',
				$this->model->getData()
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/profile');
			exit();
		}
	}
	
	public function logout()
	{	
		if(!empty($_COOKIE['client_id'])){
			unset($_COOKIE["client_id"]);
			setcookie('client_id', null, -1, '/'); 
		}
		if(!empty($_COOKIE['client_phone'])){
			unset($_COOKIE["client_phone"]);
			setcookie('client_phone', null, -1, '/'); 
		}
		
		header('HTTP/1.1 200 OK');
		if($this->params[0] == 'login'){
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/login');
		} else {
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram');
		}
		exit();
	}
	
	//отправка звонка клиенту для авторизации и получение кода (последние четыре цифры)
	public function get_call_code()
	{	
		$phone = preg_replace("/[^0-9]/", '', $this->params[0]);
		
		setcookie('client_phone', $phone, time()+2592000, '/');
		if(App::$current_landing->id == '6cf59565-a918-ff2e-9d5d-5bf32ae80893' || App::$current_landing->id == 'fe620a49-6ecc-4a90-5181-62f550b36f2a'){//пряников
			$login = 'pryanikov38';
			$password = '89501442426Abc';
		} else{
			$login = 'yenon';
			$password = '18291829';
		}
		if($login == 'pryanikov38' || $login == 'yenon') {
			$GLOBALS['log']->fatal('Авторзация: '.$phone.' инфо: '.$_SERVER['HTTP_USER_AGENT'].' ip: '.$_SERVER['REMOTE_ADDR']);
			if($_SERVER['HTTP_USER_AGENT'] == 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148') {
				$GLOBALS['log']->fatal('Пошел нахуй пидарас: '.$phone);
				echo md5(random_int(1111,9999));
				return;
			}
		}
		
		//$result = NFfunctions::get_curl('https://smsc.ru/sys/send.php?login='.$login.'&psw='.$password.'&phones='.$phone.'&mes=code&call=1&fmt=3');

		/*$request_message = json_decode($result , JSON_OBJECT_AS_ARRAY);
		if(isset($request_message['id']) && isset($request_message['code'])){
			$code = substr($request_message['code'], -4); //берем последние 4ре символа

			echo md5($code);
		}*/
		echo md5(random_int(1111,9999));
	}
	
	//отправка звонка клиенту для авторизации и получения кода (сами генерим код и диктует робот)
	public function get_call_voice_code()
	{	
		$phone = preg_replace("/[^0-9]/", '', $this->params[0]);
		$imgcode = preg_replace("/[^0-9]/", '', $this->params[1]);
		
		setcookie('client_phone', $phone, time()+2592000, '/');
		if(App::$current_landing->id == '6cf59565-a918-ff2e-9d5d-5bf32ae80893' || App::$current_landing->id == 'fe620a49-6ecc-4a90-5181-62f550b36f2a'){//пряников
			$login = 'pryanikov38';
			$password = '89501442426Abc';
		} else{
			$login = 'yenon';
			$password = '18291829';
		}
		
		$code = random_int(1111,9999);
		
		if(!empty($phone) && !empty($imgcode)){
			$result = NFfunctions::get_curl('https://smsc.ru/sys/send.php?login='.$login.'&psw='.$password.'&phones='.$phone.'&mes=%D0%9A%D0%BE%D0%B4%20%D0%B0%D0%B2%D1%82%D0%BE%D1%80%D0%B8%D0%B7%D0%B0%D1%86%D0%B8%D0%B8:%20'.$code.'&call=1&fmt=3&imgcode='.$imgcode.'&userip='.$_SERVER["REMOTE_ADDR"]);
			//$request_message = json_decode($result, JSON_OBJECT_AS_ARRAY);
		}
		echo md5($code);
	}
	
	//получение номера телефона на который должен позвонить пользователь, чтобы авторизоваться
	public function get_phone_number()
	{	
		$phone = preg_replace("/[^0-9]/", '', $this->params[0]);
		
		setcookie('client_phone', $phone, time()+2592000, '/');
		
		if(App::$current_landing->id == '6cf59565-a918-ff2e-9d5d-5bf32ae80893' || App::$current_landing->id == 'fe620a49-6ecc-4a90-5181-62f550b36f2a'){//пряников
			$login = 'pryanikov38';
			$password = '89501442426Abc';
		} else{
			$login = 'yenon';
			$password = '18291829';
		}
	
		if(!empty($phone)){
			$result = NFfunctions::get_curl('https://smsc.ru/sys/wait_call.php?login='.$login.'&psw='.$password.'&phone='.$phone.'&fmt=3');
			$result_json = json_decode($result, true);
			echo NFfunctions::phone($result_json['phone']);
		}
	}
	
	public function check_call_auth()
	{	
		$phone = preg_replace("/[^0-9]/", '', $this->params[0]);
		
		global $db;
		$client_id = $db->fetchRow(
			$db->query("
				SELECT cc.id 
				FROM clnts_clients_cstm cc_cstm 
				JOIN clnts_clients cc ON cc.id = cc_cstm.id_c AND cc.deleted = 0 
				WHERE cc_cstm.phone_c = '".NFfunctions::phone($phone)."'
				AND cc_cstm.auth_status_c = '03';
			")
		)['id'];
	
		if(!empty($client_id)){
			$db->query("UPDATE clnts_clients_cstm cc_cstm SET cc_cstm.auth_status_c = '01' WHERE id_c = '".$client_id."'");
			
			setcookie('client_id', $client_id, time()+2592000, '/');
			echo 'ok';
		} else {
			echo 'bad';
		}
	}
	
	//проверка введенного кода клиентом
	public function check_call_code()
	{	
		$call_code = preg_replace("/[^0-9]/", '', $this->params[0]);
		$md5_code = $this->params[1];
		if(md5($call_code) == $md5_code){//авторизация прошла
			global $db;
			$client_id = $db->fetchRow($db->query("SELECT cc.id FROM clnts_clients_cstm cc_cstm JOIN clnts_clients cc ON cc.id = cc_cstm.id_c AND cc.deleted = 0 WHERE cc_cstm.phone_c = '".NFfunctions::phone($_COOKIE['client_phone'])."';"))['id'];
			if(empty($client_id)){
				$newClientBean = BeanFactory::newBean('clnts_clients');
				$newClientBean->name = "НЕ УКАЗАНО";
				$newClientBean->city_cities_id_c = App::$current_city->id;
				$newClientBean->assigned_user_id = App::$current_landing->assigned_user_id;
				$newClientBean->phone_c = NFfunctions::phone($_COOKIE['client_phone']);
				$newClientBean = $newClientBean->save();
				
				$client_id = $newClientBean->id;
			}
			setcookie('client_id', $client_id, time()+2592000, '/');
			echo 'ok';
		} else {
			echo 'bad';
		}	
	}
	
	//проверка введенного кода клиентом
	public function check_telegram_code()
	{	
		$code = preg_replace("/[^0-9]/", '', $this->params[0]);
		$phone = preg_replace("/[^0-9]/", '', $this->params[1]);

		global $db;
		
		$subscriber = $db->fetchRow($db->pQuery(
			"SELECT
				tts.id,
				tts_cstm.auth_code_c,
                tts_cstm.phone_c
			FROM tgsub_telegram_subscribers tts
			JOIN tgsub_telegram_subscribers_cstm tts_cstm ON tts_cstm.id_c = tts.id AND tts.deleted = 0
			JOIN tgath_telegram_auth_tgsub_telegram_subscribers_1_c tta_tts ON tta_tts.tgath_teled8d1cribers_idb = tts.id AND tta_tts.deleted = 0
			JOIN tgath_telegram_auth_cstm tta_cstm ON tta_cstm.id_c = tta_tts.tgath_tele1ca0am_auth_ida
            JOIN tgath_telegram_auth tta ON tta.id = tta_cstm.id_c AND tta.deleted = 0
            JOIN tgath_telegram_auth_lngng_landings_1_c tta_ll ON tta_ll.tgath_telegram_auth_lngng_landings_1tgath_telegram_auth_ida = tta.id AND tta_ll.deleted = 0 AND tta_ll.tgath_telegram_auth_lngng_landings_1lngng_landings_idb = '?'
			WHERE 
				tts_cstm.phone_c = '?'
				AND tts_cstm.auth_code_c = '?'",
			[
				App::$current_landing->id, 
				$phone, 
				$code
			]
		));
		

		if(!empty($subscriber['id'])){//авторизация прошла
			global $db;
			$client_id = $db->fetchRow($db->query("SELECT cc.id FROM clnts_clients_cstm cc_cstm JOIN clnts_clients cc ON cc.id = cc_cstm.id_c AND cc.deleted = 0 WHERE cc_cstm.phone_c = '".NFfunctions::phone($phone)."';"))['id'];
			
			//если клиента нет, то создаем
			if(empty($client_id)){
				$newClientBean = BeanFactory::newBean('clnts_clients');
				$newClientBean->name = "НЕ УКАЗАНО";
				$newClientBean->city_cities_id_c = App::$current_city->id;
				$newClientBean->assigned_user_id = App::$current_landing->assigned_user_id;
				$newClientBean->phone_c = NFfunctions::phone($phone);
	
				$client_id = $newClientBean->save();
				
				//прикрепляем к подписке телеграмм
				$db->query("
					INSERT INTO clnts_clients_tgsub_telegram_subscribers_1_c
						(id, date_modified, deleted, clnts_clients_tgsub_telegram_subscribers_1clnts_clients_ida, clnts_clieb089cribers_idb) 
						VALUES ('".create_guid()."',NOW(),0,'".$client_id."','".$subscriber['id']."')
				");
			}
			setcookie('client_id', $client_id, time()+2592000, '/');
			echo 'ok';
		} else {
			echo 'bad';
		}

	}
	
	public function profile()
	{	
		if(!empty(App::$current_user->id)) {
			if(!empty($_SESSION['current_order'])){
				$this->model->getOrderProducts($_SESSION['current_order']);
			}
			$this->model->getTitle('Профиль пользователя');
			$this->model->getDescription('Вы можете просмотреть статус заказа, информацию о текущем и прошлых заказах. А также изменить информацию о своем профиле.');

			$this->view->generateWithTemplate(
				'profile_view.php', 
				'template_main_telegram_view.php', 
				$this->model->getData()
			);
		} else {
			if(!empty($_COOKIE['client_id'])){
				unset($_COOKIE["client_id"]);
				setcookie('client_id', null, -1, '/'); 
			}
			if(!empty($_COOKIE['client_phone'])){
				unset($_COOKIE["client_phone"]);
				setcookie('client_phone', null, -1, '/'); 
			}
		
		
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram');
			exit();
		}
	}
	
	public function update_user_settings()
	{	
		if(!empty(App::$current_user->id)) {
			$client_name_c = htmlspecialchars($_POST['client_name_c']);
			$email_c = htmlspecialchars($_POST['email_c']);
			$instagram_c = htmlspecialchars($_POST['instagram_c']);
			
			global $db;
			if($client_name_c){
				$db->query("UPDATE clnts_clients SET name = '".$client_name_c."' WHERE id='".App::$current_user->id."'");
			}
			if($email_c || $instagram_c){
				$db->query("UPDATE clnts_clients_cstm SET email_c = '".$email_c."', instagram_c = '".$instagram_c."' WHERE id_c = '".App::$current_user->id."'");
			}
			
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/profile');
			exit();
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram');
			exit();
		}
	}
	
	
	public function contact()
	{
		$this->model->getTitle('Контактная информация - '.App::$current_organization->name_rus_c.' в городе '.App::$current_city->name);
		$this->model->getDescription('Вы можете связаться с нами по номеру телефона '.App::$current_landing->phone1_c.'. Мы нахоимся по адресу: '.str_replace(PHP_EOL, ' ', strip_tags(html_entity_decode(App::$current_landing->address_c))));
		$this->model->getCustomSEO('contact');
		
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'contact_view.php', 
			'template_main_telegram_view.php', 
			$this->model->getData()
		);
	}
	
	public function newyear()
	{
		$this->model->getTitle(App::$current_organization->name_rus_c.' - Дедушка Мороз лично поздравит ваших детей #winmon');
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'newyear_view.php', 
			'template_main_telegram_view.php', 
			$this->model->getData()
		);
	}
	
	public function review()
	{
		$this->model->getTitle('Отзывы о '.App::$current_organization->name_rus_c.' в городе '.App::$current_city->name);
		$this->model->getDescription('Ознакомьтесь с отзывами о нашем заведении. Мы имеем отзывы в Google, Яндекс, Вконтакте, Instagram.');
		$this->model->getCustomSEO('review');
		
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'review_view.php', 
			'template_main_telegram_view.php', 
			$this->model->getData()
		);
	}
	
	public function product()
	{	
		$prdctBean = BeanFactory::getBean('prdct_products', $this->params[0]);
		
		if(!empty($prdctBean->id)){
			$this->model->getTitle($prdctBean->name.' - '.App::$current_organization->name_rus_c);
			$this->model->getDescription(str_replace(PHP_EOL, ' ', strip_tags(html_entity_decode($prdctBean->description))));
			if(isset($_SESSION['current_order'])) {
				$this->model->getOrderProducts($_SESSION['current_order']);
			}
			
			$data = $this->model->getData();
			$data['product_id'] = $this->params[0];
			
			$this->view->generateWithTemplate(
				'product_view.php', 
				'template_main_telegram_view.php',
				$data
			);
		} else {
			header('HTTP/1.1 301 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram');
			exit();
		}
	}
	
	public function category()
	{	
		//$this->model->getTitle($prdctBean->name.' от "'.App::$current_organization->name_rus_c.'".');
		//$this->model->getDescription(str_replace(PHP_EOL, ' ', strip_tags(html_entity_decode($prdctBean->description))));
		
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		$data = $this->model->getData();
	
		global $db;
		
		$group = $db->fetchRow($db->query("
			SELECT ppg.*, ppg_cstm.*
			FROM pdgrp_product_groups ppg
			JOIN pdgrp_product_groups_cstm ppg_cstm ON ppg_cstm.id_c = ppg.id AND ppg.deleted = 0 AND ppg_cstm.translite_name_c = '".$this->params[0]."'
			JOIN lngng_landings_pdgrp_product_groups_1_c ll_ppg ON ll_ppg.lngng_landings_pdgrp_product_groups_1pdgrp_product_groups_idb = ppg_cstm.id_c AND ll_ppg.deleted = 0 AND ll_ppg.lngng_landings_pdgrp_product_groups_1lngng_landings_ida = '".App::$current_landing->id."'
		"));
		
		$queryProducts = $db->query("
			SELECT pp.*, pp_cstm.*, image.id as product_image_id
			FROM prdct_products pp 
			JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp.deleted = 0 AND pp_cstm.active_c = 1
			JOIN pdgrp_product_groups_prdct_products_1_c pg_pp ON pg_pp.pdgrp_product_groups_prdct_products_1prdct_products_idb = pp_cstm.id_c AND pg_pp.pdgrp_product_groups_prdct_products_1pdgrp_product_groups_ida = '".$group['id']."'
            JOIN img_img_images_prdct_products_1_c img_product ON img_product.img_img_images_prdct_products_1prdct_products_idb = pp.id AND img_product.deleted = 0
            JOIN img_img_images image ON image.id = img_product.img_img_images_prdct_products_1img_img_images_ida AND image.deleted = 0
			ORDER BY pp_cstm.show_order_c
		");
		

		
		$products = [];
		while($product = $db->fetchByAssoc($queryProducts)) {
			$product['max_row'] = $groupProducts['max_row_c'] ?? '3';
			
			if($groupProducts['height_image_c']){
				$product['height_image'] = $groupProducts['height_image_c'];
			}elseif(App::$current_organization->product_image_height_c) {
				$product['height_image'] = App::$current_organization->product_image_height_c;
			} else { 
				$product['height_image'] = '260';
			}
			
			$product['count'] = 0;
			
			if(isset($data['order_products'])){
				foreach($data['order_products'] as $orderProduct){
					if($orderProduct['product']->id == $product['id']){
						$product['count'] = $orderProduct['count'];
					}
				}
			}
			$products[] = $product;
		}
		
		if(!empty($products)){
			$data['products'] = $products;
			
			$this->view->generateWithTemplate(
				'category_view.php', 
				'template_main_telegram_view.php',
				$data
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram');
			exit();
		}
	}
	
	
	public function delete_lead(){
		$this->cleanCurrentOrder();
		header('HTTP/1.1 200 OK');
		header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram');
		exit();
	}
	
	public function duplicate_order(){
		$duplicateOrder = BeanFactory::getBean('ordrs_orders', $this->params[0]);

		$_SESSION['current_order']->street_c = $duplicateOrder->street_c;
		$_SESSION['current_order']->home_c = $duplicateOrder->home_c;
		$_SESSION['current_order']->room_c = $duplicateOrder->room_c;
		$_SESSION['current_order']->count_persons_c = $duplicateOrder->count_persons_c;
				
		global $db;
		$query_duplicate_products = $db->query("
			SELECT 
				prod.id as product_id,
				pp.name as product_name,
				pp_cstm.sale_price_c as sale_price,
				count(pp.name) as count
			FROM ordrs_orders oo
				LEFT JOIN ordrs_orders_cstm oo_cstm ON oo_cstm.id_c = oo.id AND oo.deleted = 0
				LEFT JOIN ordrs_orders_prord_products_in_order_1_c oo_pp ON oo_pp.ordrs_orders_prord_products_in_order_1ordrs_orders_ida = oo_cstm.id_c AND oo_pp.deleted = 0
				LEFT JOIN prord_products_in_order pp ON pp.id = oo_pp.ordrs_orde5b35n_order_idb AND pp.deleted = 0
				LEFT JOIN prord_products_in_order_cstm pp_cstm ON pp_cstm.id_c = pp.id
				LEFT JOIN prdct_products prod ON prod.id = pp_cstm.prdct_products_id_c AND prod.deleted = 0
				LEFT JOIN prdct_products_cstm prod_cstm ON prod_cstm.id_c = prod.id
			WHERE 
				oo.id = '".$this->params[0]."'
				AND prod_cstm.active_c = 1
			GROUP BY pp.name
		");

		while($duplicate_product = $db->fetchByAssoc($query_duplicate_products)) {
			if($duplicate_product['sale_price'] > 0){
				$_POST['product_id'][] = $duplicate_product['product_id'];
				$_POST['count'][] = $duplicate_product['count'];
			}
		}
		
		$this->updateSessionbyPostData();
		
		//простановка промо-кода
		$discountBean = BeanFactory::getBean('dscnt_discount', $duplicateOrder->dscnt_discount_id_c);
		if(!empty($discountBean->promo_code_c)){
			App::$basket->checkPromoCode($discountBean->promo_code_c);
		} 

		header('HTTP/1.1 200 OK');
		header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/lead');
		exit();
	}
	
	public function term_of_use()
	{	
		$this->model->getTitle('Пользовательское соглашение службы доставки '.App::$current_organization->name_rus_c.' по городу '.App::$current_city->name);
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		
		$this->view->generateWithTemplate(
			'term_view.php', 
			'template_main_telegram_view.php', 
			$this->model->getData()
		);
	}
	
	public function agreement()
	{	
		$this->model->getTitle('Согласие на обработку персональных данных');
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'agreement_view.php', 
			'template_main_telegram_view.php', 
			$this->model->getData()
		);
	}
	
	public function agreement_lead()
	{	
		$this->model->getTitle('Согласие на обработку персональных данных');
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'agreement_lead_view.php', 
			'template_main_telegram_view.php', 
			$this->model->getData()
		);
	}
	
	public function job()
	{
		$this->model->getTitle('Открытые вакансии в '.App::$current_organization->name_rus_c.' - город '.App::$current_city->name);
		$this->model->getDescription('Здесь вы можете ознакомиться с открытыми вакансиями. Мы всегда ждем целеустремленных и талантливых!');
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'job_view.php', 
			'template_main_telegram_view.php', 
			$this->model->getData()
		);
	}
	
	public function send_job(){
		if (md5($_POST['norobot']) == $_SESSION['job_captcha'])	{ 
			$_SESSION['job_captcha'] = null;
			
			$smmrBean = BeanFactory::newBean('smmr_summary');
			$smmrBean->vacancy_c = $_POST['vacancy_c'];
			$smmrBean->first_name_c = $_POST['first_name_c'];
			$smmrBean->last_name_c = $_POST['last_name_c'];
			$smmrBean->middle_name_c = $_POST['middle_name_c'];
			$smmrBean->work_phone_c = $_POST['work_phone_c'];
			$smmrBean->date_of_birth_c = $_POST['date_of_birth_c'] ? DateTime::createFromFormat('d.m.Y', $_POST['date_of_birth_c'])->format('Y-m-d') : '';
			$smmrBean->marital_status_c = $_POST['marital_status_c'];
			$smmrBean->childrens_c = $_POST['childrens_c'];
			$smmrBean->residence_c = $_POST['residence_c'];
			$smmrBean->previous_jobs_c = $_POST['previous_jobs_c'];
			$smmrBean->reason_dissmisal_c = $_POST['reason_dissmisal_c'];
			$smmrBean->car_model_c = $_POST['car_model_c'];
			$smmrBean->fuel_type_c = $_POST['fuel_type_c'];
			$smmrBean->fuel_consumption_c = $_POST['fuel_consumption_c'];
			$smmrBean->education_c = $_POST['education_c'];
			$smmrBean->education_position_c = $_POST['education_position_c'];
			$smmrBean->assigned_user_id = App::$current_landing->assigned_user_id;
			$smmrBean->city_cities_id_c = App::$current_city->id;
			$smmrBean->lngng_landings_id_c = App::$current_landing->id;
			$smmrBean->save();

			NFfunctions::addSecuritygroupInBean($smmrBean);//чтобы отработали для ролевой модели
			
			$from    = 'job@yandex.ru';
			$subject = 'Отклик на вакансию : '.App::$current_organization->name_rus_c.' в городе '.App::$current_city->name;
			
			$marital_status_c = ($smmrBean->marital_status_c == '01') ? 'В браке' : 'Холост';
			$childrens_c = ($smmrBean->childrens_c == '01') ? 'Есть' : 'Нет';
			$html_body =  '
				<b>Отклик на вакансию '.NFfunctions::getSelectValue($smmrBean, 'vacancy_c').' ('.App::$current_organization->name_rus_c.' '.App::$current_city->name.')</b><br><br>
				<b>ФИО:</b> '.$smmrBean->name.' <br>
				<b>Дата рождения:</b> '.$smmrBean->date_of_birth_c.' <br>
				<b>Телефон:</b> '.$smmrBean->work_phone_c.' <br>
				<b>Адрес проживания:</b> '.$smmrBean->residence_c.' <br><br>
				
				<b>Пердыдущие места работы:</b> '.$smmrBean->previous_jobs_c.' <br>
				<b>Причины увольнения:</b> '.$smmrBean->reason_dissmisal_c.' <br><br>
				
				<b>Семейное положение:</b> '.$marital_status_c.' <br>
				<b>Дети:</b> '.$childrens_c.' <br><br>';
			if($smmrBean->vacancy_c == '01'){
				$html_body .= '
					<b>Марка авто:</b> '.$smmrBean->car_model_c.' <br>
					<b>Вид топлива:</b> '.$smmrBean->fuel_type_c.' <br>
					<b>Расход авто:</b> '.$smmrBean->fuel_consumption_c.' <br><br>';
			}
			$html_body .= '
				<b>Образование (где учился):</b> '.$smmrBean->education_c.' <br>
				<b>Образование (на кого учился):</b> '.$smmrBean->education_position_c.' <br><br><br>
				<b>Подробности в CRM</b>';
			$to = App::$current_landing->email_order_c;
			
			//отсылаем почту админу лендинга
			/*$res = NFfunctions::post_to_url(
				'http://potemkin24.ru/send_mail.php',
				[
					'subject' => $subject,
					'html_body' => htmlspecialchars_decode($html_body),
					'to' => $to
				]
			);*/
			
			//отсылаем почту на spotemkin94@yandex.ru
			/*NFfunctions::post_to_url(
				'http://potemkin24.ru/send_mail.php',
				[
					'subject' => $subject,
					'html_body' => htmlspecialchars_decode($html_body),
					'to' => 'spotemkin94@yandex.ru'
				]
			);*/
			
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/job?&success=yes');
			exit();
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/job?&success=no');
			exit();
		}
	}
	
	public function faq()
	{	
		$this->model->getTitle('Ответы на часто задаваемые вопросы');
		$this->model->getDescription('На этой странице мы постарались ответить на самые часто задаваемые вами вопросы.');
		$this->model->getCustomSEO('faq');
		
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'faq_view.php', 
			'template_main_telegram_view.php', 
			$this->model->getData()
		);
	}

	public function news()
	{	
		if($this->params) {
			global $db;
			$news = $db->fetchRow($db->query("
				SELECT 
					nn.id,
					nn.name,
					nn_cstm.link_name_c,
					nn_cstm.text_c,
					nn_cstm.seo_title_c,
					nn_cstm.seo_description_c 
				FROM news_news nn 
				JOIN news_news_cstm nn_cstm ON nn_cstm.id_c = nn.id AND nn.deleted = 0
				JOIN lngng_landings_news_news_1_c ll_nn ON ll_nn.lngng_landings_news_news_1news_news_idb = nn_cstm.id_c AND ll_nn.deleted = 0 AND lngng_landings_news_news_1lngng_landings_ida = '".App::$current_landing->id."'
				WHERE 
					(nn.id = '".$this->params[0]."' OR nn_cstm.link_name_c = '".$this->params[0]."') 
					AND nn.deleted = 0
			"));
			
			if(empty($news)){
				$this->error_404();
			}

			//редирект если перешли на новость по ID
			if($this->params[0] == $news['id'] && $news['link_name_c'] != null){
				header("HTTP/1.1 301 Moved Permanently");
				header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/news/'.$news['link_name_c']);
				exit();
			}
			
			//SEO
			if(!empty($news['seo_title_c'])){
				$this->model->getTitle($news['seo_title_c']);
			} else {
				$this->model->getTitle($news['name']);
			}
			
			if(!empty($news['seo_description_c'])){
				$this->model->getDescription(mb_substr(str_replace(PHP_EOL, ' ', strip_tags(html_entity_decode($news['seo_description_c']))), 0, 150));
			} else {
				$this->model->getDescription(mb_substr(str_replace(PHP_EOL, ' ', strip_tags(html_entity_decode($news['text_c']))), 0, 150));
			}
				
			if(isset($_SESSION['current_order'])) {
				$this->model->getOrderProducts($_SESSION['current_order']);
			}
			
			$data = $this->model->getData();
			$data['news_id'] = $news['id'];
			

			$this->view->generateWithTemplate(
				'news_item_view.php', 
				'template_main_telegram_view.php', 
				$data
			);
		} else {
			$this->model->getTitle('Новости '.App::$current_organization->name_rus_c);
			$this->model->getDescription('Ознакомьтесь с нашими акциями, последними нововведениями и новостями.');
			$this->model->getCustomSEO('/news/');
			
			if(isset($_SESSION['current_order'])) {
				$this->model->getOrderProducts($_SESSION['current_order']);
			}

			$this->view->generateWithTemplate(
				'news_view.php', 
				'template_main_telegram_view.php', 
				$this->model->getData()
			);
		}
	}
	
	public function stocks()
	{	
		$this->model->getTitle('Акции от '.App::$current_organization->name_rus_c);
		$this->model->getDescription('Ознакомьтесь с нашими акциями');
		$this->model->getCustomSEO('stocks');
		
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'stocks_view.php', 
			'template_main_telegram_view.php', 
			$this->model->getData()
		);
	}
	
	public function page()
	{	
		if(empty($this->params)) {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram');
			exit();
		}
		
		global $db;
		$page = $db->fetchRow($db->query("
			SELECT 
				pp.id,
				pp_cstm.link_name_c,
				pp.name,
				pp_cstm.content_c,
				pp_cstm.seo_title_c,
				pp_cstm.seo_description_c 
			FROM pgs_pages pp 
			JOIN pgs_pages_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp.deleted = 0
			JOIN lngng_landings_pgs_pages_1_c ll_pp ON ll_pp.lngng_landings_pgs_pages_1pgs_pages_idb = pp_cstm.id_c AND ll_pp.deleted = 0 AND lngng_landings_pgs_pages_1lngng_landings_ida = '".App::$current_landing->id."'
			WHERE 
				(pp.id = '".$this->params[0]."' OR pp_cstm.link_name_c = '".$this->params[0]."') 
				AND pp.deleted = 0
		"));
		
		if(empty($page)) {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram');
			exit();
		}

		//редирект если перешли на новость по ID
		if($this->params[0] == $page['id'] && $page['link_name_c'] != null){
			header("HTTP/1.1 301 Moved Permanently");
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/page/'.$page['link_name_c']);
			exit();
		}
		
		//SEO
		if(!empty($page['seo_title_c'])){
			$this->model->getTitle($page['seo_title_c']);
		} else {
			$this->model->getTitle($page['name']);
		}
		
		if(!empty($page['seo_description_c'])){
			$this->model->getDescription(mb_substr(str_replace(PHP_EOL, ' ', strip_tags(html_entity_decode($page['seo_description_c']))), 0, 150));
		} else {
			$this->model->getDescription(mb_substr(str_replace(PHP_EOL, ' ', strip_tags(html_entity_decode($page['text_c']))), 0, 150));
		}
			
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		
		$data = $this->model->getData();
		$data['page_id'] = $page['id'];
		

		$this->view->generateWithTemplate(
			'page_view.php', 
			'template_main_telegram_view.php', 
			$data
		);
	}
	
	public function get_products()
	{	
		$group_id = $this->params[0];
		
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		$data = $this->model->getData();
		
		global $db;
		$groupProducts = $db->fetchRow($db->query("
			SELECT 
				max_row_c, 
				height_image_c,
				height_short_description_c
			FROM pdgrp_product_groups_cstm ppg_cstm
			WHERE ppg_cstm.id_c = '".$group_id."';
		"));
		
		$queryProducts = $db->query("
			SELECT pp.*, pp_cstm.* , image.id as product_image_id
			FROM prdct_products pp 
			JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp.deleted = 0 AND pp_cstm.active_c = 1
			JOIN pdgrp_product_groups_prdct_products_1_c pg_pp ON pg_pp.pdgrp_product_groups_prdct_products_1prdct_products_idb = pp_cstm.id_c AND pg_pp.pdgrp_product_groups_prdct_products_1pdgrp_product_groups_ida = '".$group_id."'
            LEFT JOIN img_img_images_prdct_products_1_c img_product ON img_product.img_img_images_prdct_products_1prdct_products_idb = pp.id AND img_product.deleted = 0
            LEFT JOIN img_img_images image ON image.id = img_product.img_img_images_prdct_products_1img_img_images_ida AND image.deleted = 0
			ORDER BY pp_cstm.show_order_c
		");
		$products = [];
		while($product = $db->fetchByAssoc($queryProducts)) {
			$product['max_row'] = $groupProducts['max_row_c'] ?? '3';
			
			if($groupProducts['height_image_c']){
				$product['height_image'] = $groupProducts['height_image_c'];
			}elseif(App::$current_organization->product_image_height_c) {
				$product['height_image'] = App::$current_organization->product_image_height_c;
			} else { 
				$product['height_image'] = '260';
			}
			
			//выставляем значение высоты блока с описание у карточки товара
			$product['height_short_description'] = $groupProducts['height_short_description_c'] ?? '90';
			
			$product['count'] = 0;
			
			if(isset($data['order_products'])){
				foreach($data['order_products'] as $orderProduct){
					if($orderProduct['product']->id == $product['id']){
						$product['count'] = $orderProduct['count'];
					}
				}
			}
			
			//делаем проверку на отображение продукта
			$product['visible'] = 'show';
			if($product['use_time_work_c']){
				$product['visible'] = 'hide';
				
				date_default_timezone_set('UTC'); //выставляем гринвич
				
				$currentWeekDay = date('w'); //текущий день недели
				$currentTime = date("H:i", strtotime('+'.$product['time_work_timezone_c'].' hours')); // текущее время
				
				if($currentWeekDay == 1){//понедельник
					$times_work = explode('^|^',$product['times_work_mo_c']);
				}elseif($currentWeekDay == 2){//вторник
					$times_work = explode('^|^',$product['times_work_tu_c']);
				}elseif($currentWeekDay == 3){//среда
					$times_work = explode('^|^',$product['times_work_we_c']);
				}elseif($currentWeekDay == 4){//четверг
					$times_work = explode('^|^',$product['times_work_th_c']);
				}elseif($currentWeekDay == 5){//пятница
					$times_work = explode('^|^',$product['times_work_fr_c']);
				}elseif($currentWeekDay == 6){//суббота
					$times_work = explode('^|^',$product['times_work_sa_c']);
				}elseif($currentWeekDay == 0){//воскресенье
					$times_work = explode('^|^',$product['times_work_su_c']);
				}
				
				foreach($times_work as $k => $time_work){
					if(!empty($time_work)){
						$time_work_explode = explode(' - ', $time_work);
						$time_work_start = $time_work_explode[0];
						$time_work_end = $time_work_explode[1];
						if($currentTime >= $time_work_start && $currentTime <= $time_work_end){
							$product['visible'] = 'show';
						}
					}
				}
			}
			
			$products[] = $product;
		}
		
		if($products){
			echo json_encode($products);
		}
		
		return;
	}
	
	public function get_all_products()
	{	
		$data = $this->model->getData();
		
		global $db;
		$queryAllProducts = $db->query("
			SELECT pg_pp.pdgrp_product_groups_prdct_products_1pdgrp_product_groups_ida as group_id, pp.*, pp_cstm.*
			FROM prdct_products pp 
			JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp.deleted = 0 AND pp_cstm.active_c = 1 AND pp_cstm.lngng_landings_id_c = '".App::$current_landing->id."'
			JOIN pdgrp_product_groups_prdct_products_1_c pg_pp ON pg_pp.pdgrp_product_groups_prdct_products_1prdct_products_idb = pp_cstm.id_c 
			WHERE pp_cstm.lngng_landings_id_c = '".App::$current_landing->id."'
			ORDER BY pp_cstm.show_order_c;
		");
		$all_products = [];
		while($product = $db->fetchByAssoc($queryAllProducts)) {
			$product['count'] = 0;
			if(!empty($data['order_all_products'])){
				foreach($data['order_all_products'] as $orderProduct){
					if($orderProduct['product']->id == $product['id']){
						$product['count'] = $orderProduct['count'];
					}
				}
			}
			$all_products[] = $product;
		}
		
		if($products){
			echo json_encode($all_products);
		}
		
		return;
	}
	
	public function products_file_yml(){
		$out = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
		$out = '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . "\r\n";
		$out .= '<yml_catalog date="' . date('Y-m-d H:i') . '">' . "\r\n";
		$out .= '<shop>' . "\r\n";
		 
		$out .= '<name>'.App::$current_organization->name_rus_c.'</name>' . "\r\n";// Короткое название магазина, должно содержать не более 20 символов.
		$out .= '<company>ИП «'.App::$current_organization->name_rus_c.'»</company>' . "\r\n";// Полное наименование компании, владеющей магазином.
		$out .= '<url>'.App::$current_landing->link_c.'</url>' . "\r\n";// URL главной страницы магазина.
		 
		// Список курсов валют магазина.
		$out .= '<currencies>' . "\r\n";
		$out .= '<currency id="RUR" rate="1"/>' . "\r\n";
		$out .= '</currencies>' . "\r\n";
		 
		// Список категорий
		global $db;
		$queryProductGroups = $db->query("
			SELECT distinct ppg.*, ppg_cstm.*  
			FROM pdgrp_product_groups ppg 
			JOIN pdgrp_product_groups_cstm ppg_cstm ON ppg_cstm.id_c = ppg.id AND ppg.deleted = 0
			JOIN lngng_landings_pdgrp_product_groups_1_c ll_ppg ON ll_ppg.lngng_landings_pdgrp_product_groups_1pdgrp_product_groups_idb = ppg_cstm.id_c
			AND ll_ppg.lngng_landings_pdgrp_product_groups_1lngng_landings_ida = '".App::$current_landing->id."'
			JOIN pdgrp_product_groups_prdct_products_1_c ppg_pp ON ppg_pp.pdgrp_product_groups_prdct_products_1pdgrp_product_groups_ida = ppg_cstm.id_c
			JOIN prdct_products pp ON pp.id = ppg_pp.pdgrp_product_groups_prdct_products_1prdct_products_idb AND pp.deleted = 0
			JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp_cstm.active_c = 1
			ORDER BY ppg_cstm.show_order_c;
		");
		while($productGroup = $db->fetchByAssoc($queryProductGroups)) {
			$product_groups[] = $productGroup;
		}
		$out .= '<categories>' . "\r\n";
		$product_groups_id = [];
		foreach ($product_groups as $product_group_key => $product_group) {
			$out .= '<category id="'.($product_group_key+1).'">'.$product_group['name'].'</category>' . "\r\n";
			$product_groups_id[] = $product_group['id'];
		}
		$out .= '</categories>' . "\r\n";

		// Список товаров
		$queryAllProducts = $db->query("
			SELECT pg_pp.pdgrp_product_groups_prdct_products_1pdgrp_product_groups_ida as group_id, pp.*, pp_cstm.*
			FROM prdct_products pp 
			JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp.deleted = 0 AND pp_cstm.active_c = 1 AND pp_cstm.lngng_landings_id_c = '".App::$current_landing->id."'
			JOIN pdgrp_product_groups_prdct_products_1_c pg_pp ON pg_pp.pdgrp_product_groups_prdct_products_1prdct_products_idb = pp_cstm.id_c 
			WHERE pp_cstm.lngng_landings_id_c = '".App::$current_landing->id."'
			ORDER BY pp_cstm.show_order_c;
		");
		$all_products = [];
		$out .= '<offers>' . "\r\n";
		while($product = $db->fetchByAssoc($queryAllProducts)) {
			$out .= '<offer id="'.$product['id'].'">'."\r\n";
			
			// URL страницы товара на сайте магазина.
			$out .= '<url>'.App::$current_landing->link_c.'/telegram/product/'.$product['id'].'</url>'."\r\n";
		 
			// Цена, предполагается что в БД хранится цена и цена со скидкой.
			if (!empty($product['red_sale_price_c'])) {
				$out .= '<price>'.$product['sale_price_c'].'</price>' . "\r\n";
				$out .= '<oldprice>'.$product['red_sale_price_c'].'</oldprice>'."\r\n";
			} else {
				$out .= '<price>'.$product['sale_price_c'].'</price>'."\r\n";
			}
		 
			// Валюта товара.
			$out .= '<currencyId>RUR</currencyId>' . "\r\n";
		 
			// ID категории.
			$out .= '<categoryId>'.(array_keys($product_groups_id, $product['group_id'])[0]+1).'</categoryId>'."\r\n";
		 
			// Изображения товара, до 10 ссылок.
			$out .= '<picture>'.$product['image_link_c'].'</picture>'."\r\n";
		 
			// Название товара.
			$out .= '<name>'.$product['name'].'</name>'."\r\n";
		 
			// Описание товара, максимум 3000 символов.
			$description = strip_tags(html_entity_decode(stripslashes($product['description'])));
			$description = mb_strimwidth($description, 0, 249, "...");//обрезать описание
			$out .= '<description><![CDATA['.$description.']]></description>'."\r\n";    
			$out .= '</offer>' . "\r\n";
		}
		$out .= '</offers>' . "\r\n";
		$out .= '</shop>' . "\r\n";
		$out .= '</yml_catalog>' . "\r\n";
		
		$out = str_replace('&', 'и', $out); //заменяем символ (например, ролл Киш & Миш)
		 
		header('Content-Type: text/xml; charset=utf-8');
		echo $out;
		exit;
	}
	
	public function area_map()
	{	
		$this->model->getTitle('Карта доставки '.App::$current_organization->name_rus_c.' по городу '.App::$current_city->name);
		$this->model->getDescription('Мы доставляем заказы по городу '.App::$current_city->name.'. Изучите подробную карту доставки.');
		$this->model->getCustomSEO('area_map');
		
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		
		$this->view->generateWithTemplate(
			'area_map_view.php', 
			'template_main_telegram_view.php', 
			$this->model->getData()
		);
	}
	
			
	public function mobile_app()
	{	
		$this->model->getTitle('Мобильное приложение '.App::$current_organization->name_rus_c.' в городе '.App::$current_city->name);
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		
		$this->view->generateWithTemplate(
			'mobile_app_view.php', 
			'template_main_telegram_view.php', 
			$this->model->getData()
		);
	}
	
	public function pre_order_check() {
		new BasketController();
		$this->updateSessionbyPostData();
		new BasketController();

		header('HTTP/1.1 200 OK');
		header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/lead');
		exit();
	}
	
	public function order_check() {
		if(App::$current_landing->status_c == '02' || in_array($_SESSION['current_order']->pay_method_c, ['01','02','04','05'])){
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/order_offline_pay');
			exit();
		}elseif($_SESSION['current_order']->pay_method_c == '03'){

			//сбербанк
			if(!empty(App::$current_landing->sberbank_acquiring_payments_c)){
				require_once '/var/www/domains_nginx/'.CRM_URL.'/custom/modules/sberb_sberbank_acquiring/Sberbank.php';
				
				$sberbank = new Sberbank();
				$sberbank->setConfigByLandingId(App::$current_landing->id);

				$response = $sberbank->register(
					substr(sha1(time()), 0, 32), 
					$_SESSION['current_order']->all_price_c.'00', 
					NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/main/order_online_pay',
					NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/main/lead',
					'Клиент:'.$_SESSION['current_order']->phone_c
				);
				if(!empty($response['orderId'])) {
					$_SESSION['current_order']->sberbank_acquiring_id_c = $response['orderId'];
					
					$preOrderBean = $this->savePreOrder();
					$_SESSION['old_order'] = clone $_SESSION['current_order'];
					$_SESSION['old_order']->id = $preOrderBean->id;
					
					$this->send_email();
					$this->cleanCurrentOrder();
					
					header('HTTP/1.1 200 OK');
					header('Location: '.$response['formUrl']);
					exit();
				} else {
					header('HTTP/1.1 200 OK');
					header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/main/lead');
					exit();
				}
			}
			//yookassa
			if(!empty(App::$current_landing->yookassa_acquiring_c)){
				require_once '/var/www/domains_nginx/'.CRM_URL.'/custom/modules/yooks_yookassa_acquiring/Yookassa.php';
				
				$yookassa = new Yookassa();
				$yookassa->setConfigByLandingId(App::$current_landing->id);

				$response = $yookassa->register(
					$_SESSION['current_order']->all_price_c, 
					NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/order_online_pay',
					'Клиент:'.$_SESSION['current_order']->phone_c
				);
		
				if(!empty($response['confirmation'])) {
					$_SESSION['current_order']->yookassa_acquiring_id_c = $response['id'];
					
					$preOrderBean = $this->savePreOrder();
					$_SESSION['old_order'] = clone $_SESSION['current_order'];
					$_SESSION['old_order']->id = $preOrderBean->id;
			
					$this->send_email();
					$this->cleanCurrentOrder();
					
					header('HTTP/1.1 200 OK');
					header('Location: '.$response['confirmation']['confirmation_url']);
					exit();
				} else {
					header('HTTP/1.1 200 OK');
					header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/lead');
					exit();
				}
			}
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/lead');
			exit();
		}
	}
	
	public function sberbank_acquiring_success_pay(){
		/*if(!empty($_SESSION['current_order']->client_name_c)){ //проверка есть ли текущий заказ
			$preOrderBean = $this->savePreOrder();
			$_SESSION['old_order'] = clone $_SESSION['current_order'];
			$this->send_email();
			$this->cleanCurrentOrder();
		}*/

		header('HTTP/1.1 200 OK');
		header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/order_online_pay');
		exit();
	}
	
	public function order_online_pay() {
		//проверяем - реально было оплачено онлайн
		if($_SESSION['old_order']->pay_method_c != '03'){
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/order_offline_pay');
			exit();
		}
		$this->model->getCurrentProductGroups();
		$this->model->getLandings();
		$this->model->getTitle('Онлайн-оплата заказа');
		$data = $this->model->getData();
		
		$this->view->generateWithTemplate( 
			'order_processed_online_view.php', 
			'template_main_telegram_view.php', 
			$data
		);
	}
	
	public function order_offline_pay() {
		$this->model->getCurrentProductGroups();
		$this->model->getLandings();
		$this->model->getTitle(App::$current_organization->name.' '.App::$current_city->name.' - доставка на дом!');
		$data = $this->model->getData();
		
		global $db;
		$sharedPromo = $db->fetchRow($db->query("
			SELECT dd.*, dd_cstm.* 
			FROM dscnt_discount dd
			JOIN dscnt_discount_cstm dd_cstm ON dd_cstm.id_c = dd.id AND dd.deleted = 0
			JOIN lngng_landings_dscnt_discount_1_c ll_dd ON ll_dd.lngng_landings_dscnt_discount_1dscnt_discount_idb = dd.id AND ll_dd.deleted = 0 AND ll_dd.lngng_landings_dscnt_discount_1lngng_landings_ida = '".App::$current_landing->id."'
			WHERE dd_cstm.shared_promo_code_c = 1
		"));
		if(!empty($sharedPromo)){
			$productBean = BeanFactory::getBean('prdct_products', $sharedPromo['prdct_products_id_c']);
			$this->model->getTitle('Доставка '.App::$current_organization->name_rus_c.' в городе '.App::$current_city->name.' : промо-код '.$sharedPromo['promo_code_c'].' на БЕСПЛАТНЫЙ "'.$productBean->name.'" ! #winmon #'.App::$current_organization->name_rus_c.' #'.App::$current_organization->name);
			$data = $this->model->getData();
		}
		
		if(isset($_SESSION['current_order']->delivery_price_c) && $_SESSION['current_order']->delivery_price_c !== NULL && $_SESSION['current_order']->phone_c !== NULL  ) {
			$preOrderBean = $this->savePreOrder();
			$_SESSION['old_order'] = clone $_SESSION['current_order'];
			$_SESSION['old_order']->id = $preOrderBean->id;
			
			$this->send_email();
			$this->cleanCurrentOrder();
		} 
		
		if(!empty($_SESSION['old_order'])){
			$this->view->generateWithTemplate(
				'order_processed_offline_view.php', 
				'template_main_telegram_view.php', 
				$data
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram/lead');
			exit();
		}
	}
	
	// определение мобильного устройства
	public function checkPlatform() { 
		if($_SESSION['type_platform']){
			return $_SESSION['type_platform'];
		} else {
			$agent = strtolower($_SERVER['HTTP_USER_AGENT']); 
			
			$ios_platforms = array('ipad', 'iphone', 'ipod', 'mac os');
			$android_platforms = array('android');
			
			foreach ($ios_platforms as $value) {    
				if (strpos($agent, $value)) return 'ios';   
			}  
			foreach ($android_platforms as $value) {    
				if (strpos($agent, $value)) return 'android';   
			} 	
		}
	}
	
	public function savePreOrder() {
		$_SESSION['current_order']->lead_path_c = 'tg_bot'; 
		
		$preorderBean = BeanFactory::newBean('pordr_preorder');
		
		$preorderBean->client_name_c = $_SESSION['current_order']->client_name_c;
		$preorderBean->phone_c = $_SESSION['current_order']->phone_c;
		$preorderBean->client_email_c = $_SESSION['current_order']->client_email_c;
		$preorderBean->client_instagram_c = $_SESSION['current_order']->client_instagram_c;
		
		$preorderBean->brnch_branch_id_c = $_SESSION['current_order']->brnch_branch_id_c;
		$preorderBean->city_cities_id_c = App::$current_city->id;
		if(!empty($_SESSION['current_order']->CUSTOM_area->id)){
			$preorderBean->area_area_id_c = $_SESSION['current_order']->CUSTOM_area->id;
		}
		$preorderBean->street_c = $_SESSION['current_order']->street_c;
		$preorderBean->home_c = $_SESSION['current_order']->home_c;
		$preorderBean->room_c = $_SESSION['current_order']->room_c;
		$preorderBean->porch_c = $_SESSION['current_order']->porch_c;
		$preorderBean->level_c = $_SESSION['current_order']->level_c;
		
		$preorderBean->comment_client_c = $_SESSION['current_order']->comment_client_c ?? '';
		$preorderBean->count_persons_c = $_SESSION['current_order']->count_persons_c;
		
		$preorderBean->pay_method_c = $_SESSION['current_order']->pay_method_c;
		$preorderBean->doit_c = $_SESSION['current_order']->doit_c;
		
		$preorderBean->receiving_method_c = $_SESSION['current_order']->receiving_method_c;
		$preorderBean->date_future_delivery_c = $_SESSION['current_order']->date_future_delivery_c;
		$preorderBean->time_future_delivery_c = $_SESSION['current_order']->time_future_delivery_c;
		$preorderBean->delivery_method_c = $_SESSION['current_order']->delivery_method_c;
		
		//рассчет стоимости доставки надо выносить в хук
		$preorderBean->delivery_price_c = $_SESSION['current_order']->delivery_price_c;
		
		$preorderBean->ip_c = $_SERVER["REMOTE_ADDR"];
		$preorderBean->user_agent_c = $_SERVER["HTTP_USER_AGENT"];
		
		$preorderBean->lngng_landings_id_c = App::$current_landing->id;
		
		$preorderBean->lead_path_c = $_SESSION['current_order']->lead_path_c;
		
		$preorderBean->status_c = '01';
		
		if($promoBean = BeanFactory::getBean('dscnt_discount', $_SESSION['current_order']->CUSTOM_promo)){
			$preorderBean->dscnt_discount_id_c = $promoBean->id;
		}
		
		$preorderBean->assigned_user_id = 1;
		
		//онлайн-оплаты
		$preorderBean->sberbank_acquiring_id_c = $_SESSION['current_order']->sberbank_acquiring_id_c ?? null;
		$preorderBean->yookassa_acquiring_id_c = $_SESSION['current_order']->yookassa_acquiring_id_c ?? null;
		
		//проставляем продукты в заказ
		$preorderBean->products_c = null;
		foreach($_SESSION['current_order']->CUSTOM_products as $productBean){
			$preorderBean->products_c .= $productBean->id.'^|^';
			
			$source = $productBean->CUSTOM_sourse ?? null;
			$preorderBean->products_source_c .= $source.'^|^';
		}
		
		$preorderBean->products_c = mb_substr($preorderBean->products_c, 0, -3);
		$preorderBean->products_source_c = mb_substr($preorderBean->products_source_c, 0, -3);
		
		//рассчитываем бонусы
		$this->updateBonuses();
		$preorderBean->client_money_discount_c = $_SESSION['current_order']->client_money_discount_c;//сколько списали бонусов
		$preorderBean->bonuses_accrued_c = $_SESSION['current_order']->bonuses_accrued_c;//сколько начислили бонусов
		
		$preorderBean->save();
		
		return $preorderBean;
	}
	
	private function updateBonuses(){
		if(empty($_SESSION['current_order']->client_money_discount_c) && !empty(App::$current_user->id)){
			global $db;
			$bonuseSystem = $db->fetchRow($db->query("
				SELECT 
					bb.name, 
					bb_cstm.bonuses_name_c, 
					bbl_cstm.cashback_c,
					MAX(bbl_cstm.ransom_cost_c) as level_ransom_cost,
					bbl.name as level_name
				FROM
					bnss_bonuses_lngng_landings_1_c bl
					JOIN bnss_bonuses bb ON bb.id = bl.bnss_bonuses_lngng_landings_1bnss_bonuses_ida AND bb.deleted = 0 AND bl.bnss_bonuses_lngng_landings_1lngng_landings_idb = '".App::$current_landing->id."' AND bl.deleted = 0
					JOIN bnss_bonuses_cstm bb_cstm ON bb.id = bb_cstm.id_c
					JOIN bnss_bonuses_bnlvl_bonuses_level_1_c bb_bbl ON bb_bbl.bnss_bonuses_bnlvl_bonuses_level_1bnss_bonuses_ida = bb_cstm.id_c AND bb_bbl.deleted = 0
					JOIN bnlvl_bonuses_level bbl ON bbl.id = bb_bbl.bnss_bonuses_bnlvl_bonuses_level_1bnlvl_bonuses_level_idb AND bbl.deleted = 0
					JOIN bnlvl_bonuses_level_cstm bbl_cstm ON bbl_cstm.id_c = bbl.id
				WHERE '".App::$current_user->paid_all_c."' >= bbl_cstm.ransom_cost_c
				HAVING MAX(bbl_cstm.ransom_cost_c) IS NOT NULL
			"));
			if(!empty($bonuseSystem['cashback_c'])){
				$_SESSION['current_order']->bonuses_accrued_c = round($_SESSION['current_order']->sale_price_c*$bonuseSystem['cashback_c']/100);
			}
		}
	}
	
	
	private function updateSessionbyPostData(){
		//так как post отрабатывает быстрее ajax - если он есть, то юзаем его для обновления сессии продуктов
		if( isset($_POST['count']) && isset($_POST['product_id']) ) {
			$_SESSION['current_order']->CUSTOM_products = null;
			$products_price = 0;
			for($i = 0; $i < count($_POST['product_id']);$i++){
				$count = $_POST['count'][$i];
				if($count > 0 ){
					$product_id = $_POST['product_id'][$i];
					$product = BeanFactory::getBean('prdct_products', $product_id);
					for($j = 0; $j < $count; $j++){
						$_SESSION['current_order']->CUSTOM_products[] = $product;
						$products_price += $product->sale_price_c;
					}
				}
			}
			$_SESSION['current_order']->sale_price_c = $products_price;
			$_SESSION['current_order']->all_price_c = $_SESSION['current_order']->sale_price_c+$_SESSION['current_order']->delivery_price_c;
		}
	}
	
	public function cleanCurrentOrder() {
		unset($_SESSION['current_order']);
	}
	
	public function body_html(){
		if(!empty($_SESSION['current_order']->CUSTOM_area->id)) {
			$delivery_price = $_SESSION['current_order']->CUSTOM_area->delivery_price_c;
		} else {
			$delivery_price = App::$current_landing->delivery_price_c;
		}
		
		$sum_all = 0;
		$new_sum_all = 0;
		
		$html_body = 
			'<p>
				<strong>Заказ - закупка в '.str_replace('&', '-', App::$current_organization->name_rus_c).':</strong>
				<ul>';
		$this->model->getOrderProducts($_SESSION['current_order']);
		$data = $this->model->getData();
		foreach($data['order_products'] as $product){
			if($product['count'] > 0 ){
				$system_description = !empty($product['product']->system_description_c) ? ' ( '.$product['product']->system_description_c.' ) ' : '';
				$html_body .= '<li>'.html_entity_decode($product['product']->name).$system_description.'('.$product['product']->purchase_price_c.'руб.) - '.$product['count'].'шт. - '.$product['product']->purchase_price_c*$product['count'].'руб.</li>';
				$sum_all += $product['product']->purchase_price_c*$product['count'];
			}
		}
		
		if(!empty($_SESSION['current_order']->CUSTOM_promo)) {
			$promoBean = BeanFactory::getBean('dscnt_discount',$_SESSION['current_order']->CUSTOM_promo);
			if($promoBean->promo_type_c == '01') {
				$productPromo = BeanFactory::getBean('prdct_products',$promoBean->prdct_products_id_c);
				$system_description = !empty($productPromo->system_description_c) ? ' ( '.$productPromo->system_description_c.' ) ' : '';
				$html_body .= '<li>Подарок по промо-коду "'.$promoBean->promo_code_c.'": '.html_entity_decode($productPromo->name).$system_description.'('.$productPromo->purchase_price_c.'руб.) - 1шт. - '.$productPromo->purchase_price_c.'руб.</li>';
				$sum_all += $productPromo->purchase_price_c;
			}
		}
		
		
		$html_body .= 
				'</ul>Итого без скидки: '.$sum_all.' руб.<br>
					Итого + скидка 10%: '.($sum_all*0.9).' руб.<br>
			</p>';
			
		if($_SESSION['current_order']->receiving_method_c == '01'){
			$receiving_method = "<br>Метод получения заказа: доставка курьером";
			
			$area = !empty($_SESSION['current_order']->CUSTOM_area) ? $_SESSION['current_order']->CUSTOM_area->name : 'не указан';
			$street = !empty($_SESSION['current_order']->street_c) ? $_SESSION['current_order']->street_c : 'не указана';
			$home = !empty($_SESSION['current_order']->home_c) ? $_SESSION['current_order']->home_c : 'не указан';
			$room = !empty($_SESSION['current_order']->room_c) ? $_SESSION['current_order']->room_c : 'не указана';
			$porch = !empty($_SESSION['current_order']->porch_c) ? $_SESSION['current_order']->porch_c : 'не указан';
			$level = !empty($_SESSION['current_order']->level_c) ? $_SESSION['current_order']->level_c : 'не указан';
			
			$address = "<br>Адрес: район '".$area."', улица '".$street."', дом '".$home."', квартира '".$room."' подъезд '".$porch."' (этаж '".$level."')";
		} elseif($_SESSION['current_order']->receiving_method_c == '02'){
			$receiving_method = "<br>Метод получения заказа: самовывоз";
			$address = '<br>Адрес доставки: нет';
		}
		
		$promo_code = '';
		if(!empty($_SESSION['current_order']->CUSTOM_promo)) {
			$promoBean = BeanFactory::getBean('dscnt_discount',$_SESSION['current_order']->CUSTOM_promo);
			$promo_code = "<br>Промо-код: ".$promoBean->name;
		}
		
		$html_body .= 
			'<p>
				<strong>Клиент:</strong><br>
				Имя: '.$_SESSION['current_order']->client_name_c.'<br>
				Телефон: <a href="tel:'.$_SESSION['current_order']->phone_c.'">'.$_SESSION['current_order']->phone_c.'</a>'.
				$promo_code.
				$address.
				$receiving_method.
			'</p>
			<ul>';
			
		foreach($data['order_products'] as $product){
			if($product['count'] > 0 ){
				$system_description = !empty($product['product']->system_description_c) ? ' ( '.$product['product']->system_description_c.' ) ' : '';
				$html_body .= '<li>'.html_entity_decode($product['product']->name).$system_description.'('.$product['product']->sale_price_c.'руб.) - '.$product['count'].'шт. - '.$product['product']->sale_price_c*$product['count'].'руб.</li>';
				$new_sum_all += $product['product']->sale_price_c*$product['count'];
			}
		}
		if(!empty($_SESSION['current_order']->CUSTOM_promo)) {
			$promoBean = BeanFactory::getBean('dscnt_discount',$_SESSION['current_order']->CUSTOM_promo);
			if($promoBean->promo_type_c == '01') {
				$productPromo = BeanFactory::getBean('prdct_products',$promoBean->prdct_products_id_c);
				$system_description = !empty($productPromo->system_description_c) ? ' ( '.$productPromo->system_description_c.' ) ' : '';
				$html_body .= '<li>Подарок по промо-коду "'.$promoBean->promo_code_c.'": '.html_entity_decode($productPromo->name).$system_description.'(0руб.) - 1 шт. - 0руб.</li>';
			}
		}
		
		if($_SESSION['current_order']->pay_method_c == '03'){
			$pay_method = 'оплатили онлайн с коммисией';
		}else if($_SESSION['current_order']->pay_method_c == '02'){
			$pay_method = 'оплата Сбербанк-онлайн курьеру';
		}else if($_SESSION['current_order']->pay_method_c == '01'){
			$pay_method = 'оплата наличными курьеру';
		}else if($_SESSION['current_order']->pay_method_c == '04'){
			$pay_method = 'оплата через терминал курьеру';
		} else if($_SESSION['current_order']->pay_method_c == '05'){
			$pay_method = 'оплата через QR-код';
		}
		
		if($_SESSION['current_order']->delivery_method_c == '01') {
			$delivery_method = 'ближайшее время';
		} elseif($_SESSION['current_order']->delivery_method_c == '02') {
			$delivery_method = 'к '.$_SESSION['current_order']->date_future_delivery_c.' '.$_SESSION['current_order']->time_future_delivery_c;
		}
		$html_body .= '
			</ul>
			<p>
				Срочность заказа: '.$delivery_method.' <br>
				Способ оплаты: '.$pay_method.' <br>';
			
		if( $new_sum_all >= App::$current_landing->delivery_free_c && !empty(App::$current_landing->delivery_free_c) ){
			$delivery_price = 0;
			$html_body .= '
					К оплате: '.$new_sum_all.'руб. (заказ) и '.$delivery_price.'руб.(стоимость доставки) = '.($new_sum_all+$delivery_price).'руб. <br>
					Сдача с '.($sdacha = intval(($new_sum_all+$delivery_price)/1000)*1000+1000).' : '.($sdacha - ($new_sum_all+$delivery_price)).'руб. <br>
					Сдача с '.($sdacha = intval(($new_sum_all+$delivery_price)/1000)*1000+500).' : '.($sdacha - ($new_sum_all+$delivery_price)).'руб. <br> 
				</p>';
		} else {
			$html_body .= '
					К оплате: '.$new_sum_all.'руб. (заказ) и '.$delivery_price.'руб.(стоимость доставки) = '.($new_sum_all+$delivery_price).'руб. <br>
					Сдача с '.($sdacha = intval(($new_sum_all+$delivery_price)/1000)*1000+1000).' : '.($sdacha - ($new_sum_all+$delivery_price)).'руб. <br>
					Сдача с '.($sdacha = intval(($new_sum_all+$delivery_price)/1000)*1000+500).' : '.($sdacha - ($new_sum_all+$delivery_price)).'руб. <br> 
			</p>';
		}
		$html_body .= '<br><br><br><br> Чтобы отписаться от получения уведомлений перейдите по ссылке http://potemkin24.ru/delete_mail.php?landing_id='.App::$current_landing->id.'&email='.App::$current_landing->email_order_c;
		
		return $html_body;
	}
	
	public function send_email() {
		$subject = 'WINMON - Новый заказ на доставку '.str_replace('&', '-', App::$current_organization->name_rus_c).' в городе '.App::$current_city->name;
		$html_body =  $this->body_html();
		$to      = App::$current_landing->email_order_c;
		
		/*$res = NFfunctions::post_to_url(
			'http://potemkin24.ru/send_mail.php',
			[
				'subject' => $subject,
				'html_body' => htmlspecialchars_decode($html_body),
				'to' => $to
			]
		);*/
		
		$res = NFfunctions::sendMail(
			$subject,
			htmlspecialchars_decode($html_body),
			[$to]
		);

		//отсылаем почту на spotemkin94@yandex.ru
		/*NFfunctions::post_to_url(
			'http://potemkin24.ru/send_mail.php',
			[
				'subject' => $subject,
				'html_body' => htmlspecialchars_decode($html_body),
				'to' => 'spotemkin94@yandex.ru'
			]
		);*/
	}
	
	public function download_app(){
		if($this->checkPlatform() == 'android' && !empty(App::$current_landing->google_play_c)) {
			header('Location: '.App::$current_landing->google_play_c);
			exit();
		}elseif($this->checkPlatform() == 'ios' && !empty(App::$current_landing->app_store_c)) {
			header('Location: '.App::$current_landing->app_store_c);
			exit();
		}
		
		header('HTTP/1.1 200 OK');
		header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/telegram');
		exit();
	}
}