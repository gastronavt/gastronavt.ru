<style>
/*---TABLE--*/
@media 
only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px)  {
	.table-card table, .table-card thead, .table-card tbody, .table-card th, .table-card td, .table-card tr { 
		display: block;
	}
	
	.table-card thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
	
	.table-card tr { 
		border: 1px solid #ccc;
		border-bottom:5px solid rgb(255, 127, 1);
		text-align:center;
	}
	
	.product_sum_price {
		font-size:18pt;
	}
	
	td { 
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
	}
	
	td:before { 
		position: absolute;
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
	}
}
.table-card { 
	background:#fff;
	border-radius:.25rem;
	box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
	birder:1px solid #eee;
}
.table-card tr img{ 
	width:auto;
	max-height:100px;
	margin:0 auto;
	padding:5px;
	border-radius:10px;
}
/*--END TABLE--*/

</style>

<div class="container mb-3 bg-template shadow-sm" style="padding:20px;">
	<center style="width:100%">
		<img class="avatar avatar-60" style="display:inline-block;" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/good_green.png" alt="Спасибо за заказ" title="Спасибо за Ваш заказ">
		<div style="display:inline-block;">
			<h1 class="mb-1 text-white" style="font-size:19pt;">Спасибо за заказ!</h1>
			<p class="text-mute small text-white">Всегда Ваша - доставка из «<?=App::$current_organization->name_rus_c?>»</p>
		</div>
	</center>
</div>

<!--<div class="card-body border-bottom">
	<div class="row">
		<div class="col">
			<h5 class="mb-0 font-weight-normal text-center">Номер Вашего заказа #<?=$_SESSION['old_order']->name?></h5>
		</div>
	</div>
</div>-->
<div class="card-body bg-none px-4">
	<div class="row">
		<center style="width:100%;">
			<div style="max-width:600px;margin:0 auto;text-align:left;padding:10px;">
				<?php if(isset($_SESSION['old_order']->CUSTOM_pay_online) && $_SESSION['old_order']->CUSTOM_pay_online == 'yes') { ?>
					<p> Заказ успешно оплачен: <b><?=$_SESSION['old_order']->all_price_c?> руб</b></p>
				<?php } else {
					if($_SESSION['old_order']->receiving_method_c == '01') {
						$delivery_method = 'курьеру';
						$price = $_SESSION['old_order']->all_price_c;
					}
					elseif($_SESSION['old_order']->receiving_method_c == '02') {
						$delivery_method = 'при самовывозе';
						$price = $_SESSION['old_order']->sale_price_c;
					}
					
					if($_SESSION['old_order']->pay_method_c == '02') {
						$pay_method = 'через приложение Сбербанк-онлайн '.$delivery_method;
					}
					elseif($_SESSION['old_order']->pay_method_c == '04') {
						$pay_method = 'через терминал '.$delivery_method;
					}
					elseif($_SESSION['old_order']->pay_method_c == '03') {
						$pay_method = 'оплачено онлайн ';
					}
					elseif($_SESSION['old_order']->pay_method_c == '01') {
						$pay_method = 'наличными '.$delivery_method;
					}
					elseif($_SESSION['old_order']->pay_method_c == '05') {
						$pay_method = 'QR-код СПБ '.$delivery_method;
					}
				?>
					<p> Сумма к оплате (<?=$pay_method?>): <b><?=$price?> руб</b></p>
				<?php } ?>
				<p> 
					Ожидайте звонка оператора <img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/dispatcher.png" alt="Оператор службы доставки" title="На картинке изображено условное изображение оператора службы доставки. Выполенено в синем цвете." style="width:20px;margin-left:5px;margin-right:5px;"> на Ваш номер: <b><?=$_SESSION['old_order']->phone_c?></b>
					<div class="small" style="margin-top:-10px;text-align:right;">
						<? if(!empty(App::$current_landing->payment_message_c)) { ?>
							* <?=App::$current_landing->payment_message_c?>
						<? } else { ?>
							* Заказ не считается оформленным без подтверждения заказа оператором.
						<? } ?>
					</div>
				</p>
			</div>
			
			<div>
				<?php if($_SESSION['old_order']->CUSTOM_products != NULL){ ?>
					<table class="table table-hover table-card" style="max-width:600px;">
						<thead>
							<tr>
								<th style="text-align:center;">Продукт</th>
								<th style="text-align:center;">Количество</th>
							</tr>
						</thead>
						<tbody>
						<?php
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
						?>
								<tr id="product<?=$product_unique->id?>">
									<td style="text-align:center;vertical-align: middle;">
										<div class="mb-1 mt-2 h6 d-block" style="color:<?=App::$current_organization->main_color_c?>"><?=$product_unique->name?></div>
										<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$product_unique->img_img_images_prdct_products_1img_img_images_ida?>_image_c" alt="Покупаемый продукт" />
									</td>
									<td style="text-align:center;vertical-align: middle;">
										<span><?=$product_unique->sale_price_c*$count?></span> руб. <div style="font-size:10pt;margin-top:-5px;">за <span><?=$count?></span> шт.</div>
									</td>
								</tr>
						<?php	
							}
						?>
							<tr id="promo_block" <?php if($_SESSION['old_order']->CUSTOM_promo_work != 'yes') { ?> style="display:none;vertical-align: middle;" <?php } ?> >
								<td style="text-align:center;vertical-align: middle;">
									<div class="mb-1 mt-2 h6 d-block" style="color:<?=App::$current_organization->main_color_c?>">Промо-код: <span><?=$_SESSION['old_order']->CUSTOM_promo_code?></span></div>
									<img style="width:auto;max-height:100px;margin:0 auto;padding:5px;border-radius:10px;" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/promo.png" alt="Промо-код" />
								</td>
								<td style="text-align:center;vertical-align: middle;">
									<div>
										<?=$_SESSION['old_order']->CUSTOM_promo_html?>
									</div>
								</td>
							</tr>
							<tr <?php if($_SESSION['old_order']->delivery_price_c === NULL || $_SESSION['old_order']->receiving_method_c == '02') { ?> style="display:none;" <?php } ?>>
								<td style="text-align:center;vertical-align: middle;">
									<div class="mb-1 mt-2 h6 d-block" style="color:<?=App::$current_organization->main_color_c?>"><span>Доставка</span></div>
									<img style="width:auto;max-height:100px;margin:0 auto;padding:5px;border-radius:10px;" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/skuter.png" alt="Доставка" />
								</td>
								<td style="text-align:center;vertical-align: middle;">
									<span><?=$_SESSION['old_order']->delivery_price_c?></span> руб.	
								</td>
							</tr>
						</tbody>
					</table>
				<?php } ?>
			</div>
		</center>
	</div>
</div>