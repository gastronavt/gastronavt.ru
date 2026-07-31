<?php

class MobileController extends Controller
{
	public function __construct($params  = array())
	{
		parent::__construct($params);
		
		$this->model = new Main();

		$this->model->getTitle();
	}
	
	public function lead()
	{	
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}
		
		if(!empty($_SESSION['current_order']->CUSTOM_products)){
			$this->model->getCurrentProductGroups();
			$this->model->getCurrentBranchs();
			$this->model->getCurrentAreas('show_order_c');
			$this->model->getLandings();
			$this->model->getOrderProducts($_SESSION['current_order']);
			$this->model->getCurrentStreets();
			$this->model->getTitle('Оформление доставки продукции '.App::$current_organization->name_rus_c.' в городе '.App::$current_city->name);
			$data = $this->model->getData();
			
			$this->view->generateWithTemplate(
				'lead_view.php', 
				'mobile/template_main_view.php', 
				$data
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: http://'.$_SERVER['HTTP_HOST']);
			exit();
		}
	}
	
	public function login()
	{	
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}
		
		if(empty($_SESSION['client_id'])){
			$this->model->getTitle('Страница авторизации - '.App::$current_organization->name_rus_c);
			$this->view->generate(
				'mobile/login_view.php',
				$this->model->getData()
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/mobile/profile?session_id='.$_SESSION['session_id']);
			exit();
		}
	}
	
	public function logout()
	{	
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}
	
		if(!empty($_SESSION['client_id'])){
			$_SESSION['client_id'] = '';
		}

