<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

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
		$current_product_groups[] = $productGroup;
	}
	
	$_SESSION['gift_modal_showed'] = true;
?>

<div class="header" id="scroll_to_header">
	<?php if( !empty(App::$current_aggregator)){ ?>
		<a href="<?=App::$current_aggregator->link_c?>" class="row no-gutters" style="max-width:1230px;margin:0 auto;background: linear-gradient(to right bottom, rgb(218, 95, 0), rgb(227, 102, 5), rgb(236, 109, 11), rgb(246, 117, 17), rgb(255, 124, 22));text-decoration: none;color:#fff;">
			<div class="col-auto">
				<button class="btn btn-link text-dark"><i class="material-icons" style="color:#fff;font-size:26pt;">call_received</i></button><span>Вернуться к списку ресторанов</span>
			</div>
		</a>
	<?php } ?>
	<div class="row no-gutters" style="height:50px;max-width:1230px;margin:0 auto;">
		<? if(empty($_SESSION['mobile_app'])) { ?>
			<div class="col-2 col-xl-1" id="icon_menu" style="padding-top:7px;padding-left:10px;cursor:pointer;" >
				<svg class="menu-btn" width="35" height="27" viewBox="0 0 24 24" fill="none" xmlns="https://www.w3.org/2000/svg">
					<rect x="2" y="4" width="25" height="3.2" rx="1.5" fill="currentColor"></rect>
					<rect x="2" y="11" width="18" height="3.2" rx="1.5" fill="currentColor"></rect>
					<rect x="2" y="18" width="25" height="3.2" rx="1.5" fill="currentColor"></rect>
				</svg>
			</div>
		<? } ?>
		<?php include CORE_FOLDER.'/application/views/widget/category_top.php'; //Слайдер ?>
		<div class="col-2 col-xl-1 text-center categories-icon scroll" data-scroll="header">
			<span class='material-icons' style="cursor: pointer; color: rgb(255, 255, 255);padding-top: 8px;font-size:27pt;">keyboard_arrow_up</span>
		</div>
	</div>
</div>

<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:50px;" <?php } ?>>
	<?php include CORE_FOLDER.'/application/views/widget/slider.php'; //Слайдер ?>
	
	<?php include CORE_FOLDER.'/application/views/widget/city.php'; //Ваш город ?>
	
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
				nn_cstm.image_fon_c,
				nn_cstm.link_name_c
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
		<div class="subtitle" style="font-weight: 500;">Акции <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/stocks" class="float-right small">Смотреть все</a></div>
		<div id="stock_news" class="row" style="display:none;">
			<div class="container px-0">
				<div class="swiper-container offer-slide swiper-container-horizontal" style="position: relative;overflow: hidden;">
					<div class="swiper-wrapper" style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;">
						<?php 
							foreach($news as $new) { 
								$link_news = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/main/news/';
								if(!empty($new['link_name_c'])){
									$link_news .= $new['link_name_c'];
								} else {
									$link_news .= $new['id'];
								}
						?>
							<div class="swiper-slide" >
								<a href="<?=$link_news?>" style="text-decoration:none;">
									<div class="card shadow border-0 bg-template" style="height:130px; <?php if($new['image_fon_c']) { ?> background-image: url(<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$new['id']?>_image_fon_c);background-repeat: no-repeat; background-size: cover;background-position: center; <?php } else {?> background:<?=$new['color_background_c']?>; <? } ?>">
										<div class="card-body">
											<div class="row">
												<div class="col pr-0 align-self-center">
													<div class="mb-2 font-weight-normal" style="color:<?=$new['color_text_c']?>;font-size:1.20rem;"><?=mb_strimwidth($new['name'],0, 25, "...")?></div>
													<p class="text-mute" style="color:<?=$new['color_text_c']?>;width:210px;"><?=mb_strimwidth(strip_tags(html_entity_decode($new['text_c'])),0, 60, "...")?></p>
												</div>
												<div class="btn btn-default button-rounded-36 shadow-sm float-bottom-right"><i class="material-icons md-18">arrow_forward</i></div>
											</div>
										</div>
									</div>
								</a>
							</div>
						<?php } ?>
						<div class="swiper-slide" >
							<a class="text-decoration-none" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/stocks">
								<div class="card shadow border-0 bg-template btn-default" style="height:130px;background:<?=App::$current_organization->main_color_c?>;width:130px;">
									<div class="card-body">
										<div class="row">
											<div class="col-12" style="margin:0px;padding:0px;">
												<div class="font-weight-normal text-center" style="color:#fff;font-size: 1.25rem;line-height: 1.2;">Смотреть <br> другие</div>
											</div>
											<div class="col-12 text-center" style="margin:0px;padding:0px;">
												<i class="material-icons" style="color:#fff;font-size:35pt;">add</i>
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
		<script>
			$(window).on('load', function() {
				$('#stock_news').show();
			});
		</script>
	<?php } ?>
	
	<?php include CORE_FOLDER.'/application/views/widget/review.php'; //Отзывы ?>

	<?php include CORE_FOLDER.'/application/views/widget/category.php'; //Категории ?>
	
	<?php if(App::$current_landing->status_c == '03') { ?>
		<div class="container">
			<div class="card bg-template shadow mt-4 h-190">
				<div class="card-body">
					<center style="width:100%">
						<div style="display:inline-block;">
							<div class="mb-1 text-white" style="font-size:19pt;">Технические работы</div>
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
		<?php include CORE_FOLDER.'/application/views/widget/products.php'; //Продукты ?>
	<?php } ?>

	
	<script>
		$(window).on('load', function() {
		   $("#modal_block").append($('.modal'));//добавляем модальные окна в блок модальных окон в окнце HTML (template_main_view.php)
		});
	</script>
