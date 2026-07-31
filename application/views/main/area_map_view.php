<?php
	$header_name = 'О доставке';
	$header_back_url = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'];
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

<!-- Карта доставки -->
<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
	<div class="jumbotron mt-3 bg-white shadow-sm" style="padding-top:10px;">
		<div class="row mb-4 mb-lg-5">
			<div class="col-12 col-md-6 mt-4">
				<div class="shadow-sm">
					<?=html_entity_decode(App::$current_landing->yandex_area_c)?>
				</div>
			</div>
			<div class="col-12 col-md-6">
				<p class="text-secondary"><?=html_entity_decode(App::$current_landing->description_area_c)?></p>
			</div>
		</div>
	</div>
</div>
<!-- END Карта доставки -->

<?php  include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>
