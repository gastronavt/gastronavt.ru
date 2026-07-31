<!DOCTYPE HTML>
<html lang="ru-RU" class="pink-theme">
<head>
	<?php
		//Настройка Content-Security-Policy для защиты от баннеров и рекламы
		header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' crm.winmon.ru winmon.ru dump.video yandex.net *.yandex.net yastatic.net yandex.ru *.yandex.ru data: *.googleapis.com fonts.gstatic.com vk.com www.w3.org *.vk.com google-analytics.com *.google-analytics.com googletagmanager.com *.googletagmanager.com");
	?>
	<!--Мета информация-->
	<title><?=$data['title']?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="HandheldFriendly" content="True">
	<meta name="description" content="<?=App::$current_landing->seo_description_c?>">
	<meta name="keywords" content="<?=App::$current_landing->seo_keywords_c?>">
	
	<meta name="theme-color" content="<?=App::$current_organization->main_color_c?>">
	<link rel="shortcut icon" href="<?=NFfunctions::getSiteProtocol().CRM_URL.'/upload/'.App::$current_organization->id.'_image_icon_c'?>" type="image/x-icon">

	<!-- Стили -->
    <!-- Roboto fonts CSS -->
    <link media="none" onload="if(media!='all') media='all'" rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons&display=swap">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-4.3.1/css/bootstrap.min.css?param=noscript">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-float-label/bootstrap-float-label.min.css?param=noscript">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/css/style.php?main_color=<?=App::$current_organization->main_color_c?>">
	<link media="none" onload="if(media!='all') media='all'" rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/swiper/css/swiper.min.css?param=noscript">
	<!-- END Стили -->
	
	<!-- jquery, popper and bootstrap js -->
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/jquery-3.3.1.min.js?param=noscript"></script>
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/jquery.maskedinput.min.js?param=noscript"></script>
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/sweetalert.min.js?param=noscript"></script>
</head>

<body>
	<?php
		if(App::$current_landing->vk_chat_c != NULL){
			echo html_entity_decode(App::$current_landing->vk_chat_c);
		}
	?>
	
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
  
	<?php 
		include CORE_FOLDER.'/application/views/widget/right_menu.php'; //Основное меню
	?>
      
	<div class="wrapper" style="padding-bottom:0px;">
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
  
<script async src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-4.3.1/js/bootstrap.min.js?param=noscript"></script>
<!-- template custom js -->
<script async src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/main.js?param=noscript"></script>


<script>
	function disableCard(){
		$("#work_ajax").show();
	}
	
	function enadleCard(){
		$("#work_ajax").hide();
	}
</script>

<?=NFfunctions::decodeString(App::$current_landing->seo_app_yandex_metrika_c)?>
</body>

</html>