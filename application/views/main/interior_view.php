<?php
	global $db;
	
	$interior = $db->fetchRow($db->query("
		SELECT
			e.id,
			e.name
		FROM extrr_exterior e 
        JOIN extrr_exterior_cstm e_cstm ON e_cstm.id_c = e.id 
			AND e.deleted = 0
			AND e_cstm.lngng_landings_id_c = '".App::$current_landing->id."'
	"));
	
	$query_screens = $db->query("
		SELECT
			ps.id,
			ps_cstm.main_c,
			ps_cstm.yaw_c,
			ps_cstm.pitch_c,
			p_cstm.comment_c
		FROM pnscr_panorama_screen ps
		JOIN pnscr_panorama_screen_cstm ps_cstm ON ps.id = ps_cstm.id_c AND ps.deleted = 0
		JOIN pnrma_panorama_pnscr_panorama_screen_1_c p_ps ON p_ps.pnrma_panorama_pnscr_panorama_screen_1pnscr_panorama_screen_idb = ps_cstm.id_c AND p_ps.deleted = 0
		JOIN pnrma_panorama p ON p.id = p_ps.pnrma_panorama_pnscr_panorama_screen_1pnrma_panorama_ida AND p.deleted = 0
		JOIN pnrma_panorama_cstm p_cstm ON p_cstm.id_c = p.id
        JOIN extrr_exterior_pnrma_panorama_1_c e_p ON e_p.extrr_exterior_pnrma_panorama_1pnrma_panorama_idb = p.id AND e_p.deleted = 0 AND e_p.extrr_exterior_pnrma_panorama_1extrr_exterior_ida = '".$interior['id']."'
	");
	
	$screens = [];

	$main_screen_id = null;
	while($screen = $db->fetchByAssoc($query_screens)) {
		if($screen['main_c'] == 1){
			$main_screen_id = $screen['id'];
		}
		//получаем метки
		$query_hotspot = $db->query("
			SELECT
				h.id,
				h.name,
				h_cstm.yaw_c,
				h_cstm.pitch_c,
				h_cstm.pnscr_panorama_screen_id_c
			FROM pnhtp_panorama_hotspots h
			JOIN pnhtp_panorama_hotspots_cstm h_cstm ON h.id = h_cstm.id_c AND h.deleted = 0
			JOIN pnscr_panorama_screen_pnhtp_panorama_hotspots_1_c ps_h ON ps_h.pnscr_pano9f76otspots_idb = h_cstm.id_c AND ps_h.deleted = 0 AND ps_h.pnscr_pano74c9_screen_ida = '".$screen['id']."'
		");

		$hotspots = [];
		while($hotspot = $db->fetchByAssoc($query_hotspot)) {
			$hotspots[] = $hotspot;
		}

		//добавляем метри в скрин
		$screen['hotspots'] = $hotspots;
		
		$screens[] = $screen;
	}
	
	
	$query_galleries = $db->query("
		SELECT
			ig.id,
			ig_cstm.col_style_c
		FROM igall_interior_gallery ig
		JOIN igall_interior_gallery_cstm ig_cstm ON ig.id = ig_cstm.id_c AND ig.deleted = 0
		JOIN extrr_exterior_igall_interior_gallery_1_c ee_ig ON 
			ee_ig.extrr_extedbaagallery_idb = ig_cstm.id_c 
			AND ee_ig.deleted = 0 
			AND ee_ig.extrr_exterior_igall_interior_gallery_1extrr_exterior_ida = '".$interior['id']."'
		ORDER BY ig_cstm.sorting_c ASC
	");
	
	$galleries = [];
	while($gallery = $db->fetchByAssoc($query_galleries)) {
		$galleries[] = $gallery;
	}
?>

<?php
	$header_name = 'Интерьер';
	$header_back_url = NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'];
	include CORE_FOLDER.'/application/views/widget/header_menu.php'; //Верхнее меню 
?>

<?php include CORE_FOLDER.'/application/views/widget/card.php'; //Ваш заказ ?>

<!-- Интерьер -->
<div class="container" <?php if(!empty(App::$current_aggregator)){ ?> style="margin-top:70px;" <?php } ?>>
	<div class="jumbotron text-center mt-3 bg-white shadow-sm" style="padding-top:10px;">
		<?php 
		if(!empty($screens)) { ?>
			<div class="row">
				<div class="col-sm pt-2">
					<div id="panorama"></div>
					
					<link rel="stylesheet" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/css/pannellum.css"/>
					<script type="text/javascript" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/pannellum.js"></script>
					<style>
						#panorama {
							max-width: 600px;
							height: 400px;
						}
						
						.custom-hotspot {
							height: 35px;
							width: 35px;
							background: url('/assets_new/images/point.png') no-repeat;
							background-size: contain;
						}
						.custom-hotspot:hover {
							height: 45px;
							width:45px;
						}
					</style>
					
					<script>
						pannellum.viewer('panorama', {   
							"default": {
								"firstScene": "<?=$main_screen_id?>",
								"sceneFadeDuration": 1000,
								"autoLoad": true
							},

							"scenes": {
								<?php foreach($screens as $screen) { ?>
									"<?=$screen['id']?>": {
										//"title": "Подпись",
										"autoRotate": -2,
										"hfov": 110,
										"pitch": <?=$screen['pitch_c']?>,
										"yaw": <?=$screen['yaw_c']?>,
										"type": "equirectangular",
										"panorama": "<?=NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST'].'/upload/'.$screen['id'].'_image_c'?>",
										"hotSpots": [
											<?php foreach($screen['hotspots'] as $hotspot) { ?>
											{
												"pitch": <?=$hotspot['pitch_c']?>,
												"yaw": <?=$hotspot['yaw_c']?>,
												"cssClass": "custom-hotspot",
												"text": "<?=$hotspot['name']?>",
												"sceneId": "<?=$hotspot['pnscr_panorama_screen_id_c']?>",
												"scale": "true",
											},
											<?php } ?>
										]
									},
								<?php }  ?>
							}
						});
					</script>
				</div>
				<div class="col-sm pt-2">
					<?=html_entity_decode($screen['comment_c'])?>
				</div>
			</div>
		<?php } ?>
		
		<?php if(!empty($galleries)) { ?>
			<div class=" gallery-container">
				<link rel="stylesheet" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/css/baguetteBox.min.css">
				<link rel="stylesheet" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/css/fluid-gallery.css">

				<h2>Фото-галерея</h2>
				
				<div class="tz-gallery">
					<div class="row">
						<?php foreach($galleries as $gallery) { ?>
						<div class="col-sm-12 <?=$gallery['col_style_c']?>">
							<a class="lightbox" href="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$gallery['id']?>_image_c">
								<img src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$gallery['id']?>_image_c">
							</a>
						</div>
						<?php } ?>
					</div>

				</div>
			</div>
		<?php } ?>
	</div>
</div>

<script src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/assets_new/js/baguetteBox.min.js"></script>
<script>
    baguetteBox.run('.tz-gallery');
</script>
<!-- END Интерьер -->


<?php  include CORE_FOLDER.'/application/views/widget/buttom_menu.php'; //Нижнее меню ?>