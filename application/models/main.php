<?php
class Main extends Model
{
	function __construct() {
		parent::__construct();
	}

	public function getTitle($title = NULL){
		if($title == NULL){
			$this->data['title'] = App::$current_landing->seo_title_c;
		} else {
			$this->data['title'] = $title;
		}
	}
	
	public function getDescription($description = NULL){
		if($description == NULL){
			$this->data['description'] = App::$current_landing->seo_description_c;
		} else {
			$this->data['description'] = $description;
		}
	}
	
	public function getCustomSEO($page = NULL){
		global $db;
		$seo = $db->fetchRow($db->query("
			SELECT 
				ss_cstm.seo_title_c,
				ss_cstm.seo_description_c
			FROM seo_seo ss 
			JOIN seo_seo_cstm ss_cstm ON ss_cstm.id_c = ss.id AND ss.deleted = 0
			JOIN lngng_landings_seo_seo_1_c ll_ss ON ll_ss.lngng_landings_seo_seo_1seo_seo_idb = ss.id AND ll_ss.deleted = 0 
			WHERE ll_ss.lngng_landings_seo_seo_1lngng_landings_ida = '".App::$current_landing->id."'
			AND ss.name LIKE '%".$page."%'
			LIMIT 1
		"));
		
		if(!empty($seo)){
			$this->data['title'] = $seo['seo_title_c'];
			$this->data['description'] = $seo['seo_description_c'];
		}
	}
	
	public function getCurrentProductGroups($order_field = null){
		global $db;
		$queryProductGroups = $db->query("
			SELECT distinct ppg.*, ppg_cstm.*, image.id as product_group_image
			FROM pdgrp_product_groups ppg 
			JOIN pdgrp_product_groups_cstm ppg_cstm ON ppg_cstm.id_c = ppg.id AND ppg.deleted = 0
			JOIN lngng_landings_pdgrp_product_groups_1_c ll_ppg ON ll_ppg.lngng_landings_pdgrp_product_groups_1pdgrp_product_groups_idb = ppg_cstm.id_c
			AND ll_ppg.lngng_landings_pdgrp_product_groups_1lngng_landings_ida = '".App::$current_landing->id."'
			JOIN pdgrp_product_groups_prdct_products_1_c ppg_pp ON ppg_pp.pdgrp_product_groups_prdct_products_1pdgrp_product_groups_ida = ppg_cstm.id_c
			LEFT JOIN prdct_products pp ON pp.id = ppg_pp.pdgrp_product_groups_prdct_products_1prdct_products_idb AND pp.deleted = 0
			LEFT JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id
            LEFT JOIN img_img_images_pdgrp_product_groups_1_c img_group ON img_group.img_img_images_pdgrp_product_groups_1pdgrp_product_groups_idb = ppg.id AND img_group.deleted = 0
            LEFT JOIN img_img_images image ON image.id = img_group.img_img_images_pdgrp_product_groups_1img_img_images_ida AND image.deleted = 0
            LEFT JOIN pdgrp_product_groups_cmprd_complex_products_1_c pg_ccp ON pg_ccp.pdgrp_prodb0d7_groups_ida = ppg_cstm.id_c AND pg_ccp.deleted = 0
            LEFT JOIN cmprd_complex_products ccp ON ccp.id = pg_ccp.pdgrp_prodd6b7roducts_idb AND ccp.deleted = 0
            LEFT JOIN cmprd_complex_products_cstm ccp_cstm ON ccp_cstm.id_c = ccp.id
            WHERE ccp_cstm.active_c = 1 OR pp_cstm.active_c
			ORDER BY ppg_cstm.show_order_c
		");
		while($productGroup = $db->fetchByAssoc($queryProductGroups)) {
			$this->data['current_product_groups'][] = $productGroup;
		}
	}
	
	public function getCurrentBranchs(){
		$this->data['current_branchs'] = NFfunctions::getChildBeans(App::$current_landing, 'brnch_branch');
	}
	
	public function getCurrentAreas($order_field = null){
		$this->data['current_areas'] = NFfunctions::getChildBeans(App::$current_landing, 'area_area');
	}
	
	public function getLandings(){
		$this->data['landings'] = BeanFactory::getBean('lngng_landings')->get_full_list();
	}
	
	public function getCurrentStreets(){
		$this->data['current_streets'] = [];
		
		global $db; 
		$query = "
			SELECT ss.id , ss.name
			FROM strt_street ss
			JOIN city_cities_strt_street_1_c city_rel ON city_rel.city_cities_strt_street_1strt_street_idb = ss.id AND city_rel.deleted = 0
			WHERE city_rel.city_cities_strt_street_1city_cities_ida = '".App::$current_city->id."' AND ss.deleted = 0";

        $result = $db->query($query);
        while ($row = $db->fetchByAssoc($result)){
			$this->data['current_streets'][] = $row['name'];
		}
	}
	
	public function getOrderProducts($session_order){
		$sale_all = 0;
		$products_unique = [];
		$order_products = [];

		if(isset($_SESSION['current_order']->CUSTOM_products)){
			$products_unique = $this->products_unique($_SESSION['current_order']->CUSTOM_products);//уникальные продукты
		}

		foreach($products_unique as $product_unique){
			$count = 0;
			$product_unique->CUSTOM_options = [];
			foreach($_SESSION['current_order']->CUSTOM_products as $product){
				if($product_unique->id == $product->id){
					$count++;
					
					if(!empty($product->CUSTOM_compositions)){
						$product_unique->CUSTOM_options[] = $product->CUSTOM_compositions;
					}
				}
			}
			$sale = $product_unique->sale_price_c*$count;
			$sale_all += $sale;
			$order_products[] = ['product' => $product_unique,'count' => $count, 'sale' => $sale];
		}
		
		
		$this->data['order_products'] = $order_products;
		$this->data['sale_all'] = $sale_all;
	}
	
	public function products_unique($products) { 
		$products_unique = $key_array = []; 
		$i = 0; 
	 
		foreach($products as $product) { 
			if (!in_array($product->id, $key_array)) { 
				$key_array[$i] = $product->id; 
				$products_unique[$i] = $product; 
			}
			$i++; 
		} 
		return $products_unique; 
	}
	
	public function getData() {
		return $this->data;
	}
}