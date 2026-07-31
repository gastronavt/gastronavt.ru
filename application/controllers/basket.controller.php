<?php

class BasketController extends Controller
{

	public function __construct($params  = array())
	{
		parent::__construct($params);
		if( !isset($_SESSION['current_order'] )){
			$_SESSION['current_order'] = BeanFactory::newBean('ordrs_orders');
			$_SESSION['current_order']->client_name_c = null;
			$_SESSION['current_order']->phone_c = null;
			$_SESSION['current_order']->client_instagram_c = null;
			$_SESSION['current_order']->sale_price_c = 0;
			$_SESSION['current_order']->all_price_c = 0;
			$_SESSION['current_order']->delivery_method_c = '01';
			if(App::$current_landing->delivery_active_c){
				$_SESSION['current_order']->receiving_method_c = '01';
			} elseif(App::$current_landing->pickup_c){
				$_SESSION['current_order']->receiving_method_c = '02';
			}
			
			//способ оплаты по умолчанию
			if(App::$current_landing->cash_payments_c){
				$_SESSION['current_order']->pay_method_c = '01';
			} elseif(App::$current_landing->terminal_payments_c){
				$_SESSION['current_order']->pay_method_c = '04';
			}
			elseif(App::$current_landing->qr_payments_c){
				$_SESSION['current_order']->pay_method_c = '05';
			}elseif(App::$current_landing->sberbank_payments_c){
				$_SESSION['current_order']->pay_method_c = '02';
			}elseif( 
				App::$current_landing->sberbank_acquiring_payments_c //сбербанк эквайринг
				||
				App::$current_landing->yookassa_acquiring_c //yookassa эквайринг
			){
				$_SESSION['current_order']->pay_method_c = '03';
			}
			
			
			
			$_SESSION['current_order']->brnch_branch_id_c = null;
			$_SESSION['current_order']->street_c = null;
			$_SESSION['current_order']->home_c = null;
			$_SESSION['current_order']->room_c = null;
			$_SESSION['current_order']->porch_c = null;
			$_SESSION['current_order']->level_c = null;
			$_SESSION['current_order']->doit_c = null;
			$_SESSION['current_order']->promo_code = null;

			$_SESSION['current_order']->CUSTOM_date_future_array = [];
			$_SESSION['current_order']->CUSTOM_time_future_array = [];
			$_SESSION['current_order']->CUSTOM_time_start_work_today = null;//время когда начинаем работать СЕГОДНЯ
			$_SESSION['current_order']->CUSTOM_time_stop_work_today = null;//время когда заканчиваем работать СЕГОДНЯ
			$_SESSION['current_order']->CUSTOM_date_work_today = null;
			$_SESSION['current_order']->CUSTOM_products = [];
			$_SESSION['current_order']->CUSTOM_area = '';
			$_SESSION['current_order']->CUSTOM_check_phone_code = null;
			$_SESSION['current_order']->CUSTOM_promo = null;
			$_SESSION['current_order']->CUSTOM_promo_work = null;
			$_SESSION['current_order']->CUSTOM_promo_code = null;
			$_SESSION['current_order']->CUSTOM_promo_html = null;
			$_SESSION['current_order']->CUSTOM_promo_product_sale_price = null;
			$_SESSION['current_order']->date_future_delivery_c = date('d.m.Y');
			$_SESSION['current_order']->time_future_delivery_c = null;
			$_SESSION['current_order']->upload_products_c = '02';
		}
		if(!isset($_SESSION['current_order']->CUSTOM_promo_product_sale_price)){
			$_SESSION['current_order']->CUSTOM_promo_product_sale_price = null;
		}
		$this->getAllOrderInfo();
	}

	private function generatePhoneCode($length = 5){
	  $chars = '123456789';
	  $numChars = strlen($chars);
	  $string = '';
	  for ($i = 0; $i < $length; $i++) {
		$string .= substr($chars, rand(1, $numChars) - 1, 1);
	  }
	  return $string;
	}

	public function add_product()
	{
		$product_id = $this->params[0];
		$product = BeanFactory::getBean('prdct_products', $product_id);
		
		if(!empty($this->params[1])){//если передан источник товара (доп.категрия или рекомендованные)
			$product->CUSTOM_source = $this->params[1];
		}
		
		//проверяем сколько уже товаров в корзине
		$product_count = 0;
		if( !empty($_SESSION['current_order']->CUSTOM_products) ) {
			foreach($_SESSION['current_order']->CUSTOM_products as $product_temp){
				if($product_id == $product_temp->id){
					$product_count++;
				}
			}
		}
		
		//если можно добавить еще
		if(empty($product->max_count_in_order_c) || $product_count < $product->max_count_in_order_c){
			array_push($_SESSION['current_order']->CUSTOM_products, $product);
			$product_count++;
		}
		
		$product_info = [
			'product_price' => $product->sale_price_c*$product_count,
			'product_count' => $product_count
		];
		
		
		$this->getAllOrderInfo();
		
		echo json_encode(
			[
				'product' => $product_info,
				'only_online_payments' => $_SESSION['current_order']->CUSTOM_only_online_payments,
				'delivery_price' => $_SESSION['current_order']->delivery_price_c,
				'delivery_text' => $_SESSION['current_order']->CUSTOM_delivery_text,
				'receiving_method' => $_SESSION['current_order']->receiving_method_c,
				'products_price' => $_SESSION['current_order']->sale_price_c,
				'all_price' => $_SESSION['current_order']->all_price_c,
				'promo_code' => $_SESSION['current_order']->CUSTOM_promo_code,
				'promo_html' => $_SESSION['current_order']->CUSTOM_promo_html,
				'promo_price' => $_SESSION['current_order']->CUSTOM_promo_product_sale_price,
				'promo_work' => $_SESSION['current_order']->CUSTOM_promo_work
			]
		);
	}

