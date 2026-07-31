<?php
	$header_name = 'Контакты';
	$header_back_url = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'];
	
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

<!-- Контакты -->
<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
	<div class="jumbotron mt-3 bg-white shadow-sm pt-4">
		<div class="row justify-content-center">
			<div class="col-12 col-md-6 col-lg-8 mb-4 text-left">
				<h6 class="text-center">Контакты</h6>
				<p class="pl-2">
					<? if(App::$current_landing->phone1_c != NULL) { ?>
						<i class="material-icons md-18">phone</i> Телефон:  <a href="tel:<?=App::$current_landing->phone1_c?>"><?=App::$current_landing->phone1_c?></a>
						<? if(App::$current_landing->phone2_c != NULL) { ?>
								, <a href="tel:<?=App::$current_landing->phone2_c?>"><?=App::$current_landing->phone2_c?></a>
						<? 	} ?>
					<? } ?>
				</p>
				<? if(App::$current_landing->email_c != NULL) { ?>
				<p class="pl-2">
					<i class="material-icons md-18">email</i> Электронная почта: <?=NFfunctions::decodeString(App::$current_landing->email_c)?>
				</p>
				<? } ?>
				<hr>
			</div>
			<div class="col-12 col-md-6 col-lg-4">
				<div class="card shadow-sm mb-4">
					<div class="card-body text-center">
						<div class="bottom">
							<h6>Социальные сети</h6>
							<!-- Социальные сети -->
							<? if(App::$current_landing->vk_social_c != NULL) { ?>
								<a a target="_blank" class="mr-1" href="<?=App::$current_landing->vk_social_c?>" >
									<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/vk.png" alt="Вконтакте" style="width:40px;margin:5px;" title="Группа Вконтакте"/>
								</a>
							<? } 
							if(App::$current_landing->insta_social_c != NULL && false) { ?>
								<a a target="_blank" class="mr-1" href="<?=App::$current_landing->insta_social_c?>" >
									<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/insta.png" alt="Инстаграмм" style="width:40px;margin:5px;" title="Профиль Инстаграмм"/>
								</a>
							<? }
							if(App::$current_landing->ok_social_c != NULL) { ?>
								<a a target="_blank" class="mr-1" href="<?=App::$current_landing->ok_social_c?>" >
									<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/odnoklassniki.png" alt="Одноклассники" style="width:40px;margin:5px;" title="Группа в Одноклассниках"/>
								</a>
							<? }
							 if(App::$current_landing->youtube_social_c != NULL) { ?>
							<a target="_blank" class="mr-1" href="<?=App::$current_landing->youtube_social_c?>">
								<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/youtube.png" style="width:45px;margin:5px;" alt="Youtube" title="Канал Youtube" />
							</a>
							<? }  
							if(App::$current_landing->tiktok_social_c != NULL) { ?>
							<a target="_blank" class="mr-1" href="<?=App::$current_landing->tiktok_social_c?>">
								<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/tiktok.png" style="width:45px;margin:5px;" alt="TikTok" title="Профиль TikTok" />
							</a>
							<? }
							if(App::$current_landing->telegram_social_c != NULL) { ?>
								<a target="_blank" href="<?=App::$current_landing->telegram_social_c?>">
									<img class="img" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/telegram.png" style="width:35px;" alt="Telegram" title="Данная картинка является ссылкой на Telegram канал" />
								</a>
							<? } ?>
							<!-- END Социальные сети -->
						</div> 
					</div>
                </div>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-12 col-lg-8 mb-4">
				<div class="card shadow-sm">
					<div class="card-body">
						<?=html_entity_decode(App::$current_landing->yandex_map_c)?>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-4 mb-4 text-left">
				<div class="row">
					<div class="col-12 mb-4">
						<div class="card shadow-sm">
							<div class="card-body">
								<h6><i class="material-icons md-18">public</i> Адрес</h6>
								<p><?=html_entity_decode(App::$current_landing->address_c)?></p>
							</div>
						</div>
					</div>
					<?php 
						$current_branchs = NFfunctions::getChildBeans(App::$current_landing, 'brnch_branch');
						if(count($current_branchs) > 1) { 
					?>
					<div class="col-12 mb-4">
						<div class="card shadow-sm">
							<div class="card-body">
								<h6><i class="material-icons md-18">public</i> Адреса из которых осуществляется доставка</h6>
								<p>
								<?php foreach($current_branchs as $branch){ ?>
									<div>• Торговая точка: <?=App::$current_city->name?>, ул.<?=$branch->street_c?>, д.<?=$branch->home_c?></div>
								<?php } ?>
								</p>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-12 col-lg-8 mb-4">
			</div>
			<div class="col-12 col-lg-4 mb-4 text-right pr-2">
				<? if(!empty(App::$current_landing->company_details_c)){ ?>
				<h6>Юридическая информация</h6>
				<p> 
					<?=html_entity_decode(App::$current_landing->company_details_c)?>
				</p>
				<hr>
				<? } ?>
			</div>
		</div>
	</div>
</div>
<!-- END Контакты -->


<?php  include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>