<?php

class ApiController extends Controller
{
	private $basketController;
	
	public function __construct($params  = array())
	{
		parent::__construct($params);
		
		$this->model = new Main();
		$this->basketController = new BasketController();
	}
	
	public function download_product_image()
	{
		global $db;
		$queryImages = $db->query("
			SELECT img_cstm.id_c
			FROM img_img_images_cstm img_cstm
			INNER JOIN orgns_organizations_img_img_images_1_c org_img ON org_img.orgns_organizations_img_img_images_1img_img_images_idb = img_cstm.id_c 
			WHERE org_img.orgns_organizations_img_img_images_1orgns_organizations_ida = '".App::$current_organization->id."'
		");
		$imagesHtml  = '<!DOCTYPE html><html><head><meta charset="utf-8"> <title>download</title></head><body>';
		while($image = $db->fetchByAssoc($queryImages)) {
			$imagesHtml .= '<p><a href="'.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.$image['id_c'].'_image_c">Скачать файл</a>';
		}
		$imagesHtml  .= '</body></html>';
		echo $imagesHtml;
	}
	
	
	public function get_objects()
	{
		$start = microtime(true);
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && !empty($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
			if(!isset($_SESSION['current_order'])) {
				$this->basketController = new BasketController();
			}
			
			if(!empty($_SESSION['client_id'])){
				App::$current_user = BeanFactory::getBean('clnts_clients', $_SESSION['client_id']);
			}
		}

		global $db;
		$result = [];
		
		if(isset($_SESSION['current_order'])) {
			$this->model->getOrderProducts($_SESSION['current_order']);
		}
		$data = $this->model->getData();
		
		//главная информация
		$main['session_id'] = session_id();
		$main['user_id'] = !empty($_SESSION['client_id']) ? $_SESSION['client_id'] : '';
		$main['site_id'] = App::$current_landing->id;
		$main['host'] = $_SERVER['HTTP_HOST'];
		$main['status'] = App::$current_landing->status_c;
		$main['name'] = App::$current_organization->name;
		$main['name_rus'] = App::$current_organization->name_rus_c;
		$main['city'] = App::$current_city->name;
		$main['description'] = App::$current_landing->delivery_description_c;
		$main['information_block'] = App::$current_landing->information_block_c;
		$main['image'] = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.App::$current_organization->id.'_image_organization_c';
		$main['background_color'] = App::$current_organization->main_color_c;
		$result['main'] = $main;
		
		$contact['phone1'] = App::$current_landing->phone1_c;
		$contact['phone2'] = App::$current_landing->phone2_c;
		$contact['contact_whatsapp'] = App::$current_landing->contact_whatsapp_c;
		$contact['contact_viber'] = App::$current_landing->contact_viber_c;
		$contact['vk_social'] = App::$current_landing->vk_social_c;
		$contact['insta_social'] = App::$current_landing->insta_social_c;
		$contact['tiktok_social'] = App::$current_landing->tiktok_social_c;
		$contact['youtube_social'] = App::$current_landing->youtube_social_c;
		$contact['ok_social'] = App::$current_landing->ok_social_c;
		$contact['email'] = App::$current_landing->email_c;
		$contact['address'] = str_replace(PHP_EOL, ' ', strip_tags(html_entity_decode(App::$current_landing->address_c)));
		
		$result['contact'] = $contact;
	
		
		//СЛАЙДЕР
		$querySliders = $db->query("
			SELECT ss.id, ss_cstm.link_c, ss_cstm.show_order_c, ss_cstm.image_c, ss_cstm.mobile_image_c
			FROM sld_slide ss
			LEFT JOIN sld_slide_cstm ss_cstm ON ss_cstm.id_c = ss.id AND ss.deleted = 0
			LEFT JOIN lngng_landings_sld_slide_1_c ll_ss ON ll_ss.lngng_landings_sld_slide_1sld_slide_idb = ss_cstm.id_c AND ll_ss.deleted = 0
			WHERE ll_ss.lngng_landings_sld_slide_1lngng_landings_ida = '".App::$current_landing->id."'
			ORDER BY ss_cstm.show_order_c;
		");
		$sliders = [];
		while($slider = $db->fetchByAssoc($querySliders)) {
			$sliders[] = array(
				'id' => $slider['id'],
				'link' =>  $slider['link_c'],
				'image' => $slider['image_c'] ? NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.$slider['id'].'_image_c' : '',
				'mobile_image' =>  $slider['mobile_image_c'] ? NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.$slider['id'].'_mobile_image_c' : '',
				'show_order' => $slider['show_order_c'],
			);
		}
		$result['sliders'] = $sliders;
		
		//ГОРОДА
		$queryCities = $db->query("
			SELECT
				cc.name,
				ll_cstm.id_c, 
				ll_cstm.link_c
			FROM orgns_organizations_lngng_landings_1_c oo_ll 
				JOIN lngng_landings ll ON ll.id = oo_ll.orgns_organizations_lngng_landings_1lngng_landings_idb AND oo_ll.deleted = 0 AND ll.deleted = 0
				JOIN lngng_landings_cstm ll_cstm ON ll_cstm.id_c = ll.id AND ll.deleted = 0
				JOIN city_cities_lngng_landings_1_c cc_ll ON cc_ll.city_cities_lngng_landings_1lngng_landings_idb = ll_cstm.id_c AND cc_ll.deleted = 0
				JOIN city_cities cc ON cc.id = cc_ll.city_cities_lngng_landings_1city_cities_ida AND cc.deleted = 0
			WHERE oo_ll.orgns_organizations_lngng_landings_1orgns_organizations_ida = '".App::$current_organization->id."'
				ORDER BY cc.name;
		");
			
		$cities = [];
		while($city = $db->fetchByAssoc($queryCities)) {
			$cities[] = array(
				'site_id' => $city['id_c'],
				'name' =>  $city['name'],
				'api_link' =>  $city['link_c'],
			);
		}
		$result['cities'] = $cities;
		
		//АКЦИИ
		$queryDiscounts = $db->query("
			SELECT 
				nn.id, 
				nn.name, 
				nn_cstm.text_c, 
				nn_cstm.color_background_c,
				nn_cstm.color_text_c,
				nn_cstm.image_fon_c,
				nn_cstm.image_c,
				nn_cstm.publish_date_c
			FROM news_news nn
			LEFT JOIN news_news_cstm nn_cstm ON nn_cstm.id_c = nn.id AND nn.deleted = 0
			LEFT JOIN lngng_landings_news_news_1_c ll_nn ON ll_nn.lngng_landings_news_news_1news_news_idb = nn_cstm.id_c AND ll_nn.deleted = 0
			WHERE 
				ll_nn.lngng_landings_news_news_1lngng_landings_ida = '".App::$current_landing->id."'
				AND nn_cstm.type_c LIKE '%^02^%'
			ORDER BY nn_cstm.publish_date_c DESC
			LIMIT 5;
		");
		$discounts = [];
		while($discount = $db->fetchByAssoc($queryDiscounts)) {
			$discounts[] = array(
				'id' => $discount['id'],
				'name' =>  $discount['name'],
				'text' =>  $discount['text_c'],
				'color_background' =>  $discount['color_background_c'],
				'color_text' =>  $discount['color_text_c'],
				'image_fon' =>  $discount['image_fon_c'] ? NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.$discount['id'].'_image_fon_c' : '',
				'image' =>  $discount['image_c'] ? NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.$discount['id'].'_image_c' : '',
				'publish_date' => $discount['publish_date_c'],
			);
		}
		$result['discounts'] = $discounts;
		
		//ОТЗЫВЫ
		$queryReviews = $db->query("
			SELECT 
				rr.*,
				rr_cstm.*
			FROM rvw_review rr
			LEFT JOIN rvw_review_cstm rr_cstm ON rr_cstm.id_c = rr.id AND rr.deleted = 0
			LEFT JOIN lngng_landings_rvw_review_1_c ll_rr ON ll_rr.lngng_landings_rvw_review_1rvw_review_idb = rr_cstm.id_c AND ll_rr.deleted = 0
			WHERE ll_rr.lngng_landings_rvw_review_1lngng_landings_ida = '".App::$current_landing->id."'
			ORDER BY rr_cstm.sorting_c ASC
			LIMIT 20;
		");

		$reviews = [];
		while($review = $db->fetchByAssoc($queryReviews)) {
			$reviews[] = array(
				'id' => $review['id'],
				'name' => $review['name'],
				'icon' => NFfunctions::getSiteProtocol().CRM_URL.'/upload/'.$review['id'].'_icon_c',
				'video_link' => $review['video_link_c'],
				'sorting' => $review['sorting_c'],
			);
		}
		$result['reviews'] = $reviews;
		
		//НОВОСТИ
		$queryNews = $db->query("
			SELECT 
				nn.id, 
				nn.name, 
				nn_cstm.text_c, 
				nn_cstm.color_background_c,
				nn_cstm.color_text_c,
				nn_cstm.image_fon_c,
				nn_cstm.image_c,
				nn_cstm.publish_date_c,
				nn_cstm.type_c
			FROM news_news nn
			LEFT JOIN news_news_cstm nn_cstm ON nn_cstm.id_c = nn.id AND nn.deleted = 0
			LEFT JOIN lngng_landings_news_news_1_c ll_nn ON ll_nn.lngng_landings_news_news_1news_news_idb = nn_cstm.id_c AND ll_nn.deleted = 0
			WHERE 
				ll_nn.lngng_landings_news_news_1lngng_landings_ida = '".App::$current_landing->id."'
			ORDER BY nn_cstm.publish_date_c DESC;
		");
		$news = [];
		while($new = $db->fetchByAssoc($queryNews)) {
			if(strpos($new['type_c'], '02')) {
				$type = '02';
			} elseif(strpos($new['type_c'], '01')) {
				$type = '01';
			}
			$news[] = array(
				'id' => $new['id'],
				'name' => $new['name'],
				'text' => $new['text_c'],
				'color_background' => $new['color_background_c'],
				'color_text' => $new['color_text_c'],
				'image_fon' => $new['image_fon_c'] ? NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.$new['id'].'_image_fon_c' : '',
				'image' => $new['image_c'] ? NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.$new['id'].'_image_c' : '',
				'publish_date' => $new['publish_date_c'],
				'type' => $type
			);
		}
		$result['news'] = $news;
		
		//Вопрос/ответы
		$queryFaq = $db->query("
			SELECT 
				ff.*,
				ff_cstm.*
			FROM faq_faq ff
			LEFT JOIN faq_faq_cstm ff_cstm ON ff_cstm.id_c = ff.id AND ff.deleted = 0
			LEFT JOIN lngng_landings_faq_faq_1_c ll_ff ON ll_ff.lngng_landings_faq_faq_1faq_faq_idb = ff_cstm.id_c AND ll_ff.deleted = 0
			WHERE 
				ll_ff.lngng_landings_faq_faq_1lngng_landings_ida = '".App::$current_landing->id."'
			ORDER BY ff_cstm.sorting_c ASC;
		");
		$faqs = [];
		while($faq = $db->fetchByAssoc($queryFaq)) {
			$faqs[] = array(
				'id' => $faq['id'],
				'question' => $faq['name'],
				'answer' => $faq['answer_c'],
				'sorting' => $faq['sorting_c']
			);
		}
		$result['faqs'] = $faqs;
		
		//Категории продуктов и продукты
		$queryProductGroups = $db->query("
			SELECT distinct ppg.id, ppg.name,ppg_cstm.show_order_c 
			FROM pdgrp_product_groups ppg 
			JOIN pdgrp_product_groups_cstm ppg_cstm ON ppg_cstm.id_c = ppg.id AND ppg.deleted = 0
			JOIN lngng_landings_pdgrp_product_groups_1_c ll_ppg ON ll_ppg.lngng_landings_pdgrp_product_groups_1pdgrp_product_groups_idb = ppg_cstm.id_c
			AND ll_ppg.lngng_landings_pdgrp_product_groups_1lngng_landings_ida = '".App::$current_landing->id."'
			JOIN pdgrp_product_groups_prdct_products_1_c ppg_pp ON ppg_pp.pdgrp_product_groups_prdct_products_1pdgrp_product_groups_ida = ppg_cstm.id_c
			JOIN prdct_products pp ON pp.id = ppg_pp.pdgrp_product_groups_prdct_products_1prdct_products_idb AND pp.deleted = 0
			JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp_cstm.active_c = 1
			ORDER BY ppg_cstm.show_order_c;
		");
		$productGroups = [];
		while($productGroup = $db->fetchByAssoc($queryProductGroups)) {
				$queryProducts = $db->query("
				SELECT pp.id,pp.name, pp.description, pp_cstm.sale_price_c, pp_cstm.red_sale_price_c,pp_cstm.show_order_c, pp_cstm.tag_c, pp_cstm.tag_color_c, pp_cstm.tag_text_color_c
				FROM prdct_products pp 
				JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp.deleted = 0 AND pp_cstm.active_c = 1
				JOIN pdgrp_product_groups_prdct_products_1_c pg_pp ON pg_pp.pdgrp_product_groups_prdct_products_1prdct_products_idb = pp_cstm.id_c 
				AND pg_pp.pdgrp_product_groups_prdct_products_1pdgrp_product_groups_ida = '".$productGroup['id']."'
				ORDER BY pp_cstm.show_order_c;
			");
			
			if($main['name_rus'] == 'Сочная Курочка'){
				
			}	
			
				
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
				if($main['name_rus'] == 'Сочная Курочка'){
					
				}
				
				if(!$product['tag_text_color_c']){
					$product['tag_text_color_c'] = 'rgb(0, 0, 0)';
				}
				
				$products[] = $product;
			}
			$productGroup['products'] = $products;
			$productGroups[] = $productGroup;
		}
		$result['productGroups'] = $productGroups;
		
		//Левое меню
		$leftMenu = [];
		if(!empty($_SESSION['old_order'])) {
			//$leftMenu[''] = ['Мой последний заказ', NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/main/order_offline_pay'];
		}

		if(App::$current_landing->yandex_area_c != NULL) {
			$leftMenu['area_map'] = ['Карта доставки', NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/main/area_map'];
		}
		$leftMenu['contact'] = ['Контакты', NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/main/contact'];
		$leftMenu['news'] = ['Новости', NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/main/news'];
		/*if(App::$current_landing->reviews_yandex_c || App::$current_landing->reviews_c){
			$leftMenu['review'] = ['Ваши отзывы', NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/main/area_map'];
		}*/
		$leftMenu['faq'] = ['Вопросы/ответы', NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/main/faq'];
		$leftMenu['job'] = ['Вакансии', NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/main/job'];
		
		$result['leftMenu'] = $leftMenu;
	
	
		//профиль
		$user = [];
		if(App::$current_landing->active_profile_c) {
			if(empty(App::$current_user)) {
				$user = 'login';
			} else {
				$user = 'profile';
			}
		} else{
			$user = '';
		}
		$result['user'] = $user;


		//Корзина
		$cart = [];
		$countProducts = '0';
		$productPrice = $_SESSION['current_order']->sale_price_c;
		if(isset($_SESSION['current_order']->CUSTOM_products)) { 
			$countProducts = count($_SESSION['current_order']->CUSTOM_products); 
		} 
		/*if( (App::$current_landing->accept_future_delivery_c != 1) && (date('H:i') < $_SESSION['current_order']->CUSTOM_time_start_work_today || date('H:i') > $_SESSION['current_order']->CUSTOM_time_stop_work_today) ) {
			swal("Внимание", "К сожалению, мы уже не работаем. \nОткроемся <?=$_SESSION['current_order']->CUSTOM_date_work_today?> в <?=$_SESSION['current_order']->CUSTOM_time_start_work_today?>! Заходите!", "warning");
		} else 
		*/
		$cartError = (object)[];
		if($productPrice <= 0) {
			$cartError = array(
				'title' => 'Внимание', 
				'message' => 'Вы ничего не заказали!'
			);
		} else if($productPrice < App::$current_landing->delivery_min_order_c) {
			$cartError = array(
				'title' => 'Внимание', 
				'message' => 'Заказ должен быть на сумму не менее '.App::$current_landing->delivery_min_order_c.' руб. !'
			);
		}
		$cart = [
			'sale_price' => $productPrice,
			'count_products' => $countProducts,
			'error' => $cartError,
		];
		$result['cart'] = $cart;
		
		$result['timeout'] = microtime(true) - $start;
		
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
		//print_rr($_SESSION);
		//print_rre($result);
		
		
		
	}
	
	public function add_product()
	{
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && !empty($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}
		
		$product_id = $this->params[0];
		
		
		$product = BeanFactory::getBean('prdct_products', $product_id);
		array_push($_SESSION['current_order']->CUSTOM_products, $product);
		
		$product_info = $this->basketController->getProductInfo($product_id);
		$this->basketController->getAllOrderInfo();
		
		$result = $product_info;

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function remove_product()
	{
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && !empty($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}
		
		$product_id = $this->params[0];
		
		
		foreach($_SESSION['current_order']->CUSTOM_products as $key => $product){
			if($product_id == $product->id){
				unset($_SESSION['current_order']->CUSTOM_products[$key]);
				break;
			}
		}
		
		$product_info = $this->basketController->getProductInfo($product_id);
		$this->basketController->getAllOrderInfo();
		
		$result = $product_info;

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function auth()
	{
		//восстановление сессии
		if(isset($_REQUEST['session_id']) && !empty($_REQUEST['session_id']) && session_id() != $_REQUEST['session_id']){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_REQUEST['session_id'];
			session_id($_REQUEST['session_id']);
			session_start();
		}
	
		$type = $_REQUEST['type'];
		$token = $_REQUEST['token'];
		$user_id = $_REQUEST['user_id'];
		
		setcookie('client_id', $user_id, time()+2592000, '/');
		$_SESSION['client_id'] = $user_id;
		
		echo json_encode('ok', JSON_UNESCAPED_UNICODE);die();
	}	
}
