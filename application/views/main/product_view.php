<?php
	global $db;
	$product = $db->fetchRow($db->query(
		"SELECT 
			pp.id,
            pp.name,
            pp.description,
			pp_cstm.tag_c,
            pp_cstm.tag_color_c,
            pp_cstm.tag_text_color_c,
            pp_cstm.sale_price_c,
            pp_cstm.tag_c,
			pp_cstm.use_time_work_c,
			pp_cstm.time_work_timezone_c,
			pp_cstm.times_work_mo_c,
			pp_cstm.times_work_tu_c,
			pp_cstm.times_work_we_c,
			pp_cstm.times_work_th_c,
			pp_cstm.times_work_fr_c,
			pp_cstm.times_work_sa_c,
			pp_cstm.times_work_su_c,
            img_pp.img_img_images_prdct_products_1img_img_images_ida as image_id
		FROM prdct_products pp 
		JOIN prdct_products_cstm pp_cstm ON pp_cstm.id_c = pp.id AND pp.deleted = 0
        JOIN img_img_images_prdct_products_1_c img_pp ON img_pp.img_img_images_prdct_products_1prdct_products_idb = pp.id AND img_pp.deleted = 0
		WHERE pp.id = '".$data['product_id']."';
	"));
	
	//делаем проверку на отображение продукта
	$product['visible'] = 'show';
	if($product['use_time_work_c']){
		$product['visible'] = 'hide';
		
		date_default_timezone_set('UTC'); //выставляем гринвич
		
		$currentWeekDay = date('w'); //текущий день недели
		$currentTime = date("H:i", strtotime('+'.$product['time_work_timezone_c'].' hours')); // текущее время
		
		if($currentWeekDay == 1){//понедельник
			$times_work = explode('^|^',$product['times_work_mo_c']);
		}elseif($currentWeekDay == 2){//вторник
			$times_work = explode('^|^',$product['times_work_tu_c']);
		}elseif($currentWeekDay == 3){//среда
			$times_work = explode('^|^',$product['times_work_we_c']);
		}elseif($currentWeekDay == 4){//четверг
			$times_work = explode('^|^',$product['times_work_th_c']);
		}elseif($currentWeekDay == 5){//пятница
			$times_work = explode('^|^',$product['times_work_fr_c']);
		}elseif($currentWeekDay == 6){//суббота
			$times_work = explode('^|^',$product['times_work_sa_c']);
		}elseif($currentWeekDay == 0){//воскресенье
			$times_work = explode('^|^',$product['times_work_su_c']);
		}
		
		foreach($times_work as $k => $time_work){
			$time_work = explode(' - ', $time_work);
			$time_work_start = $time_work[0];
			$time_work_end = $time_work[1];
			if($currentTime >= $time_work_start && $currentTime <= $time_work_end){
				$product['visible'] = 'show';
			}
		}
	}
	
?>

<?php
	$header_name = 'О продукте';
	$header_tag = 'span';
	$header_back_url = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'];
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>
<? 
	$count = 0;
	if(isset($data['order_products'])){
		foreach($data['order_products'] as $orderProduct){
			if($orderProduct['product']->id == $product['id']){
				$count = $orderProduct['count'];
				break;
			}
		}
	}
?>
<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:50px;" <?php } ?>>
	<div class="jumbotron bg-white shadow-sm" style="padding-top:20px;">
		<div style="padding: 0 0 30px 0;text-align:center;">
			<h1 class="mb-2 h3 d-block product_name" data-product-id="<?=$product['id']?>" style="color:<?=App::$current_organization->main_color_c?>"><?=$product['name']?></h1>
			<? if($product['tag_c']) { ?><div class="badge" style="position:absolute;top:120px;background-color:<?=$product['tag_color_c']?>;color:<?=$product['tag_text_color_c']?>;margin-left:10px;"><?=$product['tag_c']?></div><? } ?>
			<img class="product_image_<?=$product['id']?>" data-product-id="<?=$product['id']?>" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$product['image_id']?>_image_c" alt="Фото <?=$product['name']?>" style="border-radius: .25rem;width:100%;max-width:450px;">
		</div>
		
		<div class="row mb-4">
			<div class="col-4">
				<!--<button class="btn btn-sm btn-link p-0"><i class="material-icons md-18">favorite_outline</i></button>
				<a class="btn btn-sm btn-default btn-rounded ml-2" data-toggle="modal" data-target="#openShare"><i class="material-icons mb-18 mr-2">share</i>Поделиться</a>-->
				<script defer="" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/share.js?banner=off"></script>

				<!--<p class="text-secondary my-3 small">
					<i class="material-icons text-warning md-18 vm">star</i>
					<i class="material-icons text-warning md-18 vm">star</i>
					<i class="material-icons text-warning md-18 vm">star</i>
					<i class="material-icons text-warning md-18 vm">star</i>
					<i class="material-icons text-warning md-18 vm">star</i>
					<span class="text-dark vm ml-2">Рейтинг 4.2</span> <span class="vm">на основе 245 голосов</span>
				</p>-->
				
				<div class="text-success font-weight-normal mb-0 product_price" style="font-size:1.75rem;" data-product-id="<?=$product['id']?>"><?=$product['sale_price_c']?> ₽</div>
			</div>
			<? if(App::$current_landing->delivery_active_c || App::$current_landing->pickup_c){ ?>
				<? if($product['visible'] == 'show' ){ ?>
					<div class="col-8 text-right">
						<div class="btn-group btn-group-lg mb-2 mt-2">
							<?php if($count > 0){ ?>
							<button type="button" class="btn btn-primary btn-minus-product" data-product-id="<?=$product['id']?>" style="width: 60px;background:<?=App::$current_organization->color_product_btn_c?>;">-</button>
							<?php } ?>
							<button type="button" class="btn btn-primary active" style="background:<?=App::$current_organization->color_product_btn_c?>;width:50px;padding:0px;">
								<input type="text" style="border:none;background:none;outline:none;padding:0;color:#fff;width:50px;text-align:center;" class="product_count" data-product-id="<?=$product['id']?>" value="<?=$count?>" readonly="">
							</button>
							<?php if($count > 0){ ?>
								<button type="button" class="btn btn-primary btn-add-product mr-1" data-product-id="<?=$product['id']?>" style="background:<?=App::$current_organization->color_product_btn_c?>;width: 60px;">+</button>
							<?php } else { ?>
								<button type="button" class="btn btn-primary btn-add-product mr-1" data-product-id="<?=$product['id']?>" style="background:<?=App::$current_organization->color_product_btn_c?>;">Добавить</button>
							<?php } ?>
						</div>
					</div>
				<? } ?>
			<? } ?>
		</div>

		<p class="text-secondary"><?=html_entity_decode($product['description'])?></p>
		
		<div class="row mb-4">
			<div class="col-4">
				<a class="btn btn-sm btn-default btn-rounded ml-2" data-toggle="modal" data-target="#openShare" style="width:150px;"><i class="material-icons mb-18 mr-2">share</i>Поделиться</a>
			</div>
		</div>

		<div class="row p-3">
			<script type="text/javascript" src="https://vk.com/js/api/openapi.js?167"></script>

			<script type="text/javascript">
			  VK.init({apiId: <?=App::$current_landing->id_reviews_c?>, onlyWidgets: true});
			</script>

			<div id="vk_comments"></div>
			<script type="text/javascript">
			VK.Widgets.Comments("vk_comments", {limit: 15, attach: "*", pageUrl: "<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/product/<?=$product['id']?>"});
			</script>
		</div>
	</div>
</div>

<?php include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>