	public function remove_product()
	{
		$product_id = $this->params[0];
		foreach($_SESSION['current_order']->CUSTOM_products as $key => $product){
			if($product_id == $product->id){
				unset($_SESSION['current_order']->CUSTOM_products[$key]);
				break;
			}
		}

		$product_info = $this->getProductInfo($product_id);
		$this->getAllOrderInfo();
		
		//удаляем товары, у которых есть проверка на минимальный размер суммы корзины, для их покупки
		foreach($_SESSION['current_order']->CUSTOM_products as $key => $product){
			if(!empty($product->min_order_price_from_buy_c) && $_SESSION['current_order']->sale_price_c < $product->min_order_price_from_buy_c){
				unset($_SESSION['current_order']->CUSTOM_products[$key]);
				break;
			}
		}

		echo json_encode(
			array(
				$product_info,
				'only_online_payments' => $_SESSION['current_order']->CUSTOM_only_online_payments,
				'delivery_price' => $_SESSION['current_order']->delivery_price_c,
				'delivery_text' => $_SESSION['current_order']->CUSTOM_delivery_text,
				'receiving_method' => $_SESSION['current_order']->receiving_method_c,
				'products_price' => $_SESSION['current_order']->sale_price_c,
				'all_price' => $_SESSION['current_order']->all_price_c,
				'promo_code' => $_SESSION['current_order']->CUSTOM_promo_code,
				'promo_html' => $_SESSION['current_order']->CUSTOM_promo_html,
				'promo_price' => $_SESSION['current_order']->CUSTOM_promo_product_sale_price,
				'promo_work' => $_SESSION['current_order']->CUSTOM_promo_work
			)
		);
	}

	//предварительная обработка района доставки
	private function CUSTOM_area($area_id){
		$_SESSION['current_order']->CUSTOM_area = BeanFactory::getBean('area_area',$area_id);
		if(!$_SESSION['current_order']->CUSTOM_area->name){
			$_SESSION['current_order']->CUSTOM_area = NULL;
		}
		$this->getAllPrice();

		echo json_encode(
			array(
				'delivery_price' => $_SESSION['current_order']->delivery_price_c,
				'delivery_text' => $_SESSION['current_order']->CUSTOM_delivery_text,
				'products_price' => $_SESSION['current_order']->sale_price_c,
				'all_price' => $_SESSION['current_order']->all_price_c
			)
		);

		die();
	}

	//предварительная обработка способа доставки(сразу или ко времени)
	private function delivery_method_c($delivery_method){
		if($delivery_method == '01'){
			$_SESSION['current_order']->date_future_delivery_c = null;
			$_SESSION['current_order']->time_future_delivery_c = null;
		}
		$_SESSION['current_order']->delivery_method_c = $delivery_method;
		die();
	}

	//предварительная обработка способа оплаты
	private function pay_method_c($pay_method){
		$_SESSION['current_order']->pay_method_c = $pay_method;
		if($_SESSION['current_order']->pay_method_c == '03' && empty($_SESSION['current_order']->online_pay_transaction_c)){
			$_SESSION['current_order']->online_pay_transaction_c = uniqid();
		}
		echo json_encode( array( 'result' => 'OK'));
		die();
	}

	//предварительная обработка Способ получения заказа
	private function receiving_method_c($receiving_method){
		$_SESSION['current_order']->receiving_method_c = $receiving_method;
		if($_SESSION['current_order']->receiving_method_c == '02'){ //самовывоз
			$_SESSION['current_order']->delivery_method_c = '02'; //ко времени
			if(empty(App::$current_landing->is_datetime_pickup_c)){//если на самовывоз отключены дата и время
				$_SESSION['current_order']->date_future_delivery_c = null;
				$_SESSION['current_order']->time_future_delivery_c = null;
			}
		}elseif($_SESSION['current_order']->receiving_method_c == '01'){
			$_SESSION['current_order']->delivery_method_c = '01'; //ближайшее время
		}
		$this->update_date_time_future_array();
		$this->getAllPrice();
		echo json_encode(
			array(
				'delivery_price' => $_SESSION['current_order']->delivery_price_c,
				'delivery_text' => $_SESSION['current_order']->CUSTOM_delivery_text,
				'products_price' => $_SESSION['current_order']->sale_price_c,
				'all_price' => $_SESSION['current_order']->all_price_c,
				'date_future_array' => $_SESSION['current_order']->CUSTOM_date_future_array,
				'time_future_array' => $_SESSION['current_order']->CUSTOM_time_future_array
			)
		);

		die();
	}

	//предварительная обработка даты доставки
	private function date_future_delivery_c($date_future_delivery_c){
		$_SESSION['current_order']->date_future_delivery_c = $date_future_delivery_c;
		$this->update_date_time_future_array();
		$_SESSION['current_order']->time_future_delivery_c = $_SESSION['current_order']->CUSTOM_time_future_array[0];

		echo json_encode(
			array(
				'date_future_array' => $_SESSION['current_order']->CUSTOM_date_future_array,
				'time_future_array' => $_SESSION['current_order']->CUSTOM_time_future_array
			)
		);

		die();
	}

