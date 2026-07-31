<?php

class VkappController extends Controller
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
		new BasketController();
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		
		$data = $this->model->getData();

		$this->view->generateWithTemplate(
			'main_view.php', 
			'template_main_vkapp_view.php',
			$data
		);
	}
	
		public function iframe()
	{
		$this->view->generate(
			'vkapp/iframe_view.php',
			$this->model->getData()
		);
	}
	
	public function menu()
	{
		new BasketController();
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		
		$data = $this->model->getData();
		$this->view->generateWithTemplate(
			'menu_view.php', 
			'template_main_vkapp_view.php',
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
			'template_main_vkapp_view.php', 
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
				'template_main_vkapp_view.php', 
				$data
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp');
			exit();
		}
	}
	
	public function lead_mobile()
	{	
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_GET['session_id']);
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
			$this->model->getDescription('На этой странице Вы можете оформить свой заказ в городе '.App::$current_city->name.'. Наши диспетчеры свяжутся с Вами сразу после оформления.');
			$data = $this->model->getData();
			
			$this->view->generateWithTemplate(
				'lead_mobile_view.php', 
				'template_main_vkapp_view.php', 
				$data
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp');
			exit();
		}
	}
	
	public function lead_mobile2()
	{	
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_GET['session_id']);
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
				'lead_mobile2_view.php', 
				'template_main_vkapp_view.php', 
				$data
			);
		} else {

			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp');
			exit();
		}
	}
	
	public function pwa()
	{	
		$this->model->getTitle('Страница - '.App::$current_organization->name_rus_c);
		$this->view->generate(
			'vkapp/pwa_view.php',
			$this->model->getData()
		);
	}
	
	public function login()
	{	
		if(empty($_COOKIE['client_id'])){
			$this->model->getTitle('Страница авторизации - '.App::$current_organization->name_rus_c);
			$this->model->getDescription('Авторизуйтесь на сайте '.App::$current_organization->name_rus_c.' ,указав свой номер телефона. После авторизации вы сможете отслеживать статус выполнения заказа.');
			$this->view->generate(
				'vkapp/login_view.php',
				$this->model->getData()
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/profile');
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
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/login');
		} else {
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp');
		}
		exit();
	}
	
	//отправка звонка клиенту для авторизации и получение кода
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
		$result = NFfunctions::get_curl('https://smsc.ru/sys/send.php?login='.$login.'&psw='.$password.'&phones='.$phone.'&mes=code&call=1&fmt=3');

		$request_message = json_decode($result , JSON_OBJECT_AS_ARRAY);
		if(isset($request_message['id']) && isset($request_message['code'])){
			$code = substr($request_message['code'], -4); //берем последние 4ре символа

			echo md5($code);
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
			if(!$client_id){
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
				'template_main_vkapp_view.php', 
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
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp');
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
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/profile');
			exit();
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp');
			exit();
		}
	}
	
	
	public function contact()
	{
		$this->model->getTitle('Контактная информация - '.App::$current_organization->name_rus_c.' в городе '.App::$current_city->name);
		$this->model->getDescription('Вы можете связаться с нами по номеру телефона '.App::$current_landing->phone1_c.'. Мы нахоимся по адресу: '.str_replace(PHP_EOL, ' ', strip_tags(html_entity_decode(App::$current_landing->address_c))));
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'contact_view.php', 
			'template_main_vkapp_view.php', 
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
			'template_main_vkapp_view.php', 
			$this->model->getData()
		);
	}
	
	public function review()
	{
		$this->model->getTitle('Отзывы о '.App::$current_organization->name_rus_c.' в городе '.App::$current_city->name);
		$this->model->getDescription('Ознакомьтесь с отзывами о нашем заведении. Мы имеем отзывы в Google, Яндекс, Вконтакте, Instagram.');
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'review_view.php', 
			'template_main_vkapp_view.php', 
			$this->model->getData()
		);
	}
	
	public function product()
	{	
		$prdctBean = BeanFactory::getBean('prdct_products', $this->params[0]);
		
		if($prdctBean->id){
			$this->model->getTitle($prdctBean->name.' - '.App::$current_organization->name_rus_c);
			$this->model->getDescription(str_replace(PHP_EOL, ' ', strip_tags(html_entity_decode($prdctBean->description))));
			if(isset($_SESSION['current_order'])) {
				$this->model->getOrderProducts($_SESSION['current_order']);
			}
			
			$data = $this->model->getData();
			$data['product_id'] = $this->params[0];
			
			$this->view->generateWithTemplate(
				'product_view.php', 
				'template_main_vkapp_view.php',
				$data
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp');
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
				$product['height_image'] = '315';
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
			$data['group'] = $group;
			$data['products'] = $products;
			
			$this->view->generateWithTemplate(
				'category_view.php', 
				'template_main_vkapp_view.php',
				$data
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp');
			exit();
		}
	}
	
	
	public function delete_lead(){
		$this->cleanCurrentOrder();
		header('HTTP/1.1 200 OK');
		header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp');
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
		$basket = new BasketController();
		
		
		//простановка промо-кода
		if($discountBean = NFfunctions::getParentBean($duplicateOrder, 'dscnt_discount')){
			$basket->checkPromoCode($discountBean->promo_code_c);
		}

		header('HTTP/1.1 200 OK');
		header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/lead');
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
			'template_main_vkapp_view.php', 
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
			'template_main_vkapp_view.php', 
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
			'template_main_vkapp_view.php', 
			$this->model->getData()
		);
	}
	
	public function send_job(){
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
		header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/job?&success=yes');
		exit();
	}
	
	public function faq()
	{	
		$this->model->getTitle('Ответы на часто задаваемые вопросы');
		$this->model->getDescription('На этой странице мы постарались ответить на самые часто задаваемые вами вопросы.');
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}

		$this->view->generateWithTemplate(
			'faq_view.php', 
			'template_main_vkapp_view.php', 
			$this->model->getData()
		);
	}

	public function news()
	{	
		if($this->params) {
			global $db;
			$news = $db->fetchRow($db->query("
				SELECT nn.name, nn_cstm.text_c 
				FROM news_news nn 
				JOIN news_news_cstm nn_cstm ON nn_cstm.id_c = nn.id AND nn.deleted = 0
				WHERE nn.id = '".$this->params[0]."' AND nn.deleted = 0
			"));
			
			$this->model->getTitle($news['name']);
			$this->model->getDescription(mb_substr(str_replace(PHP_EOL, ' ', strip_tags(html_entity_decode($news['text_c']))), 0, 150));
			if(isset($_SESSION['current_order'])) {
				$this->model->getOrderProducts($_SESSION['current_order']);
			}
			
			$data = $this->model->getData();
			$data['news_id'] = $this->params[0];
			

			$this->view->generateWithTemplate(
				'news_item_view.php', 
				'template_main_vkapp_view.php', 
				$data
			);
		} else {
			$this->model->getTitle('Новости '.App::$current_organization->name_rus_c);
			$this->model->getDescription('Ознакомьтесь с нашими акциями, последними нововведениями и новостями.');
			if(isset($_SESSION['current_order'])) {
				$this->model->getOrderProducts($_SESSION['current_order']);
			}

			$this->view->generateWithTemplate(
				'news_view.php', 
				'template_main_vkapp_view.php', 
				$this->model->getData()
			);
		}
		
		
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
				height_image_c 
			FROM pdgrp_product_groups_cstm ppg_cstm
			WHERE ppg_cstm.id_c = '".$group_id."';
		"));
		
		$queryProducts = $db->query("
			SELECT pp.*, pp_cstm.* , image.id as product_image_id
			FROM prdct_products pp 
			JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp.deleted = 0 AND pp_cstm.active_c = 1
			JOIN pdgrp_product_groups_prdct_products_1_c pg_pp ON pg_pp.pdgrp_product_groups_prdct_products_1prdct_products_idb = pp_cstm.id_c AND pg_pp.pdgrp_product_groups_prdct_products_1pdgrp_product_groups_ida = '".$group_id."'
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
				$product['height_image'] = '315';
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
			$out .= '<url>'.App::$current_landing->link_c.'/vkapp/product/'.$product['id'].'</url>'."\r\n";
		 
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
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		
		$this->view->generateWithTemplate(
			'area_map_view.php', 
			'template_main_vkapp_view.php', 
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
			'template_main_vkapp_view.php', 
			$this->model->getData()
		);
	}
	
	public function pre_order_check() {
		new BasketController();
		$this->updateSessionbyPostData();
		new BasketController();

		header('HTTP/1.1 200 OK');
		header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/lead');
		exit();
	}
	
	public function order_check() {
		if(App::$current_landing->status_c == '02' || in_array($_SESSION['current_order']->pay_method_c, ['01','02','04'])){
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/order_offline_pay');
			exit();
		}elseif($_SESSION['current_order']->pay_method_c == '03'){
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/order_online_pay');
			exit();
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/lead');
			exit();
		}
	}
	
	public function sberbank_acquiring($sberbank_login, $sberbank_password, $order){
		$vars = array();
 
		$vars['userName'] = $sberbank_login;
		$vars['password'] = $sberbank_password;
		 
		/* ID заказа в магазине */
		$vars['orderNumber'] = $order->name;
		 
		/* Сумма заказа в копейках */
		$vars['amount'] = $order->all_price_c*100;
		 
		/* URL куда клиент вернется в случае успешной оплаты */
		$vars['returnUrl'] = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/sberbank_acquiring_success_pay';
			
		/* URL куда клиент вернется в случае ошибки */
		$vars['failUrl'] = 'http://example.com/error/';
		 
		/* Описание заказа, не более 24 символов, запрещены % + \r \n */
		$vars['description'] = 'Онлайн-заказ №' . $order->name;
		$ch = curl_init('https://3dsec.sberbank.ru/payment/rest/register.do?' . http_build_query($vars));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$res = curl_exec($ch);
		curl_close($ch);
		
		return $res;
	}
	
	public function sberbank_acquiring_success_pay(){
		if(!empty($_GET['orderId'])){
			global $db;
			$db->query("UPDATE ordrs_orders_cstm SET status_pay_c = '02' WHERE sberbank_acquiring_id_c = '".$_GET['orderId']."'");
		}
		header('HTTP/1.1 200 OK');
		header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/order_offline_pay');
		exit();
	}
	
	public function order_online_pay() {
		$this->model->getCurrentProductGroups();
		$this->model->getLandings();
		$this->model->getTitle('Онлайн-оплата заказа');
		$data = $this->model->getData();
		if(isset($_SESSION['current_order']->delivery_price_c) && $_SESSION['current_order']->delivery_price_c !== NULL && $_SESSION['current_order']->phone_c !== NULL && empty($_GET['online_pay_transaction'])) {
			$this->saveOrder();

			//оплата сбербанк эквайринг
			if(App::$current_landing->sberbank_acquiring_payments_c){
				$result = $this->sberbank_acquiring(
					App::$current_landing->sberbank_acquiring_login_c,
					App::$current_landing->sberbank_acquiring_password_c,
					$_SESSION['current_order']
				);
				$res = json_decode($result, JSON_OBJECT_AS_ARRAY);
				if (empty($res['orderId'])){
					/* Возникла ошибка: */
					echo $res['errorMessage'];						
				} else {
					/* Успех: */
					global $db;
					$db->query("UPDATE ordrs_orders_cstm SET sberbank_acquiring_id_c = '".$res['orderId']."' WHERE id_c = '".$_SESSION['current_order']->id."'");
					/* Перенаправление клиента на страницу оплаты */
					header('Location: ' . $res['formUrl'], true);
				}
				
			} else {
				$this->view->generateWithTemplate( 
					'order_processed_online_view.php', 
					'template_main_vkapp_view.php', 
					$data
				);
			}
		} elseif( $_GET['online_pay_transaction'] == $_SESSION['current_order']->online_pay_transaction_c) {
			$order = BeanFactory::getBean('ordrs_orders', $_SESSION['old_order']->id);
			$order->status_pay_c = '02';
			$_SESSION['old_order']->status_pay_c = '02';
			$order->saveStopHooks();
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/order_offline_pay');
			exit();
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/lead');
			exit();
		}
	}
	
	public function order_offline_pay() {
		$this->model->getCurrentProductGroups();
		$this->model->getLandings();
		$this->model->getTitle(App::$current_organization->name.' '.App::$current_city->name.' - доставка на дом!');
		$data = $this->model->getData();
		if(!empty($promoBeans = NFfunctions::getChildBeans(App::$current_landing, 'dscnt_discount'))){
			foreach($promoBeans as $promoBean){
				if($promoBean->shared_promo_code_c){
					$sharedPromo = $promoBean;
					break;
				}
			}
		}
		if(!empty($sharedPromo)){
			$productBean = BeanFactory::getBean('prdct_products', $sharedPromo->prdct_products_id_c);
			$this->model->getTitle('Доставка '.App::$current_organization->name_rus_c.' в городе '.App::$current_city->name.' : промо-код '.$sharedPromo->promo_code_c.' на БЕСПЛАТНЫЙ "'.$productBean->name.'" ! #winmon #'.App::$current_organization->name_rus_c.' #'.App::$current_organization->name);
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
			$this->saveOrder();
			$_SESSION['old_order'] = clone $_SESSION['current_order'];
			$this->send_email();
			$this->cleanCurrentOrder();
		} 
		
		if($_SESSION['old_order']){
			$this->view->generateWithTemplate(
				'order_processed_offline_view.php', 
				'template_main_vkapp_view.php', 
				$data
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp/lead');
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
	
	public function saveOrder() {
		$start = microtime(true);
		audt_Audit::message('POTEMKIN время: '. round(microtime(true) - $start, 3) . ' сек. СТАРТ saveORDER', 'ordrs_orders');
		if($_SESSION['current_order']->upload_products_c != '01'){
			$_SESSION['current_order']->ip_c = $_SERVER["REMOTE_ADDR"];
			$_SESSION['current_order']->user_agent_c = $_SERVER["HTTP_USER_AGENT"];
			$_SESSION['current_order']->status_c = '01';
			
			$_SESSION['current_order']->source_c = '11'; //android
			if($this->checkPlatform() == 'ios') {
				$_SESSION['current_order']->source_c = '12';//ios
			}
			
			$_SESSION['current_order']->assigned_user_id = App::$current_landing->assigned_user_id;
			
			//проставляем лендинг
			$_SESSION['current_order']->lngng_landings_id_c = App::$current_landing->id;
			//проставляем город
			$_SESSION['current_order']->city_cities_id1_c = App::$current_city->id;
			
			//проставляем район
			if(!empty($_SESSION['current_order']->CUSTOM_area->id)){
				$_SESSION['current_order']->area_area_id_c = $_SESSION['current_order']->CUSTOM_area->id;
			}
			
			//audt_Audit::message('POTEMKIN время: '. round(microtime(true) - $start, 3) . ' сек. ДО первого SAVE', 'ordrs_orders');
			
			$_SESSION['current_order']->save();
			
			//audt_Audit::message('POTEMKIN Заказ_ID: '.$_SESSION['current_order']->id.' время: '. round(microtime(true) - $start, 3) . ' сек. ПОСЛЕ первого SAVE', 'ordrs_orders');
			
			//проставляем промо-код
			if($promoBean = BeanFactory::getBean('dscnt_discount',$_SESSION['current_order']->CUSTOM_promo)){
				$_SESSION['current_order']->dscnt_discount_id_c = $promoBean->id;
			}

			$_SESSION['current_order']->save();
			//audt_Audit::message('POTEMKIN Заказ_ID: '.$_SESSION['current_order']->id.' время: '. round(microtime(true) - $start, 3) . ' сек. ПОСЛЕ второго SAVE', 'ordrs_orders');
			
			//проставляем продукты в заказе
			foreach($_SESSION['current_order']->CUSTOM_products as $productBean){
				//создаем Продукт в заказе
				$product_in_order = BeanFactory::newBean('prord_products_in_order');
				$product_in_order->name = 'Продукт не создался - уточнить у клиента';
				$product_in_order->prdct_products_id_c = $productBean->id;
				$product_in_order->lngng_landings_id_c = $productBean->lngng_landings_id_c;
				$product_in_order->assigned_user_id = App::$current_landing->assigned_user_id;
				$product_in_order->source_c = $productBean->CUSTOM_sourse;

				$product_in_order->save();
				//audt_Audit::message('POTEMKIN Заказ_ID: '.$_SESSION['current_order']->id.' время: '. round(microtime(true) - $start, 3) . ' сек. ПОСЛЕ создания продукта и его SAVE', 'ordrs_orders');
				
				//создаем связь с заказом
				NFfunctions::setParentBean($product_in_order, $_SESSION['current_order']);
				//audt_Audit::message('POTEMKIN Заказ_ID: '.$_SESSION['current_order']->id.' время: '. round(microtime(true) - $start, 3) . ' сек. ПОСЛЕ создания связи продукта с заказом', 'ordrs_orders');
			}
			//audt_Audit::message('POTEMKIN Заказ_ID: '.$_SESSION['current_order']->id.' время: '. round(microtime(true) - $start, 3) . ' сек. ПОСЛЕ СОЗДАНИЯ ВСЕХ ПРОДУКТОВ', 'ordrs_orders');
			
			$_SESSION['current_order']->upload_products_c = '01';

			//если заказ полностью прогружен, то отправляем оповещения диспетчерам данного лендинга
			if($_SESSION['current_order']->upload_products_c == '01'){
				$vk_dispatcher_message_id = '';
				$telegram_dispatch_message_id_c = '';
				foreach(NFfunctions::getChildBeans(App::$current_landing, 'dsptc_dispatcher') as $dsptcBean){
					//если принимаем оповещения в ВК и статус отличный от НЕ РАБОТАЕТ
					if($dsptcBean->vk_active_c && $dsptcBean->status_c != '02'){ 
						//02 = Вконтакте, 01 = отправить оповещение диспетчеру о новом заказа
						NFfunctions::addMessageInSystemNotificationsQueue('02', '01', $dsptcBean->vk_id_c, 'ДИСПЕТЧЕР! Заказ #'.$_SESSION['current_order']->name.' - '.App::$current_organization->name.' '.App::$current_city->name.'<br>Адрес: '.$_SESSION['current_order']->address_c.'<br> Подробнее: <br>  http://cabinet.giveat.ru/dispatcher/order/'.$_SESSION['current_order']->id.'<br>Статус заказа: Новый заказ', $_SESSION['current_order']->id);
					}
					//если принимаем оповещения в телеграм и статус отличный от НЕ РАБОТАЕТ
					if($dsptcBean->telegram_active_c && $dsptcBean->status_c != '02'){ 
						//03 = Телеграмм, 01 = отправить оповещение диспетчеру о новом заказа
						NFfunctions::addMessageInSystemNotificationsQueue('03', '01', $dsptcBean->telegram_chat_id_c, "<b>ДИСПЕТЧЕР</b>!\n<b>Заказ #".$_SESSION['current_order']->name.":</b> ".App::$current_organization->name." ".App::$current_city->name."\n<b>Адрес:</b> ".$_SESSION['current_order']->address_c."\n<b>Текущий статус:</b> Новый заказ", $_SESSION['current_order']->id);
					}
					
					//если принимает в мобильном приложении
					if($dsptcBean->android_mobile_token_c){
						//04 = Телеграмм, 01 = отправить оповещение диспетчеру о новом заказа
						NFfunctions::addMessageInSystemNotificationsQueue('04', '01', $dsptcBean->android_mobile_token_c, 'Новый заказ #'.$_SESSION['current_order']->name.' - '.App::$current_organization->name.' '.App::$current_city->name, $_SESSION['current_order']->id);
					}
				}


				//если было хотябы одно сообщение для диспетчера ВК
				if(!empty($vk_dispatcher_message_id)){
					$_SESSION['current_order']->vk_dispatcher_message_id_c = $vk_dispatcher_message_id;
				}
				
				//если было хотябы одно сообщение для диспетчера ТЕЛЕГРАМ
				if(!empty($telegram_dispatch_message_id_c)){
					$_SESSION['current_order']->telegram_dispatch_message_id_c = $telegram_dispatch_message_id_c;
				}
			}
			
			$_SESSION['current_order']->saveStopHooks();
			$_SESSION['old_order'] = clone $_SESSION['current_order'];
			
			NFfunctions::addSecuritygroupInBean($_SESSION['current_order']);//чтобы отработали для ролевой модели
			
			audt_Audit::message('POTEMKIN Заказ_ID: '.$_SESSION['current_order']->id.' время: '. round(microtime(true) - $start, 3) . ' сек. ПОСЛЕ addSecuritygroupInBean', 'ordrs_orders');
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
	
	public function download_app(){
		if($this->checkPlatform() == 'android' && !empty(App::$current_landing->google_play_c)) {
			header('Location: '.App::$current_landing->google_play_c);
			exit();
		}elseif($this->checkPlatform() == 'ios' && !empty(App::$current_landing->app_store_c)) {
			header('Location: '.App::$current_landing->app_store_c);
			exit();
		}
		
		header('HTTP/1.1 200 OK');
		header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/vkapp');
		exit();
	}
}