<?php include CORE_FOLDER.'/application/views/widget/card2.php'; //Ваш заказ ?>

<?php
	//функция для обрезки строки
	function internoetics_string_strrpos($string, $length = 137, $trimmarker = '...') {
		$len = strlen(trim($string));                             
		$newstring = ($len > $length) ? rtrim(substr($string, 0, strrpos(substr($string, 0, $length), ' '))) . $trimmarker : $string;
		return $newstring;
	}

	global $db;
	$queryProductGroups = $db->query("
		SELECT distinct ppg.*, ppg_cstm.*, image.id as product_group_image_id
		FROM pdgrp_product_groups ppg 
		JOIN pdgrp_product_groups_cstm ppg_cstm ON ppg_cstm.id_c = ppg.id AND ppg.deleted = 0
		JOIN lngng_landings_pdgrp_product_groups_1_c ll_ppg ON ll_ppg.lngng_landings_pdgrp_product_groups_1pdgrp_product_groups_idb = ppg_cstm.id_c
		AND ll_ppg.lngng_landings_pdgrp_product_groups_1lngng_landings_ida = '".App::$current_landing->id."'
		JOIN pdgrp_product_groups_prdct_products_1_c ppg_pp ON ppg_pp.pdgrp_product_groups_prdct_products_1pdgrp_product_groups_ida = ppg_cstm.id_c
		JOIN prdct_products pp ON pp.id = ppg_pp.pdgrp_product_groups_prdct_products_1prdct_products_idb AND pp.deleted = 0
		JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp_cstm.active_c = 1
		LEFT JOIN img_img_images_pdgrp_product_groups_1_c img_group ON img_group.img_img_images_pdgrp_product_groups_1pdgrp_product_groups_idb = ppg.id AND img_group.deleted = 0
		LEFT JOIN img_img_images image ON image.id = img_group.img_img_images_pdgrp_product_groups_1img_img_images_ida AND image.deleted = 0
		ORDER BY ppg_cstm.show_order_c
	");
	while($productGroup = $db->fetchByAssoc($queryProductGroups)) {
		$current_product_groups[] = $productGroup;
	}
?>

<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:50px;" <?php } ?>>

<link rel="stylesheet" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/flickity-slider/css/flickity.min.css" />
<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/flickity-slider/js/flickity.pkgd.min.js"></script>

<?php if(empty(App::$current_landing->slider_c)){ ?>
	<img class="img" style="width:100%;border-radius:15px; margin-top:-30px;" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_landing_background_c" alt="Изображение с информацией о <?=App::$current_organization->name?>" />
<?php } else { ?>
	<?php 
		global $db;
		$querySliders = $db->query("
			SELECT 
				ss.id, 
				ss_cstm.link_c, 
				ss_cstm.mobile_image_c,
				ss_cstm.display_device_c
			FROM sld_slide ss
			LEFT JOIN sld_slide_cstm ss_cstm ON ss_cstm.id_c = ss.id AND ss.deleted = 0
			LEFT JOIN lngng_landings_sld_slide_1_c ll_ss ON ll_ss.lngng_landings_sld_slide_1sld_slide_idb = ss_cstm.id_c AND ll_ss.deleted = 0
			WHERE ll_ss.lngng_landings_sld_slide_1lngng_landings_ida = '".App::$current_landing->id."'
			ORDER BY ss_cstm.show_order_c;
		");
	?>
		<div class="main-carousel" style="height:100%;">
			<?php 
				$sliders = [];
				while($slider = $db->fetchByAssoc($querySliders)) { 
					if(in_array($slider['display_device_c'], ['01','02']) ){
						$sliders[] = $slider;
					}
				}
			?>
			<?php 
				foreach($sliders as $slider){
			?>
			<div class="carousel-cell" style="width: 100%; margin-right:20px;">
				<a href="<?=str_replace('http://', NFfunctions::getSiteProtocol(), $slider['link_c'])?>">
					<?php if($slider['mobile_image_c']) { ?>
						<picture>
							<source media="(min-width:768px)"
							  srcset="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$slider['id']?>_image_c">
							<img class="img lazyload"
							  data-src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$slider['id']?>_mobile_image_c"
							  style="width:100%;border-radius:15px;" alt="Изображение с информацией о <?=App::$current_organization->name?>">
						<picture>
					<?php } else { ?>
						<img class="img" style="width:100%;border-radius:15px;" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$slider['id']?>_image_c" alt="Изображение с информацией о <?=App::$current_organization->name?>" />
					<?php } ?>
				</a>
			</div>
			<?php } ?>
		</div>
<?php } ?>



<script>
$(function() {
   $('.main-carousel').flickity({
	  cellAlign: 'left',
	  contain: true,
	  autoPlay: true,
	  
	  //freeScroll: true,
//freeScrollFriction: 0.03
	});
});
</script>


	
	<?php if(!App::$current_landing->hidden_other_city_c) { ?>
		<div class="input-group my-4">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="material-icons">location_on</i> Ваш город</span>
			</div>	
			<select class="custom-select col" onchange="top.location=this.value" style="border-radius: 0 30px 30px 0;cursor: pointer;">
				<option selected=""><?=App::$current_city->name?></option>
			<?php
				global $db;
				$queryCities = $db->query("
					SELECT
						cc.name,
						ll_cstm.link_c
					FROM orgns_organizations_lngng_landings_1_c oo_ll 
						JOIN lngng_landings ll ON ll.id = oo_ll.orgns_organizations_lngng_landings_1lngng_landings_idb AND oo_ll.deleted = 0 AND ll.deleted = 0
						JOIN lngng_landings_cstm ll_cstm ON ll_cstm.id_c = ll.id AND ll.deleted = 0
						JOIN city_cities_lngng_landings_1_c cc_ll ON cc_ll.city_cities_lngng_landings_1lngng_landings_idb = ll_cstm.id_c AND cc_ll.deleted = 0
						JOIN city_cities cc ON cc.id = cc_ll.city_cities_lngng_landings_1city_cities_ida AND cc.deleted = 0
					WHERE oo_ll.orgns_organizations_lngng_landings_1orgns_organizations_ida = '".App::$current_organization->id."'
						AND ll_cstm.status_c != '03'
					ORDER BY cc.name;
				");
				
				while($other_city = $db->fetchByAssoc($queryCities)) {
					if($other_city['name'] != App::$current_city->name) {
			?>
				<option value="<?=$other_city['link_c']?>"><?=$other_city['name']?></option>
			<? 		}
				} 
			?>
			</select>
			<!--<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="Улица, дом">-->
		</div>
	<?php } ?>
	
	<?php if(App::$current_landing->information_block_c) { ?>
		<div class="row mt-3">
			<div class="col-12">
				<div class="card mb-3 border-0 shadow-sm">
					<div class="card-body">
						<div class="row">
							<div class="col-auto align-self-center pr-1">
								<span class="btn btn-success button-rounded-26 padding-top:5px;">
									!
								</span>
							</div>
							<div class="col pl-1">
								<p class="mb-0" style="font-size:10pt;"><?=html_entity_decode(App::$current_landing->information_block_c)?></p>
							</div>
						</div>
						<?php if(App::$current_landing->id == '6cf59565-a918-ff2e-9d5d-5bf32ae80893') { //по Пряникову?>
						<div class="row">
							<div class="col" style="margin-bottom:10px;margin-top:10px;">
								<a class="btn btn-lg btn-default text-white btn-block btn-rounded shadow send_lead" href="https://taishet38.ru/?l=pryanikov_site_btn" style="max-width:100%;font-size:16pt;">САЙТ ПАНАЗИАНТСКОЙ КУХНИ</a>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	
	<?php 
		global $db;
		$queryNews = $db->query("
			SELECT 
				nn.id, 
				nn.name, 
				nn_cstm.text_c, 
				nn_cstm.color_background_c,
				nn_cstm.color_text_c,
				nn_cstm.image_fon_c
			FROM news_news nn
			LEFT JOIN news_news_cstm nn_cstm ON nn_cstm.id_c = nn.id AND nn.deleted = 0
			LEFT JOIN lngng_landings_news_news_1_c ll_nn ON ll_nn.lngng_landings_news_news_1news_news_idb = nn_cstm.id_c AND ll_nn.deleted = 0
			WHERE 
				ll_nn.lngng_landings_news_news_1lngng_landings_ida = '".App::$current_landing->id."'
				AND nn_cstm.type_c LIKE '%^02^%'
			ORDER BY nn_cstm.publish_date_c DESC
			LIMIT 6;
		");
		$news = [];
		while($new = $db->fetchByAssoc($queryNews)) {
			$news[] = $new;
		}
		
		if($news){
	?>
		<h6 class="subtitle">Акции <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news" class="float-right small">Смотреть все</a></h6>
		<div class="row">
			<div class="container px-0">
				<div class="swiper-container offer-slide swiper-container-horizontal">
					<div class="swiper-wrapper" style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;">
						<?php foreach($news as $new) { ?>
							<div class="swiper-slide" >
								<div class="card shadow border-0 bg-template" style="height:130px; <?php if($new['image_fon_c']) { ?> background-image: url(<?=NFfunctions::getSiteProtocol()?>crm.winmon.ru/index.php?entryPoint=download&id=<?=$new['id']?>_image_fon_c&type=news_news);background-repeat: no-repeat; background-size: cover;background-position: center; <?php } else {?> background:<?=$new['color_background_c']?>; <? } ?>">
									<div class="card-body">
										<div class="row">
											<div class="col pr-0 align-self-center">
												<h5 class="mb-2 font-weight-normal" style="color:<?=$new['color_text_c']?>;font-size: 1.20rem;"><?=mb_strimwidth($new['name'],0, 25, "...")?></h5>
												<p class="text-mute" style="color:<?=$new['color_text_c']?>;width:210px;"><?=mb_strimwidth(strip_tags(html_entity_decode($new['text_c'])),0, 60, "...")?></p>
											</div>
											<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news/<?=$new['id']?>" class="btn btn-default button-rounded-36 shadow-sm float-bottom-right"><i class="material-icons md-18">arrow_forward</i></a>
										</div>
										
									</div>
								</div>
							</div>
						<?php } ?>
						<div class="swiper-slide" >
							<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news">
								<div class="card shadow border-0 bg-template" style="height:130px;background:<?=App::$current_organization->main_color_c?>;width:130px;">
									<div class="card-body">
										<div class="row">
											<div class="col pr-0 align-self-center" style="margin:0px;padding:0px;">
												<center><h5 class="mb-2 font-weight-normal" style="color:#fff;">Смотреть <br> другие</h5></center>
											</div>
											<div class="col" style="margin:0px;padding:0px;">
												<center><i class="material-icons" style="color:#fff;font-size:35pt;">add</i></center>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	
	<?php include CORE_FOLDER.'/application/views/widget/review.php'; //Отзывы ?>

	<?php include CORE_FOLDER.'/application/views/widget/category2.php'; //Категории ?>
	
	<!-- Скроллинг для пунктов меню -->
	<script>
		$(".category_scroll").click(function() {
			var scroll_id  = $(this).data("scroll"); // получем идентификатор блока из атрибута href

			var top = $('#productGroup'+scroll_id).position().top;
			$('body,html').animate({scrollTop: top-50}, 500); // анимируем переход к блоку, время: 800 мс
			
			$('.container-category').removeClass('modal-open');
			
		});
	</script>
	<!-- END Скроллинг для пунктов меню -->
	
	
	
	<!-- swiper js -->
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/swiper/js/swiper.min.js"></script>
	<!-- swiper -->

	<style>
		a.active{
			border-bottom: 3px solid #fff;
		}
	</style>

	<script>
		$(window).on('load', function() {
			var swiper_categories = new Swiper('.small-slide', {
				slidesPerView: 'auto',
				spaceBetween: 0,
				loop: false,//зацикливание
				pagination: {
					el: '.swiper-pagination',
					clickable: true,
					dynamicBullets: true,
				},
			});

			var swiper_offer = new Swiper('.offer-slide', {
				slidesPerView: 'auto',
				spaceBetween: 0,
				loop: false,//зацикливание
				autoplay: {
					delay: 4000,
				},
			});

			var swiper_news = new Swiper('.news-slide', {
				slidesPerView: 5,
				spaceBetween: 0,
				breakpoints: {
					1150: {
						slidesPerView: 4,
						spaceBetween: 0,
					},
					991: {
						slidesPerView: 3,
						spaceBetween: 0,
					},
					767: {
						slidesPerView: 2,
						spaceBetween: 0,
					},
					320: {
						slidesPerView: 2,
						spaceBetween: 0,
					}
				}
			});
			
			<?php if(!empty(App::$current_landing->slider_c)){ ?>
				var swiper_banner = new Swiper('.banner-swiper', {
					effect: 'coverflow',
					spaceBetween: 10,
					speed: 900,
					centeredSlides: true,
					loop: true, //зацикливание
					<?php if(count($sliders) > 1){ ?>
					autoplay: {
						delay: 6000,
						disableOnInteraction: false,
					},
					pagination: {
						el: '.swiper-pagination',
						clickable: true,
					},
					navigation: {
						nextEl: '.swiper-button-next',
						prevEl: '.swiper-button-prev',
					},
					onImagesReady: function (swiper) {
						iper.onResize();
					}
					<?php } ?>
				});
			<?php } ?>
		});
	</script>

	
	<?php if(App::$current_landing->status_c == '03') { ?>
		<div class="container">
			<div class="card bg-template shadow mt-4 h-190">
				<div class="card-body">
					<center style="width:100%">
						<div style="display:inline-block;">
							<h1 class="mb-1 text-white" style="font-size:19pt;">Технические работы</h1>
						</div>
					</center>
				</div>
			</div>
		</div>
		<div class="container mt-3">
			<div class="jumbotron text-center bg-white shadow-sm">
				Уважаемый пользователь! Уведомляем Вас, что доставка временно не принимает новые заказы, ввиду высокой загруженности! Приносим свои извинения за доставленные неудобства!
			</div>
		</div>
	<?php } else { ?>
	
					

		<input type="text" id="search_input" class="form-control form-control-lg search my-3" placeholder="Поиск" style="width:95%;margin:0 auto;">

		
		<?php foreach($current_product_groups as $productGroup){ ?>
		
			<h3 class="categories" style="text-align:center;width:100%;color:<?=App::$current_organization->main_color_c?>;margin-top: 20px; margin-bottom:15px;" id="productGroup<?=$productGroup['id']?>"><?=$productGroup['name']?></h3>
			<div class="row" id="row_productGroup<?=$productGroup['id']?>">
				<?
					global $db;
					$groupProducts = $db->fetchRow($db->query("
						SELECT 
							max_row_c, 
							height_image_c,
							height_short_description_c
						FROM pdgrp_product_groups_cstm ppg_cstm
						WHERE ppg_cstm.id_c = '".$productGroup['id']."';
					"));
					
					
					$queryProducts = $db->query("
						SELECT 
							pp.*, 
							pp_cstm.*, 
							image.id as product_image_id
						FROM prdct_products pp 
							JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp.deleted = 0 AND pp_cstm.active_c = 1
							JOIN pdgrp_product_groups_prdct_products_1_c pg_pp ON pg_pp.pdgrp_product_groups_prdct_products_1prdct_products_idb = pp_cstm.id_c AND pg_pp.pdgrp_product_groups_prdct_products_1pdgrp_product_groups_ida = '".$productGroup['id']."'
							LEFT JOIN img_img_images_prdct_products_1_c img_product ON img_product.img_img_images_prdct_products_1prdct_products_idb = pp.id AND img_product.deleted = 0
							LEFT JOIN img_img_images image ON image.id = img_product.img_img_images_prdct_products_1img_img_images_ida AND image.deleted = 0
						ORDER BY pp_cstm.show_order_c
					");
					
					$queryComplexGroup = $db->query("
						SELECT 
							cpp.*,
							cpp_cstm.*
						FROM cmprd_complex_products cpp 
							JOIN cmprd_complex_products_cstm cpp_cstm ON cpp_cstm.id_c = cpp.id AND cpp.deleted = 0
							JOIN pdgrp_product_groups_cmprd_complex_products_1_c pg_cpp ON pg_cpp.pdgrp_prodd6b7roducts_idb = cpp_cstm.id_c AND pg_cpp.pdgrp_prodb0d7_groups_ida = '".$productGroup['id']."'
						ORDER BY cpp_cstm.show_order_c
					");

					$complexGroups = [];
					while($complexGroup = $db->fetchByAssoc($queryComplexGroup)) {
						$queryComplexProducts = $db->query("
							SELECT 
								pp.*, 
								pp_cstm.*,
								image.id as product_image_id
							FROM prdct_products pp 
							LEFT JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp.deleted = 0 AND pp_cstm.active_c = 1
                            JOIN cmprd_complex_products_prdct_products_1_c cpp_pp ON cpp_pp.cmprd_complex_products_prdct_products_1prdct_products_idb = pp.id AND cpp_pp.cmprd_comp1b1droducts_ida = '".$complexGroup['id']."' AND cpp_pp.deleted = 0
							LEFT JOIN img_img_images_prdct_products_1_c img_product ON img_product.img_img_images_prdct_products_1prdct_products_idb = pp.id AND img_product.deleted = 0
							LEFT JOIN img_img_images image ON image.id = img_product.img_img_images_prdct_products_1img_img_images_ida AND image.deleted = 0
							ORDER BY pp_cstm.show_order_c
						");

						while($complexProduct = $db->fetchByAssoc($queryComplexProducts)) {
							$complexGroup['complexProducts'][] = $complexProduct;
						}
						$complexGroups[] = $complexGroup;
					}
					print_rr($complexGroups);
					

					$products = [];
					while($product = $db->fetchByAssoc($queryProducts)) {
						$product['max_row'] = $groupProducts['max_row_c'] ?? '3';
						
						//выставляем значение высоты изображения у карточки товара
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
				?>
				
				<? foreach ($complexGroups as $complexGroup) { ?>
						<div class="col-12 col-md-6 col-lg-4 col-xl-3 desctop_productInfo sizeup">
							<div class="card shadow-sm border-0 mb-4" style="cursor: pointer;border-radius: .25rem;">
								<div class="card-body p-0">
									<div style="overflow:hidden; height:<?=$product['height_image']?>px;" data-toggle="modal" data-target="#openProduct<?=$product['id']?>">
										<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$product['product_image_id']?>_image_c" alt="Фото <?=$product['name']?>" style="width:100%; border-radius: .25rem .25rem 0 0; background:<?=App::$current_organization->product_background_color_c?>">
									</div>
									<div style="position:absolute;width:100%;top:0px;left:0;padding:10px;">
										<? if($product['tag_c']){ ?>
											<div class="badge float-right" style="background-color:<?=$product['tag_color_c']?>; color:<?=$product['tag_text_color_c']?>; font-size:12pt; border-radius: .25rem; position:relative; top:-20px; font-weight: 500;"><?=$product['tag_c']?></div>
										<? } ?>
									</div>
									<div data-toggle="modal" data-target="#openProduct<?=$complexGroup['id']?>" style="padding:0 10px; overflow:hidden; height:<?=$product['height_short_description']?>px;">
										<span class="mb-1 mt-2 h6 d-block" style="color:<?=App::$current_organization->main_color_c?>;"><?=$complexGroup['name']?></span>
										<? if($product['description']){
											$description = str_replace('&lt;br&gt;&lt;br&gt;', '', $product['description']);//удаляем левые теги
											$description = strip_tags( html_entity_decode($description));
											$description = internoetics_string_strrpos($description, 100, '...');
										?>
											<div class="text-secondary small mb-3" style="height:35px;"><?=$description?></div>
										<? } ?>
									</div>
									<div class="row" style="padding:0 10px; height:55px;">
										<div class="col-8 text-success font-weight-normal" style="padding-top:10px; font-size:15pt;" data-toggle="modal" data-target="#openProduct<?=$product['id']?>">
											От 1400 ₽
										</div>
									
										<div class="col-4">
											<input type="text" class="btn button-rounded-36 shadow-sm float-bottom-right product_count<?=$product['id']?>" style="font-size:13pt;text-align:left;min-width:65px;padding-left:10px;padding-top:3px;" value="<?=$product['count']?>" readonly>
											<a class="btn btn-default button-rounded-36 shadow-sm float-bottom-right btn-mini-add-product" data-product-id="<?=$product['id']?>"><i class="material-icons md-18">add</i></a>
										</div>
								
									</div>
								</div>
							</div>
						</div>
				<?php } ?>
				
				<? foreach ($products as $product) { ?>
					<?php if(App::$current_landing->product_desktop_style_c == "01")  { ?>
						<? if($product['visible'] == 'show' || ($product['visible'] == 'hide' && $product['type_hidden_c'] == '02')){ ?>
							<div class="col-12 col-md-6 col-lg-4 col-xl-<?=$product['max_row']?> desctop_productInfo sizeup">
								<div class="card shadow-sm border-0 mb-4" style="cursor: pointer;border-radius: .25rem;"
									<? if($product['count'] > 0) { ?>
										style="box-shadow: 0 .125rem .25rem <?=App::$current_organization->product_bckg_color_active_c?> !important;"
									<? } ?>
									>
									<div class="card-body p-0">
										<div style="overflow:hidden; height:<?=$product['height_image']?>px;" data-toggle="modal" data-target="#openProduct<?=$product['id']?>">
											<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$product['product_image_id']?>_image_c" alt="Фото <?=$product['name']?>" style="width:100%; border-radius: .25rem .25rem 0 0; background:<?=App::$current_organization->product_background_color_c?>">
										</div>
										<div style="position:absolute;width:100%;top:0px;left:0;padding:10px;">
											<? if($product['tag_c']){ ?>
												<div class="badge float-right" style="background-color:<?=$product['tag_color_c']?>; color:<?=$product['tag_text_color_c']?>; font-size:12pt; border-radius: .25rem; position:relative; top:-20px; font-weight: 500;"><?=$product['tag_c']?></div>
											<? } ?>
										</div>
										<div data-toggle="modal" data-target="#openProduct<?=$product['id']?>" style="padding:0 10px; overflow:hidden; height:<?=$product['height_short_description']?>px;">
											<span class="mb-1 mt-2 h6 d-block product_name" style="color:<?=App::$current_organization->main_color_c?>;"><?=$product['name']?></span>
											<? if($product['weight_c'] !== null && $product['weight_c'] !== ''){ ?>
												<span style="font-size: 14px;line-height: 20px;color: rgb(92, 99, 112);margin-bottom: 4px;"><?=$product['weight_c']?></span>
											<? } ?>
											<? if($product['kbzhu_c'] !== null && $product['kbzhu_c'] !== ''){ ?>
												<? if($product['weight_c'] !== null && $product['weight_c'] !== ''){ ?>
													<span> • </span>
												<? } ?>
												<span style="font-size: 14px;line-height: 20px;color: rgb(92, 99, 112);margin-bottom: 4px;"><?=$product['kbzhu_c']?></span>
											<? } ?>
											<? if($product['description']){
												$description = str_replace('&lt;br&gt;&lt;br&gt;', '', $product['description']);//удаляем левые теги
												$description = strip_tags( html_entity_decode($description));
												$description = internoetics_string_strrpos($description, 100, '...');
											?>
												<div class="text-secondary small mb-3 product_description" style="height:35px;"><?=$description?></div>
											<? } ?>
										</div>
										<div class="row" style="padding:0 10px; height:55px;">
											<div class="col-8 text-success font-weight-normal" style="padding-top:10px; font-size:15pt;" data-toggle="modal" data-target="#openProduct<?=$product['id']?>">
												<?=$product['sale_price_c']?> ₽
												<? if($product['red_sale_price_c'] > 0) { ?>
													<span class="small text-mute ml-1" style="color:red;text-decoration:line-through;"><?=$product['red_sale_price_c']?> ₽</span>
												<? } ?>
											</div>
											<? if(App::$current_landing->delivery_active_c || App::$current_landing->pickup_c){ ?>
												<? if($product['visible'] == 'show'){ ?>
													<div class="col-4">
														<input type="text" class="btn button-rounded-36 shadow-sm float-bottom-right product_count<?=$product['id']?>" style="font-size:13pt;text-align:left;min-width:65px;padding-left:10px;padding-top:3px;" value="<?=$product['count']?>" readonly>
														<a class="btn btn-default button-rounded-36 shadow-sm float-bottom-right btn-mini-add-product" data-product-id="<?=$product['id']?>"><i class="material-icons md-18">add</i></a>
													</div>
												<? } ?>
											<? } ?>
										</div>
									</div>
								</div>
							</div>
						<? } ?>
					<?php } elseif(App::$current_landing->product_desktop_style_c == "02") { ?>
						<? if($product['visible'] == 'show' || ($product['visible'] == 'hide' && $product['type_hidden_c'] == '02')){ ?>
							<div class="col-12 col-lg-6 desctop_productInfo sizeup" data-toggle="modal" data-target="#openProduct<?=$product['id']?>">
								<div class="card shadow-sm border-0 mb-4" style="cursor: pointer;border-radius: .25rem;"
									<? if($product['count'] > 0) { ?>
											style="box-shadow: 0 .125rem .25rem <?=App::$current_organization->product_bckg_color_active_c?> !important;"
									<? } ?>
									>
									<div class="card-body">
										<div class="row">
											<div class="col-5 col-md-5 col-lg-5">
												<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$product['product_image_id']?>_image_c" alt="Фото <?=$product['name']?>" style="width:100%; border-radius: .25rem .25rem 0 0; background:<?=App::$current_organization->product_background_color_c?>">
												<? if($product['tag_c']){ ?>
													<span class="badge d-inline-block ml-2" style="border-radius: .25rem;position:absolute;top:-15px;left:-8px;font-size:11pt;background-color:<?=$product['tag_color_c']?>;color:<?=$product['tag_text_color_c']?>;font-weight: 500;"><?=$product['tag_c']?></span>
												<? } ?>
											</div>
											<div class="col pl-0">
												<span class="mb-1 h6 d-block search_text" style="color:<?=App::$current_organization->main_color_c?>;"><?=$product['name']?></span>
												<? if($product['weight_c'] !== null && $product['weight_c'] !== ''){ ?>
													<span style="font-size: 14px;line-height: 20px;color: rgb(92, 99, 112);margin-bottom: 4px;"><?=$product['weight_c']?></span>
												<? } ?>
												<? if($product['kbzhu_c'] !== null && $product['kbzhu_c'] !== '') { ?>
													<? if($product['weight_c'] !== null && $product['weight_c'] !== ''){  ?>
														<span> • </span>
													<? } ?>
													<span style="font-size: 14px;line-height: 20px;color: rgb(92, 99, 112);margin-bottom: 4px;"><?=$product['kbzhu_c']?></span>
												<? } ?>
												<? if($product['description']){
													$description = str_replace('&lt;br&gt;&lt;br&gt;', '', $product['description']);//удаляем левые теги
													$description = html_entity_decode($description);
													$description = internoetics_string_strrpos($description, 180);
												?>
													<p class="text-secondary small mb-5"><?=$description?> ...</p>
												<? } ?>
												<? if(App::$current_landing->delivery_active_c || App::$current_landing->pickup_c){ ?>
													<? if($product['visible'] == 'show'){?>
														<input type="text" class="btn button-rounded-36 shadow-sm float-bottom-right product_count<?=$product['id']?>" style="bottom:0;font-size:13pt;text-align:left;min-width:65px;padding-left:10px;padding-top:3px;" value="<?=$product['count']?>" readonly>'+
														<a class="btn btn-default button-rounded-36 shadow-sm float-bottom-right btn-mini-add-product" data-product-id="<?=$product['id']?>" style="bottom:0;"><i class="material-icons md-18">add</i></a>';
													<? } ?>
												<? } ?>
												<h5 class="text-success font-weight-normal" style="bottom:0;position:absolute;width:100%;"><?=$product['sale_price_c']?> ₽
													<? if($product['red_sale_price_c'] > 0){ ?>
														<div class="small text-mute" style="color:red;text-decoration:line-through;text-align:left;bottom:-1;position:absolute;"><?=$product['red_sale_price_c']?> ₽</div>
													<? } ?>
												</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					<?php } ?>
					
					
					
					<?php if(App::$current_landing->product_mobile_style_c == "01")  { ?>
						<? if($product['visible'] == 'show' || ($product['visible'] == 'hide' && $product['type_hidden_c'] == '02')){ ?>
								<div class="col-12 col-md-6 col-lg-4 col-xl-3 mobile_productInfo" data-toggle="modal" data-target="#openProduct<?=$product['id']?>">
									<div class="card shadow-sm border-0 mb-4" style="cursor: pointer;border-radius: .25rem;">
										<div class="card-body">
											<div style="top:0px;left:0;width:100%;height:100%;">
												<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$product['product_image_id']?>_image_c" alt="Фото <?=$product['name']?>" style="width:100%;height:100%;border-radius: .25rem .25rem 0 0;max-height:320px;background:<?=App::$current_organization->product_background_color_c?>">
											</div>
											<div style="position:absolute;width:100%;top:0px;left:0;padding:10px;">
												<? if($product['tag_c']){ ?>
													<div class="badge float-right mt-1" style="border-radius: .25rem;background-color:<?=$product['tag_color_c']?>;color:<?=$product['tag_text_color_c']?>;font-size:12pt;font-weight: 500;"><?=$product['tag_c']?></div>
												<? } ?>
											</div>
											<div style="left:0;bottom:0;width:100%;;background:#fff;padding:0 10px;border-radius:0 0 15px 15px;">
												<span class="mb-1 mt-2 h6 d-block search_text" style="color:<?=App::$current_organization->main_color_c?>;" ><?=$product['name']?></span>
												<? if($product['weight_c'] !== null && $product['weight_c'] !== ''){ ?>
													<span style="font-size: 14px;line-height: 20px;color: rgb(92, 99, 112);margin-bottom: 4px;"><?=$product['weight_c']?></span>
												<? } ?>
												<? if($product['kbzhu_c'] !== null && $product['kbzhu_c'] !== ''){ ?>
													<? if($product['weight_c'] !== null && $product['weight_c'] !== ''){ ?>
														<span> • </span>
													<? } ?>
														<span style="font-size: 14px;line-height: 20px;color: rgb(92, 99, 112);margin-bottom: 4px;"><?=$product['kbzhu_c']?></span>
												<? } ?>
												<? if($product['description']){
													$description = str_replace('&lt;br&gt;&lt;br&gt;', '', $product['description']);//удаляем левые теги
													$description = html_entity_decode($description);
													$description = internoetics_string_strrpos($description, 100);
												?>
													<div class="text-secondary small mb-2 search_text" style="height:35px;"><?=$description?></div>
												<? } ?>
												<h5 class="text-success font-weight-normal mb-0"><?=$product['sale_price_c']?> ₽</h5>
												<? if($product['red_sale_price_c'] > 0){ ?>
													<p class="small text-mute mb-0" style="color:red; text-decoration:line-through;"><?=$product['red_sale_price_c']?> ₽</p>
												<? } ?>
												<div style="margin-top:30px;">
													<? if(App::$current_landing->delivery_active_c || App::$current_landing->pickup_c){ ?>
														<input type="text" class="btn-mini-add-product btn button-rounded-36 shadow-sm float-bottom-right product_count<?=$product['id']?>" data-product-id="<?=$product['id']?>" style="font-size:13pt;text-align:left;min-width:65px;padding-left:10px;padding-top:3px;" value="<?=$product['count']?>" readonly>
														<a class="btn btn-default button-rounded-36 shadow-sm float-bottom-right btn-mini-add-product" data-product-id="<?=$product['id']?>"><i class="material-icons md-18">add</i></a>
													<? } ?>
												</div>
											</div>
										</div>
									</div>
								</div>
						<?php } ?>
					<?php } elseif(App::$current_landing->product_mobile_style_c == "02") { ?>
						<? if($product['visible'] == 'show' || ($product['visible'] == 'hide' && $product['type_hidden_c'] == '02')){ ?>
							<div class="col-12 col-lg-6 mobile_productInfo" style="padding-left:10px; padding-right:10px;">
								<div class="card shadow-sm border-0 mb-4" style="cursor: pointer;border-radius: .25rem; margin-bottom: 1rem!important;"
									<? if($product['count'] > 0) { ?>
											style="box-shadow: 0 .125rem .25rem <?=App::$current_organization->product_bckg_color_active_c?> !important;"
									<? } ?>
									>
									<div class="card-body">
										<div class="row">
											<div class="col-6" data-toggle="modal" data-target="#openProduct<?=$product['id']?>">
												<img class="mr-3" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$product['product_image_id']?>_image_c" alt="Фото <?=$product['name']?>" style="border-radius:.25rem;width:100%;background:<?=App::$current_organization->product_background_color_c?>">
												<? if($product['tag_c']){ ?>
													<span class="badge d-inline-block ml-2" style="border-radius: .25rem;position:absolute;top:-15px;left:-8px;font-size:11pt;background-color:<?=$product['tag_color_c']?>;color:<?=$product['tag_text_color_c']?>;font-weight: 500;"><?=$product['tag_c']?></span>
												<? } ?>
											</div>
											<div class="col-6">
												<div class="row pb-2" data-toggle="modal" data-target="#openProduct<?=$product['id']?>">
													<div class="col-10 p-0">
														<span class="mb-1 h6 product_name" style="color:<?=App::$current_organization->main_color_c?>;"><?=$product['name']?></span>
													</div>
													<? if($product['weight_c'] !== null && $product['weight_c'] !== ''){ ?>
														<span style="font-size: 14px;line-height: 20px;color: rgb(92, 99, 112);"><?=$product['weight_c']?></span>
													<? } ?>
													<? if($product['kbzhu_c'] !== null && $product['kbzhu_c'] !== ''){ ?>
														<? if($product['weight_c'] !== null && $product['weight_c'] !== ''){ ?>
															<span style='margin:0 3px 0 3px;'>•</span>
														<? } ?>
														<span style="font-size: 14px;line-height: 20px;color: rgb(92, 99, 112);"><?=$product['kbzhu_c']?></span>
													<? } ?>
													<? if($product['description']){
														$description = str_replace('&lt;br&gt;&lt;br&gt;', '', $product['description']);//удаляем левые теги
														$description = html_entity_decode($description);
														$description = internoetics_string_strrpos($description, 130);
													?>
														<div class="text-secondary small pr-3 product_description"><?=$description?> ...</div>
													<? } ?>
												</div>
												<div class="row pt-1" style="height:45px;">
													<div class="col-8 p-0" data-toggle="modal" data-target="#openProduct<?=$product['id']?>">
														<h5 class="text-success font-weight-normal"><?=$product['sale_price_c']?> ₽
															<? if($product['red_sale_price_c'] > 0){ ?>
																<div class="small text-mute" style="color:red;text-decoration:line-through;"><?=$product['red_sale_price_c']?> ₽</div>
															<? } ?>
														</h5>
													</div>
													<div class="col-4">
														<? if(App::$current_landing->delivery_active_c || App::$current_landing->pickup_c){ ?>
															<? if($product['visible'] == 'show'){ ?>
																<input type="text" class="btn-mini-add-product btn button-rounded-36 shadow-sm float-bottom-right product_count<?=$product['id']?>" data-product-id="<?=$product['id']?>" style="font-size:13pt;text-align:left;min-width:65px;padding-left:10px;padding-top:3px;" value="<?=$product['count']?>" readonly>
																<a class="btn btn-default button-rounded-36 shadow-sm float-bottom-right btn-mini-add-product" data-product-id="<?=$product['id']?>"><i class="material-icons md-18">add</i></a>
															<? } ?>
														<? } ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					<?php } ?>
					
					
					
					<div class="modal fade modal-fullscreen-sm" id="openProduct<?=$product['id']?>" tabindex="-1" role="dialog" style="display:none;" aria-hidden="true">
						<div class="modal-dialog modal-sm modal-dialog-centered">
							<div class="modal-content shadow">
								<div class="modal-body p-0">
									<img id="animation_in_card<?=$product['id']?>" class="product_image_<?=$product['id']?> mb-2 modal_product_image" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$product['product_image_id']?>_image_c" alt="" style="width:100%;border-radius:15px 15px 0 0;">
									<? if($product['tag_c']){ ?>
										<span class="badge d-inline-block ml-2" style="position:absolute;top:15px;left:10px;font-size:16pt;background-color:<?=$product['tag_color_c']?>;color:<?=$product['tag_text_color_c']?>;"><?=$product['tag_c']?></span>
									<? } ?>
									<a class="btn btn-default button-rounded-36" data-dismiss="modal" aria-label="Close" style="top: 12px; width: 35px;right: 12px;height: 35px;position: absolute;box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.07);border-radius: 50%;background-color: white;cursor:pointer;"><i class="material-icons" style="color:#000;">close</i></a>
									<div class="pr-4 pl-4">
										<h5 class="header-title mb-0 product_name_<?=$product['id']?>" style="color:<?=App::$current_organization->main_color_c?>;float:left;"><?=$product['name']?></h5>
										<h5 class="text-success font-weight-normal mb-2 product_price_<?=$product['id']?>" style="text-align:right;"><?=$product['sale_price_c']?> ₽</h5>
										<? if($product['weight_c'] !== null && $product['weight_c'] !== ''){ ?>
											<span style="font-size: 14px;line-height: 20px;color: rgb(92, 99, 112);"><?=$product['weight_c']?></span>
										<? } ?>
										<? if($product['kbzhu_c'] !== null && $product['kbzhu_c'] !== ''){ ?>
											<? if($product['weight_c'] !== null && $product['weight_c'] !== ''){ ?>
												<span style='margin:0 3px 0 3px;'>•</span>
											<? } ?>
											<span style="font-size: 14px;line-height: 20px;color: rgb(92, 99, 112);"><?=$product['kbzhu_c']?></span>
										<? } ?>
									</div>
									<div class="pr-4 pl-4" style="max-height:380px;overflow: auto;">
										<p class="text-secondary small mb-2"><?=html_entity_decode($product['description'])?></p>
										<div class="text-center">
											<div><a href="/main/product/<?=$product['id']?>">Отзывы о продукте</a></div>
										</div>
									</div>
								</div>
								<div class="modal-footer" style="padding:10px 0px;">
									<div class="text-center" style="width:100%;">
										<div class="btn-group btn-group-lg">
											<button type="button" class="btn btn-primary mr-1" data-dismiss="modal" style="font-size:8pt;background:#343a40;">Продолжить покупки</button>
										</div>
										<div class="btn-group btn-group-lg">
											<? if(App::$current_landing->delivery_active_c || App::$current_landing->pickup_c){ ?>
												<? if($product['visible'] == 'show'){ ?>
													<? if($product['count'] > 0){ ?>
														<button type="button" class="btn btn-primary btn-minus-product" data-product-id="<?=$product['id']?>" style="background:<?=App::$current_organization->color_product_btn_c?>;width: 70px;">-</button>
													<? } ?>
													<button type="button" class="btn btn-primary active" style="background:<?=App::$current_organization->color_product_btn_c?>;width:50px;padding:0px;"><input type="text" style="border:none;background:none;outline:none;padding:0;color:#fff;width:50px;text-align: center;" class="product_count<?=$product['id']?>" value="<?=$product['count']?>" readonly></button>
													<? if($product['count'] > 0){ ?>
														<button type="button" class="btn btn-primary btn-add-product mr-1" data-product-id="<?=$product['id']?>" style="background:<?=App::$current_organization->color_product_btn_c?>;width: 70px;">+</button>
													<? }else{ ?>	
														<button type="button" class="btn btn-primary btn-add-product mr-1" data-product-id="<?=$product['id']?>" style="background:<?=App::$current_organization->color_product_btn_c?>;">Добавить</button>
													<? } ?>
												<? } ?>
											<? } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
		
		
		
				<? } ?>
			</div>
		<?php } ?>
		</div>
	<?php } ?>
	
	<script>
	searchInput = $('#search_input');

	searchInput.keyup(function(){ 
	    searchQuery = searchInput.val().toLowerCase();
		console.log(searchQuery);
		

		
		$('.product_name').each(function (index, element) {
			$(element).closest('.card').hide();
		});
		
		$('.product_name').each(function (index, element) {
			text = $(element).text().toLowerCase();
			
			if (text.includes(searchQuery)) {
				$(element).closest('.card').show();
			}
		});
		
		$('.product_description').each(function (index, element) {
			text = $(element).text().toLowerCase();

			if (text.includes(searchQuery)) {
				$(element).closest('.card').show();
			}
		});
	});
	
	  
	
  </script>

	
	<script>
		$(function() {
		   $("#modal_block").append($('.modal'));//добавляем модальные окна в блок модальных окон в окнце HTML (template_main_view.php)
		});
	</script>

	<style>
		@media screen and (max-width:767px) {
			.desctop_productInfo {
				display: none;
			}
		}
		
		@media screen and (min-width:768px) {
			.mobile_productInfo {
				display: none;
			}
		}
	</style>
</div>
<div class="container-fluid bg-warning text-white my-3 info-block">
	<div class="row" style="background:<?=App::$current_organization->main_color_c?>;">
		<div class="container">
			<div class="row py-4">
				<div class="col">
					<? if(!empty(App::$current_landing->title_description_c)) {?>
						<h1 class="text-uppercase mb-3"><?=strtoupper(App::$current_landing->title_description_c);?></h1>
					<? } else { ?>
					<h1 class="text-uppercase mb-3">Служба доставки из <?=strtoupper(App::$current_organization->name);?> в городе <span style="word-break: break-word;"><?=App::$current_city->name;?></span></h1>
					<? } ?>
					<p class="mb-3">
						<?=html_entity_decode(App::$current_landing->delivery_description_c)?>
					</p>
				</div>
				<div class="col-12 col-md-3 col-lg-2 col-xl-2">
				<? if(!empty(App::$current_landing->image_description_c)){ ?>
					<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_landing->id?>_image_description_c" alt="Изображение с описания доставки" style="display:block;max-width:200px;max-height:200px;margin-left:auto;margin-right:auto;">
				<? } else { ?>
					<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/deliveryman.png" alt="Изображение курьера доставки" style="display:block;width:200px;margin-left:auto;margin-right:auto;">
				<? } ?>
				</div>
				
			</div>
		</div>
	</div>
</div>
<div class="container">
	<?php 
		global $db;
		$queryNews = $db->query("
			SELECT 
				nn.id, 
				nn.name, 
				nn_cstm.text_c, 
				nn_cstm.color_background_c,
				nn_cstm.color_text_c,
				nn_cstm.image_fon_c
			FROM news_news nn
			LEFT JOIN news_news_cstm nn_cstm ON nn_cstm.id_c = nn.id AND nn.deleted = 0
			LEFT JOIN lngng_landings_news_news_1_c ll_nn ON ll_nn.lngng_landings_news_news_1news_news_idb = nn_cstm.id_c AND ll_nn.deleted = 0
			WHERE 
				ll_nn.lngng_landings_news_news_1lngng_landings_ida = '".App::$current_landing->id."'
				AND nn_cstm.type_c LIKE '%^01^%'
			ORDER BY nn.date_entered DESC
			LIMIT 6;
		");
		$news = [];
		while($new = $db->fetchByAssoc($queryNews)) {
			$news[] = $new;
		}
		
		if($news){
	?>
	<h6 class="subtitle">Наши новости <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news" class="float-right small">Смотреть все</a></h6>
	<div class="row">
		<div class="container px-0">
			<div class="swiper-container news-slide swiper-container-horizontal">
				<div class="swiper-wrapper" style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;">
					<?php foreach($news as $new) { ?>
						<div class="swiper-slide swiper-slide-active">
							<div class="card shadow border-0 bg-template" style="<?php if($new['image_fon_c']) { ?> background-image: url(<?=NFfunctions::getSiteProtocol()?>crm.winmon.ru/index.php?entryPoint=download&id=<?=$new['id']?>_image_fon_c&type=news_news);background-repeat: no-repeat; background-size: cover;background-position: center; <?php } else {?> background:<?=$new['color_background_c']?>; <? } ?> height:130px;">
								<div class="card-body">
									<div class="row">
										<div class="col pr-0 align-self-center">
											<h5 class="mb-2 font-weight-normal" style="color:#fff;"><?=mb_strimwidth($new['name'],0, 20, "...")?></h5>
											<p class="text-mute" style="color:#fff;"><?=mb_strimwidth(strip_tags(html_entity_decode($new['text_c'])),0, 25, "...")?></p>
										</div>
										<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news/<?=$new['id']?>" class="btn btn-default button-rounded-36 shadow-sm float-bottom-right"><i class="material-icons md-18">arrow_forward</i></a>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="swiper-slide" >
						<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news">
							<div class="card shadow border-0 bg-template" style="height:130px;background:<?=App::$current_organization->main_color_c?>;width:130px;">
								<div class="card-body">
									<div class="row">
										<div class="col pr-0 align-self-center" style="margin:0px;padding:0px;">
											<center><h5 class="mb-2 font-weight-normal" style="color:#fff;">Смотреть <br> другие</h5></center>
										</div>
										<div class="col" style="margin:0px;padding:0px;">
											<center><i class="material-icons" style="color:#fff;font-size:35pt;">add</i></center>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
</div>

<div class="container mb-3">
	<div class="row">
		<div class="col text-center">
			<h5 class="subtitle mb-1">Статистика</h5>
			<p class="text-secondary">Интересные параметры</p>
		</div>
	</div>
	<div class="row text-center mt-4">
	<?php
		global $db;
		$queryStatistic = $db->query("
			SELECT distinct ss.*, ss_cstm.*  
			FROM sttc_statistic ss 
			JOIN sttc_statistic_cstm ss_cstm ON ss_cstm.id_c = ss.id AND ss.deleted = 0
			JOIN lngng_landings_sttc_statistic_1_c ll_ss ON ll_ss.lngng_landings_sttc_statistic_1sttc_statistic_idb = ss_cstm.id_c
			AND ll_ss.lngng_landings_sttc_statistic_1lngng_landings_ida = '".App::$current_landing->id."'
			ORDER BY ss_cstm.sorting_c asc;
		");
		$statistics = [];
		while($statistic = $db->fetchByAssoc($queryStatistic)) {
			$statistics[] = $statistic;
		}
		
		if(!empty($statistics)) {
			foreach($statistics as $statistic){
	?>
		<div class="col-6 col-md-3">
			<div class="card shadow-sm border-0 mb-4">
				<div class="card-body">
					<? if($statistic['icon_c']) { ?>
						<i class="material-icons mb-4 md-36 text-template"><?=$statistic['icon_c']?></i>
					<? } ?>
					<h2> <?=$statistic['parameter_value_c']?> </h2>
					<p class="text-secondary text-mute"><?=$statistic['name']?></p>
				</div>
			</div>
		</div>
	<?php 
			}
		} else { 
	?>
		<div class="col-6 col-md-3">
			<div class="card shadow-sm border-0 mb-4">
				<div class="card-body">
					<i class="material-icons mb-4 md-36 text-template">card_giftcard</i>
					<h2> <?=App::$current_landing->delivery_time_c?> </h2>
					<p class="text-secondary text-mute">минут - среднее время доставки</p>
				</div>
			</div>
		</div>
		<div class="col-6 col-md-3">
			<div class="card shadow-sm border-0 mb-4">
				<div class="card-body">
					<i class="material-icons mb-4 md-36 text-template">subscriptions</i>
					<h2> <?=App::$current_landing->subscribers_c?></h2>
					<p class="text-secondary text-mute">подписчиков в соц.сетях</p>
				</div>
			</div>
		</div>
		<div class="col-6 col-md-3">
			<div class="card shadow-sm border-0 mb-4">
				<div class="card-body">
					<i class="material-icons mb-4 md-36 text-template">local_florist</i>
					<h2> <?=App::$current_landing->reviews_count_c?></h2>
					<p class="text-secondary text-mute">реальных отзывов</p>
				</div>
			</div>
		</div>
		<div class="col-6 col-md-3">
			<div class="card shadow-sm border-0 mb-4">
				<div class="card-body">
					<i class="material-icons mb-4 md-36 text-template">location_city</i>
					<h2>78%</h2>
					<p class="text-secondary text-mute">повторных заказов</p>
				</div>
			</div>
		</div>
	<?php }?>
	</div>
	
	<h6 class="subtitle">Вы ищете:</h6>
	<div class="row">
		<div class="col">
			<? foreach(explode(",", App::$current_landing->seo_keywords_c) as $searchKey) { ?>
				<button class="btn btn-lg btn-light btn-rounded shadow-xs my-1 mr-2"><?=$searchKey?></button>
			<? } ?>
		</div>
	</div>
</div>

<?php include CORE_FOLDER.'/application/views/widget/buttom_menu2.php'; //Нижнее меню ?>

<script>

</script>
