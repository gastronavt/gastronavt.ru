<?php
	global $db;
	$page = $db->fetchRow($db->query(
		"SELECT 
			pp.*,
			pp_cstm.*
		FROM pgs_pages pp 
		JOIN pgs_pages_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp.deleted = 0
		WHERE pp.id = '".$data['page_id']."';
	"));
?>

<?php
	$header_name = mb_strimwidth($page['name'],0, 20, "...");
	if(empty($page['header_h1_c'])){
		$header_tag = 'span';
	}
	$header_back_url = empty($_SERVER['HTTP_REFERER']) ? NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'] : $_SERVER['HTTP_REFERER'];
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
	<div class="jumbotron mt-3 bg-white shadow-sm pt-4">
		<?=html_entity_decode($page['content_c'])?>
		
		<? if(!empty($page) && $page['show_share_c']) { ?>
			<div class="row mb-2">
				<div class="col-4">
					<a class="btn btn-sm btn-default btn-rounded ml-2" data-toggle="modal" data-target="#openShare" style="width:150px;"><i class="material-icons mb-18 mr-2">share</i>Поделиться</a>
				</div>
			</div>
		<? } ?>
			
	</div>
</div>

<?php include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>