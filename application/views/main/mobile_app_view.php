<div class="header" style="background-color: <?=App::$current_organization->main_color_c?>;">
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
			<h1 class="text-white" style="font-size:19pt;width:100%;display:inline;">Мобильное приложение</h1>
			<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>">
				<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=App::$current_organization->id?>_image_organization_c" alt="<?=App::$current_organization->name_rus_c?>-логотип" class="header-logo" style="border-radius:30px;height:40px;margin-top:-5px;">
			</a>
		</div>
	</div>
</div>

<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

<!-- Карта доставки -->
<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
	<div class="jumbotron text-center mt-3 bg-white shadow-sm">
		<div class="card-body bg-none px-4">
			<div class="mt-2 mb-4">
				<?=html_entity_decode(App::$current_landing->description_area_c)?>
			</div>
		</div>
	</div>
</div>
<!-- END Карта доставки -->

<?php  include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>
