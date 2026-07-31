<?php
	$header_name = 'Вопрос - Ответ';
	$header_back_url = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'];
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

<!-- Вопрос/Ответ -->
<div class="container mt-2" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
	<?php 
		global $db;
		$queryFaq = $db->query("
			SELECT 
				ff.*,
				ff_cstm.*
			FROM faq_faq ff
			LEFT JOIN faq_faq_cstm ff_cstm ON ff_cstm.id_c = ff.id AND ff.deleted = 0
			LEFT JOIN lngng_landings_faq_faq_1_c ll_ff ON ll_ff.lngng_landings_faq_faq_1faq_faq_idb = ff_cstm.id_c AND ll_ff.deleted = 0
			WHERE 
				ll_ff.lngng_landings_faq_faq_1lngng_landings_ida = '".App::$current_landing->id."'
			ORDER BY ff_cstm.sorting_c ASC;
		");
		$faqs = [];
		while($faq = $db->fetchByAssoc($queryFaq)) {
			$faqs[] = $faq;
		}
		
		if($faqs){	
	?>
	<div id="accordion">
		<? 
			for ($count=0; $count<count($faqs); $count++) {
		?>
		<div class="card mb-2 border-0 shadow-sm collapsed" data-toggle="collapse" data-target="#collapse<?=$faqs[$count]['id']?>" aria-expanded="true" aria-controls="collapse<?=$faqs[$count]['id']?>" style="cursor:pointer;">
			<div class="card-header p-3" id="heading<?=$faqs[$count]['id']?>" style="font-size:15pt;">
				<?=$faqs[$count]['name']?>
			</div>

			<div id="collapse<?=$faqs[$count]['id']?>" class="collapse <? if($count == 0) { ?> show <? } ?>" data-parent="#accordion">
				<div class="card-body">
					<?=html_entity_decode($faqs[$count]['answer_c'])?>
				</div>
			</div>
		</div>
		<? } ?>
	</div>
	<?php }	?>
</div>
<!-- END Вопрос/Ответ -->


<?php  include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>