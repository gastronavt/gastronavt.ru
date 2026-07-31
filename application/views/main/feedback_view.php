<?
	global $db;	
	$feedback_settings = $db->fetchRow($db->query(
		"SELECT
			ff.id,
			ff_cstm.title_c,
			ff_cstm.subtitle_c,
			ff_cstm.good_rating_c
		FROM fbsg_feedback_settings_cstm ff_cstm
		LEFT JOIN fbsg_feedback_settings ff ON ff.id = ff_cstm.id_c AND ff.deleted = 0
		WHERE ff_cstm.lngng_landings_id_c = '".App::$current_landing->id."';"
	));
	
	if(!$feedback_settings['id']){
		header('HTTP/1.1 200 OK');
		header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST']);
		exit();
	}
	
	$query_feedback_platforms = $db->query(
		"SELECT 
			ffp.name,
			ffp_cstm.review_link_c
		FROM fbsg_feedback_settings ff
		JOIN fbsg_feedback_settings_fbplt_feedback_platforms_1_c ff_ffp ON ff_ffp.fbsg_feedbc38dettings_ida = ff.id AND ff.deleted = 0 AND ff_ffp.deleted = 0
		JOIN fbplt_feedback_platforms ffp ON ffp.id = ff_ffp.fbsg_feedb9e21atforms_idb AND ffp.deleted = 0
		JOIN fbplt_feedback_platforms_cstm ffp_cstm ON ffp_cstm.id_c = ffp.id
		WHERE ff.id = '".$feedback_settings['id']."'
		ORDER BY ffp_cstm.sorting_c ASC"
	);
	
	$feedback_platforms = [];

	while($feedback_platform = $db->fetchByAssoc($query_feedback_platforms)) {
		$feedback_platforms[] = $feedback_platform;
	}
?>

<!DOCTYPE HTML>
<html lang="ru-RU" class="pink-theme">
<head>
	<?php
		//Настройка Content-Security-Policy для защиты от баннеров и рекламы
		header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' winmon.ru *.winmon.ru dump.video yandex.net *.yandex.net yastatic.net yandex.ru *.yandex.ru tlgur.com data: *.googleapis.com fonts.gstatic.com vk.com www.w3.org *.vk.com google-analytics.com *.google-analytics.com googletagmanager.com *.googletagmanager.com my.mail.ru connect.facebook.net www.facebook.com");
	?>
	<!--Мета информация-->
	<title>Форма обратной связи о <?=App::$current_organization->name_rus_c?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="HandheldFriendly" content="True">
	<meta name="description" content="<?=App::$current_organization->name_rus_c?> - оставьте Ваш отзыв и помогите нам стать еще лучше!">
	<meta name="keywords" content="<?=App::$current_organization->name_rus_c?>, форма обратной связи">
	
	<meta name="theme-color" content="<?=App::$current_organization->main_color_c?>">
	<link rel="shortcut icon" href="<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.App::$current_organization->id.'_image_icon_c'?>" type="image/x-icon">

	<!-- Стили -->
    <!-- Roboto fonts CSS -->
    <link media="none" onload="if(media!='all') media='all'" rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons&display=swap">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/bootstrap-float-label/bootstrap-float-label.min.css?=9">
	<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/css/style.php?main_color=<?=App::$current_organization->main_color_c?>">
	<!-- END Стили -->
	
	<!-- jquery, popper and bootstrap js -->
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/jquery-3.3.1.min.js?banner=off"></script>
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/jquery.maskedinput.min.js?banner=off"></script>
	<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/sweetalert.min.js?banner=off"></script>
		<link rel="stylesheet" type="text/css" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/rating/starability-heartbeat.min.css"/>	
