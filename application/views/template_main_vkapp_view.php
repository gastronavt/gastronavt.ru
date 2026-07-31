<!DOCTYPE HTML>
	<!--Для работы приложения в вк-->
	<script src="https://unpkg.com/@vkontakte/vk-bridge/dist/browser.min.js"></script>
	<script>
		vkBridge.send('VKWebAppInit');
	
		/*function $_GET(key) {
			var p = window.location.search;
			p = p.match(new RegExp(key + '=([^&=]+)'));
			return p ? p[1] : false;
		}
		
		if(!$_GET('unique_token').length){
			document.location='https://pryanikov38.ru/vkapp?unique_token=<?=session_id()?>';
		}
		console.log($_GET('unique_token'));*/
	  
		/*var unique_token = '';
	  
		vkBridge.send("VKWebAppStorageGet", {"keys": ["unique_token"]})
		  .then(data => {
			unique_token = data.keys[0].value;
			if(!unique_token.length){
				unique_token = "<?=session_id()?>";
				
				vkBridge.send("VKWebAppStorageSet", {
				   key: "unique_token",
				   value: unique_token
				});
			}
			
			console.log(unique_token);
		  })
		  .catch(error => {}
		);*/
		document.cookie = "test=111";
		console.log(document.cookie);
	</script>
	
	<?php

		//восстановление сессии
		if(!empty($_COOKIE['_ym_uid'])){
			session_destroy();
			$_COOKIE['PHPSESSID'] = $_COOKIE['_ym_uid'];
			session_id($_COOKIE['_ym_uid']);
			session_start();
		}
		
		print_rr($_SESSION);
	?>
	<!-- END Для работы приложения в вк -->
<html lang="ru-RU" class="pink-theme">
<head>
	<?php
		//Настройка Content-Security-Policy для защиты от баннеров и рекламы
		header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' winmon.ru *.winmon.ru crm.giveat.ru dump.video yandex.net *.yandex.net yastatic.net yandex.ru *.yandex.ru tlgur.com data: *.googleapis.com fonts.gstatic.com vk.com www.w3.org *.vk.com google-analytics.com *.google-analytics.com googletagmanager.com *.googletagmanager.com my.mail.ru *.facebook.net www.facebook.com unpkg.com");
	?>
	<!--Мета информация-->
	<title><?=$data['title']?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="HandheldFriendly" content="True">
	<meta name="description" content="<?=$data['description']?>">
	<meta name="keywords" content="<?=App::$current_landing->seo_keywords_c?>">
	
	<meta name="yandex-verification" content="5bbdf66d67c4949b" />
	<meta name="theme-color" content="<?=App::$current_organization->main_color_c?>">
	<link rel="shortcut icon" href="<?=NFfunctions::getSiteProtocol().'crm.winmon.ru/upload/'.App::$current_organization->id.'_image_icon_c'?>" type="image/x-icon">
	
	<?php if(!empty(App::$current_landing->pwa_c)) {?>
	<!--PWA-->
	<meta name="theme-color" content="#f57622">
	<meta name="apple-mobile-web-app-status-bar" content="#f57622">
	<link rel="apple-touch-icon" href="https://winmon.ru/pwa/icon/maskable_icon_x192.png">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="WINMON - доставка еды">
	<link rel="manifest" href="/pwa_manifest.php">
	<script src="./pwa_app.js"></script>
	<!--END PWA-->
	<?php } ?>
	
	
	<!--для твитера-->
	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="<?=App::$current_organization->name?> доставка <?=App::$current_city->name?> на дом"/>
	<meta name="twitter:title" content="<?=App::$current_organization->name?> <?=App::$current_city->name?> - доставка на дом!">
	<meta name="twitter:creator" content="<?=App::$current_organization->name?>"/>
	<meta name="twitter:image:src" content="<?=NFfunctions::getSiteProtocol()?>crm.winmon.ru/upload/<?=App::$current_organization->id?>_landing_logo_c"/>
	<meta name="twitter:domain" content="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST']?>"/>
	<meta http-equiv="Cache-Control" content="public">
	
	<meta property="og:title" content="<?=$data['title']?>" />
	<meta property="og:site_name" content="<?=App::$current_organization->name?> доставка <?=App::$current_city->name?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>" />
	<meta property="og:description" content="<?=App::$current_landing->seo_description_c?>" />
	<meta property="og:locale" content="ru_RU">
	<meta property="og:image" content="<?=NFfunctions::getSiteProtocol()?>crm.winmon.ru/upload/<?=App::$current_organization->id?>_landing_logo_c">
		
	<!--для вк-->
	<link rel="image_src" href="<?=NFfunctions::getSiteProtocol()?>crm.winmon.ru/upload/<?=App::$current_organization->id?>_landing_logo_c"/>
	<!--END Мета информация-->

	<!-- Стили -->
    <!-- Roboto fonts CSS -->
    <link media="none" onload="if(media!='all') media='all'" rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons&display=swap">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol().ASSETS_URL?>/assets_new/vendor/bootstrap-4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol().ASSETS_URL?>/assets_new/vendor/bootstrap-float-label/bootstrap-float-label.min.css?=9">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol().ASSETS_URL?>/assets_new/css/style.php?main_color=<?=App::$current_organization->main_color_c?>">
	<link media="none" onload="if(media!='all') media='all'" rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol().ASSETS_URL?>/assets_new/vendor/swiper/css/swiper.min.css">
	<!-- END Стили -->
	
	<!-- jquery, popper and bootstrap js -->
	<script src="<?=NFfunctions::getSiteProtocol().ASSETS_URL?>/assets_new/js/jquery-3.3.1.min.js?banner=off"></script>
	<script src="<?=NFfunctions::getSiteProtocol().ASSETS_URL?>/assets_new/js/jquery.maskedinput.min.js?banner=off"></script>
	<script src="<?=NFfunctions::getSiteProtocol().ASSETS_URL?>/assets_new/js/sweetalert.min.js?banner=off"></script>
