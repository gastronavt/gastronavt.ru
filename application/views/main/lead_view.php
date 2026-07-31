<?php
	$header_name = 'Ваш заказ';
	$header_back_url = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'];
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<?
	$haveBonuseSystem = $db->fetchRow($db->query("
		SELECT 
			bb.name
		FROM
			bnss_bonuses_lngng_landings_1_c bl
			JOIN bnss_bonuses bb ON bb.id = bl.bnss_bonuses_lngng_landings_1bnss_bonuses_ida AND bb.deleted = 0 AND bl.bnss_bonuses_lngng_landings_1lngng_landings_idb = '".App::$current_landing->id."'  AND bl.deleted = 0
			JOIN bnss_bonuses_cstm bb_cstm ON bb.id = bb_cstm.id_c
	"))['name'];

	$bonuseSystem = '';
	$currentUserBonuse = '';
	
	if(!empty(App::$current_user->id) && !empty($haveBonuseSystem)){
		$bonuseSystem = $db->fetchRow($db->query("
			SELECT 
				bb.name, 
				bb_cstm.bonuses_name_c, 
				bbl_cstm.cashback_c,
				MAX(bbl_cstm.ransom_cost_c) as level_ransom_cost,
				bbl.name as level_name
			FROM
				bnss_bonuses_lngng_landings_1_c bl
				JOIN bnss_bonuses bb ON bb.id = bl.bnss_bonuses_lngng_landings_1bnss_bonuses_ida AND bb.deleted = 0 AND bl.bnss_bonuses_lngng_landings_1lngng_landings_idb = '".App::$current_landing->id."'  AND bl.deleted = 0
				JOIN bnss_bonuses_cstm bb_cstm ON bb.id = bb_cstm.id_c
				JOIN bnss_bonuses_bnlvl_bonuses_level_1_c bb_bbl ON bb_bbl.bnss_bonuses_bnlvl_bonuses_level_1bnss_bonuses_ida = bb_cstm.id_c AND bb_bbl.deleted = 0
				JOIN bnlvl_bonuses_level bbl ON bbl.id = bb_bbl.bnss_bonuses_bnlvl_bonuses_level_1bnlvl_bonuses_level_idb AND bbl.deleted = 0
				JOIN bnlvl_bonuses_level_cstm bbl_cstm ON bbl_cstm.id_c = bbl.id
                WHERE '".App::$current_user->paid_all_c."' >= bbl_cstm.ransom_cost_c
				HAVING MAX(bbl_cstm.ransom_cost_c) IS NOT NULL
		"));

		if(!empty($bonuseSystem)){
			$currentUserBonuse = $db->fetchRow($db->query(
				"SELECT *
				FROM clorg_client_organizations cco
				JOIN clorg_client_organizations_cstm cco_cstm ON cco_cstm.id_c = cco.id AND cco.deleted = 0 AND cco_cstm.orgns_organizations_id_c = '".App::$current_organization->id."'
				JOIN clnts_clients_clorg_client_organizations_1_c cc_cco ON cc_cco.clnts_cliea48bzations_idb = cco.id AND cc_cco.deleted = 0 AND cc_cco.clnts_clients_clorg_client_organizations_1clnts_clients_ida = '".App::$current_user->id."';
			"));
		}
	}
?>

<?php
	usort($data['current_areas'], function($a, $b) { return strcmp($a->show_order_c, $b->show_order_c); });
?>

	<form method="POST" action="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/order_check" name="create_order">
		<div class="bg-white" style="padding-top:50px;padding-left:5px;padding-right:5px;padding-bottom:100px;<?php if(!empty(App::$current_aggregator)){ ?> padding-top:100px; <?php } ?>">
			<div style="max-width:755px; margin:0 auto;">
				<?php if(!empty($_SESSION['current_order']->CUSTOM_products)){ ?>
					<div class="row m-0">
						<div class="col-6">
							<h6 class="subtitle" style="margin:0px 0px 15px 10px;font-size:18pt;">Корзина</h6>
						</div>
						<div class="col-6 d-flex justify-content-end">
							<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/delete_lead" title="Очистить корзину" style="margin:0px 0px 15px 10px;font-size:18pt;">
								<i class="material-icons">delete</i>  
							</a>
						</div>
					</div>
					<table class="table table-hover table-card">

						<?php
							$sum_all = 0;
							foreach($data['order_products'] as $product){
								if($product['count'] > 0 ){
									$sum_all = $_SESSION['current_order']->sale_price_c;
						?>
							<tr id="product<?=$product['product']->id?>">
								<td style="padding:7px 5px 7px 15px;">
									<div class="row d-flex align-items-center">
										<div class="col-sm">
											<div style="color:<?=App::$current_organization->main_color_c?>;font-size:11pt;"><?=$product['product']->name?></div>
											<input type="hidden" name="product_id[]" value="<?=$product['product']->id?>">
										</div>
										<div class="col-sm">
											<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$product['product']->img_img_images_prdct_products_1img_img_images_ida?>_image_c" alt="Покупаемый продукт" />
										</div>
									</div>
								</td>
								<td style="vertical-align: middle;">
									<div class="input-group input-group-sm" style="width:110px;margin:0 auto;">
										<? if(!empty($product['product']->no_edit_to_card_c)) { ?>
											<input type="text" id="product_count<?=$product['product']->id?>" name="count[]"  value="<?=$product['count']?>" class="w-35" value="3" style="color:#fff;border:none;border-radius:15px;background-color: <?=App::$current_organization->color_product_btn_c?>; " readonly>
										<? } else { ?>
										<div class="input-group-prepend">
											<a class="btn px-1 minus-btn btn-default" data-id="<?=$product['product']->id?>" style="color:#fff;background-color: <?=App::$current_organization->color_product_btn_c?>; ">
												<i class="material-icons" alt="Убрать -1" title="Нажмите, чтобы убрать 1 элемент из корзины">remove</i>
											</a>
										</div>
										<input type="text" id="product_count<?=$product['product']->id?>" name="count[]"  value="<?=$product['count']?>" class="w-35" value="3" style="color:#fff;border:none;background-color: <?=App::$current_organization->color_product_btn_c?>; " readonly>
										<div class="input-group-append">
											<a class="btn px-1 plus-btn btn-default" data-id="<?=$product['product']->id?>" style="color:#fff;background-color: <?=App::$current_organization->color_product_btn_c?>; ">
												<i class="material-icons"  alt="Добавить +1" title="Нажмите, чтобы добавить 1 элемент в корзину">add</i>
											</a>
										</div>
										<? } ?>
									</div>
								</td>
								<td style="text-align:right;vertical-align:middle;width:120px;padding:0px 10px 0px 0px;margin:0px;">
									<input id="product_price<?=$product['product']->id?>"  value="<?=$product['product']->sale_price_c?>" type="hidden" />
									<span class="product_sum_price" id="product_sum_price<?=$product['product']->id?>"><?=$product['sale']?></span> руб. <div id="product_count_two<?=$product['product']->id?>" style="font-size:10pt;margin-top:-5px;">за <span id="product_count2<?=$product['product']->id?>" ><?=$product['count']?></span> шт.</div>
								</td>
							</tr>
						<?php
								}
							}
						?>
							<tr id="promo_block" <?php if($_SESSION['current_order']->CUSTOM_promo_work != 'yes') { ?> style="display:none;vertical-align: middle;" <?php } ?> >
								<td style="padding:7px 5px 7px 15px;">
									<div class="row d-flex align-items-center">
										<div class="col">
											<div style="color:<?=App::$current_organization->main_color_c?>;font-size:11pt;">Промокод: <span id="promo_code_num" ><?=mb_strtoupper($_SESSION['current_order']->CUSTOM_promo_code)?></span></div>
										</div>
										<div class="col">
											<div id="promo_html">
												<?=$_SESSION['current_order']->CUSTOM_promo_html?>
											</div>
										</div>
									</div>

								</td>
								<td></td>
								<td style="text-align:right;vertical-align:middle;width:120px;padding:0px 10px 0px 0px;margin:0px;">
									<?
										if(!empty($_SESSION['current_order']->CUSTOM_promo_product_sale_price)) {
											$promo_price = $_SESSION['current_order']->CUSTOM_promo_product_sale_price;
										} else {
											$promo_price = 0;
										}
									?>
									<span id="promo_price"><?=$promo_price?></span> руб.
								</td>
							</tr>

							<? if(App::$current_landing->delivery_active_c){ ?>
							<tr id="fast_delivery" style="border-bottom: 1px solid #dee2e6;border-top: 3px solid #dee2e6;" <?php if($_SESSION['current_order']->delivery_price_c === NULL || $_SESSION['current_order']->receiving_method_c == '02') { ?> style="display:none;" <?php } ?> >
								<td style="padding:15px 5px 15px 15px;text-align:left;vertical-align: middle;" colspan="2">
									<div class="row d-flex align-items-center">
										<div class="col-4">
											<div style="color:<?=App::$current_organization->main_color_c?>;font-size:11pt;"><span id="promo_code_num" >Доставка</span></div>
										</div>
										<? if(App::$current_landing->delivery_message_c ) { ?>
											<div class="col">
												<div style="font-size:11px;"><?=html_entity_decode(App::$current_landing->delivery_message_c)?></div>
											</div>
										<? } ?>
										<? if(App::$current_landing->delivery_free_c && $_SESSION['current_order']->delivery_price_c > 0 ) { ?>
											<div class="col">
												<div style="font-size:8pt;">
												<? if(App::$current_landing->message_delivery_free_c) { ?>
													<br><?=html_entity_decode(App::$current_landing->message_delivery_free_c)?>
												<? } else { ?>
													<br>* Доставка будет бесплатной, при заказе товаров на сумму от <strong><?=App::$current_landing->delivery_free_c?></strong> руб.
												<? }  ?>
												</div>
											</div>
										<? } ?>
									</div>
								</td>
								<td style="text-align:right;vertical-align:middle;width:120px;padding:0px 10px 0px 0px;margin:0px;">
									<span class="product_sum_price" id="delivery_price"><?=$_SESSION['current_order']->delivery_price_c?></span> руб.
								</td>
							</tr>
							<tr id="no_fast_delivery" class="itemTable" align="center" <?php if($_SESSION['current_order']->delivery_price_c !== NULL) { ?> style="display:none;" <?php } ?> >
								<td colspan="3" id="area_scroll" style="text-align:center;vertical-align: middle;">
									<span style="font-size:10pt;">
										* Чтобы узнать стоимость доставки, укажите Ваш район
									</span>
									<a id="go_area" class="btn btn-lg btn-default text-white btn-block btn-rounded shadow" style="background:rgb(180, 4, 9);font-size:9pt;padding:5px 4px;margin-top:5px;display:block;">
										<span>Указать</span>
									</a>
									<?php if(App::$current_landing->delivery_free_c != NULL){ ?>
										<span style="font-size:10pt;">
											* Или доставка будет бесплатной, при заказе на сумму от <strong><?=App::$current_landing->delivery_free_c?></strong> руб.
										</span>
									<?php } ?>
								</td>
							</tr>
							<? } ?>
							<tr class="itemTable" style="height:70px;" >
								<td colspan="3">
									<div class="description">
										<span id="sum_all" style="font-size:13pt;padding-top:15px;">
											<span>Сумма заказа: </span>
											<span id="all_price_c" style="display:inline;font-size:16pt;color:green;" ><?=$_SESSION['current_order']->all_price_c?></span> руб.
											<span id="delivery_text" style="display:inline;font-size:13pt;"><?=$_SESSION['current_order']->CUSTOM_delivery_text?> </span>
										</span>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				<?php } ?>
				
				<? if(!empty($haveBonuseSystem)) { ?>
					<? if(!empty(App::$current_user)) { ?>
						<? if(!empty($bonuseSystem) &&!empty($currentUserBonuse) && $currentUserBonuse['bonuses_c'] > 0) { ?>
							<div style="margin:0px 10px;">
								<div id="bonuses_block" class="card shadow mt-3" <? if($_SESSION['current_order']->client_money_discount_c) { ?> style="border: 2px solid <?=App::$current_organization->main_color_c?>"; <? } ?> >
									<div class="card-body">
										<div class="row">
											<div class="col-6">
												<h6 class="form-check-label" for="use_bonuses" >
													Списать бонусы 
													<input name="use_bonuses" class="form-check-input" type="checkbox" <? if($_SESSION['current_order']->client_money_discount_c) { ?> checked <? } ?> id="use_bonuses" style="margin-left:10px;margin-top:-3px;width:30px;height:30px;">
												</h6>
											</div>
											<div class="col-6">
												<p style="margin:0px;font-size:11pt;text-align:right;">Доступно: <?=$currentUserBonuse['bonuses_c']?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } else { ?>
							<div style="margin:0px 10px;">
								<div class="card shadow mt-3" >
									<div class="card-body">
										<div class="row">
											<div class="col-auto align-self-center pr-0">
												<span class="btn btn-success button-rounded-26">!</span>
											</div>
											<div class="col-10">
												<h6 class="form-check-label" style="font-size:10pt;padding-top:3px;">
													Вам будут начислены бонусы за заказ!
												</h6>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					<?php } else { ?>
							<div style="margin:0px 10px;">
								<div class="card shadow mt-3" >
									<div class="card-body">
										<div class="row">
											<div class="col-auto align-self-center pr-0">
												<span class="btn btn-success button-rounded-26">!</span>
											</div>
											<div class="col-10">
												<h6 class="form-check-label" style="font-size:10pt;padding-top:2px;">
													Для начисления бонусов - <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/login">авторизуйтесь</a>
												</h6>
											</div>
										</div>
									</div>
								</div>
							</div>
					<?php } ?>
				<?php } ?>
		

				<!-- swiper js -->
				<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/vendor/swiper/js/swiper-bundle.min.js"></script>
				<!-- swiper -->

				<?php include CORE_FOLDER.'/application/views/widget/recommended_products.php'; //связанные-рекомендуемые товары ?>

				<?php include CORE_FOLDER.'/application/views/widget/group_in_basket.php'; //дополнительные категории товаров в корзине ?>

				<div style="margin:0px 10px;">
					<?php if(App::$current_landing->accept_promo_code_c){ ?>
						<h6 class="subtitle">Промокод</h6>
						<div class="row" style="text-align:left;">
							<div class="col">
								<div class="<? if($_SESSION['current_order']->CUSTOM_promo_code) {?> active <? } ?>" style="display:inline-block;vertical-align: top;">
									<input id="promo_code" value="<?=($_SESSION['current_order']->CUSTOM_promo_code)?>" placeholder="Введите промокод" autocomplete="disabled" style="width: 160px; height: 40px;font-size: 13px; border-top-left-radius: 100px;border-bottom-left-radius: 100px; border: 1px solid rgb(186, 191, 208); padding: 0px 15px;color: rgb(0, 0, 0);outline: none;">
									<div>
										<div class="tooltip-base promocode__tooltip promocode__tooltip_warning " data-testid="">
										</div>
									</div>
								</div>
								<button type="button" id="send_promo" class="btn  btn-default" style="margin-left: -6px;font-size: 14px;height: 40px;border: none;border-top-right-radius: 100px;border-bottom-right-radius: 100px;cursor: pointer;">
									Применить
								</button>
							</div>
						</div>
					<?php } ?>

					<h6 class="subtitle" style="margin-top:50px;">Контактные данные</h6>
					<div class="row">
						<?php if( App::$current_landing->request_lead_name_c){ ?>
							<?
								if(empty($_SESSION['current_order']->client_name_c) && !empty(App::$current_user->name) && App::$current_user->name != 'НЕ УКАЗАНО') {
									$_SESSION['current_order']->client_name_c = App::$current_user->name;
								}
							?>
						<div class="col-md-6">
							<div class="form-group has-float-label <? if($_SESSION['current_order']->client_name_c) {?> active <? } ?>">
								<input class="form-control" id="client_name_c" type="text" placeholder=" " value="<?=$_SESSION['current_order']->client_name_c?>">
								<label for="client_name_c">Ваше имя</label>
							</div>

						</div>
						<?php } if( App::$current_landing->request_lead_phone_c){ ?>
							<?
								if(!empty(App::$current_user->phone_c)) {
									$_SESSION['current_order']->phone_c = App::$current_user->phone_c;
								}
							?>
						<div class="col-md-6">
							<div class="form-group has-float-label <? if($_SESSION['current_order']->phone_c) {?> active <? } ?>">
								<input class="form-control" id="phone_c" type="text" placeholder="+7 (___) ___ __ __" value="<?=$_SESSION['current_order']->phone_c?>" <?php if($_SESSION['current_order']->CUSTOM_check_phone_code) { ?> style="background-color:#F5FFF7;" disabled <?php } ?> <? if(!empty(App::$current_user->phone_c)) { ?> style="background:#eee;" readonly <? } ?>>
								<label for="phone_c">Ваш номер</label>
							</div>
							<? if(!empty(App::$current_user->phone_c)) { ?>
							<a href="/main/logout/login" style="float:right;margin-top:-15px;font-size:12px;">Изменить номер</a>
							<?php } ?>
						</div>
						<?php } ?>
					</div>

					<div class="row">
						<?php if( App::$current_landing->request_lead_email_c){ ?>
							<?
								$_SESSION['current_order']->client_email_c = App::$current_user->email_c ?? '';
							?>
						<div class="col-md-6">
							<div class="form-group has-float-label <? if($_SESSION['current_order']->client_email_c) {?> active <? } ?>">
								<input class="form-control" id="client_email_c" type="text" placeholder="email@example.ru" value="<?=$_SESSION['current_order']->client_email_c?>">
								<label for="client_email_c">E-mail (отправим чек)</label>
							</div>
							<?php if(!App::$current_landing->email_required_c){ ?>
							<span style="float:right;margin-top:-15px;font-size:10px;">*необязательно для заполнения</span>
							<?php } ?>
						</div>
						<?php } ?>
						<?php if( App::$current_landing->request_lead_instagram_c){ ?>
							<?
								$_SESSION['current_order']->client_instagram_c = App::$current_user->instagram_c ?? '';
							?>
						<div class="col-md-6">
							<div class="form-group has-float-label <? if($_SESSION['current_order']->client_instagram_c) {?> active <? } ?>">
								<input class="form-control" id="client_instagram_c" type="text" placeholder="@username" value="<?=$_SESSION['current_order']->client_instagram_c?>">
								<label for="client_instagram_c">Социальная сеть(для фото портрета)</label>
							</div>
							<?php if(!App::$current_landing->order_instagram_required_c){ ?>
							<span style="float:right;margin-top:-15px;font-size:10px;">*необязательно для заполнения</span>
							<?php } ?>
						</div>
						<?php } ?>
					</div>

					<?php if(App::$current_landing->delivery_active_c && App::$current_landing->pickup_c){ ?>
						<h6 class="subtitle" style="margin-top:50px;">Способ получения заказа</h6>
						<div class="row">
							<div class="col btn-group btn-group-toggle" data-toggle="buttons">
								<label class="btn btn-secondary d-flex align-items-center justify-content-center <?php if($_SESSION['current_order']->receiving_method_c == '01') { ?> active <?php } ?>" style="height:50px;">
									<input type="radio" name="receiving_method_c" id="receiving_method_c1" autocomplete="disabled" value="01" <?php if($_SESSION['current_order']->receiving_method_c == '01') { ?> checked <?php } ?>> Доставка
								</label>
								<label class="btn btn-secondary d-flex align-items-center justify-content-center <?php if($_SESSION['current_order']->receiving_method_c == '02') { ?> active <?php } ?>" style="height:50px;">
									<input type="radio" name="receiving_method_c" id="receiving_method_c2" autocomplete="disabled" value="02" <?php if($_SESSION['current_order']->receiving_method_c == '02') { ?> checked <?php } ?>> <div>Самовывоз <?php if(App::$current_landing->pickup_products_discount_c){ ?><br><div style="font-size:10pt;">(Скидка <?=App::$current_landing->pickup_products_discount_c?>%)</div><?php } ?></div>
								</label>
							</div>
						</div>
					<?php } ?>

					<?php if(App::$current_landing->pickup_c){ ?>
						<div id="block_pickup_address" style="margin-top:20px;<?php if($_SESSION['current_order']->receiving_method_c != '02'){ ?>display:none;<?php } ?>">
							<h6 class="subtitle">Адрес самовывоза</h6>
							<div class="row">
								<div class="col">
									<span class="has-float-label">
										<select class="form-control" id="brnch_branch_id_c" required>
											<option value="no" <? if(!$_SESSION['current_order']->brnch_branch_id_c) { ?> selected="selected" <? } ?>>Не выбрано</option>
											<?php
												global $db;
												$branchs_query = $db->query("
													SELECT
														bb.id,
														bb_cstm.street_c,
														bb_cstm.home_c
													FROM
														lngng_landings_brnch_branch_1_c ll_bb
														JOIN brnch_branch bb ON bb.id = ll_bb.lngng_landings_brnch_branch_1brnch_branch_idb AND ll_bb.deleted = 0
														JOIN brnch_branch_cstm bb_cstm ON bb_cstm.id_c = bb.id AND bb.deleted = 0
													WHERE
														ll_bb.lngng_landings_brnch_branch_1lngng_landings_ida = '".App::$current_landing->id."'
														AND bb_cstm.pickup_c = 1");
												while($branch = $db->fetchByAssoc($branchs_query)) {
											?>
											<option value="<?=$branch['id']?>" <? if($_SESSION['current_order']->brnch_branch_id_c == $branch['id']){?> selected="selected" <?}?> >улица <?=$branch['street_c']?>, <?=$branch['home_c']?></option>
											<?php } ?>
										</select>
										<label style="width:150px;" for="brnch_branch_id_c">Откуда заберете заказ?</label>
									</span>
								</div>
							</div>
						</div>
					<?php } ?>

					<?php if(App::$current_landing->delivery_active_c){ ?>
						<div id="block_client_address" style="margin-top:20px;<?php if($_SESSION['current_order']->receiving_method_c == '02'){ ?>display:none;<?php } ?>">
							<h6 class="subtitle">Адрес доставки</h6>
							<? if(!empty(App::$current_user) && App::$current_user->name == 'тестё') { ?>
								<?
									global $db;
									$addresses_query = $db->query("
										SELECT DISTINCT
											oo_cstm.street_c,
											oo_cstm.home_c,
											oo_cstm.room_c,
											oo_cstm.porch_c,
											oo_cstm.level_c
										FROM clnts_clients cc
										JOIN clnts_clients_ordrs_orders_1_c cc_oo ON cc_oo.clnts_clients_ordrs_orders_1clnts_clients_ida = cc.id AND cc.deleted = 0 AND cc_oo.deleted = 0
										JOIN ordrs_orders oo ON oo.id = cc_oo.clnts_clients_ordrs_orders_1ordrs_orders_idb AND oo.deleted = 0
										JOIN ordrs_orders_cstm oo_cstm ON oo_cstm.id_c = oo.id
										WHERE 
											cc.id = '".App::$current_user->id."' 
											AND oo_cstm.city_cities_id1_c = '".App::$current_city->id."'
											AND oo_cstm.street_c != ''
										ORDER BY oo_cstm.street_c,oo_cstm.home_c,oo_cstm.room_c,oo_cstm.porch_c,oo_cstm.level_c");
										
										if(!empty($addresses_query) && $addresses_query->num_rows  > 0) {
								?>
									<div class="row" style="margin-bottom:20px;" >
										<div class="col">
											<span class="has-float-label">
												<select class="form-control" id="old_addresses" required>
													<option value="no" selected="selected" >Не выбрано</option>
													<?php
														while($old_addresses = $db->fetchByAssoc($addresses_query)) {
															$old_address = $old_addresses['street_c'];
															$old_address .= $old_addresses['home_c'] ? ', дом '.$old_addresses['home_c'] : '';
															$old_address .= $old_addresses['room_c'] ? ', кв. '.$old_addresses['room_c'] : '';
															$old_address .= $old_addresses['porch_c'] ? ' под. '.$old_addresses['porch_c'] : '';
															$old_address .= $old_addresses['level_c'] ? ', этаж '.$old_addresses['level_c'] : '';
													?>
														<option><?=$old_address?></option>
													<?php } ?>
												</select>
												<label style="width:150px;" for="brnch_branch_id_c">Куда доставить заказ?</label>
											</span>
										</div>
									</div>
								<?php } ?>
							<?php } ?>
							<?php if( App::$current_landing->request_lead_area_c && count($data['current_areas']) > 0 ){ ?>
								<div class="row" id="area_row" style="margin-bottom:20px;<?php if($_SESSION['current_order']->delivery_price_c !== null) { ?> display:none; <?php } ?>" >
									<div class="col">
										<span class="has-float-label">
											<select class="form-control" id="CUSTOM_area" required>
												<option value="">Укажите район доставки</option>
												<?php
													foreach($data['current_areas'] as $areaBean) {
														if($areaBean->is_active_c){
												?>
														<option value="<?=$areaBean->id?>" <?php if($_SESSION['current_order']->CUSTOM_area == $areaBean->id){ ?> selected <?php } ?> >
															<?=$areaBean->name?> - <?=$areaBean->delivery_price_c?> руб.
														</option>
												<?php
														}
													}
												?>
											</select>
											<label for="CUSTOM_area">Район</label>
										</span>
									</div>
								</div>
							<?php } ?>

							<div class="row">
								<div class="col-5 col-md-4" style="margin-top:-10px;text-align:left;font-size:10pt;">
									<div class="form-group label-floating">
										Ваш город
										<div style="padding-top:2px;color:green;font-size:13pt;"><?=App::$current_city->name?></div>
									</div>
								</div>
								<?php if(App::$current_landing->request_lead_street_c){ ?>
								<div class="col-7 col-md-8">
									<div class="form-group has-float-label">
										<input class="form-control" id="street_c" type="text" placeholder="Улица" autocomplete="disabled" value="<?=$_SESSION['current_order']->street_c?>">
										<label for="street_c">Улица</label>
									</div>
								</div>
								<?php } ?>
							</div>
							<div class="row">
								<?php if(App::$current_landing->request_lead_home_c){ ?>
								<div class="col-5 col-md-4">
									<div class="form-group has-float-label">
										<input class="form-control" id="home_c" type="text" placeholder="Дом" autocomplete="disabled" maxlength="70" value="<?=$_SESSION['current_order']->home_c?>">
										<label for="home_c">Дом</label>
									</div>
								</div>
								<?php } ?>
								<?php if(App::$current_landing->request_lead_room_c){ ?>
								<div class="col-7 col-md-3">
									<div class="form-group has-float-label">
										<input class="form-control" id="room_c" type="text" placeholder="Квартира" autocomplete="disabled" maxlength="30" value="<?=$_SESSION['current_order']->room_c?>">
										<label for="room_c">Квартира</label>
									</div>
								</div>
								<?php } ?>
								<?php if(App::$current_landing->request_lead_porch_c){ ?>
								<div class="col-6 col-md-3">
									<div class="form-group has-float-label">
										<input class="form-control" id="porch_c" type="text" placeholder="Подъезд" autocomplete="disabled" maxlength="30" value="<?=$_SESSION['current_order']->porch_c?>">
										<label for="porch_c">Подъезд</label>
									</div>
								</div>
								<?php } ?>
								<?php if(App::$current_landing->request_lead_level_c){ ?>
								<div class="col-6 col-md-2">
									<div class="form-group has-float-label">
										<input class="form-control" id="level_c" type="text" placeholder="Этаж" autocomplete="disabled" maxlength="30" value="<?=$_SESSION['current_order']->level_c?>">
										<label for="level_c">Этаж</label>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					
					<?php 
						if(App::$current_landing->pickup_c && App::$current_landing->is_datetime_pickup_c){ 
							$display_datetime_pickup = '';
							if($_SESSION['current_order']->receiving_method_c == '01') {
								$display_datetime_pickup = 'display: none';
							}
					?>
						<div id="block_datetime_pickup" style="<?=$display_datetime_pickup?>;">
							<h6 class="subtitle" style="margin-top:50px;">Когда заберете?</h6>
							<?php if(App::$current_landing->message_pickup_datetime_c){ // если сообщение подсказка?>
								<div style="margin-top:-15px;font-size:10px;"><?=App::$current_landing->message_pickup_datetime_c?></div>
							<?php } ?>
							<div class="row" style="margin-top:25px;">
								<div class="col-6">
									<span class="has-float-label">
										<select class="form-control" id="date_future_pickup_c" required>
											<?php foreach($_SESSION['current_order']->CUSTOM_date_future_array as $date) {?>
											<option value="<?=$date?>" <?php if($_SESSION['current_order']->date_future_delivery_c == $date){ ?> selected <?php } ?> ><?=$date?></option>
											<?php }?>
										</select>
										<label for="date_future_delivery_c">Дата</label>
									</span>
								</div>
								<div class="col-6">
									<span class="has-float-label">
										<select class="form-control" id="time_future_pickup_c" required>
											<?php foreach($_SESSION['current_order']->CUSTOM_time_future_array as $time) {?>
											<option value="<?=$time?>" <?php if($_SESSION['current_order']->time_future_delivery_c == $time){ ?> selected <?php } ?> ><?=$time?></option>
											<?php }?>
										</select>
										<label for="time_future_delivery_c">Время</label>
									</span>
								</div>
							</div>
						</div>
					<?php } ?>
					
					<?php if(App::$current_landing->accept_future_delivery_c){ // если есть доставка?>
						<?php 
							$display_datetime_delivery = '';
							if($_SESSION['current_order']->receiving_method_c == '02') {  //если в данный момент выбран самовывоз
								$display_datetime_delivery = 'display: none';
							}
						?>
						<div id="block_datetime_delivery" style="<?=$display_datetime_delivery?>;">
							<h6 class="subtitle" style="margin-top:50px;">Время и пожелания</h6>
							<?php if(App::$current_landing->message_datetime_c){ // если сообщение подсказка?>
								<div style="margin-top:-15px;font-size:10px;"><?=App::$current_landing->message_datetime_c?></div>
							<?php } ?>
							<div class="row" id="block_delivery_method" <?php if($_SESSION['current_order']->receiving_method_c == '02') { ?> style="display:none;" <?php }?>>
								<div class="col btn-group btn-group-toggle" data-toggle="buttons">
									<span id="delivery_method1_block" class="btn btn-secondary align-items-center justify-content-center <?php if($_SESSION['current_order']->delivery_method_c == '01') { ?> active <?php } ?>" style="font-size:10pt;height:50px;">
										<input type="radio" name="delivery_method_c" autocomplete="disabled" value="01" id="delivery_method1">Ближайшее время
									</span>
									<?php if(App::$current_landing->accept_future_delivery_c){ ?>
									<span id="delivery_method2_block" class="btn btn-secondary align-items-center justify-content-center <?php if($_SESSION['current_order']->delivery_method_c == '02') { ?> active <?php } ?>" style="font-size:10pt;height:50px;">
										<input type="radio" name="delivery_method_c" autocomplete="disabled" value="02" id="delivery_method2">Ко времени
									</span>
									<?php } ?>
								</div>
							</div>

							<?php 
								$display_datetime_future_delivery = '';
								if($_SESSION['current_order']->delivery_method_c == '01') {  //если ближайшее время
									$display_datetime_future_delivery = 'display: none';
								}
							?>
							<div class="row" id="block_datetime_future_delivery" style="margin-top:25px;<?=$display_datetime_future_delivery?>;">
								<div class="col-6">
									<span class="has-float-label">
										<select class="form-control" id="date_future_delivery_c" required>
											<?php foreach($_SESSION['current_order']->CUSTOM_date_future_array as $date) {?>
											<option value="<?=$date?>" <?php if($_SESSION['current_order']->date_future_delivery_c == $date){ ?> selected <?php } ?> ><?=$date?></option>
											<?php }?>
										</select>
										<label for="date_future_delivery_c">Дата</label>
									</span>
								</div>
								<div class="col-6">
									<span class="has-float-label">
										<select class="form-control" id="time_future_delivery_c" required>
											<?php foreach($_SESSION['current_order']->CUSTOM_time_future_array as $time) {?>
											<option value="<?=$time?>" <?php if($_SESSION['current_order']->time_future_delivery_c == $time){ ?> selected <?php } ?> ><?=$time?></option>
											<?php }?>
										</select>
										<label for="time_future_delivery_c">Время</label>
									</span>
								</div>
							</div>
						</div>
					<?php } ?>

					<div class="row" style="margin-top:25px;">
						<?php if(App::$current_landing->request_lead_comment_c){ ?>
						<div class="col-12 col-md-6" style="margin-bottom:10px;">
							<span class="form-group has-float-label <? if(!empty($_SESSION['current_order']->comment_client_c)) {?> active <? } ?>">
								<textarea class="form-control" id="comment_client_c" rows="3" maxlength="160" placeholder="Комментарий к заказу" ><?=$_SESSION['current_order']->comment_client_c ?? ''?></textarea>
								<label style="width:135px;" for="comment_client_c">Комментарий к заказу</label>
							</span>
						</div>
						<?php } ?>
						<?php if(App::$current_landing->request_lead_count_persons_c){ ?>
							<div class="col-6 col-md-3" >
								<div class="has-float-label">
									<input class="form-control" id="count_persons_c" type="text" placeholder="Количество персон" maxlength="10" value="<?=$_SESSION['current_order']->count_persons_c?>">
									<label for="count_persons_c" style="font-size:11pt;">Количество персон</label>
								</div>
							</div>
							<div class="col-6 col-md-3" >
								<div class="form-check" id="no_person_block">
								  <input name="no_person" class="form-check-input" type="checkbox" value="" id="no_person" style="width:30px;height:30px;">
								  <label class="form-check-label" for="no_person" style="margin-left:20px;margin-top:7px;font-size:11pt;">
									Без приборов
								  </label>
								</div>
							</div>
						<?php } ?>
					</div>

					<?php
						$cashPayments = false;
						$onlinePayments = false;
						$sberbankPayments = false;
						$terminalPayments = false;
						$qrPayments = false;
						if(App::$current_landing->cash_payments_c){
							$cashPayments = true;
						}
						if(App::$current_landing->terminal_payments_c){
							$terminalPayments = true;
						}
						if(App::$current_landing->qr_payments_c){
							$qrPayments = true;
						}
						if(App::$current_landing->sberbank_payments_c){
							$sberbankPayments = true;
						}
						if( 
							App::$current_landing->sberbank_acquiring_payments_c //сбербанк эквайринг
							||
							App::$current_landing->yookassa_acquiring_c //yookassa эквайринг
						){
							$onlinePayments = true;
						}
					?>

					<?php if($sberbankPayments || $onlinePayments || $terminalPayments || $qrPayments){ ?>
					<div id="block_delivery_payments">
						<h6 class="subtitle" style="margin-top:50px;">Способ оплаты</h6>
						<div class="row">
							<div class="col-md-12">
								<?php if($cashPayments){ ?>
								<span id="pay_method1_block" <?php if( $onlinePayments && !empty($_SESSION['current_order']->only_online_payments_с) && $_SESSION['current_order']->only_online_payments_с == 'yes' ){ ?> style="display:none;" <? } ?> >
									<input class="radio_nice" type="radio" name="pay_method_c" id="pay_method1" value="01" <? if($_SESSION['current_order']->pay_method_c == '01') { ?> checked <? } ?> />
									<label class="radio_label" for="pay_method1" id="pay_method1_label">Наличными <? if($_SESSION['current_order']->receiving_method_c == '01') { ?>курьеру <? } else { ?>при получении<? } ?></label>
								</span>
								<?php } ?>
								<?php if($terminalPayments){ ?>
								<span id="pay_method4_block" <?php if( $onlinePayments && !empty($_SESSION['current_order']->only_online_payments_с) && $_SESSION['current_order']->only_online_payments_с == 'yes' ){ ?> style="display:none;" <? } ?> >
									<input class="radio_nice" type="radio" name="pay_method_c" id="pay_method4" value="04" <? if($_SESSION['current_order']->pay_method_c == '04') { ?> checked <? } ?> />
									<label class="radio_label" for="pay_method4" id="pay_method4_label">На терминал <? if($_SESSION['current_order']->receiving_method_c == '01') { ?>курьеру <? } else { ?>при получении<? } ?></label>
								</span>
								<?php } ?>
								<?php if($sberbankPayments){ ?>
								<span id="pay_method3_block" <?php if( $onlinePayments && !empty($_SESSION['current_order']->only_online_payments_с) && $_SESSION['current_order']->only_online_payments_с == 'yes' ){ ?> style="display:none;" <? } ?> >
									<input class="radio_nice" type="radio" name="pay_method_c" id="pay_method3" value="02" <? if($_SESSION['current_order']->pay_method_c == '02') { ?> checked <? } ?> />
									<label class="radio_label" for="pay_method3" id="pay_method3_label">Сбербанк-онлайн <? if($_SESSION['current_order']->receiving_method_c == '01') { ?>курьеру <? } else { ?>при получении<? } ?></label>
								</span>
								<?php } ?>
								<?php if($qrPayments){ ?>
								<span id="pay_method5_block" <?php if( $onlinePayments && !empty($_SESSION['current_order']->only_online_payments_с) && $_SESSION['current_order']->only_online_payments_с == 'yes' ){ ?> style="display:none;" <? } ?> >
									<input class="radio_nice" type="radio" name="pay_method_c" id="pay_method5" value="05" <? if($_SESSION['current_order']->pay_method_c == '05') { ?> checked <? } ?> />
									<label class="radio_label" for="pay_method5" id="pay_method5_label">QR-код СБП <? if($_SESSION['current_order']->receiving_method_c == '01') { ?>курьеру <? } else { ?>при получении<? } ?></label>
								</span>
								<?php } ?>
								<?php if($onlinePayments){ ?>
								<span id="pay_method2_block">
									<input class="radio_nice" type="radio" name="pay_method_c" id="pay_method2" value="03" <?php if($_SESSION['current_order']->pay_method_c == '03') { ?> checked <?php } ?> />
									<label class="radio_label" for="pay_method2" id="pay_method2_label">Онлайн на сайте</label>
								</span>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php } ?>

					

					<?php if(App::$current_landing->request_lead_doit_c){ ?>
					<div class="row" style="margin-top:10px;">
						<div class="col-md-4">
							<div class="has-float-label">
								<input class="form-control" id="doit_c" type="number" placeholder="Нужна сдача с" min="1" maxlength="10" value="<?=$_SESSION['current_order']->doit_c?>">
								<label for="doit_c">Нужна сдача с</label>
							</div>
						</div>
					</div>
					<?php } ?>

				
					<?php if(!empty(App::$current_landing->lead_agree_c)) {?>
						<div style="font-size:10pt;max-width:480px;margin:0 auto;margin-top:40px;">
							<div>
								<input type="checkbox" id="optionsCheckboxes" name="optionsCheckboxes" style="width:30px;height:30px;float:left;margin-top:5px;margin-right:20px;" required>
								<div for="optionsCheckboxes">
									<?=str_replace('/main/', '/main/', NFfunctions::decodeString(App::$current_landing->lead_agree_c))?>
								</div>
							</div>
						</div>
					<?php } else { ?>
						<div style="font-size:10pt;max-width:480px;margin:0 auto;margin-top:40px;">
							<div>
								<input type="checkbox" id="optionsCheckboxes" name="optionsCheckboxes" style="width:30px;height:30px;float:left;margin-top:5px;margin-right:20px;" required>
								<?php if(!empty(App::$current_landing->agreement_lead_c)) {?>
									<div for="optionsCheckboxes">
										Подтвердите Ваше <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/agreement_lead" style="text-decoration: underline;">согласие на обработку своих персональных данных</a> и согласие с <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/agreement" style="text-decoration: underline;">Политикой в отношении обработки персональных данных</a> и <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/term_of_use" style="text-decoration: underline;">правилами предоставления услуг</a>
									</div>
								<?php } else { ?>
									<div for="optionsCheckboxes">
										Подтвердите Ваше согласие <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/agreement" style="text-decoration: underline;">на обработку своих персональных данных</a> и согласие с <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/term_of_use" style="text-decoration: underline;">правилами предоставления услуг</a>
									</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
				

					<div style="margin:20px 0px;">
						<div class="row">
							<div class="col" style="margin-bottom:10px;">
								<a class="btn btn-lg btn-default text-white btn-block btn-rounded shadow send_lead" style="max-width:100%;font-size:16pt;">Оформить заказ<i class="material-icons">navigate_next</i></a>
								<a class="btn btn-lg btn-default text-white btn-rounded shadow col-12" style="max-width:220px;background:#343a40;padding:5px 0;font-size:12pt;margin-top:10px;" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>"><i class="material-icons">navigate_before</i>Вернуться в меню</a>
								<div>
									<a class="float-left btn btn-lg btn-default text-white btn-rounded shadow col-12" style="max-width:220px;background:#343a40;padding:5px 0;font-size:12pt;margin-top:10px;" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/delete_lead"><i class="material-icons">clear</i> Отменить заказ</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>


<?php include CORE_FOLDER.'/application/views/widget/buttom_menu_without_card.php'; //Нижнее меню ?>

<script type="text/javascript">
	$(window).on('load', function () {
		$(".send_lead").click(function(){
			if( (parseInt($('#all_price_c').text()) - parseInt($('#delivery_price').text())) <  <?=App::$current_landing->delivery_min_order_c?> && $("label.active input[name='receiving_method_c']").val() != '02') {
				<?
					$message = 'Вы не сможете оформить заказ НА ДОСТАВКУ при стоимости товаров менее '.App::$current_landing->delivery_min_order_c.' руб.';
					if(!empty(App::$current_landing->pickup_c) && App::$current_landing->pickup_min_order_c < App::$current_landing->delivery_min_order_c ){
						$message .= '<br>Но можете оформить САМОВЫВОЗ';
						if(App::$current_landing->pickup_products_discount_c){ 
							$message .= '(со скидкой '.App::$current_landing->pickup_products_discount_c.'%)';
						}
						$message .= '.';
					}
				?>
				showErrorModal('<?=$message?>');
			} else if( parseInt($('#all_price_c').text()) <  <?=App::$current_landing->pickup_min_order_c?> && $("label.active input[name='receiving_method_c']").val() == '02') {
				showErrorModal('Вы не сможете оформить заказ НА САМОВЫВОЗ при стоимости товаров менее <?=App::$current_landing->pickup_min_order_c?> руб.');
			}
			
			<?php
				$deliveryFree = App::$current_landing->delivery_free_c ? App::$current_landing->delivery_free_c : '100000';
				if(App::$current_landing->name_required_c){
			?>
			else if( !$("#client_name_c").val().trim() != '' ) {
				showErrorModal('Укажите Ваше имя!', 'error');
			}
			<?php } if(App::$current_landing->phone_required_c){ ?>
			else if( !$("#phone_c").val().trim() != '' ) {
				showErrorModal('Укажите Ваш номер телефона!', 'error');
			}
			<?php } if(App::$current_landing->email_required_c){ ?>
			else if( !$("#client_email_c").val().trim() != '' ) {
				showErrorModal('Укажите Ваш e-mail!', 'error');
			}
			<?php } if(App::$current_landing->order_instagram_required_c){ ?>
			else if( !$("#client_instagram_c").val().trim() != '' ) {
				showErrorModal('Укажите социальную сеть для портрета!', 'error');
			}
			<?php } if(App::$current_landing->order_area_required_c && empty($_SESSION['current_order']->CUSTOM_area->name) && App::$current_landing->delivery_active_c) { ?>
			else if( !$("#CUSTOM_area").val() != '' && parseInt($('#all_price_c').text()) < <?=$deliveryFree?> && !$('#receiving_method_c2').is(':checked')) {
				showErrorModal('Укажите Ваш район!', 'error');
			}
			<?php } if(App::$current_landing->order_street_required_c && App::$current_landing->delivery_active_c){ ?>
			else if( $("#street_c").val().trim() == '' && !$('#receiving_method_c2').is(':checked')) { //улица пустая и это не самовывоз
				showErrorModal('Укажите Вашу улицу!', 'error');
			}
			<?php } if(App::$current_landing->order_home_required_c && App::$current_landing->delivery_active_c){ ?>
			else if( $("#home_c").val().trim() == '' && !$('#receiving_method_c2').is(':checked')) { //номер дома пустой и это не самовывоз
				showErrorModal('Укажите Ваш номер дома!', 'error');
			}
			<?php } if(App::$current_landing->order_room_required_c && App::$current_landing->delivery_active_c){ ?>
			else if( $("#room_c").val().trim() == '' && !$('#receiving_method_c2').is(':checked')) {  //квартира пустая и это не самовывоз
				showErrorModal('Укажите номер Вашей квартиры!', 'error');
			}
			<?php } if(App::$current_landing->order_porch_required_c && App::$current_landing->delivery_active_c){ ?>
			else if( $("#porch_c").val().trim() == '' && !$('#receiving_method_c2').is(':checked')) { //подъезд пустой и это не самовывоз
				showErrorModal('Укажите ПОДЪЕЗД Вашей квартиры!', 'error');
			}
			<?php } if(App::$current_landing->order_level_required_c && App::$current_landing->delivery_active_c){ ?>
			else if( $("#level_c").val().trim() == '' && !$('#receiving_method_c2').is(':checked')) { //этаж пустой и это не самовывоз
				showErrorModal('Укажите ЭТАЖ Вашей квартиры!', 'error');
			}
			<?php } if(App::$current_landing->pickup_c){ ?>
			else if( $("#brnch_branch_id_c").val().trim() == 'no' <? if(App::$current_landing->delivery_active_c){ ?> && $('#receiving_method_c2').is(':checked')<? } ?> ) { //точка продажи пустая и это САМОВЫВОЗ
				showErrorModal('Укажите откуда Вы хотите забрать заказ!', 'error');
			}
			<?php } if(App::$current_landing->order_doit_required_c && $_SESSION['current_order']->pay_method_c == '01' ){ ?>
			else if( $("#doit_c").is(":visible") && $("#doit_c").val().trim() == '' ) {
				showErrorModal('Укажите с какой купюры подготовить сдачу!', 'error');
			}
			<?php } if(App::$current_landing->order_comment_required_c){ ?>
			else if( $("#comment_client_c").val().trim() == '' ) {
				showErrorModal('Укажите комментарий к Вашему заказу!', 'error');
			}
			<?php } if(App::$current_landing->count_persons_required_c){ ?>
			else if( $("#count_persons_c").val().trim() == '' ) {
				showErrorModal('Укажите количество персон!', 'error');
			}
			<?php } ?>
			else if( !$("#optionsCheckboxes").is(':checked') ) {
				showErrorModal('Дайте свое согласие на обработку данных! (Требуется поставить галочку)', 'error');
			}
			else {
				$(".send_lead").css('pointer-events', 'none');
				$(".send_lead").text('Подождите...');
				document.forms["create_order"].submit();
			}
		});

		$("#CUSTOM_area").change(function(){
			$('html,body').animate({'scrollTop': $('#area_scroll').offset().top-20 },'slow');
		});

		$("#go_area").click(function() {
			$('html,body').animate({'scrollTop': $('#CUSTOM_area').offset().top-80 },'slow');
		});

		if('<?=$_SESSION['current_order']->CUSTOM_only_future_delivery?>' == 'yes') {
			$('#delivery_method1_block').hide();
			$('#delivery_method2_block').hide();
			$('#delivery_method2').attr('checked', true);
			$('#delivery_method2').click();
			$('#block_datetime_delivery').show();
			showErrorModal("Сейчас мы не работаем! \r\n Откроемся <?=$_SESSION['current_order']->CUSTOM_date_work_today?> в <?=$_SESSION['current_order']->CUSTOM_time_start_work_today?>! \r\n\r\n Но не уходите, Вы ещё можете \r\n оформить предварительный заказ!", 'info');
		}

	});
</script>

<?php if(App::$current_landing->streets_support_c){ ?>
<!-- Подстановка улиц -->
<link href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/css/typeahead.css" rel="stylesheet" />
<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/typeahead.bundle.js"></script>
<script>
	$(window).on('load', function () {
		var substringMatcher = function(strs) {
		  return function findMatches(q, cb) {
			var matches, substringRegex;
			matches = [];
			substrRegex = new RegExp(q, 'i');
			$.each(strs, function(i, str) {
			  if (substrRegex.test(str)) {matches.push(str);}
			});
			cb(matches);
		  };
		};

		var streets = [
			<?php foreach($data['current_streets'] as $street){ ?>
				"<?=$street?>",
			<?php } ?>
		];

		$('#street_c').typeahead({
		  hint: true,
		  highlight: true,
		  minLength: 1
		},
		{
		  name: 'street',
		  source: substringMatcher(streets)
		});
	});

</script>
<!-- END Подстановка улиц -->
<?php } ?>

<script type="text/javascript">
	function disableButton(){
		$(".send_lead").css('pointer-events', 'none');
		$(".send_lead").text('Ждем...');
	}

	function enadleButton(){
		$(".send_lead").css('pointer-events', '');
		$(".send_lead").text('оформить заказ');
	}
</script>

<!-- Кнопки -->
<script>
	$(window).on('load', function () {
		$( ".plus-btn" ).click(function() {
			var id = $(this).data("id");
			disableCard();
			$.getJSON("/basket/add_product/"+id, function(data) {
				enadleCard();
				$("#product_count"+id).val(parseInt(data.product.product_count));
				$("#product_count2"+id).text(parseInt(data.product.product_count));
				$("#product_sum_price"+id).text(parseInt(data.product.product_price));

				$("#delivery_text").html(data.delivery_text);
				$("#all_price_c").text(data.all_price);
				<?php if(App::$current_landing->delivery_min_order_c) { ?>
				if((parseInt(data.all_price) - parseInt($('#delivery_price').text())) > <?=App::$current_landing->delivery_min_order_c?>){
					$(".send_lead").css('pointer-events', '');
				}
				<?php } ?>
				<?php if(App::$current_landing->only_online_payments_c) { ?>
				if(data.all_price >= <?=App::$current_landing->only_online_payments_c?>){
					$("#pay_method2").attr( "checked" , true);
					$('#pay_method1_block').hide();
					$('#pay_method1').attr( "checked" , false);
					$('#pay_method3_block').hide();
					$('#pay_method3').attr( "checked" , false);
					$('#pay_method4_block').hide();
					$('#pay_method4').attr( "checked" , false);
					$('#pay_method5_block').hide();
					$('#pay_method5').attr( "checked" , false);
				}
				<?php } ?>
				$('#delivery_price').text(data.delivery_price);

				if(data.receiving_method == '02'){
					$("#fast_delivery").hide();
					$("#no_fast_delivery").hide();
				}else if(data.delivery_price != null){
					$("#fast_delivery").show();
					$("#no_fast_delivery").hide();
				} else{
					$("#fast_delivery").hide();
					$("#no_fast_delivery").show();
				}

				if(data.delivery_price == 0 ){
					$("#area_row").hide();
				} else{
					$("#area_row").show();
				}

				updatePromoCode(data);
			});
		});

		$( ".minus-btn" ).click(function() {
			var id = $(this).data("id");
			if($("#product_count"+id).val() > 0) {
				$("#product_count"+id).val(parseInt($("#product_count"+id).val())-1);
				$("#product_count2"+id).text(parseInt($("#product_count2"+id).text())-1);
				$("#product_sum_price"+id).text(parseInt($("#product_sum_price"+id).text())-parseInt($("#product_price"+id).val()));
				disableCard();
				$.getJSON("/basket/remove_product/"+id, function(data) {
					enadleCard();

					$("#delivery_text").html(data.delivery_text);
					$("#all_price_c").text(data.all_price);
					<?php if(App::$current_landing->delivery_min_order_c) { ?>
					if((parseInt(data.all_price) - parseInt($('#delivery_price').text())) < <?=App::$current_landing->delivery_min_order_c?>){
						showErrorModal("Вы не сможете оформить заказ, при стоимости товаров менее <?=App::$current_landing->delivery_min_order_c?> руб.", 'error');
					}
					<?php } ?>
					<?php if(App::$current_landing->only_online_payments_c) { ?>
					if(data.all_price < <?=App::$current_landing->only_online_payments_c?>){
						$('#pay_method1_block').show();
					}
					<?php } ?>
					$('#delivery_price').text(data.delivery_price);

					if(data.receiving_method == '02'){
						$("#fast_delivery").hide();
						$("#no_fast_delivery").hide();
					}else if(data.delivery_price != null){
						$("#fast_delivery").show();
						$("#no_fast_delivery").hide();
					} else{
						$("#fast_delivery").hide();
						$("#no_fast_delivery").show();
					}

					if(data.delivery_price == 0 ){
						$("#area_row").hide();
					} else {
						$("#area_row").show();
					}

					updatePromoCode(data);
				});
			}
		});

		$( "#CUSTOM_area").change(function() {
			var CUSTOM_area_id = $(this).val();
			disableButton();
			$.getJSON("/basket/set_order_field/CUSTOM_area/"+CUSTOM_area_id, function(data) {
				enadleButton();

				$("#delivery_text").html(data.delivery_text);
				$('#delivery_price').text(data.delivery_price);

				if(data.delivery_price != null){
					$("#fast_delivery").show();
					$("#no_fast_delivery").hide();
				} else{
					$("#fast_delivery").hide();
					$("#no_fast_delivery").show();
				}
				$("#all_price_c").text(data.all_price);
			});
		});

		$("#street_c, #porch_c, #home_c, #room_c, #level_c, #doit_c, #phone_c, #client_name_c, #client_email_c, #client_instagram_c, #comment_client_c, #count_persons_c, #brnch_branch_id_c" ).change(function() {
			var value = $(this).val().replace(/\//g,"-");
			var id = $(this).attr('id');
			disableButton();
			$.getJSON("/basket/set_order_field/"+id+"/"+encodeURIComponent(value), function(data) {
				if(data.result != 'OK'){
					showErrorModal(data.result);
				}

				//в случае если мы преобзазовали значение на стороне сервера(php), то обновляем его
				var value = eval('data.'+id);//eval выполняет код записанный в виде строки
				if(value !== undefined){
					$('#'+id).val(value);
				}


				enadleButton();
			});
		});


		$("#promo_code, #phone_c").change(function() {
			var promo_code = $("#promo_code").val();
			if(promo_code){
				var phone = $("#phone_c").val();
				disableButton();
				$.getJSON("/basket/set_promo_code/"+promo_code+"/main/"+phone, function(data) {
					enadleButton();

					updatePromoCode(data);

					if(data.promo_work != 'yes' && data.promo_work != 'no'){
						showErrorModal('Вы указали НЕ верный промокод!', 'error');
					}
				});
			}
		});
		
		
		$(".tt-menu" ).click(function() {
			var street_c = $("#street_c").val();
			disableButton();
			$.getJSON("/basket/set_order_field/street_c/"+street_c, function(data) {
				enadleButton();
			});
		});

		$("input[name$='pay_method_c']" ).change(function() {
			var pay_method_c = $(this).val();
			disableButton();
			$.getJSON("/basket/set_order_field/pay_method_c/"+pay_method_c, function(data) {
				if(pay_method_c == '03' || pay_method_c == '02' || pay_method_c == '04'){
					$('#doit_c').parent().hide();
				} else if(pay_method_c == '01'){
					$('#doit_c').parent().show();
				}
				enadleButton();
			});
		});

		$("input[name$='use_bonuses']" ).change(function() {
			var use_bonuses = $(this).is(':checked');
			disableButton();
			$.getJSON("/basket/use_bonuses/"+use_bonuses, function(data) {
				
				$("#delivery_text").html(data.delivery_text);
				$("#all_price_c").text(data.all_price);
				<?php if(App::$current_landing->delivery_min_order_c) { ?>
				if((parseInt(data.all_price) - parseInt($('#delivery_price').text())) > <?=App::$current_landing->delivery_min_order_c?>){
					$(".send_lead").css('pointer-events', '');
				}
				<?php } ?>
				<?php if(App::$current_landing->only_online_payments_c) { ?>
				if(data.all_price >= <?=App::$current_landing->only_online_payments_c?>){
					$("#pay_method2").attr( "checked" , true);
					$('#pay_method1_block').hide();
					$('#pay_method1').attr( "checked" , false);
					$('#pay_method3_block').hide();
					$('#pay_method3').attr( "checked" , false);
					$('#pay_method4_block').hide();
					$('#pay_method4').attr( "checked" , false);
					$('#pay_method5_block').hide();
					$('#pay_method5').attr( "checked" , false);
				}
				<?php } ?>
				$('#delivery_price').text(data.delivery_price);

				if(data.receiving_method == '02'){
					$("#fast_delivery").hide();
					$("#no_fast_delivery").hide();
				}else if(data.delivery_price != null){
					$("#fast_delivery").show();
					$("#no_fast_delivery").hide();
				} else{
					$("#fast_delivery").hide();
					$("#no_fast_delivery").show();
				}

				if(data.delivery_price == 0 ){
					$("#area_row").hide();
				} else{
					$("#area_row").show();
				}
				
				if(use_bonuses){
					$('#bonuses_block').css('border', '2px solid <?=App::$current_organization->main_color_c?>');
				} else {
					$('#bonuses_block').css('border', 'none');
				}

				updatePromoCode(data);
				enadleButton();
			});
		});

		<? if(App::$current_landing->delivery_active_c && App::$current_landing->pickup_c){ ?>
		$("input[name$='receiving_method_c']").change(function() {
			var receiving_method_c = $(this).val();

			$.getJSON("/basket/set_order_field/receiving_method_c/"+receiving_method_c, function(data) {
				$("#delivery_text").html(data.delivery_text);
				$('#delivery_price').text(data.delivery_price);
				$("#all_price_c").text(data.all_price);

				if(receiving_method_c == '01'){
				$('#block_delivery_method').show(); //отобразить блок выбора способа доставки
					$('#block_datetime_delivery').show();//отобразить блок указания даты и времени для доставки
					if($("#delivery_method1").is(':visible')){
						$("#delivery_method2").trigger('click');//кликаем по кнопке - почему-то если не нажать, то не переключается
						$("#delivery_method1").trigger('click');//кликаем по кнопке - Заказ на ближайшее время
					} else if($("#delivery_method2").is(':visible')){
						$("#delivery_method2").trigger('click');//кликаем по кнопке - Заказ ко времени
					}
					$('#block_pickup_address').hide();//скрыть блок выбора адреса самовывоза
					$('#block_datetime_pickup').hide();//скурыть блок указания даты и времени для самовывоза
					$('#block_client_address').show(); //отобразить блок заполнения адресса доставки
					$("#CUSTOM_area").val('').trigger('change');//типо жмем на пустой регион
					$("#fast_delivery").show();
					$("#no_fast_delivery").hide();

					$("#pay_method1_label").text('Наличными курьеру');
					$("#pay_method3_label").text('Сбербанк-онлайн курьеру');
					$("#pay_method4_label").text('На терминал курьеру');
					$("#pay_method5_label").text('QR-код СБП курьеру');
				} else if(receiving_method_c == '02'){
					$('#block_delivery_method').hide();//скрыть блок выбора способа доставки
					$('#block_client_address').hide(); //скрыть блок заполнения адресса доставки
					$('#block_delivery').hide(); //скрыть из состава заказа пункт про доставку
					$("#fast_delivery").hide();
					$("#no_fast_delivery").hide();
					$('#block_datetime_delivery').hide();//скрыть блок указания даты и времени для доставки
					
					$('#block_pickup_address').show();//отобразить блок выбора адреса самовывоза
					$('#block_datetime_pickup').show();//отобразить блок указания даты и времени для самовывоза


					$("#pay_method1_label").text('Наличными при получении');
					$("#pay_method3_label").text('Сбербанк-онлайн при получении');
					$("#pay_method4_label").text('Терминал при получении');
					$("#pay_method5_label").text('QR-код СБП при получении');
				}

				$('#time_future_delivery_c').find('option').remove(); //удаление старых данных времени
				data.time_future_array.forEach(function(item) {
					if(item == time_future_delivery_c){
						$('#time_future_delivery_c').append($("<option></option>", {value: item, text: item, selected: true}));
					} else {
						$('#time_future_delivery_c').append($("<option></option>", {value: item, text: item}));
					}
				});
			});
		});
		<? } ?>

		$("input[name$='delivery_method_c']").change(function() {
			var delivery_method_c = $(this).val();
			if(delivery_method_c == '01'){ //если ближайшее время
				$('#block_datetime_future_delivery').hide();//скрыть блок указания даты и времени доставки
			} else if(delivery_method_c == '02'){ //если ко времени
				$('#block_datetime_future_delivery').show();//отобразить блок указания даты и времени доставки
			}
			$.getJSON("/basket/set_order_field/delivery_method_c/"+delivery_method_c, function(data) {});

			var date_future_delivery_c = $("#date_future_delivery_c").val();
			var time_future_delivery_c = $("#time_future_delivery_c").val();

			disableButton();
			$.getJSON("/basket/set_order_field/date_future_delivery_c/"+date_future_delivery_c, function(data) {
				enadleButton();
			});
			$.getJSON("/basket/set_order_field/time_future_delivery_c/"+time_future_delivery_c, function(data) {
				enadleButton();
			});
		});

		//для даты и времени доставки
		$("#date_future_delivery_c" ).change(function() {
			var date_future_delivery_c = $("#date_future_delivery_c").val();
			disableCard();
			$.getJSON("/basket/set_order_field/date_future_delivery_c/"+date_future_delivery_c, function(data) {
				enadleCard();

				$('#time_future_delivery_c').find('option').remove(); //удаление старых данных
				data.time_future_array.forEach(function(item) {
					if(item == time_future_delivery_c){
						$('#time_future_delivery_c').append($("<option></option>", {value: item, text: item, selected: true}));
					} else {
						$('#time_future_delivery_c').append($("<option></option>", {value: item, text: item}));
					}
				});
			});
		});
		$("#time_future_delivery_c" ).change(function() {
			var time_future_delivery_c = $("#time_future_delivery_c").val();
			disableCard();
			$.getJSON("/basket/set_order_field/time_future_delivery_c/"+time_future_delivery_c, function(data) {
				enadleCard();


			});
		});

		//для даты и времени самовывоза
		$("#date_future_pickup_c" ).change(function() {
			var date_future_pickup_c = $("#date_future_pickup_c").val();
			disableCard();
			$.getJSON("/basket/set_order_field/date_future_delivery_c/"+date_future_pickup_c, function(data) {
				enadleCard();

				$('#time_future_pickup_c').find('option').remove(); //удаление старых данных
				data.time_future_array.forEach(function(item) {
					if(item == time_future_delivery_c){
						$('#time_future_pickup_c').append($("<option></option>", {value: item, text: item, selected: true}));
					} else {
						$('#time_future_pickup_c').append($("<option></option>", {value: item, text: item}));
					}
				});
			});
		});
		$("#time_future_pickup_c" ).change(function() {
			var time_future_pickup_c = $("#time_future_pickup_c").val();
			disableCard();
			$.getJSON("/basket/set_order_field/time_future_delivery_c/"+time_future_pickup_c, function(data) {
				enadleCard();


			});
		});
		
		
		$('#count_persons_c').mask('9?9', {'placeholder': ''});

		//количество персон
		$('#count_persons_c').change(function() {
			var value = $('#count_persons_c').val();
			if(value > 0){
				$('#no_person').attr('disabled', true);
			} else {
				$('#no_person').attr('disabled', false);
			}
		});
		
		//Без приборов
		$('#no_person').change(function() {
			var value = $('#no_person').is(":checked");
			if(value == true){
				$('#count_persons_c').attr('disabled', true);
				$('#count_persons_c').val(0);
			} else {
				$('#count_persons_c').attr('disabled', false);
				$('#count_persons_c').val('');
			}
		});
	});

function updatePromoCode(data){
	if(data.promo_work == 'yes'){
		if(window.promo_work != 'yes'){
			showErrorModal("Промокод: '"+data.promo_code.toUpperCase()+"' активирован!","success");
			window.promo_work = 'yes';
		}
		$("#promo_code").val(data.promo_code);
		$("#promo_code").css('background-color','#F5FFF7');
		$("#promo_code").attr('disabled', true);

		$("#promo_block").show();
		$("#promo_code_num").text(data.promo_code);
		$("#promo_html").html(data.promo_html);
		$("#promo_price").html(data.promo_price);

		$("#all_price_c").text(data.all_price);
	} else if(data.promo_work == 'no') {
		if(window.promo_work != 'no'){
			showErrorModal(data.promo_html, "error");
			window.promo_work = 'no';
		}
		$("#promo_code").css('background-color', '#FFF');
		$("#promo_code").val(data.promo_code);
		$("#promo_code").attr('disabled', false);

		$("#promo_block").hide();
		$("#promo_code_num").text('');
		$("#promo_html").html('');
	}
}

</script>
<!-- END Кнопки -->

<? if(App::$current_landing->use_phone_mask_c){ ?>
<script type="text/javascript">
	$(function() {
		$("#phone_c").mask("+7 (999) 999-99-99");
	});
</script>
<? } ?>
