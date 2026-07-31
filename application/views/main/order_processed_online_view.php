<?php
	echo '<meta http-equiv="refresh" content="60">';//обновление страницы
	
	$header_name = 'Спасибо за заказ!';
	$header_back_url = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'];
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<? if(App::$current_landing->status_c == '02') { ?>
<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
	<div class="card bg-template shadow mt-4 h-190" >
		<div class="card-body">
			<center style="width:100%">
				<img class="avatar avatar-60" style="display:inline-block;" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/good_green.png" alt="Спасибо за заказ" title="Спасибо за Ваш заказ">
				<div style="display:inline-block;">
					<h1 class="mb-1 text-white" style="font-size:19pt;">Прием заказов временно приостановлен</h1>
					<p class="text-mute small text-white">Работа приостановлена по техническим причинам!</p>
				</div>
			</center>
		</div>
	</div>
</div>
<div class="container mt-2">
	<div class="card mb-4 shadow">
		<div class="card-body border-bottom">
			<div class="row px-4 py-2">
				<p>Уважаемый(ая), <?=$_SESSION['old_order']->client_name_c?>! К сожалению, сервис доставки в данный момент не работает.</p>
				<p>Доставка заказов не осуществляется! Ваш заказ автоматически был отменён.</p>
				<p>Мы делаем все возможное для устранения данной проблемы. Приносим Вам наши извинения за оказанные неудобства!</p>
			</div>
		</div>
	</div>
</div>
<? } else { ?>


<div class="bg-white" style="padding-top:50px;padding-left:5px;padding-right:5px;padding-bottom:100px;<?php if(!empty(App::$current_aggregator)){ ?> padding-top:100px; <?php } ?>">
	<div style="max-width:992px; margin:0 auto;margin-top:20px;">
		<?
			$preOrderBean = BeanFactory::getBean('pordr_preorder', $_SESSION['old_order']->id);
			
			if(!empty($preOrderBean->ordrs_orders_id_c)){				
				$order = $db->fetchRow($db->query("
					SELECT 
						oo.name, 
						oo.date_entered, 
						oo.id,
						oo_cstm.address_c,
						oo_cstm.street_c,
						oo_cstm.home_c,
						oo_cstm.room_c,
						oo_cstm.status_c,
						oo_cstm.receiving_method_c,
						oo_cstm.pay_method_c,
						oo_cstm.receiving_street_c,
						oo_cstm.receiving_home_c,
						oo_cstm.delivery_price_c,
						oo_cstm.all_price_c,
						oo_cstm.sale_price_c
					FROM ordrs_orders oo
						JOIN ordrs_orders_cstm oo_cstm ON oo_cstm.id_c = oo.id AND oo.deleted = 0 AND oo.id = '".$preOrderBean->ordrs_orders_id_c."'
				"));
				$order_name = 'Заказ № '.$order['name'];
				
				if( $order['receiving_method_c'] == '01' ){
					$receiving_method = 'доставка';
					$address = $order['street_c'];
					if($order['home_c']){
						$address .= ', д. '.$order['home_c'];
					}
					$price = $order['all_price_c'];
				} elseif( $order['receiving_method_c'] == '02' ){
					$receiving_method = 'самовывоз';
					$address = $order['receiving_street_c'];
					if($order['receiving_home_c']){
						$address .= ', д. '.$order['receiving_home_c'];
					}
					$price = $order['sale_price_c'];
				}
				
				if(in_array($order['status_c'], ['01','13','12', '02', '10', '99'])) {
					$status_message = 'Заказ передан оператору';
					if(!empty(App::$current_landing->payment_message_c)) {
						$status_info = html_entity_decode(App::$current_landing->payment_message_c);
					} else {
						$status_info = 'Ожидайте звонка оператора на Ваш номер: <b>'.$_SESSION['old_order']->phone_c.'</b><br>* Заказ не считается оформленным без подтверждения заказа оператором';
					}
					
					$status_image = 'dispatcher.jpg';
					$status_color = '#00BE7D';
					$status_persent = '33%';
				} elseif( $order['receiving_method_c'] == '02' && $order['status_c'] == '08' ){ //если самовывоз
					$status_message = 'Заказ готов, ожидает вас на точке самовывоза: ';
					$status_info = $address;
					$status_image = 'clock.jpg';
					$status_color = '#00BE7D';
					$status_persent = '80%';
				} elseif( in_array($order['status_c'], ['11','08','12']) ){
					$status_message = 'Заказ принят и передан на кухню';
					$status_info = 'Сделаем вкусно!';
					$status_image = 'cook.jpg';
					$status_color = '#00BE7D';
					$status_persent = '50%';
				} elseif( in_array($order['status_c'], ['03','04','05', '06', '07']) ){
					$status_message = 'Курьер спешит к вам';
					$status_info = 'Торопится, но соблюдает ПДД!';
					$status_image = 'courier.jpg';
					$status_color = '#00BE7D';
					$status_persent = '66%';
				} elseif( $order['status_c'] == '09' ){
					$status_message = 'Заказ доставлен';
					$status_info = 'Приятного аппетита!';
					$status_image = 'delivered.jpg';
					$status_color = '#00BE7D';
					$status_persent = '100%';
				} elseif(in_array($order['status_c'], ['20'])  ){
					$status_message = 'Заказ отменен';
					$status_image = 'cancel.jpg';
					$status_color = '#fc4646';
					$status_persent = '100%';
				}
				
				if($order['pay_method_c'] == '03') {
					$pay_method = 'Онлайн';
				}
				
				$sharedPromoCode = $db->fetchRow($db->query("
					SELECT dd.*, dd_cstm.*  
					FROM lngng_landings_dscnt_discount_1_c ll_dd
					JOIN dscnt_discount dd ON dd.id = ll_dd.lngng_landings_dscnt_discount_1dscnt_discount_idb AND ll_dd.deleted = 0 and dd.deleted = 0
					JOIN dscnt_discount_cstm dd_cstm ON dd_cstm.id_c = dd.id AND dd.deleted = 0
					WHERE 
						ll_dd.lngng_landings_dscnt_discount_1lngng_landings_ida = '".App::$current_landing->id."'
						AND dd_cstm.shared_promo_code_c = 1
					LIMIT 1;
				"));
				
				$query_products = $db->query("
					SELECT 
						pp.name,
						pp.id,
						pp_io_cstm.sale_price_c,
                        count(pp.name) as count,
						ii_pp.img_img_images_prdct_products_1img_img_images_ida as image_id
					FROM ordrs_orders oo
						JOIN ordrs_orders_cstm oo_cstm ON oo_cstm.id_c = oo.id AND oo.deleted = 0 AND oo.id = '".$order['id']."'
						JOIN ordrs_orders_prord_products_in_order_1_c oo_pp ON oo_pp.ordrs_orders_prord_products_in_order_1ordrs_orders_ida = oo_cstm.id_c AND oo_pp.deleted = 0
						JOIN prord_products_in_order pp_io ON pp_io.id = oo_pp.ordrs_orde5b35n_order_idb AND pp_io.deleted = 0
						JOIN prord_products_in_order_cstm pp_io_cstm ON pp_io_cstm.id_c = pp_io.id
						JOIN prdct_products pp ON pp.id = pp_io_cstm.prdct_products_id_c
						JOIN img_img_images_prdct_products_1_c ii_pp ON ii_pp.img_img_images_prdct_products_1prdct_products_idb = pp_io_cstm.prdct_products_id_c AND ii_pp.deleted = 0
					GROUP BY pp.name,
						pp.id,image_id
				");
				
				
				$products = [];
				while($product = $db->fetchByAssoc($query_products)) {
					$products[] = $product;
				}
				

			} else {
				$order_name = 'Ожидается оплата заказа...';
			
				if($_SESSION['old_order']->receiving_method_c == '01' ){
					$receiving_method = 'доставка';
					$address = $_SESSION['old_order']->street_c;
					if($_SESSION['old_order']->home_c){
						$address .= ', д. '.$_SESSION['old_order']->home_c;
					}
					$price = $_SESSION['old_order']->all_price_c;
				} elseif($_SESSION['old_order']->receiving_method_c == '02' ){
					$receiving_method = 'самовывоз';
					$address = $_SESSION['old_order']->receiving_street_c;
					if($_SESSION['old_order']->receiving_home_c){
						$address .= ', д. '.$_SESSION['old_order']->receiving_home_c;
					}
					$price = $_SESSION['old_order']->sale_price_c;
				}
				
				$status_message = 'Заказ ожидает оплаты';

				if(!empty(App::$current_landing->payment_message_c)) {
					$status_info = html_entity_decode(App::$current_landing->payment_message_c);
				} else {
					$status_info = 'Заказ будет отменен автоматически через 15 минут';
				}
				
				$status_image = 'dispatcher.jpg';
				$status_color = '#00BE7D';
				$status_persent = '33%';
			
				if($_SESSION['old_order']->pay_method_c == '03') {
					$pay_method = 'Онлайн';
				}


				$products = [];
				foreach($_SESSION['old_order']->CUSTOM_products as $product){
					$products_id[] = $product->id;
				}
				$products_unique_id = array_unique($products_id);//уникальные id продуктов
				foreach($products_unique_id as $product_unique_id){
					$count = 0;
					$product_unique = BeanFactory::getBean('prdct_products', $product_unique_id);
					foreach($_SESSION['old_order']->CUSTOM_products as $product){
						if($product_unique->id == $product->id){
							$count++;
						}
					}
					$products[] = [
						'name' => $product_unique->name,
						'id' => $product_unique->id,
						'sale_price_c' => $product_unique->sale_price_c,
						'count' => $count,
						'image_id' => $product_unique->img_img_images_prdct_products_1img_img_images_ida,
					];
				}
			}
		?>
			<h6 class="subtitle" style="margin:0px 0px 15px 10px;font-size:14pt;">
					<?=$order_name?>
				</h6>
				<div class="row m-0 p-3">
					<div class="col-md-5 p-2">
						<div class="p-2" style="border:1px solid #eee; border-radius: 1.125rem;box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);">
							<div class="row m-0 align-items-center">
								<div class="col-4">
									<img class="rounded-circle" style="max-width:75px;" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/<?=$status_image?>" />
								</div>
								<div class="col-8">
									<div class="h6"><?=$status_message?></div>
									<div class="small">
										<?=$status_info?>
									</div>
								</div>
							</div>
							<div class="row m-0">
								<div class="col-12 m-2">
									<div class="progress">
										<div class="progress-bar" role="progressbar" style="width:<?=$status_persent?>; background-color: <?=$status_color?>;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</div>
						</div>

						<div class="row align-items-center p-2 mt-4" style="border:1px solid #eee; border-radius: 1.125rem;box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);margin:0px 0px 15px 10px;">
							<div class="col-7">
								<div>
									<?=mb_convert_case($_SESSION['old_order']->client_name_c, MB_CASE_TITLE, "UTF-8")?>, а пока Вы ждёте, нажмите кнопочку ПОДЕЛИТЬСЯ.
									<br>😍 Это поможет нам сделать сервис лучше!
								</div>
							</div>
							<div class="col-5">
								<a class="btn btn-sm btn-default btn-rounded" data-toggle="modal" data-target="#openShare" style="width:120px;"><i class="material-icons mb-18 mr-2">share</i><br>Поделиться</a>
							</div>
						</div>
						
						<? if(App::$current_landing->phone1_c != NULL) { ?>
							<a class="btn btn-lg btn-default text-white btn-block btn-rounded shadow mt-3" href="tel:<?=App::$current_landing->phone1_c?>" style="max-width:100%;font-size:11pt;margin:0 auto;">Связаться с нами</a>
						<? } ?>
						
					</div>
					<div class="col-md-6 mt-4 mt-sm-0 ml-0 ml-md-4">
						<h6 class="subtitle" style="margin:0px 0px 15px 10px;font-size:14pt;">Состав заказа</h6>
						<?php
							foreach($products as $product){
								if($product['count'] > 0 ){
						?>
							<div class="row align-items-center border-bottom py-3">
								<div class="col-4">
									<img class="rounded" style="max-width:100px;" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$product['image_id']?>_image_c" />
								</div>
								<div class="col-5">
									<div style="color:<?=App::$current_organization->main_color_c?>;font-size:11pt;"><?=$product['name']?></div>
								</div>
								<div class="col-3">
									<span><?=$product['sale_price_c']?></span> ₽
									<div style="font-size:10pt;margin-top:-5px;">за <?=$product['count']?> шт.</div>
								</div>
							</div>
						<?php
								}
							}
						?>
						<?php if($_SESSION['old_order']->CUSTOM_promo_work == 'yes') { ?>
							<div class="row align-items-center border-bottom py-3">
								<div class="col-4">
									<img class="rounded" style="max-width:100px;" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/promo.png" />
								</div>
								<div class="col-5">
									<div style="color:<?=App::$current_organization->main_color_c?>;font-size:11pt;">Промо-код: <span><?=$_SESSION['old_order']->CUSTOM_promo_code?></div>
								</div>
								<div class="col-3">
									<span><?=$_SESSION['old_order']->CUSTOM_promo_html?></span>
								</div>
							</div>
						<? } ?>
						<?php if($_SESSION['old_order']->delivery_price_c !== NULL || $_SESSION['old_order']->receiving_method_c == '01') { ?>
							<div class="row align-items-center border-bottom py-3">
								<div class="col-4">
									<img class="rounded" style="max-width:70px;" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/skuter.png" />
								</div>
								<div class="col-5">
									<div style="color:<?=App::$current_organization->main_color_c?>;font-size:11pt;">Доставка</div>
								</div>
								<div class="col-3">
									<span><?=$_SESSION['old_order']->delivery_price_c?></span> ₽
								</div>
							</div>
						<? } ?>
							<div class="row align-items-center border-bottom py-3">
								<div class="col-9">
									<div style="font-size:16pt;">ИТОГО</div>
									<div class="text-secondary small"><?=$pay_method?></div>
								</div>
								<div class="col-3 ">
									<b><?=$price?> ₽</b>
								</div>
							</div>
						
					</div>	
				</div>
		
	</div>
</div>

<? } ?>

<!--
<? if( App::$current_landing->id == '6cf59565-a918-ff2e-9d5d-5bf32ae80893'){ ?>
	<?php  include CORE_FOLDER.'/application/views/widget/appeal.php'; //видео-обращение ?>
<? } ?>
-->

<?php include CORE_FOLDER.'/application/views/widget/buttom_menu_without_card.php'; //Нижнее меню ?>

<?
	if(App::$current_landing->scripts_order_completion_c){
		echo NFfunctions::decodeString(App::$current_landing->scripts_order_completion_c);
	}
?>