		header('Location: http://'.$_SERVER['HTTP_HOST'].'/mobile/login?session_id='.$_SESSION['session_id']);
		exit();
	}
	
	
	//отправка звонка клиенту для авторизации и получение кода (последние четыре цифры)
	public function get_call_code()
	{	
		$phone = preg_replace("/[^0-9]/", '', $this->params[0]);
		
		setcookie('client_phone', $phone, time()+2592000, '/');
		if(App::$current_landing->id == '6cf59565-a918-ff2e-9d5d-5bf32ae80893'){//пряников
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
		if(App::$current_landing->id == '6cf59565-a918-ff2e-9d5d-5bf32ae80893'){//пряников
			$login = 'pryanikov38';
			$password = '89501442426Abc';
		} else{
			$login = 'yenon';
			$password = '18291829';
		}
		
		$code = random_int(1111,9999);
		
		if(!empty($phone) && !empty($imgcode)){
			$result = NFfunctions::get_curl('https://smsc.ru/sys/send.php?login='.$login.'&psw='.$password.'&phones='.$phone.'&mes=%D0%9A%D0%BE%D0%B4%20%D0%B0%D0%B2%D1%82%D0%BE%D1%80%D0%B8%D0%B7%D0%B0%D1%86%D0%B8%D0%B8:%20'.$code.'&call=1&fmt=3&imgcode='.$imgcode.'&userip='.$_SERVER["REMOTE_ADDR"]);
		}
		echo md5($code);
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
	
	public function newyear()
	{
		$this->model->getTitle(App::$current_organization->name_rus_c.' - Дедушка Мороз лично поздравит ваших детей #winmon');
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'newyear_view.php', 
			'mobile/template_main_view.php', 
			$this->model->getData()
		);
	}
	
	public function profile()
	{	
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && !empty($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}
	
		if(!empty($_SESSION['client_id'])){
			App::$current_user = BeanFactory::getBean('clnts_clients', $_SESSION['client_id']);
		}
	
		if(!empty(App::$current_user->id)) {
			if(!empty($_SESSION['current_order'])){
				$this->model->getOrderProducts($_SESSION['current_order']);
			}
			$this->model->getTitle('Профиль пользователя');

			$this->view->generateWithTemplate(
				'profile_view.php', 
				'mobile/template_main_view.php', 
				$this->model->getData()
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: http://'.$_SERVER['HTTP_HOST']);
			exit();
		}
	}
	
	public function update_user_settings()
	{	
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}
	
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
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/mobile/profile?session_id='.$_REQUEST['session_id']);
			exit();
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/mobile/login?session_id='.$_REQUEST['session_id']);
			exit();
		}
	}
	
	
	public function delete_lead(){
		$this->cleanCurrentOrder();
		header('HTTP/1.1 200 OK');
		header('Location: http://'.$_SERVER['HTTP_HOST']);
		exit();
	}
	
	public function duplicate_order(){
		$duplicateOrder = BeanFactory::getBean('ordrs_orders', $this->params[0]);
		new BasketController();
		$_SESSION['current_order']->street_c = $duplicateOrder->street_c;
		$_SESSION['current_order']->home_c = $duplicateOrder->home_c;
		$_SESSION['current_order']->room_c = $duplicateOrder->room_c;
		$_SESSION['current_order']->count_persons_c = $duplicateOrder->count_persons_c;
		
				
		global $db;
		$query_duplicate_products = $db->query("
			SELECT 
				prod.id as product_id,
				pp.name as product_name,
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
			$_POST['product_id'][] = $duplicate_product['product_id'];
			$_POST['count'][] = $duplicate_product['count'];
		}
		
		$this->updateSessionbyPostData();
		new BasketController();
		

		header('HTTP/1.1 200 OK');
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/mobile/lead?session_id='.$_REQUEST['session_id']);
		exit();
	}
	
	public function term_of_use()
	{	
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}
	
		$this->model->getTitle('Пользовательское соглашение службы доставки '.App::$current_organization->name_rus_c.' по городу '.App::$current_city->name);
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		
		$this->view->generateWithTemplate(
			'term_view.php', 
			'mobile/template_main_view.php', 
			$this->model->getData()
		);
	}
	
	public function agreement()
	{	
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}
		
		$this->model->getTitle('Согласие на обработку персональных данных');
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'agreement_view.php', 
			'mobile/template_main_view.php', 
			$this->model->getData()
		);
	}
	
	public function job()
	{
		$this->model->getTitle('Открытые вакансии в '.App::$current_organization->name_rus_c.' - город '.App::$current_city->name);
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'job_view.php', 
			'mobile/template_main_view.php', 
			$this->model->getData()
		);
	}
	
	public function send_job(){
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}
		
		$smmrBean = BeanFactory::newBean('smmr_summary');
		$smmrBean->vacancy_c = $_POST['vacancy_c'];
		$smmrBean->first_name_c = $_POST['first_name_c'];
		$smmrBean->last_name_c = $_POST['last_name_c'];
		$smmrBean->middle_name_c = $_POST['middle_name_c'];
		$smmrBean->work_phone_c = $_POST['work_phone_c'];
		$smmrBean->date_of_birth_c = DateTime::createFromFormat('d.m.Y', $_POST['date_of_birth_c'])->format('Y-m-d');
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

		//(new Rest())->set_entry('smmr_summary',[["name" => 'id', "value" => $smmrBean->id]]); //чтобы отработали для ролевой модели
		NFfunctions::addSecuritygroupInBean($smmrBean);//чтобы отработали для ролевой модели
		
		$from    = 'winmon@yandex.ru';
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
		$res = NFfunctions::post_to_url(
			'http://potemkin24.ru/send_mail.php',
			[
				'subject' => $subject,
				'html_body' => htmlspecialchars_decode($html_body),
				'to' => $to
			]
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
		
		header('HTTP/1.1 200 OK');
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/mobile/job?&success=yes&session_id='.$_REQUEST['session_id']);
		exit();
	}
	
	public function get_products()
	{	
		$group_id = $this->params[0];
		
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		$data = $this->model->getData();
		
		global $db;
		$queryProducts = $db->query("
			SELECT pp.*, pp_cstm.* 
			FROM prdct_products pp 
			JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp.deleted = 0 AND pp_cstm.active_c = 1
			JOIN pdgrp_product_groups_prdct_products_1_c pg_pp ON pg_pp.pdgrp_product_groups_prdct_products_1prdct_products_idb = pp_cstm.id_c 
			AND pg_pp.pdgrp_product_groups_prdct_products_1pdgrp_product_groups_ida = '".$group_id."'
			ORDER BY pp_cstm.show_order_c;
		");
		$products = [];
		while($product = $db->fetchByAssoc($queryProducts)) {
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
	
	public function area_map()
	{	
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}
	
		$this->model->getTitle('Карта доставки '.App::$current_organization->name_rus_c.' по городу '.App::$current_city->name);
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		
		$this->view->generateWithTemplate(
			'area_map_view.php', 
			'mobile/template_main_view.php', 
			$this->model->getData()
		);
	}
	
	public function pre_order_check() {
		new BasketController();
		$this->updateSessionbyPostData();
		new BasketController();

		header('HTTP/1.1 200 OK');
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/mobile/lead');
		exit();
	}
	
	public function order_check() {
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}

		if(App::$current_landing->status_c == '02' || in_array($_SESSION['current_order']->pay_method_c, ['01','02','04','05'])){
			header('HTTP/1.1 200 OK');
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/mobile/order_offline_pay?session_id='.$_REQUEST['session_id']);
			exit();
		}elseif($_SESSION['current_order']->pay_method_c == '03'){
			header('HTTP/1.1 200 OK');
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/mobile/order_online_pay?session_id='.$_REQUEST['session_id']);
			exit();
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/mobile/lead');
			exit();
		}
	}
	
	public function order_online_pay() {
		$this->model->getCurrentProductGroups();
		$this->model->getLandings();
		$this->model->getTitle('Онлайн-оплата заказа');
		$data = $this->model->getData();
		if(isset($_SESSION['current_order']->delivery_price_c) && $_SESSION['current_order']->delivery_price_c !== NULL && $_SESSION['current_order']->phone_c !== NULL && empty($_GET['online_pay_transaction'])) {
			$this->savePreOrder();

			$this->view->generateWithTemplate( 
				'order_processed_online_view.php', 
				'template_main_view.php', 
				$data
			);
		} elseif( $_GET['online_pay_transaction'] == $_SESSION['current_order']->online_pay_transaction_c) {
			$order = BeanFactory::getBean('ordrs_orders', $_SESSION['old_order']->id);
			$order->status_pay_c = '02';
			$_SESSION['old_order']->status_pay_c = '02';
			$order->saveStopHooks();
			header('HTTP/1.1 200 OK');
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/mobile/order_offline_pay');
			exit();
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/mobile/lead');
			exit();
		}
	}
	
	public function order_offline_pay() {
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}

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
		
		//если чувак отказался от онлайн оплаты и перешел к оплате наличкой, то меняем способ оплаты в заказе
		if(isset($_GET['pay_method']) && $_GET['pay_method'] == '01' && $_SESSION['current_order']->phone_c !== NULL) {
			$order = BeanFactory::getBean('ordrs_orders', $_SESSION['old_order']->id);
			$order->pay_method_c = '01';
			$_SESSION['old_order']->pay_method_c = '01';
			$order->saveStopHooks();
			$this->cleanCurrentOrder();
		}elseif(isset($_SESSION['current_order']->delivery_price_c) && $_SESSION['current_order']->delivery_price_c !== NULL && $_SESSION['current_order']->phone_c !== NULL  ) {
			$start = microtime(true);
			$this->savePreOrder();
			$_SESSION['old_order'] = clone $_SESSION['current_order'];
			$this->send_email();
			$this->cleanCurrentOrder();
		} 
		if($_SESSION['old_order']){
			$this->view->generateWithTemplate(
				'order_processed_offline_view.php', 
				'mobile/template_main_view.php', 
				$data
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/mobile/lead');
			exit();
		}
	}
	
	
	// определение мобильного устройства
	public function checkPlatform() { 
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
	
	public function savePreOrder() {
		$_SESSION['current_order']->lead_path_c = 'android'; 
		if($this->checkPlatform() == 'ios') {
			$_SESSION['current_order']->lead_path_c = 'ios';
		}
		
		
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
		
		$preorderBean->comment_client_c = $_SESSION['current_order']->comment_client_c;
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
		
		//проставляем продукты в заказ
		foreach($_SESSION['current_order']->CUSTOM_products as $productBean){
			$preorderBean->products_c .= $productBean->id.'^|^';
			
			$source = $productBean->CUSTOM_sourse ?? null;
			$preorderBean->products_source_c .= $source.'^|^';
		}
		
		$preorderBean->products_c = mb_substr($preorderBean->products_c, 0, -3);
		$preorderBean->products_source_c = mb_substr($preorderBean->products_source_c, 0, -3);
		
		$preorderBean->save();
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
				$sum_all += (int)$product['product']->purchase_price_c*$product['count'];
			}
		}
		
		if(!empty($_SESSION['current_order']->CUSTOM_promo)) {
			$promoBean = BeanFactory::getBean('dscnt_discount',$_SESSION['current_order']->CUSTOM_promo);
			if($promoBean->promo_type_c == '01') {
				$productPromo = BeanFactory::getBean('prdct_products',$promoBean->prdct_products_id_c);
				$system_description = !empty($productPromo->system_description_c) ? ' ( '.$productPromo->system_description_c.' ) ' : '';
				$html_body .= '<li>Подарок по промо-коду "'.$promoBean->promo_code_c.'": '.html_entity_decode($productPromo->name).$system_description.'('.$productPromo->purchase_price_c.'руб.) - 1шт. - '.$productPromo->purchase_price_c.'руб.</li>';
				$sum_all += (int)$productPromo->purchase_price_c;
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
		
		$html_body .= 
			'<p>
				<strong>Клиент:</strong><br>
				Имя: '.$_SESSION['current_order']->client_name_c.'<br>
				Телефон: <a href="tel:'.$_SESSION['current_order']->phone_c.'">'.$_SESSION['current_order']->phone_c.'</a>'.
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
		$subject = 'CRM - Новый заказ на доставку '.str_replace('&', '-', App::$current_organization->name_rus_c).' в городе '.App::$current_city->name;
		$html_body =  $this->body_html();
		$to      = App::$current_landing->email_order_c;
		
		$res = NFfunctions::post_to_url(
			'http://potemkin24.ru/send_mail.php',
			[
				'subject' => $subject,
				'html_body' => htmlspecialchars_decode($html_body),
				'to' => $to
			]
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
}