</head>
<body>
	<?php
		if(App::$current_landing->vk_chat_c != NULL){
			echo html_entity_decode(App::$current_landing->vk_chat_c);
		}
	?>
	<? if(App::$current_landing->facebook_retarget_c != NULL){ ?> 
		<!-- Facebook Pixel Code -->
		<script>
			!function(f,b,e,v,n,t,s)
			{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};
			if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
			n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t,s)}(window, document,'script',
			'https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '<?=App::$current_landing->facebook_retarget_c?>');
			fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?=App::$current_landing->facebook_retarget_c?>&ev=PageView&noscript=1"/></noscript>
		<!-- End Facebook Pixel Code -->
	<? } ?>
	
	<div id="loader" class="row no-gutters vh-100 loader-screen">
        <div class="col align-self-center text-white text-center">
            <img src="<?=NFfunctions::getSiteProtocol()?>crm.winmon.ru/upload/<?=App::$current_organization->id?>_loading_c" alt="Логотип загрузки заведения <?=App::$current_organization->name?>" style="max-width:350px;">
            <h1><span class="font-weight-light"><?=App::$current_organization->name_rus_c?> </span></h1>
            <div class="laoderhorizontal">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
	<script>
		setTimeout(function() { $('#loader').hide() }, 3000)
	</script>
  
	<?php include CORE_FOLDER.'/application/views/vkapp/widget/right_menu.php'; //Основное меню ?>
       
	<?php
		$wrapper_background = 'rgb(237, 238, 240)';
		if(App::$current_organization->wrapper_color_c) {  
			$wrapper_background = App::$current_organization->wrapper_color_c; 
		}
	?>
    <div class="wrapper" style="background:<?=$wrapper_background?>" > 
		<?php 
			$router = App::getRouter();
			if( App::$send_404 ){
				include CORE_FOLDER.'/application/views/vkapp/404_view.php';
			} else{
				if( !$router ){
					return false;
				}
				$controller_dir = $router->getController();
				$template_name = $router->getMethodPrefix().$content_view;
				include CORE_FOLDER.'/application/views/'.$controller_dir.'/'.$template_name; 
			}
		?>
		
    </div>
	
	<div id="work_ajax" style="position:fixed;top:0; z-index:1000; width:100%; height:100%;background:#eee; opacity:0.4;display:none;"></div>
    <div id="modal_block"></div>
	
	<?php include CORE_FOLDER.'/application/views/vkapp/widget/callback.php'; //Обратная связь ?>
    
    <script async src="<?=NFfunctions::getSiteProtocol().ASSETS_URL?>/assets_new/vendor/bootstrap-4.3.1/js/bootstrap.min.js"></script>

    <!-- template custom js -->
    <script async src="<?=NFfunctions::getSiteProtocol().ASSETS_URL?>/assets_new/js/main.js"></script>

	<?php include CORE_FOLDER.'/application/views/vkapp/widget/cities.php'; //выбор города ?>

	<script>
		$(function() {
			//анимация перемещения в корзину
			$(document).on('click', '.btn-add-product', function(e) {
				id = $(this).attr("data-product-id");
				$(".product_image_"+id).first()
					.clone()
					.css({'position' : 'absolute', 'border-radius': '.25rem','width': '200', 'min-width': '', 'min-height': '', 'z-index' : '1060', top: $('.product_image_'+id).first().offset().top, left:$('.product_image_'+id).first().offset().left})
					.appendTo("body")
					.animate({opacity: 0.5,
						left: $(".basket").offset()['left'],
						top: $(".basket").offset()['top'],
						width: 50}, 1000, function() {
						$(this).remove();
					});
			});
		});
		
	</script>


	<script>
		function disableCard(){
			$("#work_ajax").show();
		}
		
		function enadleCard(){
			$("#work_ajax").hide();
		}
		
		
		//При нажатии кнопки Добавить в корзину
		$(document).on('click', '.btn-add-product', function(e) {
			var product_id = $(this).data("product-id");
			var product_count = parseInt($('.product_count'+product_id).first().val()) + 1;//увеличиваем количество продуктов на 1
			var product_image = $('.product_image_'+product_id).first().attr('src');
			var product_name = $('.product_name_'+product_id).first().text();
			var product_price = parseInt($('.product_price_'+product_id).first().text());
			
			disableCard();
			$.getJSON("/basket/add_product/"+product_id, function(data) {
				enadleCard();
			});
			
			$(".product_count"+product_id).val(product_count);
			
			var sale_all = (parseInt($(".sale_all").text())+parseInt(product_price))+" ₽";
			$(".sale_all").text(sale_all);//общая сумма заказа
			
			if(product_count == 1){
				$(this).text('+');
				$(this).css('width' , '70px');
				$(this).parent().prepend('<button type="button" class="btn btn-primary btn-minus-product" data-product-id="'+product_id+'" style="width: 70px;background:<?=App::$current_organization->color_product_btn_c?>;">-</button>');
			}
			
			
			var product_html = 
				'<li class="list-group-item" id="card-product'+product_id+'">'+
					'<input type="hidden" name="product_id[]" value="'+product_id+'">'+
					'<div class="row">'+
						'<div class="col-2 pl-0 align-self-center">'+
							'<img class="product-image h-auto product_image_'+product_id+'" src="'+product_image+'">'+
						'</div>'+
						'<div class="col-4 px-0 align-self-center">'+
							'<a href="/vkapp/product/'+product_id+'" class="mb-1 h6 d-block product_name_'+product_id+'">'+product_name+'</a>'+
							'<h5 class="text-success font-weight-normal product_price_'+product_id+'">'+product_price+' ₽</h5>'+
						'</div>'+
						'<div class="col-6 align-self-center">'+
							'<div class="input-group input-group-sm" style="margin-left:20px;">'+
								'<div class="input-group-prepend">'+
									'<button class="btn px-1 btn-minus-product" type="button" data-product-id="'+product_id+'" style="background:<?=App::$current_organization->color_product_btn_c?>;color:#fff;">'+
										'<i class="material-icons">remove</i>'+
									'</button>'+
								'</div>'+
								'<input name="count[]" type="text" class="btn product_count'+product_id+'" style="color:#fff;width:45px;border:none;border-radius:0;background:<?=App::$current_organization->color_product_btn_c?>;" value="'+product_count+'" readonly>'+
								'<div class="input-group-append">'+
									'<button class="btn px-1 btn-add-product" data-product-id="'+product_id+'" style="background:<?=App::$current_organization->color_product_btn_c?>;color:#fff;">'+
										'<i class="material-icons">add</i>'+
									'</button>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</li>';
				
			//имеется ли в корзине уже такой продукт?
			if($("li").is('#card-product'+product_id)){ // если да, заменяем
				$('#card-product'+product_id).replaceWith(product_html);
			}else{ //если нет, создаем
				$('.list-group-flush').append(product_html);
			}
			
			//проставляем общее количество продуктов в корзине
			var count_products = 0;
			$('[name="count[]"]').each(function() {
				count_products += parseInt($(this).val());
			});
			$('#count_products').text(count_products);
			$('#void_card').hide(); //скрываем блок - Ой, пусто
			$('#checkout').show(); //отображаем кнопку- Заказать
		});
		
		//При нажатии кнопки Убрать из корзины
		$(document).on('click', '.btn-minus-product', function(e) {
			var product_id = $(this).data("product-id");
			var product_count = parseInt($('.product_count'+product_id).first().val()) - 1;//уменьшаем количество продуктов на 1
			var product_price = parseInt($('.product_price_'+product_id).first().text());
			
			disableCard();
			$.getJSON("/basket/remove_product/"+product_id, function(data) {
				enadleCard();
			});
			
			$(".product_count"+product_id).val(product_count);//уменьшаем количество конкретного продукта
			
			var sale_all = (parseInt($(".sale_all").text())-product_price)+" ₽";
			$(".sale_all").text(sale_all);//общая сумма заказа
			
			if(product_count == 0 ){
				$('#card-product'+product_id).remove();//удаляем продукт из блока корзины 
				$('.btn-minus-product[data-product-id="'+product_id+'"]').remove();//удаляем кнопку минус
				$('.btn-add-product[data-product-id="'+product_id+'"]').text('Добавить');//Кнопку + переименовываем в ДОБАВИТЬ
				$('.btn-add-product[data-product-id="'+product_id+'"]').css('width','');//Удаляем у кнопки + ширину в 90px
			}
			
			//проставляем общее количество продуктов в корзине
			var count_products = 0;
			$('[name="count[]"]').each(function() {
				count_products += parseInt($(this).val());
			});
			$('#count_products').text(count_products);
			if($('#count_products').text() == 0){
				$('#void_card').show(); //отображаем блок - Ой, пусто
				$('#checkout').hide(); //скрываем кнопку- Заказать
			}
		});
		
		//При нажатии кнопки Добавить в корзину с рефрешем
		$(document).on('click', '.btn-add-product-reload', function(e) {
			var product_id = $(this).data("product-id");
			var source = $(this).data("source");
			$.getJSON("/basket/add_product/"+product_id+"/"+source, function(data) {
				enadleCard();
				if(data != null){
					window.location.reload();
				}
			});
		});
		
		
	</script>

	<!-- Ленивая загрузка изображений -->
	<script>
		var lazy = [];

		registerListener('load', setLazy);
		registerListener('load', lazyLoad);
		registerListener('scroll', lazyLoad);
		registerListener('resize', lazyLoad);

		function setLazy(){  
			lazy = document.getElementsByClassName('lazy');
			//console.log('Found ' + lazy.length + ' lazy images');
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

	<!--Для работы приложения в вк-->
	<script src="https://unpkg.com/@vkontakte/vk-bridge/dist/browser.min.js"></script>
	<script>
	  // Sends event to client
	  vkBridge.send('VKWebAppInit');
	</script>
	<!-- END Для работы приложения в вк -->

	<!-- Scripts -->

<? if(App::$current_landing->google_tag_manager_c != NULL){ ?> 
	<!-- Google Tag Manager -->
	<script>
		(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?=App::$current_landing->google_tag_manager_c?>');
	</script>

	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?=App::$current_landing->google_tag_manager_c?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
<? } ?>

<?=NFfunctions::decodeString(App::$current_landing->seo_google_metrika_c)?>

<?=NFfunctions::decodeString(App::$current_landing->seo_retarget_c)?>

<?=NFfunctions::decodeString(App::$current_landing->seo_yandex_metrika_c)?>
<!-- Scripts -->
</body>

</html>