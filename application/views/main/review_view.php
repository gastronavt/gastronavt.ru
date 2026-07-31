<?php
	$header_name = 'Отзывы';
	$header_back_url = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'];
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>


<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

<!-- Контакты -->
<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
	<div class="jumbotron text-center mt-3 bg-white shadow-sm">
		<center>
			<?php if(!empty(App::$current_landing->reviews_yandex_c)){ ?>
				<h3>Отзывы Яндекс</h3>
				<div style="width:100%;height:800px;overflow:hidden;position:relative;margin-bottom:30px;">
				
					<iframe style="width:100%;max-width:750px;height:100%;border:1px solid #e6e6e6;border-radius:8px;box-sizing:border-box" src="https://yandex.ru/maps-reviews-widget/<?=App::$current_landing->id_reviews_yandex_c?>?comments"></iframe><a href="https://yandex.ru/maps/org/pryanikov/<?=App::$current_landing->id_reviews_yandex_c?>/" target="_blank" style="box-sizing:border-box;text-decoration:none;color:#b3b3b3;font-size:10px;font-family:YS Text,sans-serif;padding:0 20px;position:absolute;bottom:8px;width:100%;text-align:center;left:0">Пряников на карте Иркутска — Яндекс.Карты</a>
				</div>
			<?php } ?>
			<?php if(!empty(App::$current_landing->reviews_c)){ ?>
				<h3>Отзывы Вконтакте</h3>
				<script type="text/javascript" src="https://vk.com/js/api/openapi.js?167"></script>

				<script type="text/javascript">
				  VK.init({apiId: <?=App::$current_landing->id_reviews_c?>, onlyWidgets: true});
				</script>

				<div  style="max-width:750px;height:800px;overflow:hidden;position:relative;margin-bottom:30px;" id="vk_comments"></div>
				<script type="text/javascript">
					VK.Widgets.Comments("vk_comments", {limit: 15, attach: "*", pageUrl: "<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/review"});
				</script>
			<?php } ?>
		</center>
	</div>
</div>
<!-- END Контакты -->


<?php  include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>