<?php
global $db;
$current_agregator = $db->fetchRow($db->query("
	SELECT aa.*, aa_cstm.*
	FROM agrr_aggregator aa 
	JOIN agrr_aggregator_cstm aa_cstm ON aa.id = aa_cstm.id_c AND aa.deleted = 0
	JOIN agrr_aggregator_lngng_landings_1_c aa_ll ON aa_ll.agrr_aggregator_lngng_landings_1agrr_aggregator_ida = aa.id AND aa_ll.deleted = 0
	WHERE aa_ll.agrr_aggregator_lngng_landings_1lngng_landings_idb = '".App::$current_landing->id."'
	LIMIT 1;
"));
  
$_SESSION['l'] = 'pwa_winmon';

?>

<!DOCTYPE HTML>
<html lang="ru-RU" class="pink-theme">
<head>
	<!--Мета информация-->
	<title>WINMON - Доставка еды в вашем городе</title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover, user-scalable=no">
    <meta name="description" content="<?=$current_agregator['seo_description_c']?>">
    <meta name="author" content="winmon">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="HandheldFriendly" content="True">
	<meta name="keywords" content="<?=$current_agregator['seo_keywords_c']?>">
	
	<meta name="yandex-verification" content="5bbdf66d67c4949b" />
	<link rel="shortcut icon" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/kotik.png" type="image/x-icon">

	<!-- Material design icons CSS -->
    <link media="none" onload="if(media!='all') media='all'" rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Roboto fonts CSS -->
    <link media="none" onload="if(media!='all') media='all'" rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">

	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/css/style.php?main_color=rgb(245, 118, 34)">
	<link media="none" onload="if(media!='all') media='all'" rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/swiper/css/swiper.min.css">
	
	<!-- jquery, popper and bootstrap js -->
    <script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/jquery-3.3.1.min.js"></script>
</head>

<body>
	
	<div class="row no-gutters vh-100 loader-screen">
        <div class="col align-self-center text-white text-center">
            <img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/kooot.png" alt="logo" style="max-width:350px;">
            <h1>
				<span class="font-weight-light">
					WINMON 
					<div style="font-size:12pt;"><?=App::$current_city->name?></div>
					<div style="font-size:22pt;">доставка еды</div>
				</span>
			</h1>
            <div class="laoderhorizontal">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
  
	<div class="sidebar">
        <div class="text-center">
            <div class="figure-menu shadow">
                <figure><img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/kotik.png" alt="Котик WinGo"></figure>
            </div>
            <h5 class="mb-1" style="color:#fff;">WINMON</h5>
            <p class="text-mute">Доставка вкусной еды</br><?=App::$current_city->name?></p>
			<div style="mt-3">
				<? if($current_agregator['vk_social_c']) { ?>
				<a target="_blank" class="mr-2" href="<?=$current_agregator['vk_social_c']?>">
					<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/vk.png" style="width:35px;" alt="VK" title="Данная картинка является ссылкой на группу Вконтакте">
				</a>
				<? } if($current_agregator['insta_social_c']) { ?>
				<a target="_blank" href="<?=$current_agregator['insta_social_c']?>">
					<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/insta.png" style="width:35px;" alt="Instagram" title="Данная картинка является ссылкой на аккаунт Instagram">
				</a>
				<? } ?>
			</div>
        </div>
        <br>
        <div class="row mx-0">
            <div class="col">
                <h5 class="subtitle text-uppercase"></h5>
                <div class="list-group main-menu">
                    <a href="/" class="list-group-item list-group-item-action active">Главная/Заведения</a>
                    <!--<a href="notification.html" class="list-group-item list-group-item-action">Оповещения <span class="badge badge-dark text-white">2</span></a>-->
                    <a href="/main/news" class="list-group-item list-group-item-action">Новости</a>
                    <!--<a href="profile.html" class="list-group-item list-group-item-action">Мой профиль</a>
                    <a href="controls.html" class="list-group-item list-group-item-action">Контроль качества <span class="badge badge-light ml-2">Check</span></a>
                    <a href="setting.html" class="list-group-item list-group-item-action">Настройки</a>
                    <a href="login.html" class="list-group-item list-group-item-action mt-4">Выход</a>-->
                </div>
            </div>
        </div>

    </div>
       
    <div class="wrapper"> 
		
		
		
		
		
		
		
		
		
		
		
		

<div class="header" style="background:linear-gradient(to right bottom, #da5f00, #e36605, #ec6d0b, #f67511, #ff7c16);">
	<div class="row no-gutters" style="height:50px;max-width:1230px;margin:0 auto;">
		<div class="col-auto">
			<i class="material-icons menu-btn" style="font-size:26pt; padding-top:7px;padding-left:10px;cursor:pointer;">menu</i>
		</div>
		<div class="col text-center categories-icon"  style="padding-top:10px;">
			<h1 class="text-white" style="font-size:19pt;width:100%;display:inline;">WINMON</h1>
			<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/kotik.png" alt="WINMON-логотип" class="header-logo" style="border-radius:30px;height:40px;margin-top:-5px;">
		</div>
	</div>
</div>
	
<div class="container">
	<script>
	<!-- Отрисовка БАННЕРОВ по ширине окна -->
	$(window).on('load', function () {
		if (window.matchMedia("(min-width: 768px)").matches) {
			$('.banner_desctop').show();
			$('.banner_modile').hide();
		} else {
			$('.banner_desctop').hide();
			$('.banner_modile').show();
		}
	});
	window.addEventListener("resize", function() {
		if (window.matchMedia("(min-width: 768px)").matches) {
			$('.banner_desctop').show();
			$('.banner_modile').hide();
		} else {
			$('.banner_desctop').hide();
			$('.banner_modile').show();
		}
	});
	</script>
		
	<?php 
		global $db;
		$querySliders = $db->query("
			SELECT ssa.id, ssa.name
			FROM slagr_slider_agregator ssa
			LEFT JOIN slagr_slider_agregator_cstm ssa_cstm ON ssa_cstm.id_c = ssa.id AND ssa.deleted = 0
			LEFT JOIN agrr_aggregator_slagr_slider_agregator_1_c aa_ssa ON aa_ssa.agrr_aggre63f8regator_idb = ssa_cstm.id_c AND aa_ssa.deleted = 0
			WHERE aa_ssa.agrr_aggregator_slagr_slider_agregator_1agrr_aggregator_ida = '".$current_agregator['id']."'
			ORDER BY ssa_cstm.show_order_c;
		");
	?>
	<div class="swiper-container banner-swiper triger-top-menu" style="padding-top:20px;">
		<div class="swiper-wrapper">
			<?php while($slider = $db->fetchByAssoc($querySliders)) {?>
			<div class="swiper-slide" style="height: auto;">
				<a href="">
					<img class="img banner_desctop" style="width:100%;border-radius:15px;" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$slider['id']?>_image_c" alt="<?=$slider['name']?>" />
					<img class="img banner_modile" style="width:100%;border-radius:15px;" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$slider['id']?>_mobile_image_c" alt="<?=$slider['name']?>" />
				</a>
			</div>
			<?php } ?>
		</div>
		<div class="swiper-button-prev"></div>
		<div class="swiper-button-next"></div>
		<div class="swiper-pagination"></div>
	</div>

	<div class="input-group my-4">
		<div class="input-group-prepend">
			<span class="input-group-text"><i class="material-icons">location_on</i> Ваш город</span>
		</div>
		<select class="custom-select col" onchange="top.location=this.value" style="border-radius: 0 30px 30px 0;">
			<? 
			global $db;
			$queryAgregator = $db->query("
				SELECT aa_cstm.link_c as aggregator_link , cc.name as city_name
				FROM agrr_aggregator aa 
				JOIN agrr_aggregator_cstm aa_cstm ON aa_cstm.id_c = aa.id AND aa.deleted = 0
				JOIN city_cities_agrr_aggregator_1_c cc_aa ON cc_aa.city_cities_agrr_aggregator_1agrr_aggregator_idb = aa_cstm.id_c AND cc_aa.deleted = 0
				JOIN city_cities cc ON cc.id = cc_aa.city_cities_agrr_aggregator_1city_cities_ida AND cc.deleted = 0
				ORDER BY cc.name;
			");
			$agregators = [];
			
			while($agregator = $db->fetchByAssoc($queryAgregator)) {	
			?>
				<option value="<?=NFfunctions::getSiteProtocol().parse_url($agregator['aggregator_link'])['host']?>" <? if($agregator['city_name'] == App::$current_city->name) { ?> selected  <? } ?>><?=$agregator['city_name']?></option>
			<? } ?>
		</select>
	</div>
	
	<? 
		global $db;
		$queryOffers = $db->query("
			SELECT nn.*, nn_cstm.*, ll_cstm.link_c
			FROM agrr_aggregator aa 
			JOIN agrr_aggregator_lngng_landings_1_c aa_ll ON aa_ll.agrr_aggregator_lngng_landings_1agrr_aggregator_ida = aa.id AND aa_ll.deleted = 0 AND aa.deleted = 0
			JOIN lngng_landings_news_news_1_c ll_nn ON ll_nn.lngng_landings_news_news_1lngng_landings_ida = aa_ll.agrr_aggregator_lngng_landings_1lngng_landings_idb AND ll_nn.deleted = 0
			JOIN lngng_landings_cstm ll_cstm ON ll_cstm.id_c = ll_nn.lngng_landings_news_news_1lngng_landings_ida
			JOIN news_news nn ON nn.id = ll_nn.lngng_landings_news_news_1news_news_idb AND nn.deleted = 0
			JOIN news_news_cstm nn_cstm ON nn_cstm.id_c = nn.id
			WHERE 
				aa.id = '".$current_agregator['id']."'
				AND nn_cstm.type_c LIKE '%^02^%'
			ORDER BY nn.date_entered
			LIMIT 5;
		");
		$offers = [];
		while($offer = $db->fetchByAssoc($queryOffers)) {	
			$offers[] = $offer;
		}
		if(!empty($offers)){
	?>
	<h6 class="subtitle">Акции в городе <?=App::$current_city->name?></h6>
	<div class="row">
		<div class="container px-0">
			<div class="swiper-container offer-slide swiper-container-horizontal">
				<div class="swiper-wrapper" style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;">
					<?php foreach($offers as $offer) { ?>
					<div class="swiper-slide" >
						<div class="card shadow border-0 bg-template" style="height:130px; <?php if($offer['image_fon_c']) { ?> background-image: url(<?=NFfunctions::getSiteProtocol()?>crm.winmon.ru/index.php?entryPoint=download&id=<?=$offer['id']?>_image_fon_c&type=news_news);background-repeat: no-repeat; background-size: cover;background-position: center; <?php } else {?> background:<?=$offer['color_background_c']?>; <? } ?>">
							<div class="card-body">
								<div class="row">
									<div class="col pr-0 align-self-center">
										<h5 class="mb-2 font-weight-normal" style="color:<?=$offer['color_text_c']?>;font-size: 1.20rem;"><?=mb_strimwidth($offer['name'],0, 25, "...")?></h5>
										<p class="text-mute" style="color:<?=$offer['color_text_c']?>;width:210px;"><?=mb_strimwidth(strip_tags(html_entity_decode($offer['text_c'])),0, 60, "...")?></p>
									</div>
									<a href="<?=$offer['link_c']?>/main/news/<?=$offer['id']?>" class="btn btn-default button-rounded-36 shadow-sm float-bottom-right"><i class="material-icons md-18">arrow_forward</i></a>
								</div>
								
							</div>
						</div>
					</div>
					<?php } ?>
					<div class="swiper-slide" >
						<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news" style="text-decoration:none;">
							<div class="card shadow border-0 bg-template" style="height:130px;width:130px;">
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
	
	<!-- swiper js -->
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/swiper/js/swiper.min.js"></script>
	
	<!-- swiper -->
	<script>
		$(window).on('load', function() {

			var swiper = new Swiper('.small-slide', {
				slidesPerView: 'auto',
				spaceBetween: 0,
				loop: false,//зацикливание
			});

			var swiper = new Swiper('.offer-slide', {
				slidesPerView: 'auto',
				spaceBetween: 0,
				loop: false,//зацикливание
				autoplay: {
					delay: 3000,
					disableOnInteraction: true,
				},
			});
 
			var swiper = new Swiper('.news-slide', {
				slidesPerView: 5,
				spaceBetween: 0,
				breakpoints: {
					1024: {
						slidesPerView: 4,
						spaceBetween: 0,
					},
					768: {
						slidesPerView: 3,
						spaceBetween: 0,
					},
					640: {
						slidesPerView: 2,
						spaceBetween: 0,
					},
					320: {
						slidesPerView: 2,
						spaceBetween: 0,
					}
				}
			});
			var swiper_slider = new Swiper('.banner-swiper', {
				effect: 'coverflow',
				spaceBetween: 10,
				speed: 2000,
		
				slidesPerView: 1,
				centeredSlides: true,
				loop: true,
				autoplay: {
					delay: 8000,
					disableOnInteraction: true,
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
			});
		});
	</script>
	
	<h3 style="text-align:center;width:100%;color:#364EB5;margin-top: 20px; margin-bottom:60px;">Все заведения</h3>
	<div class="row">
		<?php
			global $db;
			$queryOrgns = $db->query("
				SELECT 
					oo.*,
					oo_cstm.*,
					ll_cstm.rating_c as landing_rating,
					ll_cstm.link_c as landing_link,
					ll_cstm.delivery_min_order_c as landing_delivery_min_order,
					ll_cstm.sorting_c as sorting
				FROM lngng_landings ll 
				JOIN lngng_landings_cstm ll_cstm ON ll_cstm.id_c = ll.id AND ll.deleted = 0
				JOIN agrr_aggregator_lngng_landings_1_c aa_ll ON aa_ll.agrr_aggregator_lngng_landings_1lngng_landings_idb = ll_cstm.id_c AND aa_ll.agrr_aggregator_lngng_landings_1agrr_aggregator_ida = '".$current_agregator['id']."' AND aa_ll.deleted = 0
				JOIN orgns_organizations_lngng_landings_1_c oo_ll ON oo_ll.orgns_organizations_lngng_landings_1lngng_landings_idb = ll_cstm.id_c AND oo_ll.deleted = 0
				JOIN orgns_organizations oo ON oo.id = oo_ll.orgns_organizations_lngng_landings_1orgns_organizations_ida AND oo.deleted = 0
				JOIN orgns_organizations_cstm oo_cstm ON oo_cstm.id_c = oo.id
				WHERE ll_cstm.status_c = '01'
				ORDER BY ll_cstm.sorting_c ASC
			");
			while($organization = $db->fetchByAssoc($queryOrgns)) {
				if(strpos($organization['landing_link'], 'winmon.ru') !== false) {
					$landing_link = NFfunctions::getSiteProtocol().parse_url($organization['landing_link'])['host'];
				} else {
					$landing_link = $organization['landing_link'];
				}
				$landing_link .= '?referer='.NFfunctions::getSiteProtocol().parse_url($current_agregator['link_c'])['host'];
				if(!empty($_SESSION['l'])){
					$landing_link .= '&l='.$_SESSION['l'];
				}
		?>
			<a href="<?=$landing_link?>" class="col-12 col-md-6 col-lg-4 col-xl-4 mb-4">
				<div class="card shadow-sm mb-4 organization" style="cursor:pointer;border-radius: .25rem;">
					<div class="card-body" style="height:280px;">
						<div style="position:absolute;top:0px;left:0;">
							<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$organization['id'].'_landing_background_c'?>" alt="" style="width:100%;height:150px;padding:10px; border-radius:15px;">
						</div>
						<div class="card-avatar" style="width:150px;height:150px;position:relative;z-index:2;background: #fff;margin: -50px auto 0;border-radius: 50%;overflow: hidden; box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.42), 0 4px 25px 0px rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 0, 0, 0.2); border:6px solid #fff;">
							<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$organization['id']?>_image_organization_c" style="width: 100%;height: 100%;border-style: none;">
						</div>
						<div style="background:#FFC014;position: absolute; padding:3px;border-radius: 4px 0;top:109px;right:10px;font-size:8pt;font-weight:bold;color:#000;border-top:3px #fff solid;border-left:3px #fff solid;">Заказ от <?=$organization['landing_delivery_min_order']?> ₽</div>
						<div style="position:absolute;width:100%;top:100px;left:10px;padding:10px;">
							<div class="badge badge-success mt-1">Акции</div>
						</div>
						<div style="position:absolute;left:0;top:160px;width:100%;padding:0 10px;border-radius:0 0 15px 15px;max-height:65px;">
							<center>
								<h6 class="mb-0" style="font-weight:bold;font-size:18px;color:rgb(245, 118, 34);"><?=$organization['name_rus_c']?></h6>
								<? if($organization['name_rus_c'] != $organization['name'] ) { ?>
									<div style="font-size:13px;color:rgb(245, 118, 34);"><?=$organization['name']?></div>
								<? } ?>
							</center>
							<div class="text-secondary small mb-2" style="background-color: #fff;color: #000;padding: 5px 10px;line-height:1.4;"><span><?=mb_strimwidth($organization['description'], 0, 88, "...");?></span></div>
						</div>
						<div style="position:absolute;bottom:10px;right:15px;">
							<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/rating.png" style="width:25px;"><span style="font-weight:bold;font-size:14px;margin-left:3px;">4.5<span>
						</div>
					</div>
				</div>
			</a>
		<?php 
			} 
		?>
	</div>
	
	<?php
		global $db;
		$queryOrgns = $db->query("
			SELECT 
				oo.*,
				oo_cstm.*,
				ll_cstm.rating_c as landing_rating,
				ll_cstm.link_c as landing_link,
				ll_cstm.delivery_min_order_c as landing_delivery_min_order,
				ll_cstm.sorting_c as sorting
			FROM lngng_landings ll 
			JOIN lngng_landings_cstm ll_cstm ON ll_cstm.id_c = ll.id AND ll.deleted = 0
			JOIN agrr_aggregator_lngng_landings_1_c aa_ll ON aa_ll.agrr_aggregator_lngng_landings_1lngng_landings_idb = ll_cstm.id_c AND aa_ll.agrr_aggregator_lngng_landings_1agrr_aggregator_ida = '".$current_agregator['id']."' AND aa_ll.deleted = 0
			JOIN orgns_organizations_lngng_landings_1_c oo_ll ON oo_ll.orgns_organizations_lngng_landings_1lngng_landings_idb = ll_cstm.id_c AND oo_ll.deleted = 0
			JOIN orgns_organizations oo ON oo.id = oo_ll.orgns_organizations_lngng_landings_1orgns_organizations_ida AND oo.deleted = 0
			JOIN orgns_organizations_cstm oo_cstm ON oo_cstm.id_c = oo.id
			WHERE ll_cstm.status_c != '01'
			ORDER BY ll_cstm.sorting_c ASC
		");
		$organizations = [];
		while($organization = $db->fetchByAssoc($queryOrgns)) {
			$organizations[] = $organization;
		}
		if($organizations){
	?>
	<h3 style="text-align:center;width:100%;color:#364EB5;margin-top: 20px; margin-bottom:60px;">Временно не работают</h3>
	<div class="row">
		<?php
			foreach($organizations as $organization) {
		?>
			<a class="col-12 col-md-6 col-lg-4 col-xl-4 mb-4">
				<div class="card shadow-sm border-0 mb-4 " style="position:absolute;background: rgba(0, 0, 0, 0.8);z-index:2; height:280px;;width:93%;">
				</div>
				<div class="card shadow-sm mb-4" style="cursor:pointer;border-radius: .25rem;">
					<div class="card-body" style="height:280px;">
						<div style="position:absolute;top:0px;left:0;">
							<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$organization['id'].'_landing_background_c'?>" alt="" style="width:100%;height:150px;padding:10px; border-radius:15px;">
						</div>
						<div class="card-avatar" style="width:150px;height:150px;position:relative;z-index:2;background: #fff;margin: -50px auto 0;border-radius: 50%;overflow: hidden; box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.42), 0 4px 25px 0px rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 0, 0, 0.2); border:6px solid #fff;">
							<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$organization['id']?>_image_organization_c" style="width: 100%;height: 100%;border-style: none;">
						</div>
						<div style="background:#FFC014;position: absolute; padding:3px;border-radius: 4px 0;top:109px;right:10px;font-size:8pt;font-weight:bold;color:#000;border-top:3px #fff solid;border-left:3px #fff solid;">Заказ от <?=$organization['landing_delivery_min_order']?> ₽</div>
						<div style="position:absolute;width:100%;top:100px;left:10px;padding:10px;">
							<div class="badge badge-success mt-1">Акции</div>
						</div>
						<div style="position:absolute;left:0;top:160px;width:100%;padding:0 10px;border-radius:0 0 15px 15px;max-height:65px;">
							<center>
								<h6 class="mb-0" style="font-weight:bold;font-size:18px;color:rgb(245, 118, 34);"><?=$organization['name_rus_c']?></h6>
								<? if($organization['name_rus_c'] != $organization['name'] ) { ?>
									<div style="font-size:13px;color:rgb(245, 118, 34);"><?=$organization['name']?></div>
								<? } ?>
							</center>
							<div class="text-secondary small mb-2" style="background-color: #fff;color: #000;padding: 5px 10px;line-height:1.4;"><span><?=mb_strimwidth($organization['description'], 0, 88, "...");?></span></div>
						</div>
						<div style="position:absolute;bottom:10px;right:15px;">
							<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/rating.png" style="width:25px;"><span style="font-weight:bold;font-size:14px;margin-left:3px;">4.5<span>
						</div>
					</div>
				</div>
			</a>
		<?php 
			} 
		?>
	</div>
	<?php 
		} 
	?>
</div>
<div class="container-fluid bg-warning text-white my-3 info-block">
	<div class="row" style="background:linear-gradient(to right bottom, #da5f00, #e36605, #ec6d0b, #f67511, #ff7c16);">
		<div class="container">
			<div class="row py-4">
				<div class="col">
					<p class="mb-3">
						<?=html_entity_decode($current_agregator['information_c'])?>
					</p>
				</div>
				<div class="col-5 col-md-3 col-lg-2 col-xl-2">
					<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/vacancy/01.png" alt="" class="mw-100 mt-3">
				</div>
				
			</div>
		</div>
	</div>
</div>
<div class="container">
	<h6 class="subtitle">Вы ищите:</h6>
	<div class="row">
		<div class="col">
			<button class="btn btn-lg btn-light btn-rounded shadow-xs my-1 mr-2">доставка еды <?=App::$current_city->name?></button>
			<button class="btn btn-lg btn-light btn-rounded shadow-xs my-1 mr-2">Суши <?=App::$current_city->name?></button>
			<button class="btn btn-lg btn-light btn-rounded shadow-xs my-1 mr-2">доставка пиццы <?=App::$current_city->name?></button>
			<button class="btn btn-lg btn-light btn-rounded shadow-xs my-1 mr-2"><?=App::$current_city->name?> доставка</button>
			<button class="btn btn-lg btn-light btn-rounded shadow-xs my-1 mr-2">суши роллы на дом</button>
			<button class="btn btn-lg btn-light btn-rounded shadow-xs my-1 mr-2">wok <?=App::$current_city->name?></button>
			<button class="btn btn-lg btn-light btn-rounded shadow-xs my-1 mr-2">шашлык <?=App::$current_city->name?></button>
		</div>
	</div>
</div>
<div class="container mb-3">
	<div class="row">
		<div class="col text-center">
			<h5 class="subtitle mb-1">Наши социальные сети</h5>
			<p class="text-secondary">Там раздаем промо-коды :)</p>
		</div>
	</div>
	<div class="row text-center mt-4">
		<? if($current_agregator['vk_social_c']) { ?>
		<div class="col-6">
			<a href="<?=$current_agregator['vk_social_c']?>" style="text-decoration:none;">
				<div class="card shadow-sm border-0 mb-4">
					<div class="card-body">
						<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/vk.png" style="width:35px;" alt="VK" title="Данная картинка является ссылкой на группу Вконтакте">
						<h2>14 000 подписчиков</h2>
						<p class="text-secondary text-mute">Жми и подпишись!</p>
					</div>
				</div>
			</a>
		</div>
		<? } ?>
		<? if($current_agregator['insta_social_c']) { ?>
		<div class="col-6">
			<a href="<?=$current_agregator['insta_social_c']?>" style="text-decoration:none;">
				<div class="card shadow-sm border-0 mb-4">
					<div class="card-body">
						<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/insta.png" style="width:35px;" alt="Instagram" title="Данная картинка является ссылкой на аккаунт Instagram">
						<h2>18 000 подписчиков</h2>
						<p class="text-secondary text-mute">Жми и подпишись!</p>
					</div>
				</div>
			</a>
		</div>
		<? } ?>
	</div>
</div>

<script>
$('.js-click-modal').click(function(){
  $('.container-category').addClass('modal-open');
});

$('.js-close-modal').click(function(){
  $('.container-category').removeClass('modal-open');
});
</script>

<script>
		// отрисовка/скрытие меню
		$(window).scroll(function() {
			scroll();
		});
		
		$(window).on('load', function() {
			scroll();
		});
		
		function scroll(){
			var scroll = $(window).scrollTop();
			categoriesMainButtom = $(".triger-top-menu").offset().top + $(".triger-top-menu").outerHeight();
			if (scroll >= categoriesMainButtom) {
				$(".categories-icon").show();
				$(".header").css('background', 'linear-gradient(to right bottom, #da5f00, #e36605, #ec6d0b, #f67511, #ff7c16)');
			} else {
				$(".categories-icon").hide();
				$(".header").css('background','');
			}
		}

</script>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
    </div>
	
	<div id="work_ajax" style="position:fixed;top:0; z-index:1000; width:100%; height:100%;background:#eee; opacity:0.4;display:none;"></div>
    
<!-- Ленивая загрузка изображений -->
<script>
	var lazy = [];

	registerListener('load', setLazy);
	registerListener('load', lazyLoad);
	registerListener('scroll', lazyLoad);
	registerListener('resize', lazyLoad);

	function setLazy(){  
		lazy = document.getElementsByClassName('lazy');
		console.log('Found ' + lazy.length + ' lazy images');
	} 

	function lazyLoad(){
		for(var i=0; i<lazy.length; i++){
			if(isInViewport(lazy[i])){
				if (lazy[i].getAttribute('data-src')){
					lazy[i].src = lazy[i].getAttribute('data-src');
					lazy[i].removeAttribute('data-src');
				}
			}
		}
		
		cleanLazy();
	}

	function cleanLazy(){
		lazy = Array.prototype.filter.call(lazy, function(l){ return l.getAttribute('data-src');});
	}

	function isInViewport(el){
		var rect = el.getBoundingClientRect();
		
		return (
			rect.bottom >= 0 && 
			rect.right >= 0 && 
			rect.top <= (window.innerHeight || document.documentElement.clientHeight) && 
			rect.left <= (window.innerWidth || document.documentElement.clientWidth)
		 );
	}

	function registerListener(event, func) {
		if (window.addEventListener) {
			window.addEventListener(event, func)
		} else {
			window.attachEvent('on' + event, func)
		}
	}
</script>
<!-- END Ленивая загрузка изображений -->
    
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/sweetalert.min.js"></script>
    
    <script async src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-4.3.1/js/bootstrap.min.js"></script>

    <!-- template custom js -->
    <script async src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/main.js"></script>
</body>

</html>