<?php
	global $db;
	$new = $db->fetchRow($db->query(
		"SELECT 
			nn.*,
			nn_cstm.*
		FROM news_news nn 
		JOIN news_news_cstm nn_cstm ON nn_cstm.id_c = nn.id AND nn.deleted = 0
		WHERE nn.id = '".$data['news_id']."';
	"));
?>

<?php
	$header_name = mb_strimwidth($new['name'],0, 18, "...");
	$header_tag = 'span';
	$header_back_url = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/main/news';
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
	<div class="jumbotron bg-white shadow-sm">
		<? if($new['image_c']) { ?>
		<div style="padding: 0 0 30px 0;text-align:center;">
			<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$new['id']?>_image_c" alt="Изображение для статьи - <?=$new['name']?>" style="border-radius: .25rem;width:100%;max-width:350px;">
		</div>
		<? } ?>
		
		<h1 class="mb-2 h5 d-block" style="color:<?=App::$current_organization->main_color_c?>"><?=$new['name']?></h1>
		<p class="text-secondary"><?=html_entity_decode($new['text_c'])?></p>
		
		<? if(!empty($new)) { ?>
			<div class="row mb-4">
				<div class="col-4">
					<a class="btn btn-sm btn-default btn-rounded ml-2" data-toggle="modal" data-target="#openShare" style="width:150px;"><i class="material-icons mb-18 mr-2">share</i>Поделиться</a>
				</div>
			</div>
		<? } ?>
		
		<div class="row p-3">
			<script type="text/javascript" src="https://vk.com/js/api/openapi.js?167"></script>

			<script type="text/javascript">
			  VK.init({apiId: <?=App::$current_landing->id_reviews_c?>, onlyWidgets: true});
			</script>

			<div id="vk_comments"></div>
			<script type="text/javascript">
			VK.Widgets.Comments("vk_comments", {limit: 15, attach: "*", pageUrl: "<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news/<?=$new['id']?>"});
			</script>
		</div>
	</div>
</div>

<?php include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>