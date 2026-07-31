<!DOCTYPE HTML>
<html lang="ru-RU">
<head>
	<?php
		//Настройка Content-Security-Policy для защиты от баннеров и рекламы
		header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' blob: winmon.ru *.winmon.ru dump.video yandex.net lunarlogic.github.io *.yandex.net yastatic.net yandex.ru *.yandex.ru tlgur.com data: *.googleapis.com fonts.gstatic.com vk.com www.w3.org *.vk.com google-analytics.com *.google-analytics.com googletagmanager.com *.googletagmanager.com my.mail.ru *.facebook.net www.facebook.com unpkg.com *.callibri.ru top-fwz1.mail.ru");
	?>
	<!--Мета информация-->
	<title><?=$data['title']?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, viewport-fit=cover">

    <meta name="HandheldFriendly" content="True">
	<meta name="description" content="<?=$data['description']?>">
	<meta name="keywords" content="<?=App::$current_landing->seo_keywords_c?>">
	
	<meta name="yandex-verification" content="5bbdf66d67c4949b" />
	<meta name="theme-color" content="<?=App::$current_organization->main_color_c?>">
	
	<link rel="shortcut icon" type="image/png" href="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.App::$current_organization->id.'_image_icon_c'?>" sizes="32x32" />
	<?php if(!empty(App::$current_organization->icon_16x16_c)) {?>
	<link rel="icon" type="image/png" href="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.App::$current_organization->id.'_icon_16x16_c'?>" sizes="16x16" />
	<?php } ?>
	
	<?php if(!empty(App::$current_landing->pwa_c)) {?>
	<!--PWA-->
	<meta name="theme-color" content="#f57622">
	<meta name="apple-mobile-web-app-status-bar" content="#f57622">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="WINMON - доставка еды">
	<link rel="manifest" href="/pwa_manifest.php">
	<script src="./pwa_app.js"></script>
	<!--END PWA-->
	<?php } ?>
	
	<!--для Apple-->
	<?php if(!empty(App::$current_organization->icon_60x60_c)) {?>
		<link rel="apple-touch-icon" sizes="60x60"  href="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.App::$current_organization->id.'_icon_60x60_c'?>">
	<?php } if(!empty(App::$current_organization->icon_76x76_c)) {?>
		<link rel="apple-touch-icon" sizes="76x76"  href="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.App::$current_organization->id.'_icon_76x76_c'?>">
	<?php } if(!empty(App::$current_organization->icon_120x120_c)) {?>
		<link rel="apple-touch-icon" sizes="114x114" href="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.App::$current_organization->id.'_icon_120x120_c'?>">
	<?php } if(!empty(App::$current_organization->icon_120x120_c)) {?>
		<link rel="apple-touch-icon" sizes="120x120" href="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.App::$current_organization->id.'_icon_120x120_c'?>">
	<?php } if(!empty(App::$current_organization->icon_152x152_c)) {?>
		<link rel="apple-touch-icon" sizes="144x144" href="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.App::$current_organization->id.'_icon_152x152_c'?>">
	<?php } if(!empty(App::$current_organization->icon_152x152_c)) {?>
		<link rel="apple-touch-icon" sizes="152x152" href="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.App::$current_organization->id.'_icon_152x152_c'?>">
	<?php } if(!empty(App::$current_organization->icon_180x180_c)) {?>
		<link rel="apple-touch-icon" sizes="180x180" href="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.App::$current_organization->id.'_icon_180x180_c'?>">
	<?php } ?>
	<!--END для Apple-->
	
	<!--для твитера-->
	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="<?=App::$current_organization->name?> доставка <?=App::$current_city->name?> на дом"/>
	<meta name="twitter:title" content="<?=App::$current_organization->name?> <?=App::$current_city->name?> - доставка на дом!">
	<meta name="twitter:creator" content="<?=App::$current_organization->name?>"/>
	<meta name="twitter:image:src" content="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_landing_logo_c"/>
	<meta name="twitter:domain" content="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>"/>
	<meta http-equiv="Cache-Control" content="public">
	
	<meta property="og:title" content="<?=$data['title']?>" />
	<meta property="og:site_name" content="<?=App::$current_organization->name?> доставка <?=App::$current_city->name?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>" />
	<meta property="og:description" content="<?=App::$current_landing->seo_description_c?>" />
	<meta property="og:locale" content="ru_RU">
	<meta property="og:image" content="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_landing_logo_c">
		
	<!--для вк-->
	<link rel="image_src" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_landing_logo_c"/>
	<!--END Мета информация-->

	<!-- Стили -->
    <!-- fonts CSS -->
    <link media="none" onload="if(media!='all') media='all'" rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=<?=str_replace(' ', '+', App::$current_organization->font_c)?>:300,400,500,700|Material+Icons&display=swap">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-float-label/bootstrap-float-label.min.css?=9">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/css/style.php?main_color=<?=App::$current_organization->main_color_c?>&color_scroll=<?=App::$current_organization->color_scroll_c?>&font=<?=App::$current_organization->font_c?>&color_active_btn=<?=App::$current_organization->color_active_btn_c?>">
	<link media="none" onload="if(media!='all') media='all'" rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/swiper/css/swiper-bundle.min.css">
	<!-- END Стили -->
	
	<!-- jquery and bootstrap js -->
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/jquery-3.3.1.min.js?banner=off"></script>
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/jquery.maskedinput.min.js?banner=off"></script>
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/sweetalert.min.js?banner=off"></script>
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/lazysizes/lazysizes.min.js?banner=off"></script>
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
	
	<? 
		if(empty($_SESSION['loader']) || $_SERVER['REQUEST_URI'] == '/'){ 
			$_SESSION['loader'] = true;
	?> 
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
	<? } ?> 
  
	<?php include CORE_FOLDER.'/application/views/widget/right_menu.php'; //Основное меню ?>
       
	<?php
		$wrapper_background = 'rgb(231, 237, 243)';
		if(App::$current_organization->wrapper_color_c) {  
			$wrapper_background = App::$current_organization->wrapper_color_c; 
		}
	?>
    <div class="wrapper" style="background:<?=$wrapper_background?>" > 
		<?php 
			$router = App::getRouter();
			if( App::$send_404 ){
				include CORE_FOLDER.'/application/views/main/404_view.php';
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
	
	<?php include CORE_FOLDER.'/application/views/widget/callback.php'; //Обратная связь ?>
	
	<?php include CORE_FOLDER.'/application/views/widget/share_modal.php'; //кнопка Поделиться - модальное окно?>
	
	<!-- notification APP -->
	<?php include CORE_FOLDER.'/application/views/widget/download_app_modal.php'; //Предлагаем утсановить приложение мобильное?>
    <!-- notification APP ends -->
    
    <script async src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-4.3.1/js/bootstrap.min.js"></script>

    <!-- template custom js -->
    <script async src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/main.js"></script>


	<script>
		//анимация перемещения в корзину
		function animationAddCard(object,id){
			console.log(object);
			$(".product_image_"+id)
				.first()
				.clone()
				.css({'position' : 'absolute', 'border-radius': '.25rem','width': '200', 'min-width': '', 'min-height': '', 'z-index' : '1060', top: $('.product_image_'+id).first().offset().top, left:$('.product_image_'+id).first().offset().left})
				.appendTo("body")
				.animate({opacity: 0.5,
					left: $(".basket").offset()['left'],
					top: $(".basket").offset()['top'],
					width: 50}, 1000, function() {
					$(this).remove();
				});
		}

		function disableCard(){
			$("#work_ajax").show();
		}
		
		function enadleCard(){
			$("#work_ajax").hide();
		}
		
		
		window.onload = function() {
			//При нажатии кнопки Добавить в корзину
			$(document).on('click', '.btn-add-product, .btn-mini-add-product', function(e) {
				var product_id = $(this).data("product-id");
				animationAddCard(this, product_id);//анимация перемещения в корзину
				
				disableCard();
				
				var product_count = parseInt($('.product_count'+product_id).first().val()) + 1;//увеличиваем количество продуктов на 1
				var product_image = $('.product_image_'+product_id).first().attr('src');
				var product_name = $('.product_name_'+product_id).first().text();
				var product_price = parseInt($('.product_price_'+product_id).first().text());
				
				$.getJSON("/basket/add_product/"+product_id, function(data) {
					product_count = parseInt(data.product.product_count);
					$(".product_count"+product_id).val(product_count);
					
					$(".sale_all").text(data.products_price+" ₽");//общая сумма заказа
					
					enadleCard();
				});
				
				if(product_count == 1 ){
					$('.btn-add-product[data-product-id="'+product_id+'"]').text('+');//Кнопку ДОБАВИТЬ переименовываем в +
					$('.btn-add-product[data-product-id="'+product_id+'"]').css('width','70px');//Кнопку делаем шириной в 70px
					$('.btn-add-product[data-product-id="'+product_id+'"]').parent().prepend('<button type="button" class="btn btn-default btn-minus-product" data-product-id="'+product_id+'" style="width: 70px;background:<?=App::$current_organization->color_product_btn_c?>;">-</button>'); //добавляем кнопку минуса
				}
				
				
				var product_html = 
					'<li class="list-group-item" id="card-product'+product_id+'">'+
						'<input type="hidden" name="product_id[]" value="'+product_id+'">'+
						'<div class="row">'+
							'<div class="col-2 pl-0 align-self-center">'+
								'<img class="product-image h-auto product_image_'+product_id+'" src="'+product_image+'">'+
							'</div>'+
							'<div class="col-4 px-0 align-self-center">'+
								'<a href="/main/product/'+product_id+'" class="mb-1 h6 d-block product_name_'+product_id+'">'+product_name+'</a>'+
								'<h5 class="font-weight-normal product_price_'+product_id+'" style="color:<?=App::$current_organization->color_sale_price_c?>;">'+product_price+' ₽</h5>'+
							'</div>'+
							'<div class="col-6 align-self-center">'+
								'<div class="input-group input-group-sm" style="margin-left:20px;">'+
									'<div class="input-group-prepend">'+
										'<button class="btn px-1 btn-default btn-minus-product" type="button" data-product-id="'+product_id+'" style="background:<?=App::$current_organization->color_product_btn_c?>;color:#fff;">'+
											'<i class="material-icons">remove</i>'+
										'</button>'+
									'</div>'+
									'<input name="count[]" type="text" class="btn product_count'+product_id+'" style="color:#fff;width:45px;border:none;border-radius:0;background:<?=App::$current_organization->color_product_btn_c?>;" value="'+product_count+'" readonly>'+
									'<div class="input-group-append">'+
										'<button class="btn px-1 btn-default btn-add-product" data-product-id="'+product_id+'" style="background:<?=App::$current_organization->color_product_btn_c?>;color:#fff;">'+
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
		};
		
	</script>

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

<?php include CORE_FOLDER.'/application/views/widget/promocod_modal.php'; //модальное окно с промокодом ?>
</body>

</html>