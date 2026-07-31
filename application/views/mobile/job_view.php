<!-- Вакансии -->
<div class="container pt-3">
		<div class="jumbotron text-center bg-white" >
			<div class="mb-2" style="font-size:14pt;">
				Выберите вакансию на которую Вы претендуете
			</div>
			<select name="marital_status_c" class="form-control" style="width:100%;" onchange="top.location='<?=App::$current_landing->link_c?>/mobile/job/?session_id=<?=$_REQUEST['session_id']?>&vacancy='+this.value">
				<option value="">Не выбрано</option>
				<option value="01" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='01' ){ ?> selected <?}?> >Водитель-курьер</option>
				<option value="04" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='04' ){ ?> selected <?}?>>Администратор</option>
				<option value="02" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='02' ){ ?> selected <?}?>>Диспетчер</option>
				<option value="03" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='03' ){ ?> selected <?}?>>Повар</option>
				<option value="05" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='05' ){ ?> selected <?}?>>Художник</option>
				<option value="06" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='06' ){ ?> selected <?}?>>Уборщица</option>
				<option value="07" <? if(isset($_GET[ 'vacancy']) && $_GET[ 'vacancy']=='07' ){ ?> selected <?}?>>Упаковщица</option>
			</select>
			<?php if(isset($_GET['success'])){ ?>
			<div class="card-content" style="max-width:800px;">
				<div style="font-size:14pt;margin:10px;">
					Ваша заявка принята! С Вами свяжутся!
				</div>
			</div>
			<?php } elseif(!empty($_GET['vacancy'])){ ?>
				<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/images/style_images/vacancy/<?=$_GET['vacancy']?>.png" style="max-height:120px;width:auto;margin:15px;">
				<div class="card-content">
					<form method="POST" action="/mobile/send_job?session_id=<?=$_REQUEST['session_id']?>" name="create_job">
						<div style="font-size:14pt;">
							Заполните анкету, и мы свяжемся с Вами
						</div>
						<input type="hidden" name="vacancy_c" value="<?=$_GET[ 'vacancy']?>">
						<div style="border:1px solid; border-radius:10px;border-color:#eee;padding:15px;margin-top:20px;">
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
										<input id="date_of_birth_c" name="date_of_birth_c" type="text" class="form-control" value="" />
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
									<select name="marital_status_c" class="form-control" style="width:100%;">
										<option value="01">В браке</option>
										<option value="02">Холост</option>
									</select>
								</div>
								<div class="col-md-6">
									<label style="font-size:12pt;font-weight:bold;color:#000;">Дети</label>
									<select name="childrens_c" class="form-control" style="width:100%;">
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
						<div class="text-center" style="font-size:10pt;max-width:500px;">
							<div class="checkbox" style="float:left;margin-top:0px;">
								<label>
									<input type="checkbox" name="optionsCheckboxes" required>
								</label>
							</div>
							Подтвердите Ваше <a href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/mobile/agreement?session_id=<?=$_REQUEST['session_id']?>">согласие на обработку своих персональных данных</a>
						</div>
						</br>
						<a id="send_job" class="btn btn-lg btn-default text-white btn-block btn-rounded shadow" style="background:<?=App::$current_organization->main_color_c?>;"><span>Отправить анкету</span></a>
					</form>
				</div>
				<script type="text/javascript">
					$(document).ready(function() {
						$("#send_job").click(function() {
							if (!$("input[name=optionsCheckboxes]").is(':checked')) {
								swal("Внимание", "Дайте свое согласие на обработку данных!", "error");
							} else if (!$("input[name=first_name_c]").val().trim() != '') {
								swal("Внимание", "Укажите Ваше имя!", "error");
							} else if (!$("input[name=middle_name_c]").val().trim() != '') {
								swal("Внимание", "Укажите Ваше отчество!", "error");
							} else if (!$("input[name=last_name_c]").val().trim() != '') {
								swal("Внимание", "Укажите Вашу фамилию!", "error");
							} else if (!$("input[name=date_of_birth_c]").val().trim() != '') {
								swal("Внимание", "Укажите дату Вашего рождения!", "error");
							} else if (!$("input[name=work_phone_c]").val().trim() != '') {
								swal("Внимание", "Укажите Ваш номер телефона!", "error");
							} else if (!$("input[name=residence_c]").val().trim() != '') {
								swal("Внимание", "Укажите Ваш адрес проживания!", "error");
							} else if (!$("input[name=education_c]").val().trim() != '') {
								swal("Внимание", "Укажите Ваше образование!", "error");
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

</div>
<!-- END Вакансии -->