	//предварительная обработка времени доставки
	private function time_future_delivery_c($time_future_delivery_c){
		$this->update_date_time_future_array();
		$_SESSION['current_order']->time_future_delivery_c = $time_future_delivery_c;
		//$_SESSION['current_order']->date_future_delivery_c = $time_future_delivery_c;

		echo json_encode(
			array(
				'date_future_array' => $_SESSION['current_order']->CUSTOM_date_future_array,
				'time_future_array' => $_SESSION['current_order']->CUSTOM_time_future_array
			)
		);

		die();
	}

	//предварительная обработка телефона
	public function phone_c($number, $format = '[1] [(3)] 3-2-2'){
		$plus = ($number[0] == '+'); // есть ли +
		$number = preg_replace('/\D/', '', $number); // убираем все знаки кроме цифр

		$len = array_sum(preg_split('/\D/', $format)); // получаем сумму чисел из $format
		$params = array_reverse(str_split($number)); // разбиваем $number на цифры и переворачиваем массив
		$params += array_fill(0, $len, 0); // забиваем пустаты предыдущего массива нулями

		$format = '%d%d-%d%d-%d%d%d ])%d%d%d([ ]%d['; //strrev(preg_replace('/(\d)/e', "str_repeat('d%', '\\1')", $format)); // делаем форматированную строку и переворачиваем её
		$format = call_user_func_array('sprintf', array_merge(array($format), $params)); // заполняем строку цирами
		$format = ($plus ? '+' : '').strrev($format); // возвращаем строку в нормальное положение и прилепляем + обратно, если он был

		$phone = strtr(trim($format), array('[' => '', ']' => '')); // вырезаем знаки необязательности
		if($phone[0].$phone[1] == '8 '){ // если 89379616623, то заменяем 8 на 7
			$phone[0] = '7';
		}
		if($phone[0].$phone[1].$phone[2].$phone[3] == '0 (9'){ //если просто 9379616623 , то заменяем 0 на 7
			$phone[0] = '7';
		}
		if($phone[0].$phone[1] == '7 '){ //когда первая 7ка, то добавляем +
			$phone = '+'.$phone;
		}
		if($phone[0] == '('){ // когда первая скобка, то добавляем +7
			$phone = '+7 '.$phone;
		}

		if($phone[0] != '+' || $phone[1] != '7' || ($phone[4] != '9' && $phone[4] != '3' && $phone[4] != '4' && $phone[4] != '8')){
			echo json_encode(
				[
					'result' => 'Номер телефона '.$phone.' имеет НЕ верный формат!',
					'phone_c' => ''
				]
			);
		}else {
			$_SESSION['current_order']->phone_c = $phone;
			echo json_encode(
				[
					'result' => 'OK',
					'phone_c' => $phone
				]
			);
		}
		die();
	}

	//обработка ajax - простановка данные в предварительный заказ
	public function set_order_field(){
		$field = isset($this->params[0]) ? $this->params[0] : null;//поле
		$value = isset($this->params[1]) ? $this->params[1] : null;;//значение

		if(!empty($field) && !empty($value) && method_exists($this, $field)){
			$_SESSION['current_order']->$field = $this->$field($value);
		} else {
			$_SESSION['current_order']->$field = $value;
		}
		echo json_encode( array( 'result' => 'OK'));
		die();
	}

	public function set_promo_code(){
		$promo_code = empty($this->params[0]) ? '' : strtoupper($this->params[0]);
		$type = empty($this->params[0]) ? 'main' : $this->params[1];
		$_SESSION['current_order']->CUSTOM_promo_type = $type;
		$phone = empty($this->params[1]) ? '' : strtoupper($this->params[2]);

		$this->checkPromoCode($promo_code, $type, $phone);

		$this->getAllPrice();

		echo json_encode(
			array(
				'promo_code' => $_SESSION['current_order']->CUSTOM_promo_code,
				'promo_work' => $_SESSION['current_order']->CUSTOM_promo_work,
				'promo_html' => $_SESSION['current_order']->CUSTOM_promo_html,
				'promo_price' => $_SESSION['current_order']->CUSTOM_promo_product_sale_price,
				'all_price' => $_SESSION['current_order']->all_price_c
			)
		);
	}
	
