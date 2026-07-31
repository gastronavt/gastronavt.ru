<?php
	$header_name = 'Вакансии';
	$header_back_url = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'];
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

<!-- Вакансии -->
<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
	<?php if(!empty(App::$current_landing->phone_job_c)){ ?>
		<div class="row mt-2 mb-2">
			<div class="col-12">
				<div class="card border-0 shadow-sm">
					<div class="card-body">
						<div class="row">
							<div class="col-auto align-self-center pr-1">
								<span class="btn btn-success button-rounded-26 padding-top:5px;">
									!
								</span>
							</div>
							<div class="col pl-1">
								<p class="mb-0" style="font-size:10pt;">
									В случае возникновения вопросов обращайтесь в отдел подбора персонала: <a class="mr-3" href="tel:<?=App::$current_landing->phone_job_c?>" style="font-size:12pt;color:#000;white-space: nowrap;"><?=App::$current_landing->phone_job_c?></a>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if($_GET['success'] == 'yes'){ ?>
		<div class="card bg-template shadow mt-4 h-190" style="border-radius:7px;margin-bottom:10px;">
			<div class="card-body">
				<center style="width:100%">
					<img class="avatar avatar-60" style="display:inline-block;" src="https://pryanikov38.ru/assets_new/images/good_green.png" alt="Спасибо за заказ" title="Спасибо за Ваш заказ">
					<div style="display:inline-block;">
						<h1 class="mb-1 text-white" style="font-size:19pt;">Ваша заявка принята! С вами свяжутся!</h1>
					</div>
				</center>
			</div>
		</div>
	<?php } elseif($_GET['success'] == 'no'){ ?>
		<div class="card shadow mt-4 h-190" style="border-radius:7px;margin-bottom:10px;background:red;">
			<div class="card-body">
				<center style="width:100%">
					<div style="display:inline-block;">
						<h1 class="mb-1 text-white" style="font-size:19pt;">Неверный проверочный код!</h1>
					</div>
				</center>
			</div>
		</div>
	<?php } ?>
		<div class="card py-3">	
			<center>
				<div class="card-content">
					<div class="mb-2" style="font-size:14pt;">
						Выберите вакансию на которую Вы претендуете
					</div>
					<select name="marital_status_c" class="form-control" style="width:200px;" onchange="top.location='<?=NFfunctions::getSiteProtocol()?><?=parse_url(App::$current_landing->link_c)['host']?>/main/job/?vacancy='+this.value">
						<option value="">Не выбрано</option>
						<option value="01" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='01' ){ ?> selected <?}?> >Водитель-курьер</option>
						<option value="04" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='04' ){ ?> selected <?}?>>Администратор</option>
						<option value="02" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='02' ){ ?> selected <?}?>>Диспетчер</option>
						<option value="03" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='03' ){ ?> selected <?}?>>Повар</option>
						<option value="05" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='05' ){ ?> selected <?}?>>Художник</option>
						<option value="06" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='06' ){ ?> selected <?}?>>Уборщица</option>
						<option value="07" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='07' ){ ?> selected <?}?>>Упаковщица</option>
					</select>

					<?php if(!empty($_GET['vacancy'])){ ?>
						<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/vacancy/<?=$_GET['vacancy']?>.png" style="max-height:120px;width:auto;margin:15px;">
						<div class="card-content px-3">
							<form method="POST" action="/main/send_job" name="create_job">
								<div style="font-size:14pt;">
									Заполните анкету, и мы свяжемся с Вами
								</div>
								<input type="hidden" name="vacancy_c" value="<?=$_GET[ 'vacancy']?>">
								<div style="border:1px solid; border-radius:10px;border-color:#eee;padding:20px;margin-top:20px;">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Фамилия</label>
												<input name="last_name_c" placeholder="Иванов" type="text" class="form-control" value="" />
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Имя</label>
												<input name="first_name_c" placeholder="Иван" type="text" class="form-control" value="" />
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Отчество</label>
												<input name="middle_name_c" placeholder="Иванович" type="text" class="form-control" value="" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Дата рождения</label>
												<input id="date_of_birth_c" name="date_of_birth_c" placeholder="дд.мм.гггг" type="text" class="form-control" value="" />
											</div>
										</div>
										<div class="col-md-8">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Адрес проживания</label>
												<input name="residence_c" placeholder="город, улица, дом" type="text" class="form-control" value="" />
											</div>
										</div>
									</div>
								</div>
								<div style="border:1px solid; border-radius:10px;border-color:#eee;padding:20px;margin-top:20px;">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Контактный телефон</label>
												<input id="work_phone_c" name="work_phone_c" placeholder="+7 (999) 999-99-99" type="text" class="form-control" value="" />
											</div>
										</div>
									</div>
								</div>
								<div style="border:1px solid; border-radius:10px;border-color:#eee;padding:20px;margin-top:20px;">
									<div class="row">
										<div class="col-md-6">
											<label style="font-size:12pt;font-weight:bold;color:#000;">Семейное положение</label>
											<select name="marital_status_c" class="form-control" style="width:60%;margin-top:0px;">
												<option value="01">В браке</option>
												<option value="02">Холост</option>
											</select>
										</div>
										<div class="col-md-6">
											<label style="font-size:12pt;font-weight:bold;color:#000;">Дети</label>
											<select name="childrens_c" class="form-control" style="width:60%;margin-top:0px;">
												<option value="01">Есть</option>
												<option value="02">Нет</option>
											</select>
										</div>
									</div>
								</div>
								<? if($_GET['vacancy']=='01'){ ?>
								<div style="border:1px solid; border-radius:10px;border-color:#eee;padding:20px;margin-top:20px;">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Марка автомобиля</label>
												<input name="car_model_c" placeholder="лада гранта" type="text" class="form-control" value="" />
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Вид топлива</label>
												<input name="fuel_type_c" placeholder="бензин - 95й" type="text" class="form-control" value="" />
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Средний расход по городу</label>
												<input name="fuel_consumption_c" placeholder="10 литров на 100км" type="text" class="form-control" value="" />
											</div>
										</div>
									</div>
								</div>
								<? } ?>
								<div style="border:1px solid; border-radius:10px;border-color:#eee;padding:20px;margin-top:20px;">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Предыдущие места работы</label>
												<input name="previous_jobs_c" placeholder="" type="text" class="form-control" value="" />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Причины увольнения</label>
												<input name="reason_dissmisal_c" placeholder="" type="text" class="form-control" value="" />
											</div>
										</div>
									</div>
								</div>
								<div style="border:1px solid; border-radius:10px;border-color:#eee;padding:20px;margin-top:20px;margin-bottom:20px;">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Образование (где учились)</label>
												<input name="education_c" placeholder="Московкий аграрный университет" type="text" class="form-control" value="" />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Образование (на кого учились)</label>
												<input name="education_position_c" placeholder="Эколог" type="text" class="form-control" value="" />
											</div>
										</div>
									</div>
								</div>
								
								<div style="border:1px solid; border-radius:10px;border-color:#eee;padding:20px;margin-top:20px;margin-bottom:20px;">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group label-floating">
												<label style="font-size:12pt;font-weight:bold;color:#000;">Введите код с картинки</label>
												<img src="/main/captcha" style="float:left;" />
												<input name="norobot" type="text" class="form-control" value="" />
											</div>
										</div>
									</div>
								</div>
								
								<div class="text-center" style="font-size:10pt;max-width:500px;">
									<div class="checkbox" style="float:left;margin-top:0px;">
										<label>
											<input type="checkbox" name="optionsCheckboxes" required>
										</label>
									</div>
									Подтвердите Ваше <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/main/agreement" target="_blank">согласие на обработку своих персональных данных</a>
								</div>
								
								</br>
								<a id="send_job" class="btn btn-lg btn-default text-white btn-block btn-rounded shadow" style="background:<?=App::$current_organization->main_color_c?>;"><span>Отправить анкету</span></a>
							</form>
						</div>
						<script type="text/javascript">
							$(document).ready(function() {
								$("#send_job").click(function() {
									if (!$("input[name=optionsCheckboxes]").is(':checked')) {
										showErrorModal("Дайте свое согласие на обработку данных!", "error");
									} else if (!$("input[name=first_name_c]").val().trim() != '') {
										showErrorModal("Укажите Ваше имя!", "error");
									} else if (!$("input[name=middle_name_c]").val().trim() != '') {
										showErrorModal("Укажите Ваше отчество!", "error");
									} else if (!$("input[name=last_name_c]").val().trim() != '') {
										showErrorModal("Укажите Вашу фамилию!", "error");
									} else if (!$("input[name=date_of_birth_c]").val().trim() != '') {
										showErrorModal("Укажите дату Вашего рождения!", "error");
									} else if (!$("input[name=work_phone_c]").val().trim() != '') {
										showErrorModal("Укажите Ваш номер телефона!", "error");
									} else if (!$("input[name=residence_c]").val().trim() != '') {
										showErrorModal("Укажите Ваш адрес проживания!", "error");
									} else if (!$("input[name=education_c]").val().trim() != '') {
										showErrorModal("Укажите Ваше образование!", "error");
									} else if (!$("input[name=norobot]").val().trim() != '') {
										showErrorModal("Укажите код с картинки!", "error");
									} else {
										$("#send_job").attr('disabled', true);
										$("#send_job").text('Заявка отправляется...');
										document.forms["create_job"].submit();
									}
								});
							});
						</script>
						
						<script type="text/javascript">
							$(function() {
								$("#work_phone_c").mask("+7 (999) 999-99-99");
								$("#date_of_birth_c").mask("99.99.9999");
							});
						</script>
					<?php } ?>
				</div>
			</center>
		</div>
</div>
<!-- END Вакансии -->


<?php  include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>