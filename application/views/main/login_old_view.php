<html lang="ru-RU" class="pink-theme">

<head>
	<!--Мета информация-->
	<title><?=$data['title']?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="HandheldFriendly" content="True">
	<meta name="description" content="<?=$data['description']?>">
	<meta name="keywords" content="вход, авторизация, <?=App::$current_organization->name_rus_c?> профиль, войти <?=App::$current_organization->name?>">
	
	<meta name="yandex-verification" content="5bbdf66d67c4949b" />
	<link rel="shortcut icon" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_image_icon_c" type="image/x-icon">
	
	<!--для твитера-->
	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="<?=App::$current_organization->name?> доставка <?=App::$current_city->name?> на дом"/>
	<meta name="twitter:title" content="<?=App::$current_organization->name?> <?=App::$current_city->name?> - доставка на дом!">
	<meta name="twitter:creator" content="Winmon - доставка еды"/>
	<meta name="twitter:image:src" content="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_landing_logo_c"/>
	<meta name="twitter:domain" content="<?=NFfunctions::getSiteProtocol()?><?=parse_url(App::$current_landing->link_c)['host']?>"/>
	<meta http-equiv="Cache-Control" content="public">
	
	<meta property="og:title" content="<?=$data['title']?>">
	<meta property="og:site_name" content="<?=App::$current_organization->name?> доставка <?=App::$current_city->name?>">
	<meta property="og:url" content="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>">
	<meta property="og:description" content="Авторизация на сайте доставки <?=App::$current_organization->name_rus_c?>">
	<meta property="og:image" content="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_landing_logo_c">
		
	<!--для вк-->
	<link rel="image_src" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_landing_logo_c"/>
	<!--END Мета информация-->

	<!-- Стили -->
    <!-- Roboto fonts CSS -->
    <link media="none" onload="if(media!='all') media='all'" rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=<?=str_replace(' ', '+', App::$current_organization->font_c)?>:300,400,500,700|Material+Icons&display=swap">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/css/style.php?main_color=<?=App::$current_organization->main_color_c?>&color_scroll=<?=App::$current_organization->color_scroll_c?>&font=<?=App::$current_organization->font_c?>">
	<!-- END Стили -->

	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/jquery-3.3.1.min.js"></script>
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/jquery.maskedinput.min.js"></script>
	<script async src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-4.3.1/js/bootstrap.min.js"></script>
    <script async src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/main.js"></script>
</head>