	public function use_bonuses(){
		global $db;
		$bonuseSystem = $db->fetchRow($db->query("
			SELECT 
				bb.id,
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
		
		if($bonuseSystem){

			$use_bonuses = empty($this->params[0]) ? '' : strtoupper($this->params[0]);
			
			$this->checkBonuses($use_bonuses);

			$this->getAllOrderInfo();
			
			echo json_encode(
				[
					'only_online_payments' => $_SESSION['current_order']->CUSTOM_only_online_payments,
					'delivery_price' => $_SESSION['current_order']->delivery_price_c,
					'delivery_text' => $_SESSION['current_order']->CUSTOM_delivery_text,
					'receiving_method' => $_SESSION['current_order']->receiving_method_c,
					'products_price' => $_SESSION['current_order']->sale_price_c,
					'all_price' => $_SESSION['current_order']->all_price_c,
					'promo_code' => $_SESSION['current_order']->CUSTOM_promo_code,
					'promo_html' => $_SESSION['current_order']->CUSTOM_promo_html,
					'promo_price' => $_SESSION['current_order']->CUSTOM_promo_product_sale_price,
					'promo_work' => $_SESSION['current_order']->CUSTOM_promo_work
				]
			);
		}
	}
	
	private function checkBonuses($use_bonuses){
		if($use_bonuses == 'TRUE'){
			global $db;
			$currentUserBonuse = $db->fetchRow($db->query(
				"SELECT *
				FROM clorg_client_organizations cco
				JOIN clorg_client_organizations_cstm cco_cstm ON cco_cstm.id_c = cco.id AND cco.deleted = 0 AND cco_cstm.orgns_organizations_id_c = '".App::$current_organization->id."'
				JOIN clnts_clients_clorg_client_organizations_1_c cc_cco ON cc_cco.clnts_cliea48bzations_idb = cco.id AND cc_cco.deleted = 0 AND cc_cco.clnts_clients_clorg_client_organizations_1clnts_clients_ida = '".App::$current_user->id."';
			"));
			
			$_SESSION['current_order']->client_money_discount_c = $currentUserBonuse['bonuses_c'];
		} else {
			$_SESSION['current_order']->client_money_discount_c = 0;
		}
	}

	public function set_phone_code(){
		$phone = $this->phone($this->params[0]);
		if(!$_SESSION['current_order']['phone_code']){
			$_SESSION['current_order']['phone_code'] = $this->generatePhoneCode();
		}
		if($phone && $_SESSION['current_order']['phone_code_count'] < 2){
			//$res = file_get_contents('https://smsc.ru/sys/send.php?login=yenon&psw=182918&phones='.$phone.'&charset=utf-8&mes='.urlencode("Проверочный код ".$_SESSION['current_order']['phone_code']));
			if(!stristr($res, 'error')){
				$_SESSION['current_order']['phone_code_count']++;
				echo json_encode( array( 'phone_code' => 'ОК'));
			} else {
				echo json_encode( array( 'phone_code' => 'BAD'));
			}
		}
		echo json_encode( array( 'phone_code' => 'BAD'));
	}

	public function check_phone_code(){
		$phone_code = $this->params[0];
		if($phone_code == $_SESSION['current_order']['phone_code']){
			$_SESSION['current_order']['check_phone_code'] = true;
			echo json_encode( array( 'check_phone_code' => 'OK'));
		} else {
			$_SESSION['current_order']['check_phone_code'] = false;
			echo json_encode( array( 'check_phone_code' => 'BAD'));
		}
	}

	public function checkPromoCode($promo_code, $type = NULL, $phone = NULL){
		$_SESSION['current_order']->CUSTOM_promo_code = $promo_code; //введенный промокод в инпут

		global $db;
		$promocode_info = $db->fetchRow($db->query("
			SELECT dd.*, dd_cstm.*
			FROM lngng_landings_dscnt_discount_1_c ll_dd
			JOIN dscnt_discount dd ON dd.id = ll_dd.lngng_landings_dscnt_discount_1dscnt_discount_idb AND ll_dd.deleted = 0 and dd.deleted = 0
			JOIN dscnt_discount_cstm dd_cstm ON dd_cstm.id_c = dd.id AND dd.deleted = 0
			WHERE
				ll_dd.lngng_landings_dscnt_discount_1lngng_landings_ida = '".App::$current_landing->id."'
				AND dd_cstm.is_active_c = 1
				AND dd_cstm.count_can_use_c > 0
				AND dd_cstm.active_date_c >= CURDATE()
				AND UPPER(dd_cstm.promo_code_c) = UPPER('".$promo_code."')
			LIMIT 1;
		"));
		
		//проверка на одно использование
		if($promocode_info['only_one_c'] && $phone && $_SESSION['current_order']->CUSTOM_promo_code) {
			$checkOnlyOne = $db->fetchRow($db->query("
				SELECT 
					DISTINCT oo_cstm.phone_c 
				FROM ordrs_orders_cstm oo_cstm
				JOIN ordrs_orders oo ON oo.id = oo_cstm.id_c AND oo.deleted = 0
				WHERE 
					oo_cstm.dscnt_discount_id_c = '".$promocode_info['id']."'
					AND oo_cstm.phone_c = '".NFfunctions::phone($phone)."'
				")
			);
			
			if(!empty($checkOnlyOne)) {
				$_SESSION['current_order']->CUSTOM_promo_code = NULL;
				$_SESSION['current_order']->CUSTOM_promo_work = 'no';
				$_SESSION['current_order']->CUSTOM_promo_html = 'Вы уже использовали данный промокод';
				$_SESSION['current_order']->CUSTOM_promo_product = NULL;
				$_SESSION['current_order']->CUSTOM_promo_product_sale_price = 0;
				$_SESSION['current_order']->CUSTOM_promo_order = NULL;
				$_SESSION['current_order']->CUSTOM_promo_delivery = NULL;
				$_SESSION['current_order']->CUSTOM_promo = NULL;
				return;
			}
		}

		//проверка где промокод применяют
		if(!empty($type) && !empty($promocode_info)){
			if($promocode_info['work_type_c'] == '02' && $type != 'main'){
				$_SESSION['current_order']->CUSTOM_promo_html = 'Данный промокод сработает только на сайте';
				$_SESSION['current_order']->CUSTOM_promo_work = 'no';
				$_SESSION['current_order']->CUSTOM_promo_product = NULL;
				$_SESSION['current_order']->CUSTOM_promo_product_sale_price = 0;
				$_SESSION['current_order']->CUSTOM_promo_order = NULL;
				$_SESSION['current_order']->CUSTOM_promo_delivery = NULL;
				$_SESSION['current_order']->CUSTOM_promo = NULL;
				return;
			} elseif($promocode_info['work_type_c'] == '03' && $type != 'webview'){
				$message = '<div class="col-12 text-center">
							Данный промокод сработает только в мобильном приложении!
							<p class="mt-2 mb-2 text-secondary">Скачайте приложение по ссылкам:</p>';
				if(App::$current_landing->google_play_c) {
					$message .= '<a href="'.App::$current_landing->google_play_c.'"><img alt="Доступно в Google Play" style="height:55px;" src="'.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/assets_new/images/googleplay.png"/></a>';
				}
				if(App::$current_landing->app_store_c) {
					$message .= '<a href="'.App::$current_landing->app_store_c.'"><img alt="Доступно в App Store" style="height:55px;" src="'.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/assets_new/images/appstore.png"/></a>';
				}
				if(App::$current_landing->app_gallery_c) {
					$message .= '<a href="'.App::$current_landing->app_gallery_c.'"><img alt="Доступно в App Gallery" style="height:55px;" src="'.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/assets_new/images/appgallery.png"/></a>';
				}
				$message .= '</div>';

				$_SESSION['current_order']->CUSTOM_promo_html = $message;
				$_SESSION['current_order']->CUSTOM_promo_work = 'no';
				$_SESSION['current_order']->CUSTOM_promo_product = NULL;
				$_SESSION['current_order']->CUSTOM_promo_product_sale_price = 0;
				$_SESSION['current_order']->CUSTOM_promo_order = NULL;
				$_SESSION['current_order']->CUSTOM_promo_delivery = NULL;
				$_SESSION['current_order']->CUSTOM_promo = NULL;
				return;
			} elseif($promocode_info['work_type_c'] == '04' && $type != 'telegram'){

				global $db;
				$telegram_bot_info = $db->fetchRow($db->query("
					SELECT * 
					FROM tgath_telegram_auth tta
					LEFT JOIN tgath_telegram_auth_cstm tta_cstm ON tta_cstm.id_c = tta.id AND tta.deleted = 0
					LEFT JOIN tgath_telegram_auth_lngng_landings_1_c tta_ll ON tta_ll.tgath_telegram_auth_lngng_landings_1tgath_telegram_auth_ida = tta.id AND tta_ll.deleted = 0
					WHERE 
						tta_ll.tgath_telegram_auth_lngng_landings_1lngng_landings_idb = '".App::$current_landing->id."';
				"));

				$message = '
					<div class="col-12 text-center">
						Данный промокод сработает только в telegram боте!
						<div style="margin-bottom:10px;margin-top:10px;">
						<a class="btn btn-default text-white btn-block shadow" href="https://t.me/'.$telegram_bot_info['bot_username_c'].'" target="_blank" style="max-width:100%;font-size:13pt;">Перейти в Telegram-бот</a>
					</div>
				</div>';
				$_SESSION['current_order']->CUSTOM_promo_html = $message;
				$_SESSION['current_order']->CUSTOM_promo_work = 'no';
				$_SESSION['current_order']->CUSTOM_promo_product = NULL;
				$_SESSION['current_order']->CUSTOM_promo_product_sale_price = 0;
				$_SESSION['current_order']->CUSTOM_promo_order = NULL;
				$_SESSION['current_order']->CUSTOM_promo_delivery = NULL;
				$_SESSION['current_order']->CUSTOM_promo = NULL;
				return;
			}
		}	
		
		if(!empty($promocode_info) && !empty($_SESSION['current_order']->CUSTOM_promo_code)) {
			$_SESSION['current_order']->CUSTOM_promo = $promocode_info['id'];
			if($_SESSION['current_order']->sale_price_c >= $promocode_info['order_price_c']) {
				$_SESSION['current_order']->CUSTOM_promo_work = 'yes';
				if($promocode_info['promo_type_c'] == '01'){
					$product = BeanFactory::getBean('prdct_products', $promocode_info['prdct_products_id_c']);
					$_SESSION['current_order']->CUSTOM_promo_product = $promocode_info['id'];
					$_SESSION['current_order']->CUSTOM_promo_product_sale_price = $promocode_info['product_sale_price_c'];
					$_SESSION['current_order']->CUSTOM_promo_html = '<span> + '.$product->name.'</span><div><img src="'.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.$product->img_img_images_prdct_products_1img_img_images_ida.'_image_c" style="width:100px;" /></div>';
				} else if($promocode_info['promo_type_c'] == '02'){
					$_SESSION['current_order']->CUSTOM_promo_delivery = $promocode_info['discount_dilivery_c'];
					$_SESSION['current_order']->CUSTOM_promo_html = '<div>'.$promocode_info['discount_dilivery_c'].' % скидка на доставку. <div style="font-size:8pt;">* Итоговую стоимость заказа озвучит диспетчер!</div></div>';
				} else if($promocode_info['promo_type_c'] == '03'){
					$_SESSION['current_order']->CUSTOM_promo_order = $promocode_info['discount_order_c'];
					$_SESSION['current_order']->CUSTOM_promo_html = '<div>'.$promocode_info['discount_order_c'].' % скидка на заказ.<div style="font-size:8pt;">* Итоговую стоимость заказа озвучит диспетчер!</div></div>';
				}
				return;
			} else {
				$_SESSION['current_order']->CUSTOM_promo_work = 'no';
				$_SESSION['current_order']->CUSTOM_promo_html = 'Чтобы промокод сработал, необходимо купить продуктов на сумму не менее '.$promocode_info['order_price_c'].'руб. ! (Не учитывая стоимость доставки)';
				$_SESSION['current_order']->CUSTOM_promo_product = NULL;
				$_SESSION['current_order']->CUSTOM_promo_product_sale_price = 0;
				$_SESSION['current_order']->CUSTOM_promo_order = NULL;
				$_SESSION['current_order']->CUSTOM_promo_delivery = NULL;
				$_SESSION['current_order']->CUSTOM_promo = NULL;
				return;
			}
		} else {
			$_SESSION['current_order']->CUSTOM_promo_code = NULL;
			$_SESSION['current_order']->CUSTOM_promo_work = NULL;
			$_SESSION['current_order']->CUSTOM_promo = NULL;
			$_SESSION['current_order']->CUSTOM_promo_html = NULL;
			$_SESSION['current_order']->CUSTOM_promo_product = NULL;
			$_SESSION['current_order']->CUSTOM_promo_product_sale_price = 0;
			$_SESSION['current_order']->CUSTOM_promo_order = NULL;
			$_SESSION['current_order']->CUSTOM_promo_delivery = NULL;
			return;
		}
	}

	public function getAllOrderInfo(){
		$this->getLeadPath();
		$this->getAllPrice();

		if(!empty($_SESSION['current_order']->CUSTOM_promo_code)){
			$promo_type = $_SESSION['current_order']->CUSTOM_promo_type ?? null;
			$this->checkPromoCode($_SESSION['current_order']->CUSTOM_promo_code, $promo_type);
		}

		$this->update_date_time_future_array();
		$this->only_online_payments();
		$this->only_future_delivery();
	}
	
	
	private function getLeadPath(){
		$lead_path = null;
	
		if(!empty($_GET['l'])){
			$lead_path = $_GET['l'];
		} elseif(!empty($_COOKIE['lead_path'])){
			$lead_path = $_COOKIE['lead_path'];
		} elseif(!empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'], 'vk.com')){
			$lead_path = 'vk';
		} elseif(!empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'], 'instagram.com')){
			$lead_path = 'inst';
		}
		
		if(!empty($lead_path)){
			setcookie('lead_path', $lead_path, strtotime('+7 days'), '/'); //записываем в куки
			$_SESSION['current_order']->lead_path_c = $lead_path;
		}
	}

	private function update_date_time_future_array(){
		$currentWeekDay = date('w');

		//расчет возможного времени
		$time_future_array = [];

		if($_SESSION['current_order']->date_future_delivery_c == date('d.m.Y')){
			if($currentWeekDay == 1 && App::$current_landing->time_work_mo_c){//то берем данные с понедельника
				$times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_mo_c');
			}elseif($currentWeekDay == 2 && App::$current_landing->time_work_tu_c){//то берем данные с вторника
				$times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_tu_c');
			}elseif($currentWeekDay == 3 && App::$current_landing->time_work_we_c){//то берем данные с среды
				$times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_we_c');
			}elseif($currentWeekDay == 4 && App::$current_landing->time_work_th_c){//то берем данные с четверга
				$times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_th_c');
			}elseif($currentWeekDay == 5 && App::$current_landing->time_work_fr_c){//то берем данные с пятницы
				$times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_fr_c');
			}elseif($currentWeekDay == 6 && App::$current_landing->time_work_sa_c){//то берем данные  с субботы
				$times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_sa_c');
			}elseif($currentWeekDay == 0 && App::$current_landing->time_work_su_c){//то берем данные с воскресенья
				$times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_su_c');
			}
			if(empty($times_work)){
				$times_work = ['00:00','00:15','00:30','00:45','01:00','01:15','01:30','01:45','02:00','02:15','02:30','02:45','03:00','03:15','03:30','03:45','04:00','04:15','04:30','04:45','05:00','05:15','05:30','05:45','06:00','06:15','06:30','06:45','07:00','07:15','07:30','07:45','08:00','08:15','08:30','08:45','09:00','09:15','09:30','09:45','10:00','10:15','10:30','10:45','11:00','11:15','11:30','11:45','12:00','12:15','12:30','12:45','13:00','13:15','13:30','13:45','14:00','14:15','14:30','14:45','15:00','15:15','15:30','15:45','16:00','16:15','16:30','16:45','17:00','17:15','17:30','17:45','18:00','18:15','18:30','18:45','19:00','19:15','19:30','19:45','20:00','20:15','20:30','20:45','21:00','21:15','21:30','21:45','22:00','22:15','22:30','22:45','23:00','23:15','23:30','23:45'];
			}

			//на какое время можно заказать доставку
			foreach($times_work as $time_temp){
				if($_SESSION['current_order']->receiving_method_c == '01'){ // если доставка
					if(strtotime($time_temp) > strtotime(date('H:i')."+".App::$current_landing->min_future_delivery_c." hour") ) {
						$time_future_array[] = $time_temp;
					}
				}elseif($_SESSION['current_order']->receiving_method_c == '02'){  // если самовывоз
					if(strtotime($time_temp) > strtotime(date('H:i')."+".App::$current_landing->min_future_pickup_c." hour") ) {
						$time_future_array[] = $time_temp;
					}
				}
			}

			//на какие даты можно заказать доставку
			if(empty($time_future_array)){
				$_SESSION['current_order']->date_future_delivery_c = date('d.m.Y',strtotime(date('d.m.Y') . "+1 days"));
				$_SESSION['current_order']->CUSTOM_date_future_array = [date('d.m.Y',strtotime(date('d.m.Y') . "+1 days"))];
			}else{
				$_SESSION['current_order']->CUSTOM_date_future_array = [
					date('d.m.Y'), date('d.m.Y',strtotime(date('d.m.Y') . "+1 days")),
					date('d.m.Y',strtotime(date('d.m.Y') . "+2 days")),
					date('d.m.Y',strtotime(date('d.m.Y') . "+3 days")),
					date('d.m.Y',strtotime(date('d.m.Y') . "+4 days"))
				];
			}
			//со скольки открывается доставка сегодня
			foreach($times_work as $time_temp){
				if(strtotime($time_temp) > strtotime(date('H:i')) ) {
					$_SESSION['current_order']->CUSTOM_time_start_work_today = $time_temp;
					$_SESSION['current_order']->CUSTOM_date_work_today = date('d.m.Y');
					break;
				}
			}

			//во сколько закрывается доставка сегодня
			if(!empty($_SESSION['current_order']->CUSTOM_time_stop_work_today)){
				$_SESSION['current_order']->CUSTOM_time_stop_work_today = $times_work[count($times_work)-1];
			}
		}

		if(
			$_SESSION['current_order']->date_future_delivery_c == date('d.m.Y',strtotime(date('d.m.Y') . "+1 days")) ||
			$_SESSION['current_order']->date_future_delivery_c == date('d.m.Y',strtotime(date('d.m.Y') . "+2 days")) ||
			$_SESSION['current_order']->date_future_delivery_c == date('d.m.Y',strtotime(date('d.m.Y') . "+3 days")) ||
			$_SESSION['current_order']->date_future_delivery_c == date('d.m.Y',strtotime(date('d.m.Y') . "+4 days"))
		){
			if($currentWeekDay == 1 && App::$current_landing->time_work_tu_c){//то берем данные с вторника
				$time_future_array = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_tu_c');
			}elseif($currentWeekDay == 2 && App::$current_landing->time_work_we_c){//то берем данные с среды
				$time_future_array = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_we_c');
			}elseif($currentWeekDay == 3 && App::$current_landing->time_work_th_c){//то берем данные с четверга
				$time_future_array = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_th_c');
			}elseif($currentWeekDay == 4 && App::$current_landing->time_work_fr_c){//то берем данные  с пятницы
				$time_future_array = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_fr_c');
			}elseif($currentWeekDay == 5 && App::$current_landing->time_work_sa_c){//то берем данные  с субботы
				$time_future_array = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_sa_c');
			}elseif($currentWeekDay == 6 && App::$current_landing->time_work_su_c){//то берем данные с воскресенья
				$time_future_array = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_su_c');
			}elseif($currentWeekDay == 0 && App::$current_landing->time_work_mo_c){//то берем данные с понедельника
				$time_future_array = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_mo_c');
			}
		}
		//на какое время можно заказать доставку
		$time_future_array = array_values($time_future_array);
		$_SESSION['current_order']->CUSTOM_time_future_array = $time_future_array;

		//со скольки открывается доставка сегодня и во сколько закрывается сегодня
		if(empty($_SESSION['current_order']->CUSTOM_time_start_work_today)){
			$_SESSION['current_order']->CUSTOM_time_start_work_today = $time_future_array[0];
			$_SESSION['current_order']->CUSTOM_time_stop_work_today = $time_future_array[count($time_future_array)-1];
			$_SESSION['current_order']->CUSTOM_date_work_today = date('d.m.Y',strtotime(date('d.m.Y') . "+1 days"));
		}

		//если нет приема предварительных заказов
		if(!App::$current_landing->accept_future_delivery_c){
			$_SESSION['current_order']->CUSTOM_time_start_work_today = $times_work[0] ?? '';
			$_SESSION['current_order']->CUSTOM_time_stop_work_today = $time_future_array[count($time_future_array)-1];
			$_SESSION['current_order']->CUSTOM_date_work_today = date('d.m.Y',strtotime(date('d.m.Y')));
		}
	}

	private function only_online_payments(){
		if( !empty(App::$current_landing->only_online_payments_c) && $_SESSION['current_order']->sale_price_c > App::$current_landing->only_online_payments_c) {
			$_SESSION['current_order']->CUSTOM_only_online_payments = 'yes';
			$_SESSION['current_order']->pay_method_c = '03';
		} else {
			$_SESSION['current_order']->CUSTOM_only_online_payments = 'no';
		}
	}

	private function only_future_delivery() {
		//проверка что рабочее время
		$isWorkTime = false;

		$currentWeekDay = date('w');
		$all_times_work = array();
		if($currentWeekDay == 1 && App::$current_landing->time_work_mo_c){//то берем данные с понедельника
			$all_times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_mo_c');
		}elseif($currentWeekDay == 2 && App::$current_landing->time_work_tu_c){//то берем данные с вторника
			$all_times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_tu_c');
		}elseif($currentWeekDay == 3 && App::$current_landing->time_work_we_c){//то берем данные с среды
			$all_times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_we_c');
		}elseif($currentWeekDay == 4 && App::$current_landing->time_work_th_c){//то берем данные с четверга
			$all_times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_th_c');
		}elseif($currentWeekDay == 5 && App::$current_landing->time_work_fr_c){//то берем данные  с пятницы
			$all_times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_fr_c');
		}elseif($currentWeekDay == 6 && App::$current_landing->time_work_sa_c){//то берем данные  с субботы
			$all_times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_sa_c');
		}elseif($currentWeekDay == 0 && App::$current_landing->time_work_su_c){//то берем данные с воскресенья
			$all_times_work = NFfunctions::getMultiSelect(App::$current_landing, 'time_work_su_c');
		}
		$all_times_work = array_values($all_times_work);
		$time_current = (int)str_replace(':', '', date('H:i'));//час-минуты удаляем двоеточие и преобразуем в число
		if(empty($all_times_work)){
			$all_times_work = ['00:00','00:15','00:30','00:45','01:00','01:15','01:30','01:45','02:00','02:15','02:30','02:45','03:00','03:15','03:30','03:45','04:00','04:15','04:30','04:45','05:00','05:15','05:30','05:45','06:00','06:15','06:30','06:45','07:00','07:15','07:30','07:45','08:00','08:15','08:30','08:45','09:00','09:15','09:30','09:45','10:00','10:15','10:30','10:45','11:00','11:15','11:30','11:45','12:00','12:15','12:30','12:45','13:00','13:15','13:30','13:45','14:00','14:15','14:30','14:45','15:00','15:15','15:30','15:45','16:00','16:15','16:30','16:45','17:00','17:15','17:30','17:45','18:00','18:15','18:30','18:45','19:00','19:15','19:30','19:45','20:00','20:15','20:30','20:45','21:00','21:15','21:30','21:45','22:00','22:15','22:30','22:45','23:00','23:15','23:30','23:45'];
		}
		foreach($all_times_work as $time_temp){
			$time_temp = (int)str_replace(':', '', $time_temp);//удаляем двоеточие и преобразуем в число
			$time_diff = $time_temp - $time_current;
			if($time_diff <= 0 && $time_diff >= -14){
				$isWorkTime = true; break;
			}
		}


		if(App::$current_landing->accept_future_delivery_c == 1 && !$isWorkTime){
			$_SESSION['current_order']->CUSTOM_only_future_delivery = 'yes';
			$_SESSION['current_order']->delivery_method_c = '02';
			if($_SESSION['current_order']->date_future_delivery_c == null && array_key_exists(0, $_SESSION['current_order']->CUSTOM_date_future_array)){
				$_SESSION['current_order']->date_future_delivery_c = $_SESSION['current_order']->CUSTOM_date_future_array[0];
			}
			if($_SESSION['current_order']->time_future_delivery_c == null && array_key_exists(0, $_SESSION['current_order']->CUSTOM_time_future_array)){
				$_SESSION['current_order']->time_future_delivery_c = $_SESSION['current_order']->CUSTOM_time_future_array[0];
			}
		} else {
			$_SESSION['current_order']->CUSTOM_only_future_delivery= 'no';
		}
	}

	//use in add_product, remove_product
	public function getProductInfo($product_id){
		$product_count = 0;
		$product = BeanFactory::getBean('prdct_products', $product_id);
		if( !empty($_SESSION['current_order']->CUSTOM_products) ) {
			foreach($_SESSION['current_order']->CUSTOM_products as $product_temp){
				if($product_id == $product_temp->id){
					$product_count++;
				}
			}

			return array(
				'product_price' => $product->sale_price_c*$product_count,
				'product_count' => $product_count
			);
		} else {
			return array(
				'product_price' => 0,
				'product_count' => 0
			);
		}
	}

	//use in getDeliveryPrice
	public function getAllProductsPrice(){
		$products_all_price = 0;
		if( !empty($_SESSION['current_order']->CUSTOM_products) ) {
			foreach($_SESSION['current_order']->CUSTOM_products as $product){
				$products_all_price += $product->sale_price_c;
			}
		}
		$_SESSION['current_order']->sale_price_c = $products_all_price;
	}

	//use in getAllPrice
	public function getDeliveryPrice(){
		$this->getAllProductsPrice();
		if(
			(!empty(App::$current_landing->delivery_free_c) && $_SESSION['current_order']->sale_price_c >= App::$current_landing->delivery_free_c)
			|| ($_SESSION['current_order']->receiving_method_c == '02')
		){
			$_SESSION['current_order']->delivery_price_c = 0;
		} elseif($_SESSION['current_order']->CUSTOM_area != NULL){
			$_SESSION['current_order']->delivery_price_c = $_SESSION['current_order']->CUSTOM_area->delivery_price_c;
		} elseif(App::$current_landing->delivery_price_c !== NULL && empty(App::$current_landing->request_lead_area_c)){
			$_SESSION['current_order']->delivery_price_c = App::$current_landing->delivery_price_c;
		} else {
			$_SESSION['current_order']->delivery_price_c = NULL;
		}
	}

	public function getAllPrice(){
		$this->getDeliveryPrice();

		if($_SESSION['current_order']->receiving_method_c == '02'){
			$_SESSION['current_order']->all_price_c = $_SESSION['current_order']->sale_price_c;
			$_SESSION['current_order']->CUSTOM_delivery_text = 'на самовывоз';
		}elseif($_SESSION['current_order']->delivery_price_c === NULL){
			$_SESSION['current_order']->all_price_c = $_SESSION['current_order']->sale_price_c;
			$_SESSION['current_order']->CUSTOM_delivery_text = '<span style="color:red;">без учёта</span> доставки';
		}else {
			$_SESSION['current_order']->all_price_c =  $_SESSION['current_order']->sale_price_c+$_SESSION['current_order']->delivery_price_c;
			$_SESSION['current_order']->CUSTOM_delivery_text = '<span style="color:green;">с учётом</span> доставки';
		}

		if(!empty($_SESSION['current_order']->CUSTOM_promo_product_sale_price)){
			$_SESSION['current_order']->all_price_c += $_SESSION['current_order']->CUSTOM_promo_product_sale_price;
		}
		
		//считаем бонусы скидки
		if(isset($_SESSION['current_order']->client_money_discount_c)){
			$_SESSION['current_order']->all_price_c -= $_SESSION['current_order']->client_money_discount_c;
		}
	}

	public function print_new_order()
	{
		echo '<pre>';
		print_rr($_SESSION['current_order']);
		echo '</pre>';
	}

	public function print_old_order()
	{
		echo '<pre>';
		print_rr($_SESSION['old_order']);
		echo '</pre>';
	}

	public function clean()
	{
		unset($_SESSION['current_order']['phone_code']);
		unset($_SESSION['current_order']['check_phone_code']);
	}
}
