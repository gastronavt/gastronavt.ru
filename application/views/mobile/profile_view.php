<?
	global $db;
	
	$query_current_orders = $db->query("
		SELECT 
			oo.name, 
			oo.date_entered, 
			oo.id,
			oo_cstm.address_c,
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
			$current_orders[$current_order['id']] = [
				'name' => $current_order['name'], 
				'address_c' => $current_order['address_c'], 
				'timezone_c' => $current_order['timezone_c'], 
				'date_entered' => $current_order['date_entered'],
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
			$old_orders[$old_order['id']] = [
				'name' => $old_order['name'], 
				'address_c' => $old_order['address_c'], 
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

<div class="container">
	<div class="text-center" style="padding-top:10px;<?php if( !empty(App::$current_aggregator)){ ?> padding-top:40px; <?php } ?>">
		<h3 class="mb-1"><?=App::$current_user->name?></h3>
		<p class="text-secondary"><?=App::$current_user->city_c?></p>
	</div>
	
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
				<div style="font-size:11pt;">Instagram: <?=App::$current_user->instagram_c?></div>
			</div>
		</div>
		<div class="tab-pane fade" id="nav-order-info-edit" role="tabpanel" aria-labelledby="nav-order-info-edit-tab">
			<form method="POST" action="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/mobile/update_user_settings/">
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
							<span class="input-group-text">Instagram</span>
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
	
	<div class="row">
	   <div class="col">
			<p>
			<a class="mb-2 collapsed" data-toggle="collapse" href="#collapseCurrentOrders" role="button" aria-expanded="false" aria-controls="collapseCurrentOrders" style="text-decoration:none;color:#000;">
				<h6 class="subtitle">Текущие заказы - показать <i class="material-icons">keyboard_arrow_down</i></h6>
			</a>
			</p>
			<div class="collapse" id="collapseCurrentOrders">
				<div class="card shadow-sm border-0">
					<div class="card-body">
						<div class="row">
							<div class="col-12 px-0">
								<div class="list-group list-group-flush">
									<? if(!empty($current_orders)){ ?>
										<? foreach($current_orders as $current_order_key => $current_order){ ?>
										<div class="list-group-item" style="text-decoration:none;font-size:9pt;color:#000;">
											<div><b>Заказ № <?=$current_order['name']?></b> <span class="float-right"><?=date('d.m.Y H:i', strtotime($current_order['date_entered'].' '.$current_order['timezone_c'].' hours'))?></span></div>
											<div><b>Адрес доставки:</b></div> 
											<div><?=$current_order['address_c']?></div>
											<div><b>Состав заказа:</b></div>
											
											<? 
												if(!empty($current_order['products'])){
													foreach($current_order['products'] as $product_key => $product){ 
											?>
												   <div>&nbsp;&nbsp;&nbsp;&nbsp;• <?=$product['name']?> - <?=$product['count']?> шт. </div>
												   <div class="row">
													</div>
											<? 
													}
												}											
											?>
											<? if(in_array($current_order['status_c'], ['01','02','10'])){ ?>
												<div><b>Статус заказа: Поступил диспетчеру</b></div>
												<div class="progress mb-3">
													<div class="progress-bar bg-success" role="progressbar" style="width: 20%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">20%</div>
												</div>
											<? } elseif($current_order['status_c'] == '11'){ ?>
												<div><b>Статус заказа: На кухне</b></div>
												<div class="progress mb-3">
													<div class="progress-bar bg-success" role="progressbar" style="width: 45%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">45%</div>
												</div>
											<? } elseif($current_order['status_c'] == '08'){ ?>
												<div><b>Статус заказа: Заказ собран</b></div>
												<div class="progress mb-3">
													<div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">50%</div>
												</div>
											<? } elseif(in_array($current_order['status_c'], ['03'])){ ?>
												<div><b>Статус заказа: Назначен курьеру</b></div>
												<div class="progress mb-3">
													<div class="progress-bar bg-success" role="progressbar" style="width: 60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">60%</div>
												</div>
											<? } elseif(in_array($current_order['status_c'], ['04','05','06'])){ ?>
												<div><b>Статус заказа: Курьер в пути</b></div>
												<div class="progress mb-3">
													<div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">75%</div>
												</div>
											<? } elseif(in_array($current_order['status_c'], ['07'])){ ?>
												<div><b>Статус заказа: Курьер подъезжает</b></div>
												<div class="progress mb-3">
													<div class="progress-bar bg-success" role="progressbar" style="width: 90%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">90%</div>
												</div>
											<? } ?>
											
										</div>
										<? } ?>
									<? } else { ?>
										<div class="list-group-item" style="text-decoration:none;font-size:12pt;color:#000;text-align:center;">
											Нет текущего заказа
										</div>
									<? } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	   </div>
	</div>
	
	<div class="row">
	   <div class="col">
			<p>
			<a class="mb-2 collapsed" data-toggle="collapse" href="#collapseOldOrders" role="button" aria-expanded="false" aria-controls="collapseOldOrders" style="text-decoration:none;color:#000;">
				<h6 class="subtitle">Мои прошлые заказы - показать <i class="material-icons">keyboard_arrow_down</i></h6>
			</a>
			</p>
			<div class="collapse" id="collapseOldOrders">
				<div class="card shadow-sm border-0">
					<div class="card-body">
						<div class="row">
							<div class="col-12 px-0">
								<div class="list-group list-group-flush">
									<? if(!empty($old_orders)){ ?>
										<? foreach($old_orders as $old_order_key => $old_order){ ?>
										<div class="list-group-item" style="text-decoration:none;font-size:9pt;color:#000;">
											<div><b>Заказ № <?=$old_order['name']?></b> <span class="float-right"><?=date('d.m.Y H:i', strtotime($old_order['date_entered'].' '.$old_order['timezone_c'].' hours'))?></span></div>
											<div><b>Адрес доставки:</b></div> 
											<div><?=$old_order['address_c']?></div>
											<div><b>Состав заказа:</b></div>
											<? foreach($old_order['products'] as $product_key => $product){ ?>
												   <div>&nbsp;&nbsp;&nbsp;&nbsp;• <?=$product['name']?> - <?=$product['count']?> шт. </div>
												   <div class="row">
												</div>
											<? } ?>
											<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/mobile/duplicate_order/<?=$old_order_key?>?session_id=<?=$_REQUEST['session_id']?>" class="btn btn-lg btn-dark text-white btn-rounded shadow" style="padding: 5px 15px;background:<?=App::$current_organization->main_color_c?>;margin-top:10px;">Повторить заказ</a>
										</div>
										<? } ?>
									<? } else { ?>
										<div class="list-group-item" style="text-decoration:none;font-size:12pt;color:#000;text-align:center;">
											Вы не сделали ни одного заказа :(
										</div>
									<? } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	   </div>
	</div>
	<a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/mobile/logout?session_id=<?=$_REQUEST['session_id']?>" class="btn btn-lg btn-default text-white btn-block btn-rounded shadow" style="background:#343a40;margin:auto;margin-top:20px;margin-right:0px;padding-top:7px;padding-bottom:7px;display:visible;max-width:180px;">Сменить пользователя</a>
</div>
<script src="https://rawgit.com/RobinHerbots/Inputmask/5.x/dist/jquery.inputmask.js"></script>
<script>
$(document).ready(function(){
  $(":input").inputmask();
  $('#instagram_c').inputmask({
    mask: "@*{1,30}",
    definitions: {
      '*': {
        validator: "[0-9A-Za-zА-Яа-я_-.]",
        cardinality: 1,
        casing: "lower"
      }
    }
  });
});
</script>