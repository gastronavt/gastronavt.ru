<?php
	$header_name = 'Страница не найдена';
	$header_back_url = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'];
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

<!-- 404 -->
<div class="container text-center" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
	<div style="padding: 0 0 30px 0;text-align:center;">
		<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/404.png" style="width:60%;max-width:400px;margin:20px;" alt="Ошибка 404" />
	</div>
	
	<p>Мы не смогли найти страницу, но Вы ,по-прежнему, можете ознакомиться с меню и сделать заказ ;) </p>
	<center>
		<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>" class="btn btn-lg btn-default text-white btn-block btn-rounded shadow" style="background:<?=App::$current_organization->main_color_c?>;padding-top:5px;padding-bottom:5px;width:300px;"><span>Ознакомиться с меню</span></a>
	</center>
</div>
<!-- END 404 -->

<?php include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>
