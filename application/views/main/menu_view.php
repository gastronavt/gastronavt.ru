<div id="loader" class="row no-gutters vh-100 loader-screen">
	<div class="col align-self-center text-white text-center">
		<img id="loader_image" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_loading_c" alt="Логотип загрузки заведения <?=App::$current_organization->name?>" style="max-width:350px;margin-bottom:50px;">
		<div class="font-weight-light">
			<? if(!App::$current_organization->hide_name_loading_c) {?>
				<div style="font-size: 2.5rem;"><?=App::$current_organization->name_rus_c?></div>
			<? } ?>
			<div class="laoderhorizontal">
				<div></div>
				<div></div>
				<div></div>
				<div></div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#loader_image').addClass('pulse');
	setTimeout(function(){
		$('#loader_image').removeClass('pulse');
		$('#loader').hide();
	},3000)
</script>

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

<div class="header">
	<div class="row no-gutters" style="height:50px;max-width:1230px;margin:0 auto;">
		<div class="col-10 col-xl-10 categories_short_menu" style="white-space: nowrap; overflow: auto;margin-top:9px;">
			<?php foreach($current_product_groups as $productGroup){ ?>
				<a class="category<?=$productGroup['id']?> mb-1 mt-0 h6 category_scroll" data-scroll="<?=$productGroup['id']?>" style="display:inline;text-decoration:none;color:#fff;padding:5px 15px 5px 15px;cursor:pointer;">
					<?=$productGroup['name']?>
				</a>
			<?php } ?>
		</div>
		<div class="col-2 col-xl-1 text-center categories-icon">
			<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_landing_logo_c" alt="логотип" class="header-logo" style="border-radius:30px;height:40px;">
		</div>
	</div>
</div>

<div class="container" style="padding-top:0px;">
	
	<?php include CORE_FOLDER.'/application/views/widget/category.php'; //Категории ?>
	
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
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/swiper/js/swiper-bundle.min.js"></script>
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
		<?php include CORE_FOLDER.'/application/views/widget/products.php'; //Продукты ?>
	<?php } ?>

	
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
			<div class="swiper-container news-slide swiper-container-horizontal" style="margin: 0 auto;position: relative;overflow: hidden;">
				<div class="swiper-wrapper" style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;">
					<?php foreach($news as $new) { ?>
						<div class="swiper-slide swiper-slide-active">
							<div class="card shadow border-0 bg-template" style="<?php if($new['image_fon_c']) { ?> background-image: url(<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$new['id']?>_image_fon_c);background-repeat: no-repeat; background-size: cover;background-position: center; <?php } else {?> background:<?=$new['color_background_c']?>; <? } ?> height:130px;">
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
</script>