<body>

	<? 
		if(empty($_SESSION['loader'])){ 
			$_SESSION['loader'] = true;
	?> 
		<div id="loader" class="row no-gutters vh-100 loader-screen">
			<div class="col align-self-center text-white text-center">
				<img id="loader_image" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_loading_c" alt="Логотип загрузки заведения <?=App::$current_organization->name?>" style="max-width:350px;">
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
			$('#loader_image').addClass('pulse');
			setTimeout(function(){
				$('#loader_image').removeClass('pulse');
				$('#loader').hide();
			},3000)
		</script>
	<? } ?> 
	<style>
	.number_input {
		margin: 0 5px;
		text-align: center;
		line-height: 60px;
		font-size: 50px;
		border: solid 1px #ccc;
		box-shadow: 0 0 5px #ccc inset;
		outline: none;
		width: 195px;
		transition: all .2s ease-in-out;
		border-radius: 3px;
	}
	</style>
	<div class="header" style="background-color: <?=App::$current_organization->main_color_c?>;border-bottom:1px solid #fff;">
		<?php if( !empty(App::$current_aggregator)){ ?>
			<a href="<?=App::$current_aggregator->link_c?>" class="row no-gutters" style="max-width:1230px;margin:0 auto;background: linear-gradient(to right bottom, rgb(218, 95, 0), rgb(227, 102, 5), rgb(236, 109, 11), rgb(246, 117, 17), rgb(255, 124, 22));text-decoration: none;color:#fff;">
				<div class="col-auto">
					<button class="btn btn-link text-dark"><i class="material-icons" style="color:#fff;font-size:26pt;">call_received</i></button><span>Вернуться к списку ресторанов</span>
				</div>
			</a>
		<?php } ?>
		<div class="row no-gutters" style="height:50px;max-width:1230px;margin:0 auto;">
			<div class="col-auto">
				<a href="<? if(!empty($_SERVER['HTTP_REFERER'])) { echo $_SERVER['HTTP_REFERER']; } else { echo NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST']; }?>"><i class="material-icons" style="font-size:26pt; padding-top:7px;padding-left:10px;cursor:pointer;color:#fff;">navigate_before</i></a>
			</div>
			<div class="col text-center" style="padding-top:10px;">
				<h1 class="text-white" style="font-size:19pt;width:100%;display:inline;">Авторизация </h1>
				<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>">
					<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_image_organization_c" alt="<?=App::$current_organization->name_rus_c?>-логотип" class="header-logo" style="border-radius:30px;height:40px;margin-top:-5px;">
				</a>
			</div>
		</div>
	</div>
	
	<div class="row no-gutters vh-100 proh bg-template">
		<div class="col align-self-center px-3 text-center">
			<h2 class="text-white" style="font-size:32pt;text-shadow: 0px 0px 7px #000;">
				Вход
			</h2>
			<form class="form-signin shadow">
				<div class="mb-3 form-group float-label" style="display: flex;">
					<input type="tel" id="phone" class="form-control" required autofocus">
					<label for="inputPhone" class="form-control-label">Телефон для входа</label>
				</div>
		
				<p class="term-block small text-left">
					Указывая номер телефона, вы подтерждаете своё согласие <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/agreement" style="text-decoration: underline;">на обработку своих персональных данных</a> и согласие с <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/term_of_use" style="text-decoration: underline;">правилами предоставления услуг</a>
				</p>

				<? 
					if(!empty(App::$current_landing->telegram_auth_c)){ 
						global $db;
						$telegram_bot_info = $db->fetchRow($db->query("
							SELECT * 
							FROM tgath_telegram_auth tta
							LEFT JOIN tgath_telegram_auth_cstm tta_cstm ON tta_cstm.id_c = tta.id AND tta.deleted = 0
                            LEFT JOIN tgath_telegram_auth_lngng_landings_1_c tta_ll ON tta_ll.tgath_telegram_auth_lngng_landings_1tgath_telegram_auth_ida = tta.id AND tta_ll.deleted = 0
							WHERE 
								tta_ll.tgath_telegram_auth_lngng_landings_1lngng_landings_idb = '".App::$current_landing->id."';
						"));
				?>
					<div class="telegram_block" style="display:none;">
						<p class="small text-left" style="color:green;">Что-бы получить "Код подтверждения", пожалуйста, запустите нашего бота в Telegram и следуйте дальнейшим инструкциям</p>
						<div style="margin-bottom:10px;margin-top:10px;">
							<a class="btn btn-default text-white btn-block shadow" href="https://t.me/<?=$telegram_bot_info['bot_username_c']?>" target="_blank" style="max-width:100%;font-size:13pt;">Наш Telegram-бот</a>
						</div>
						<input class="number_input" type="tel" id="code_telegram" placeholder="_ _ _ _">
						<a id="get_code_call_method" class="btn btn-default text-white btn-block" style="font-size:10pt;background:#343a40;padding-top:5px;padding-bottom:5px;margin-top:20px;">
							<span>Получить код другим способом</span>
						</a>
					</div>
					
					<div class="call_block" style="display:none;">
						<p class="small text-left" style="color:green;">Что-бы получить "Код подтверждения", пожалуйста, заполните код с картинки</p>
						<div class="mb-3 form-group float-label" style="display: flex;">
							<input type="text" name="img_code" id="img_code" size="9" class="form-control" required>
							<label for="inputCode" class="form-control-label">Код с картинки</label>
							<img src="https://smsc.ru/sys/imgcode.php?1.1" onclick="src+=1" style="width:150px;height:40px;">
						</div>

						<div class="call_code" style="display:none;">
							<p class="small text-left" style="color:green;">Сейчас Вам поступит входящий звонок, необходимо ответить. Введите ЧЕТЫРЕ цифры, которые продиктует Вам робот.</p>
							<input class="number_input" type="tel" id="code_call" placeholder="_ _ _ _">
							<input type="hidden" id="code" value="" >
						</div>
					</div>
				<? } else { ?>
					<div class="call_block">
						<div class="mb-3 form-group float-label" style="display: flex;">
							<input type="text" name="img_code" id="img_code" size="9" class="form-control" required>
							<label for="inputCode" class="form-control-label">Код с картинки</label>
							<img src="https://smsc.ru/sys/imgcode.php?1.1" onclick="src+=1" style="width:150px;height:40px;">
						</div>

						<div class="call_code" style="display:none;">
							<p class="small text-left" style="color:green;">Сейчас Вам поступит входящий звонок, необходимо ответить. Введите ЧЕТЫРЕ цифры, которые продиктует Вам робот.</p>
							<input class="number_input" type="tel" id="code_call" placeholder="_ _ _ _">
							<input type="hidden" id="code" value="" >
						</div>
					</div>
				<? } ?>
				
			</form>
		</div>
	</div>

	<script>
	jQuery(function($){
		//маска для кода
		$("#code_telegram").mask("9 9 9 9");
		$("#code_call").mask("9 9 9 9");
		
		$("#phone").mask(
			"+7 (999) 999-99-99",{
				completed:function(){ 
					<? if(!empty(App::$current_landing->telegram_auth_c)){ ?>
						$('.telegram_block').show();
						$('.term-block').hide();
					<? } else { ?>
						$('.call_block').show();
					<? } ?>
				}
			}
		).on('click', function () {
			if ($(this).val() === '+7 (___) ___-__-__') {
				$(this).get(0).setSelectionRange(4, 4);
			}
		});

		
		$("#img_code").mask("99999", {
			completed:function(){ 
				if($("#phone").val()){
					$('.call_code').show();
					$('.term-block').hide();
					get_call_voice_code();
				}
			}
		});
		
		//скрытие всего, если введено мало символов телефона
		$("#phone").keyup(function(){
			if($("#phone").val().replace(/\D+/g,"").length <= 10){
				$('.term-block').show();
				$('.telegram_block').hide();
				<? if(!empty(App::$current_landing->telegram_auth_c)){ ?>
					$('.call_block').hide();
				<? } else { ?>
					$('.call_code').hide();
					$('#img_code').val('');
				<? } ?>
			}
		});
		
		//при нажатии кнопки - другой способ входа
		$('#get_code_call_method').on('click', function () {
			$('.telegram_block').hide();
			$('.call_block').show();
		});
		
		
		$("#code_telegram").keyup(function(){
			if($('#code_telegram').val()){
				$.ajax({
					url: "/main/check_telegram_code/"+$('#code_telegram').val()+"/"+$('#phone').val(),
					method: "POST",
				}).done(function(data){
					if(data == 'ok'){
						window.location.href = "/main/profile";
					}
				});	
			}
		});
		
		$("#code_call").keyup(function(){
			if($('#code_call').val()){
				$.ajax({
					url: "/main/check_call_code/"+$('#code_call').val()+'/'+$('#code').val(),
					method: "POST",
				}).done(function(data){
					if(data == 'ok'){
						window.location.href = "/main/profile";
					}
				});	
			}
		});
	});
	
	function get_call_voice_code(){
		$.ajax({
			url: "/main/get_call_voice_code/"+$('#phone').val()+"/"+$('#img_code').val(),
			method: "POST",
		}).done(function(data){
			if(data.length > 0){
				$('#code').val(data);
			}
		});
	}

	</script>
</body>
</html>