<?php
	$header_name = 'Новости';
	$header_back_url = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'];
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

<!-- Блог -->
<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
	<div class="row mt-3 mb-3">
		<div class="col">
			<h5 class="mb-0">Фильтр</h5>
		</div>
	</div>
	<?php 
			$only_news = '';
			if(!empty($_GET['type']) && $_GET['type'] == 'only_news'){
				$only_news = " AND nn_cstm.type_c LIKE '^01^' ";
			}
	?>
	<div class="row">
		<div class="col-12 mb-2">
			<div class="badge-new me-2 mb-3 shadow-sm <? if(empty($only_news)) { ?>active <? } ?>"><a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news" style="text-decoration:none;">Все</a></div>
			<div class="badge-new me-2 mb-3 shadow-sm"><a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/stocks" style="text-decoration:none;">Только акции</a></div>
			<div class="badge-new me-2 mb-3 shadow-sm <? if(!empty($only_news)) { ?>active <? } ?>"><a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/news?type=only_news" style="text-decoration:none;">Только новости</a></div>
		</div>
	</div>
	<div class="row">
		<?php 
			$only_news = '';
			if(!empty($_GET['type']) && $_GET['type'] == 'only_news'){
				$only_news = " AND nn_cstm.type_c LIKE '^01^' ";
			}
		
			global $db;
			$queryNews = $db->query("
				SELECT 
					nn.id, 
					nn.name, 
					nn_cstm.text_c, 
					nn_cstm.color_background_c,
					nn_cstm.color_text_c,
					nn_cstm.image_fon_c,
					nn_cstm.image_c,
					nn_cstm.publish_date_c,
					nn_cstm.type_c,
					nn_cstm.link_name_c
				FROM news_news nn
				LEFT JOIN news_news_cstm nn_cstm ON nn_cstm.id_c = nn.id AND nn.deleted = 0
				LEFT JOIN lngng_landings_news_news_1_c ll_nn ON ll_nn.lngng_landings_news_news_1news_news_idb = nn_cstm.id_c AND ll_nn.deleted = 0
				WHERE 
					ll_nn.lngng_landings_news_news_1lngng_landings_ida = '".App::$current_landing->id."'
					".$only_news."
				ORDER BY nn_cstm.publish_date_c DESC;
			");
			$news = [];
			while($new = $db->fetchByAssoc($queryNews)) {
				$news[] = $new;
			}
			
			if($news){
				foreach($news as $new){
					$link_news = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/main/news/';
					if(!empty($new['link_name_c'])){
						$link_news .= $new['link_name_c'];
					} else {
						$link_news .= $new['id'];
					}
		?>
			<div class="col-12 col-sm-6">
				<div class="mt-3 bg-white shadow-sm" style="border-radius: .3rem;">
					<? if(strpos($new['type_c'], '02')) { ?>
						<div class="badge" style="position:absolute;top:10px;background-color:red;right:15px;font-size:13pt;color:#fff;z-index:1;border-radius: 0.25rem;">Акция</div>
					<? } elseif(strpos($new['type_c'], '01')) { ?>
						<div class="badge" style="position:absolute;top:10px;background-color:blue;right:15px;font-size:13pt;color:#fff;z-index:1;border-radius: 0.25rem;">Новость</div>
					<? } ?>
					<? if($new['image_c']) { ?>
						<div style="position: relative;width: 270px; height: 270px;margin:0 auto;">
							<img loading="lazy" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$new['id']?>_image_c" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/load.jpg" alt="Изображение статьи - <?=$new['name']?>" style="padding:10px;border-radius:15px; position: absolute; top: 0; left: 0;bottom: 0;right: 0;max-height: 100%;max-width: 100%;display: block;margin: auto;">
						</div>
					<? } ?>
					<div class="card-body mt-4">
						<div style="font-size:10pt;text-align:right;"><?=date('d.m.Y H:i', strtotime($new['publish_date_c']))?></div>
						<h3><?=$new['name']?></h3>
						<p class="card-text text-secondary" style="font-style:italic;"><?=mb_strimwidth(strip_tags(html_entity_decode($new['text_c'])),0, 150, "...")?></p>
						<div class="d-flex justify-content-between">
							<a href="<?=$link_news?>" class="btn btn-lg btn-default text-white btn-block btn-rounded shadow" style="background:<?=App::$current_organization->main_color_c?>;padding-top:5px;padding-bottom:5px;max-width:200px;margin:0 auto;"><span>Читать целиком</span></a>
						</div>
					</div>
				</div>
			</div>
		<?php 
				}
			} 
			else { 
		?>
			<span style="text-align:center;width:100%;">Нет опубликованных новостей</span>
		<?php } ?>
		
	</div>
</div>
<!-- END Блог -->

<?php  include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>