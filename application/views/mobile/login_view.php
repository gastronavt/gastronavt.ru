<html lang="ru-RU" class="pink-theme">

<head>
	<!--Мета информация-->
	<title><?=$data['title']?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="HandheldFriendly" content="True">
	<meta name="description" content="<?=App::$current_landing->seo_description_c?>">
	<meta name="keywords" content="вход, авторизация, пряников профиль, войти пряников">
	
	<meta name="yandex-verification" content="5bbdf66d67c4949b" />
	<link rel="shortcut icon" href="<?='http://'.CRM_URL.'/upload/'.App::$current_organization->id.'_image_icon_c'?>" type="image/x-icon">
	
	<!--для твитера-->
	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="<?=App::$current_organization->name?> доставка <?=App::$current_city->name?> на дом"/>
	<meta name="twitter:title" content="<?=App::$current_organization->name?> <?=App::$current_city->name?> - доставка на дом!">
	<meta name="twitter:creator" content="Winmon - доставка еды"/>
	<meta name="twitter:image:src" content="<?='http://'.CRM_URL.'/upload/'.App::$current_organization->id.'_landing_logo_c'?>"/>
	<meta name="twitter:domain" content="<?=App::$current_landing->link_c?>"/>
	<meta http-equiv="Cache-Control" content="public">
	
	<meta property="og:title" content="<?=$data['title']?>">
	<meta property="og:site_name" content="<?=App::$current_organization->name?> доставка <?=App::$current_city->name?>">
	<meta property="og:url" content="<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>">
	<meta property="og:description" content="Авторизация на сайте доставки <?=App::$current_organization->name_rus_c?>">
	<meta property="og:image" content="<?='http://'.CRM_URL.'/upload/'.App::$current_organization->id.'_landing_logo_c'?>">
		
	<!--для вк-->
	<link rel="image_src" href="<?='http://'.CRM_URL.'/upload/'.App::$current_organization->id.'_landing_logo_c'?>"/>
	<!--END Мета информация-->

	<!-- Стили -->
    <!-- Roboto fonts CSS -->
    <link media="none" onload="if(media!='all') media='all'" rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons&display=swap">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/css/style.php?main_color=<?=App::$current_organization->main_color_c?>">
	<!-- END Стили -->

	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/jquery-3.3.1.min.js"></script>
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/jquery.maskedinput.min.js"></script>
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-4.3.1/js/bootstrap.min.js"></script>
    <script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/main.js"></script>
</head>

<body>
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
	
	<div id="loader" class="row no-gutters vh-100 loader-screen">
        <div class="col align-self-center text-white text-center">
            <img src="<?=NFfunctions::getSiteProtocol().CRM_URL?>/upload/<?=App::$current_organization->id?>_loading_c" alt="Логотип загрузки заведения <?=App::$current_organization->name?>" style="max-width:350px;">
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
	
	<div class="row no-gutters vh-100 proh bg-template">
		<div class="col align-self-center px-3 text-center">

			<h2 class="text-white" style="font-size:32pt;text-shadow: 0px 0px 7px #000;">
				У вас установлена старая версия приложения! <br>
				
				Для возможности авторизации, обновите приложение!
			</h2>
		</div>
	</div>
	
	<script>
	jQuery(function($){
		//маска для телефона
		$("#call_code").mask("9 9 9 9");
		
		$("#phone").mask(
			"+7 (999) 999-99-99",{
				completed:function(){ 
					$('.call_code').show();
					$('.term-block').hide();
					
					$.ajax({
						url: "/mobile/get_call_voice_code/"+$('#phone').val(),
						method: "POST",
					}).done(function(data){
						if(data.length > 0){
							$('#code').val(data);
						}
					});	
				}
			}
		).on('click', function () {
			if ($(this).val() === '+7 (___) ___-__-__') {
				$(this).get(0).setSelectionRange(4, 4);
			}
		});
		
		//скрытие кода смс если введено мало символов телефона
		$("#phone").keyup(function(){
			if($("#phone").val().replace(/\D+/g,"").length <= 10){
				$('.call_code').hide();
				$('.term-block').show();
				$('.number_input').val('');
			}
		});
	});

	//смс-код
	$(function() {
	  'use strict';

	  var body = $('body');

	  function goToNextInput(e) {
		if($('#call_code').val()){
			$.ajax({
				url: "/mobile/check_call_code/"+$('#call_code').val()+'/'+$('#code').val(),
				method: "POST",
			}).done(function(data){
				if(data == 'ok'){
					window.location.href = "/mobile/profile?session_id=<?=$_REQUEST['session_id']?>";
				}
			});	
		}
	  }

	  body.on('keyup', '.number_input', goToNextInput);
	})
	</script>
</body>
</html>