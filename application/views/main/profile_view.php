<?
	global $db;
	
	$query_current_orders = $db->query("
		SELECT 
			oo.name, 
			oo.date_entered, 
			oo.id,
			oo_cstm.address_c,
			oo_cstm.street_c,
			oo_cstm.home_c,
			oo_cstm.room_c,
			oo_cstm.status_c,
			pp.name as product_name,
            pp.id as product_id,
			city_cstm.timezone_c,
            count(pp.name) as count
		FROM clnts_clients cc
			LEFT JOIN clnts_clients_ordrs_orders_1_c cc_oo ON cc_oo.clnts_clients_ordrs_orders_1clnts_clients_ida = cc.id AND cc.deleted = 0 AND cc_oo.deleted = 0
			LEFT JOIN ordrs_orders oo ON oo.id = cc_oo.clnts_clients_ordrs_orders_1ordrs_orders_idb AND oo.deleted = 0
			LEFT JOIN ordrs_orders_cstm oo_cstm ON oo_cstm.id_c = oo.id
			LEFT JOIN ordrs_orders_prord_products_in_order_1_c oo_pp ON oo_pp.ordrs_orders_prord_products_in_order_1ordrs_orders_ida = oo_cstm.id_c AND oo_pp.deleted = 0
			LEFT JOIN prord_products_in_order pp ON pp.id = oo_pp.ordrs_orde5b35n_order_idb AND pp.deleted = 0
			LEFT JOIN city_cities city ON city.id = oo_cstm.city_cities_id1_c AND city.deleted = 0
			LEFT JOIN city_cities_cstm city_cstm ON city_cstm.id_c = city.id
		WHERE 
			cc.id = '".App::$current_user->id."'
			AND oo_cstm.lngng_landings_id_c = '".App::$current_landing->id."'
			AND oo_cstm.status_c IN ('01','02','11','03','04','10','05','06','07','08')
        GROUP BY oo.name, pp.name
		ORDER BY oo.date_entered DESC LIMIT 100
	");
	
	$current_orders = [];
	while($current_order = $db->fetchByAssoc($query_current_orders)) {
		if(!array_key_exists($current_order['id'], $current_orders)){
			$address = $current_order['street_c'];
			if($current_order['home_c']){
				$address .= ', д. '.$current_order['home_c'];
			}
			if($current_order['room_c']){
				$address .= ', кв. '.$current_order['room_c'];
			}
			
			$current_orders[$current_order['id']] = [
				'name' => $current_order['name'], 
				'address' => $address, 
				'date_entered' => $current_order['date_entered'],
				'timezone_c' => $current_order['timezone_c'], 
				'status_c' => $current_order['status_c']
			];
		}
		$current_orders[$current_order['id']]['products'][] = [
			'name' => $current_order['product_name'],
			'count' => $current_order['count']
		];
	}
	
	
	$query_old_orders = $db->query("
		SELECT 
			oo.name, 
			oo.date_entered, 
			oo.id,
			oo_cstm.address_c,
			oo_cstm.street_c,
			oo_cstm.home_c,
			oo_cstm.room_c,
			pp.name as product_name,
            pp.id as product_id,
			city_cstm.timezone_c,
            count(pp.name) as count
		FROM clnts_clients cc
			LEFT JOIN clnts_clients_ordrs_orders_1_c cc_oo ON cc_oo.clnts_clients_ordrs_orders_1clnts_clients_ida = cc.id AND cc.deleted = 0 AND cc_oo.deleted = 0
			LEFT JOIN ordrs_orders oo ON oo.id = cc_oo.clnts_clients_ordrs_orders_1ordrs_orders_idb AND oo.deleted = 0
			LEFT JOIN ordrs_orders_cstm oo_cstm ON oo_cstm.id_c = oo.id
			LEFT JOIN ordrs_orders_prord_products_in_order_1_c oo_pp ON oo_pp.ordrs_orders_prord_products_in_order_1ordrs_orders_ida = oo_cstm.id_c AND oo_pp.deleted = 0
			LEFT JOIN prord_products_in_order pp ON pp.id = oo_pp.ordrs_orde5b35n_order_idb AND pp.deleted = 0
			LEFT JOIN city_cities city ON city.id = oo_cstm.city_cities_id1_c AND city.deleted = 0
			LEFT JOIN city_cities_cstm city_cstm ON city_cstm.id_c = city.id
		WHERE 
			cc.id = '".App::$current_user->id."'
			AND oo_cstm.lngng_landings_id_c = '".App::$current_landing->id."'
		AND oo_cstm.status_c = '09'
        GROUP BY oo.name, pp.name
		ORDER BY oo.date_entered DESC LIMIT 100
	");
	
	
	$old_orders = [];
	while($old_order = $db->fetchByAssoc($query_old_orders)) {
		if(!array_key_exists($old_order['id'], $old_orders)){
			$address = $old_order['street_c'];
			if($old_order['home_c']){
				$address .= ', д. '.$old_order['home_c'];
			}
			if($old_order['room_c']){
				$address .= ', кв. '.$old_order['room_c'];
			}
			
			$old_orders[$old_order['id']] = [
				'name' => $old_order['name'], 
				'address' => $address, 
				'timezone_c' => $old_order['timezone_c'], 
				'date_entered' => $old_order['date_entered']
			];
		}
		$old_orders[$old_order['id']]['products'][] = [
			'name' => $old_order['product_name'],
			'count' => $old_order['count']
		];
	}
?>

<?php
	$header_name = 'Профиль';
	$header_back_url = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'];
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

<?
	$bonuseSystem = $db->fetchRow($db->query("
		SELECT 
			bb.id,
			bb.name, 
			bb_cstm.bonuses_name_c, 
			bbl_cstm.cashback_c,
			MAX(bbl_cstm.ransom_cost_c) as level_ransom_cost,
			bbl.name as level_name
		FROM
			bnss_bonuses_lngng_landings_1_c bl
			JOIN bnss_bonuses bb ON bb.id = bl.bnss_bonuses_lngng_landings_1bnss_bonuses_ida AND bb.deleted = 0 AND bl.bnss_bonuses_lngng_landings_1lngng_landings_idb = '".App::$current_landing->id."' AND bl.deleted = 0
			JOIN bnss_bonuses_cstm bb_cstm ON bb.id = bb_cstm.id_c
			JOIN bnss_bonuses_bnlvl_bonuses_level_1_c bb_bbl ON bb_bbl.bnss_bonuses_bnlvl_bonuses_level_1bnss_bonuses_ida = bb_cstm.id_c AND bb_bbl.deleted = 0
			JOIN bnlvl_bonuses_level bbl ON bbl.id = bb_bbl.bnss_bonuses_bnlvl_bonuses_level_1bnlvl_bonuses_level_idb AND bbl.deleted = 0
			JOIN bnlvl_bonuses_level_cstm bbl_cstm ON bbl_cstm.id_c = bbl.id
			WHERE '".App::$current_user->paid_all_c."' >= bbl_cstm.ransom_cost_c
			HAVING MAX(bbl_cstm.ransom_cost_c) IS NOT NULL
	"));
	
	if($bonuseSystem){
	
		$currentUserBonuse = $db->fetchRow($db->query(
			"SELECT
				cco.id,
				cco_cstm.bonuses_c
			FROM clorg_client_organizations cco
			JOIN clorg_client_organizations_cstm cco_cstm ON cco_cstm.id_c = cco.id AND cco.deleted = 0 AND cco_cstm.orgns_organizations_id_c = '".App::$current_organization->id."'
			JOIN clnts_clients_clorg_client_organizations_1_c cc_cco ON cc_cco.clnts_cliea48bzations_idb = cco.id AND cc_cco.deleted = 0 AND cc_cco.clnts_clients_clorg_client_organizations_1clnts_clients_ida = '".App::$current_user->id."';
		"));
		
		//получаем транзакции бонусов
		$transactionsQuery = $db->pQuery(
			"SELECT 
				bbt_cstm.*
			FROM bntrn_bonuses_transaction bbt
			JOIN bntrn_bonuses_transaction_cstm bbt_cstm ON bbt_cstm.id_c = bbt.id AND bbt.deleted = 0
			JOIN clorg_client_organizations_bntrn_bonuses_transaction_1_c cco_bbt ON cco_bbt.clorg_clief3ccsaction_idb = bbt.id AND cco_bbt.deleted = 0
			AND cco_bbt.clorg_cliedbfbzations_ida = '?'
			ORDER BY bbt_cstm.date_start_c DESC, bbt.date_entered DESC, bbt_cstm.date_end_c DESC",
			[
				$currentUserBonuse['id'],
			]
		);
		$transactions = [];
		while($transaction = $db->fetchByAssoc($transactionsQuery)) {
			$transactions[] = $transaction;
		}
?>
		<div class="container">
			<div class="card bg-template shadow mt-4 mb-5 h-190">
				<div class="card shadow">
					<div class="card-body" style="border-radius:15px;background-image: url(<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$bonuseSystem['id']?>_image_card_c);background-repeat: no-repeat; background-size: cover;background-position: center;">
						<div class="row mb-3">
							<div class="col">
								<span class="tag" style="width:100%;background-color:#e3e3e3;">
									<div class="row">
										<div class="col">
											<span class="btn btn-default p-2 btn-rounded-15 m-2">
												<i class="material-icons" style="font-size:16pt;">account_balance_wallet</i>
											</span>
											<span class="font-weight-normal">ВАША БОНУСНАЯ КАРТА</span>
										</div>
										<!--<div class="col-auto">
											<span style="float:right;">
												<button class="btn btn-lg btn-default text-white btn-block btn-rounded shadow" style="background:rgb(255, 127, 1);padding-top:7px;padding-bottom:7px;display:visible;font-size:10pt;"><span>Пригласить</br>друга</span></button>
											</span>
										</div>-->
									</div>
								</span>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="text-mute">СТАТУС</div>
								<div class="h4 d-block" style="color: #000 !important;"><?=$bonuseSystem['level_name']?></div>
							</div>
							<div class="col">
								<div class="text-mute">КЭШБЭК</div>
								<div class="h4 d-block" style="color: #000 !important;"><?=$bonuseSystem['cashback_c']?> %</div>
							</div>
							<div class="col">
								<div class="text-mute">БОНУСЫ</div>
								<div class="h4 d-block" style="color: #000 !important;"><?=$currentUserBonuse['bonuses_c']?></div>
							</div>
						</div>
						
						<? if(!empty($transactions)){ ?>
						<div class="row">
						   <div class="col">
								<p>
									<a class="mb-2 collapsed" data-toggle="collapse" href="#collapseTransactions" role="button" aria-expanded="false" aria-controls="collapseTransactions" style="text-decoration:none;color:#000;">
										<h6 class="subtitle">Подробнее <i class="material-icons">keyboard_arrow_down</i></h6>
									</a>
								</p>
								
								<div class="collapse" id="collapseTransactions">
									<div class="card shadow border-0 mb-3">
										<div class="card-body">
											<div class="row">
												<div class="col">
													<span class="small mb-2">
														Дата начисления/списания:
													</span>
													<span class="float-right small mb-2">Количество бонусов</span>
													<hr></hr>
													<? 
														foreach($transactions as $transaction){
															if($transaction['type_c'] == '02') {
													?>
																<div class="row small">
																	<div class="col" style="color:red;">Списано: <?=date('d.m.Y', strtotime($transaction['date_end_c'].' '.App::$current_landing->timezone_c.' hours'))?></div>
																	<div class="col-auto" style="color:red;">- <?=$transaction['bonuses_c']?> <i class="material-icons">star_border</i></div>
																</div>
														<?	
															} elseif($transaction['type_c'] == '01') {
																$date1 = strtotime($transaction['date_end_c']);
																$date2 = strtotime(date('Y-m-d H:i:s'));

																$seconds = abs($date2 - $date1);
																$days = floor($seconds / 86400);

																$text_color = '';
																if($days <= 5){
																	$text_color = 'color:red;';
																}
															?>
																<div class="row small">
																	<div class="col">
																		<div>Начислено: <?=date('d.m.Y', strtotime($transaction['date_start_c'].' '.App::$current_landing->timezone_c.' hours'))?></div>
																		<div style="<?=$text_color?>"> Cгорят: <?=date('d.m.Y', strtotime($transaction['date_end_c'].' '.App::$current_landing->timezone_c.' hours'))?></div>
																	</div>
																	<div class="col-auto"  style="color:green;">+ <?=$transaction['bonuses_c']?> <i class="material-icons">star_border</i></div>
																</div>
														<? } elseif($transaction['type_c'] == '03') { ?>
																<div class="row small">
																	<div class="col">
																		<div style="color:red;">Сгорело: <?=date('d.m.Y', strtotime($transaction['date_end_c'].' '.App::$current_landing->timezone_c.' hours'))?></div>
																	</div>
																	<div class="col-auto" style="color:red;"> <?=$transaction['bonuses_c']?> 🔥</div>
																</div>
														<?	} ?>
														<hr></hr>
													<? } ?>
												</div>
											</div>
										</div>
									</div>
								</div>
						   </div>
					</div>
					<? } ?>
					
					
					
					
				</div>
				
				
			</div>
			
			<style>
				.tag{
					padding: 5px 15px;
					display: inline-block;
					border-radius: 10px;
					line-height: 20px;
					background-color: rgba(248, 235, 238, 0.25);
				}
			</style>
		</div>


		<!--<div class="modal fade" id="addmoney" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
			<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header border-0">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body text-center pt-0">
						<img src="img/infomarmation-graphics2.png" alt="logo" class="logo-small">
						<div class="form-group mt-4">
							<input type="text" class="form-control form-control-lg text-center" placeholder="Enter amount" required="" autofocus="">
						</div>
						<p class="text-mute">You will be redirected to payment gatway to procceed further. Enter amount in USD.</p>
					</div>
					<div class="modal-footer border-0">
						<button type="button" class="btn btn-default btn-lg btn-rounded shadow btn-block" data-dismiss="modal">Next</button>
					</div>
				</div>
			</div>
		</div>-->

<?
	}
?>

<div class="container">
	<nav style="margin-top:20px;">
		<div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
			<a class="nav-item nav-link text-left active" id="nav-order-info-detail-tab" data-toggle="tab" href="#nav-order-info-detail" role="tab" aria-controls="nav-order-info-detail" aria-selected="true">
				<div class="row">
					<div class="col-auto align-self-center pr-1">
						<h6 class="text-dark my-0">Личная информация</h6>
					</div>
				</div>
			</a>
							<a class="nav-item nav-link text-left" id="nav-order-info-edit-tab" data-toggle="tab" href="#nav-order-info-edit" role="tab" aria-controls="nav-order-info-edit" aria-selected="false">
				<div class="row">
					<div class="col-auto align-self-center pr-1">
						<h6 class="text-dark my-0" style="font-size:10pt;">Редактировать</h6>
					</div>
				</div>
			</a>
		</div>
	</nav>
	<div class="tab-content" id="nav-tabContent">
		<div class="tab-pane fade show active" id="nav-order-info-detail" role="tabpanel" aria-labelledby="nav-order-info-detail-tab">
			<div class="p-3">
				<div style="font-size:11pt;">Имя: <?=App::$current_user->name?></div>  
				<div style="font-size:11pt;">Телефон: <a href="tel:<?=App::$current_user->phone_c?>"><?=App::$current_user->phone_c?></a></div>
				<div style="font-size:11pt;">E-mail: <?=App::$current_user->email_c?></div>
				<div style="font-size:11pt;">Соц.сеть: <?=App::$current_user->instagram_c?></div>
			</div>
		</div>
		<div class="tab-pane fade" id="nav-order-info-edit" role="tabpanel" aria-labelledby="nav-order-info-edit-tab">
			<form method="POST" action="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/update_user_settings/">
				<div class="p-3">
					<div class="input-group input-group-sm mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text">Имя</span>
						</div>
						<input id="client_name_c" type="text" class="form-control" name="client_name_c" maxlength="30" value="<? if(App::$current_user->name != 'НЕ УКАЗАНО') { echo App::$current_user->name; } ?>" required>
					</div>
					<div class="input-group input-group-sm mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text">E-mail</span>
						</div>
						<input id="email_c" type="text" class="form-control" name="email_c" maxlength="50" data-inputmask="'alias': 'email'" value="<?=App::$current_user->email_c?>">
					</div>
					<div class="input-group input-group-sm mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text">Соц.сеть</span>
						</div>
						<input id="instagram_c" type="text" class="form-control" name="instagram_c" value="<?=App::$current_user->instagram_c?>">
					</div>
				</div>
				<div class="row">
					<button class="btn btn-lg btn-dark text-white btn-block btn-rounded shadow p-1 mr-3 ml-3" style="background:<?=App::$current_organization->main_color_c?>;">Обновить</button>
				</div>
			</form>
		</div>
	</div>
	
	<? if(!empty($current_orders)){ ?>
	<div class="row">
	   <div class="col">
			<p>
				<a class="mb-2 collapsed" data-toggle="collapse" href="#collapseCurrentOrders" role="button" aria-expanded="false" aria-controls="collapseCurrentOrders" style="text-decoration:none;color:#000;">
					<h6 class="subtitle">Текущие заказы - показать <i class="material-icons">keyboard_arrow_down</i></h6>
				</a>
			</p>
			<div class="collapse" id="collapseCurrentOrders">
				<? if(!empty($current_orders)){ ?>
					<? foreach($current_orders as $current_order_key => $current_order){ ?>
						<div class="card shadow border-0 mb-3">
							<div class="card-body">
								<div class="row">
									<div class="col">
										<h5 class="font-weight-normal mb-1">
											Заказ № <?=$current_order['name']?></b> <span class="float-right"><?=date('d.m.Y H:i', strtotime($current_order['date_entered'].' '.$current_order['timezone_c'].' hours'))?></span>
										</h5>
										<p class="text-mute small text-secondary mb-2">
											
											<br><b>Состав заказа:</b>
											<? 
												if(!empty($current_order['products'])){
													foreach($current_order['products'] as $product_key => $product){ 
											?>
												   <br>&nbsp;&nbsp;&nbsp;&nbsp;• <?=$product['name']?> - <?=$product['count']?> шт.
											<? 
													}
												}											
											?>
											<? if(in_array($current_order['status_c'], ['01','02','10'])){ ?>
												<br><b>Статус заказа:</b> Поступил диспетчеру
												<div class="progress mb-3">
													<div class="progress-bar bg-success" role="progressbar" style="width: 20%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">20%</div>
												</div>
											<? } elseif($current_order['status_c'] == '11'){ ?>
												<br><b>Статус заказа:</b> На кухне
												<div class="progress mb-3">
													<div class="progress-bar bg-success" role="progressbar" style="width: 45%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">45%</div>
												</div>
											<? } elseif($current_order['status_c'] == '08'){ ?>
												<br><b>Статус заказа:</b> Заказ собран
												<div class="progress mb-3">
													<div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">50%</div>
												</div>
											<? } elseif(in_array($current_order['status_c'], ['03'])){ ?>
												<br><b>Статус заказа:</b> Назначен курьеру
												<div class="progress mb-3">
													<div class="progress-bar bg-success" role="progressbar" style="width: 60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">60%</div>
												</div>
											<? } elseif(in_array($current_order['status_c'], ['04','05','06'])){ ?>
												<br><b>Статус заказа:</b> Курьер в пути
												<div class="progress mb-3">
													<div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">75%</div>
												</div>
											<? } elseif(in_array($current_order['status_c'], ['07'])){ ?>
												<br><b>Статус заказа:</b> Курьер подъезжает
												<div class="progress mb-3">
													<div class="progress-bar bg-success" role="progressbar" style="width: 90%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">90%</div>
												</div>
											<? } ?>
										</p>
									</div>
								</div>
							</div>
						</div>
					<? } ?>
				<? } ?>
			</div>
	   </div>
	</div>
	<? } ?>
	
	<? if(!empty($old_orders)){ ?>
	<div class="row">
	   <div class="col">
			<p>
				<a class="mb-2 collapsed" data-toggle="collapse" href="#collapseOldOrders" role="button" aria-expanded="false" aria-controls="collapseOldOrders" style="text-decoration:none;color:#000;">
					<h6 class="subtitle">Мои прошлые заказы - показать <i class="material-icons">keyboard_arrow_down</i></h6>
				</a>
			</p>
			<div class="collapse" id="collapseOldOrders">
				<? if(!empty($old_orders)){ ?>
					<? foreach($old_orders as $old_order_key => $old_order){ ?>
						<div class="card shadow border-0 mb-3">
							<div class="card-body">
								<div class="row">
									<div class="col">
										<h5 class="font-weight-normal mb-1">
											Заказ № <?=$old_order['name']?></b> <span class="float-right"><?=date('d.m.Y H:i', strtotime($old_order['date_entered'].' '.$old_order['timezone_c'].' hours'))?></span>
										</h5>
										<p class="text-mute small text-secondary mb-2">
											<!--<b>Адрес доставки:</b>
											<?=$old_order['address']?>-->
											<br><b>Состав заказа:</b>
											<? 
												if(!empty($old_order['products'])){
													foreach($old_order['products'] as $product_key => $product){ 
											?>
												   <br>&nbsp;&nbsp;&nbsp;&nbsp;• <?=$product['name']?> - <?=$product['count']?> шт.
											<? 
													}
												}											
											?>
										</p>
										<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/duplicate_order/<?=$old_order_key?>" class="btn btn-lg btn-dark text-white btn-rounded shadow" style="padding: 5px 15px;background:<?=App::$current_organization->main_color_c?>;margin-top:10px;">Повторить заказ</a>
									</div>
								</div>
							</div>
						</div>
					<? } ?>
				<? } ?>			
			</div>
	   </div>
	</div>
	<? } ?>
	
	<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/logout" class="btn btn-lg btn-default text-white btn-block btn-rounded shadow" style="background:#343a40;margin:auto;margin-top:20px;margin-right:0px;padding-top:7px;padding-bottom:7px;display:visible;max-width:120px;">Выйти</a>
</div>

<?php  include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>