</head>
<body>
	
	<div id="loader" class="row no-gutters vh-100 loader-screen">
        <div class="col align-self-center text-white text-center">
            <img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_loading_c" alt="Логотип загрузки заведения <?=App::$current_organization->name?>" style="max-width:350px;">
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
       
    <div class="wrapper"> 
		
		
		<div class="header" style="background-color: <?=App::$current_organization->main_color_c?>;">
			<?php if( !empty(App::$current_aggregator)){ ?>
				<a href="<?=App::$current_aggregator->link_c?>" class="row no-gutters" style="max-width:1230px;margin:0 auto;background: linear-gradient(to right bottom, rgb(218, 95, 0), rgb(227, 102, 5), rgb(236, 109, 11), rgb(246, 117, 17), rgb(255, 124, 22));text-decoration: none;color:#fff;">
					<div class="col-auto">
						<button class="btn btn-link text-dark"><i class="material-icons" style="color:#fff;font-size:26pt;">call_received</i></button><span>Вернуться к списку ресторанов</span>
					</div>
				</a>
			<?php } ?>
			<div class="row no-gutters" style="height:50px;max-width:1230px;margin:0 auto;">
				<div class="col text-center" style="padding-top:10px;">
					<h1 class="text-white" style="font-size:19pt;width:100%;display:inline;">Оцените нас</h1>
					<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>">
						<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_landing_logo_c" alt="<?=App::$current_organization->name_rus_c?>-логотип" class="header-logo" style="border-radius:30px;height:40px;margin-top:-5px;">
					</a>
				</div>
			</div>
		</div>

		<!-- Обратная связь -->
		<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
			<div class="jumbotron text-center mt-3 bg-white shadow-sm" style="min-height:400px;">
				<center>
					<h3><?=$feedback_settings['title_c'];?></h3>
					<span><?=$feedback_settings['subtitle_c'];?></span>

					<div id="rateBlock">
						<div style="margin-top:15px;font-size:14pt;font-weight:bold;color:rgb(57, 83, 229);">Оцените нас:</div>
						<fieldset class="starability-heartbeat" style="margin-top:15px;">
							<input type="radio" id="no-rate" class="input-no-rate" name="rating" value="0" checked aria-label="No rating." />

							<input type="radio" id="rate1" name="rating" value="1" />
							<label for="rate1">1 star.</label>

							<input type="radio" id="rate2" name="rating" value="2" />
							<label for="rate2">2 stars.</label>

							<input type="radio" id="rate3" name="rating" value="3" />
							<label for="rate3">3 stars.</label>

							<input type="radio" id="rate4" name="rating" value="4" />
							<label for="rate4">4 stars.</label>

							<input type="radio" id="rate5" name="rating" value="5" />
							<label for="rate5">5 stars.</label>

							<span class="starability-focus-ring"></span>
						</fieldset>
						<button class="btn btn-lg btn-default text-white btn-block btn-rounded shadow" id="approve_rate_btn" style="background:rgb(17, 25, 49);padding-top:7px;padding-bottom:7px;display:visible;max-width:350px;margin-top:10px;display:none;">Оценить</button>
					</div>
					
					<div id="reviewBlock" style="display:none;">
						<div style="margin-top:15px;font-size:14pt;font-weight:bold;color:rgb(57, 83, 229);">Что мы должны улучшить?</div>
						<div class="row" style="margin-top:25px;max-width:350px;">
							<div class="col-12" style="margin-bottom:10px;">
								<span class="form-group has-float-label ">
									<textarea class="form-control" id="comment_client_c" rows="7" maxlength="460" placeholder="Напишите свой отзыв"></textarea>
									<label style="width:135px;" for="comment_client_c">Напишите свой отзыв</label>
								</span>
							</div>
						</div>
						<button class="btn btn-lg btn-default text-white btn-block btn-rounded shadow" id="approve_review_btn" style="background:rgb(17, 25, 49);padding-top:7px;padding-bottom:7px;display:visible;max-width:350px;margin-top:5px;">Отправить отзыв</button>
					</div>
					
					<div id="transferReviewBlock" style="display:none;">
						<div style="margin-top:15px;font-size:14pt;font-weight:bold;color:rgb(57, 83, 229);">Спасибо огромное! <br>Будем признательны, если поставите оценку! Это будет лучшей благодарностью от Вас для нас!</div>
						<? foreach($feedback_platforms as $feedback_platform) { ?>
							<a class="btn btn-lg btn-default text-white btn-block shadow" style="margin-top:10px;max-width:350px;" target="_blunk" href="<?=$feedback_platform['review_link_c']?>"><?=$feedback_platform['name']?></a>
						<? } ?>
					</div>

					<div id="thanksBlock" style="display:none;">
						<div style="margin-top:15px;font-size:14pt;font-weight:bold;color:rgb(57, 83, 229);">Большое спасибо! Ваша оценка учтена! <br>Мы сделаем все возможное, чтобы стать лучше!</div>
					</div>
				</center>
			</div>
		</div>

		<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/rater.js" charset="utf-8"></script>
		<script>
			$(document).ready(function(){
				var rate = 0;
				
				$('input[type=radio][name=rating]').change(function() {
					rate = this.value;
					if (rate > 0) {
						$("#approve_rate_btn").show();
					}
					else {
						$("#approve_rate_btn").hide();
					}
				});
				
				//отправляем оценку клиента
				$("#approve_rate_btn").on("click", function(ev, data){
					$.getJSON("/fb/feedback_create/"+rate+"/"+"<?=$data['orderName']?>", function(data) {
						$('#approve_review_btn').attr("data-feedback", data.feedback_id);
					});
					
					if(rate >= <?=$feedback_settings['good_rating_c'];?>) {
						$("#rateBlock").hide();
						$("#reviewBlock").hide();
						$("#transferReviewBlock").show();
					} else {
						$("#rateBlock").hide();
						$("#reviewBlock").show();
						$("#transferReviewBlock").hide();
					}
				});
				
				//отправляем текстовый отзыв клиента
				$("#approve_review_btn").on("click", function(ev, data){
					if($('#comment_client_c').val() == ''){
						swal("Внимание", "Пожалуйста, напишите ваш отзыв!", "error");
					} else {
						$("#thanksBlock").show();
						$("#reviewBlock").hide();

						$.post("/fb/feedback_add_review/"+$('#approve_review_btn').data("feedback"), { review_text: $('#comment_client_c').val() }, function(data, textStatus) {
							
						}, "json");
					}
				});
			});
		</script>

		<style>
			.rate
			{
				font-size: 45px;
				height:50px !important;
				margin-top:10px;
			}
			.rate-base-layer{
				height:50px !important;
				padding-top:10px;
			}
			.rate .rate-hover-layer
			{
				color: pink;
				height:50px !important;
				padding-top:10px;
			}
			.rate .rate-select-layer
			{
				color: orange;
				height:50px !important;
				padding-top:10px;
				
			}
		</style>

		<!-- END отзыв -->


		<div class="container mt-5 mb-3">
			<? if(App::$current_landing->google_play_c) { ?>
				<a href='<?=App::$current_landing->google_play_c?>'><img alt='Доступно в Google Play' style="height:55px;" src='<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/googleplay.png'/></a>
			<? } ?>
			<? if(App::$current_landing->app_store_c) { ?>
				<a href='<?=App::$current_landing->app_store_c?>'><img alt='Доступно в App Store' style="height:55px;" src='<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/appstore.png'/></a>
			<? } ?>
			<? if(App::$current_landing->win_technology_link_c) { ?>
			<div><a href='<?=NFfunctions::getSiteProtocol()?>win-technology.ru' target="_blunk">Нужен сайт? Пиши сюда! </a></div>
			<? } ?>
			<div style="font-size:8pt;text-align:left;font-style:italic;">
				Информация, представленная на сайте, носит справочный характер и не является публичной офертой.
			</div>
			<div>
				&copy; <script>document.write(new Date().getFullYear())</script>
				| Россия. Доставка еды из <?=App::$current_organization->name?> в городе <?=App::$current_city->name?>
			</div>
		</div>


		<div class="footer" style="z-index:12;>
			<div class="no-gutters">
				<div class="col-auto mx-auto">
					<div class="row no-gutters justify-content-center">
						<div class="col-auto">
							<a class="btn btn-link-default menu-btn" style="width: 90px;margin-left:-30px;cursor: pointer;">
								Навигация
							</a>
						</div>
						<div class="col-auto" style="width:30px;">
						</div>
						<? if(App::$current_landing->active_profile_c) { ?>
							<div class="col-auto">
								<? if(empty(App::$current_user)) { ?>
									<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/login" class="btn btn-link-default" style="color:#000;">
										Войти
									</a>
								<? } else { ?>
									<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/profile" class="btn btn-link-default" style="color:#000;">
										Профиль
									</a>
								<? } ?>
							</div>
						<? } else { ?>
							<div class="col-auto">
								<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news" class="btn btn-link-default" style="color:#000;">
									Новости
								</a>
							</div>
						<? } ?>
					</div>
				</div>
			</div>
		</div>
		
		
		
    </div>
	
    <!-- template custom js -->
    <script async src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/main.js"></script>

</body>

</html>