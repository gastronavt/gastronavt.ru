<?php
	$header_back_url = empty($_SERVER['HTTP_REFERER']) ? NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'] : $_SERVER['HTTP_REFERER'];
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

<!-- Согласие -->
<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
	<div class="card bg-template shadow mt-4 h-190">
		<div class="card-body">
			<center style="width:100%">
				<div style="display:inline-block;">
					<h1 class="mb-1 text-white" style="font-size:19pt;">СОГЛАСИЕ пользователя и клиента на обработку персональных данных</h1>
				</div>
			</center>
		</div>
	</div>
</div>
<div class="container mt-3">
	<div class="jumbotron bg-white shadow-sm">
		<?php if(!empty(App::$current_landing->agreement_lead_c)) {?>
			<?=html_entity_decode(App::$current_landing->agreement_lead_c)?>
		<?php } ?>
	</div>
</div>
<!-- END Согласие -->

<?php  include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>