</div>
<div class="container-fluid bg-warning text-white my-3 info-block">
	<div class="row" style="background:<?=App::$current_organization->main_color_c?>;">
		<div class="container">
			<div class="row py-4">
				<div class="col">
					<? if(!empty(App::$current_landing->title_description_c)) {?>
						<div class="text-uppercase mb-3" style="font-size:2.5rem;font-weight:500;line-height:1.2;"><?=strtoupper(App::$current_landing->title_description_c);?></div>
					<? } else { ?>
					<div class="text-uppercase mb-3" style="font-size:2.5rem;font-weight:500;line-height:1.2;">Служба доставки из <?=strtoupper(App::$current_organization->name);?> в городе <span style="word-break: break-word;"><?=App::$current_city->name;?></span></div>
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
	<div class="subtitle" style="font-weight: 500;">Наши новости <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news?type=only_news" class="float-right small">Смотреть все</a></div>
	<div class="row">
		<div class="container px-0">
			<div class="swiper-container news-slide swiper-container-horizontal" style="margin:0 auto;position: relative;overflow: hidden;">
				<div class="swiper-wrapper" style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;">
					<?php foreach($news as $new) { ?>
						<div class="swiper-slide swiper-slide-active">
							<div class="card shadow border-0 bg-template" style="<?php if($new['image_fon_c']) { ?> background-image: url(<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$new['id']?>_image_fon_c);background-repeat: no-repeat; background-size: cover;background-position: center; <?php } else {?> background:<?=$new['color_background_c']?>; <? } ?> height:130px;">
								<div class="card-body">
									<div class="row">
										<div class="col pr-0 align-self-center">
											<div class="mb-2 font-weight-normal" style="color:#fff;font-size:1.20rem;"><?=mb_strimwidth($new['name'],0, 20, "...")?></div>
											<p class="text-mute" style="color:#fff;"><?=mb_strimwidth(strip_tags(html_entity_decode($new['text_c'])),0, 25, "...")?></p>
										</div>
										<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news/<?=$new['id']?>" class="btn btn-default button-rounded-36 shadow-sm float-bottom-right"><i class="material-icons md-18">arrow_forward</i></a>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="swiper-slide" >
						<a class="text-decoration-none" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news?type=only_news">
							<div class="card shadow border-0 bg-template btn-default" style="height:130px;background:<?=App::$current_organization->main_color_c?>;width:130px;">
								<div class="card-body">
									<div class="row">
										<div class="col pr-0 align-self-center" style="margin:0px;padding:0px;">
											<center><div class="mb-2 font-weight-normal" style="color:#fff;font-size: 1.25rem;line-height: 1.2;">Смотреть <br> другие</div></center>
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

<?php include CORE_FOLDER.'/application/views/widget/statistics.php'; //блок статистики?>

<?php include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>

<script>
	// отрисовка/скрытие верхнего горизонтального меню
	$(window).scroll(function() {
		scroll();
	});
	
	$(window).on('load', function() {
		scroll();
	});
	
	function scroll(){
		var scroll = $(window).scrollTop();
		categoriesMainButtom = $(".categories-main").offset().top + $(".categories-main").outerHeight();
		if (scroll >= categoriesMainButtom) {
			$('.categories-icon').show();
			$('.categories_short_menu').show();
			$('.header').css('background', '<?=App::$current_organization->main_color_c?>');
			$('.header').addClass('shadow');
			$('#icon_menu').css('color', '#fff');
		} else {
			$('.categories-icon').hide();
			$('.categories_short_menu').hide();
			$('.header').css('background','');
			$('.header').removeClass('shadow');
			$('#icon_menu').css('color', '#000');
		}
	}
	
	//Скроллинг до объекта
	$(".scroll").click(function() {
		var scroll_id  = $(this).data("scroll"); // получем идентификатор блока из атрибута href
		var top = $('#scroll_to_'+scroll_id).position().top;
		$('body,html').animate({scrollTop: top-50}, 500); // анимируем переход к блоку, время: 800 мс
	});
</script>

<!-- swiper js -->
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
<!-- swiper -->