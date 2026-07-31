<?php
const CRM_URL = 'crm.giveat.ru';

chdir('/var/www/domains_nginx/'.CRM_URL.'/');
define('sugarEntry', true);
require_once '/var/www/domains_nginx/'.CRM_URL.'/include/entryPoint.php';
require_once '/var/www/domains_nginx/'.CRM_URL.'/custom/include/Neoflex/Functions.php';
require_once '/var/www/domains_nginx/'.CRM_URL.'/custom/include/Neoflex/Constants.php';

$current_landing = BeanFactory::getBean('lngng_landings', 'c57ab24b-908b-b16b-288e-65a04ba09a56');

?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="author" content="Gastronavt"/>	
		<meta name="description" content="Gastronavt - сайт, приложение, CRM | Платформа для служб доставки еды, кафе и ресторанов"/>
		<meta name="keywords" content="сайт для доставки еды, приложение для суши, сайт для суши роллов, сайт для рестора, приложение для ресторана">	
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
				
  		<!-- SITE TITLE -->
		<title>Gastronavt - сайт, приложение, CRM  для доставки еды, кафе, ресторана</title>
							
		<!-- FAVICON AND TOUCH ICONS -->
		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">

		<!-- GOOGLE FONTS -->
		<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700&display=swap" rel="stylesheet">

		<!-- BOOTSTRAP CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
				
		<!-- FONT ICONS -->
		<link href="css/flaticon.css" rel="stylesheet">

		<!-- PLUGINS STYLESHEET -->
		<link href="css/menu.css" rel="stylesheet">	
		<link id="effect" href="css/dropdown-effects/fade-down.css" media="all" rel="stylesheet">
		<link href="css/magnific-popup.css" rel="stylesheet">	
		<link href="css/owl.carousel.min.css" rel="stylesheet">
		<link href="css/owl.theme.default.min.css" rel="stylesheet">

		<!-- ON SCROLL ANIMATION -->
		<link href="css/animate.css" rel="stylesheet">

		<!-- TEMPLATE CSS -->
		<link href="css/style.css" rel="stylesheet"> 
		
		<!-- RESPONSIVE CSS -->
		<link href="css/responsive.css" rel="stylesheet">

	</head>
	
	<body>
		<!-- PRELOADER SPINNER
		============================================= -->	
		<div id="loading">
			<div id="loading-center">
				<div id="loading-center-absolute">
					<div class="object" id="object_one"></div>
					<div class="object" id="object_two"></div>
					<div class="object" id="object_three"></div>
					<div class="object" id="object_four"></div>
				</div>
			</div>
		</div>

		<!-- PAGE CONTENT
		============================================= -->	
		<div id="page" class="page">
			<!-- HEADER
			============================================= -->
			<header id="header" class="header white-menu navbar-dark">
				<div class="header-wrapper">


					<!-- MOBILE HEADER -->
				    <div class="wsmobileheader clearfix">	  	
				    	<span class="smllogo"><img src="images/logo-1-1.png" alt="mobile-logo"/></span>
				    	<a id="wsnavtoggle" class="wsanimated-arrow"><span></span></a>	
				 	</div>


				 	<!-- NAVIGATION MENU -->
				  	<div class="wsmainfull menu clearfix">
	    				<div class="wsmainwp clearfix">


	    					<!-- HEADER LOGO -->
	    					<div class="desktoplogo"><a href="#hero-1" class="logo-black"><img src="images/logo-1-1.png" alt="header-logo"></a></div>
	    					<div class="desktoplogo"><a href="#hero-1" class="logo-white"><img src="images/logo-1-1.png" alt="header-logo"></a></div>


	    					<!-- MAIN MENU -->
	      					<nav class="wsmenu clearfix">
	        					<ul class="wsmenu-list nav-orange-red-hover">
						          	<li class="nl-simple" aria-haspopup="true"><a href="https://gastronavt.ru">Главная</a></li>
									<li class="nl-simple" aria-haspopup="true"><a href="https://gastronavt.ru/#features-2">Почему выбирают нас</a></li>
							    	<li class="nl-simple" aria-haspopup="true"><a href="https://gastronavt.ru/#review-1">Отзывы</a></li>
							    	<!--<li class="nl-simple" aria-haspopup="true"><a href="#faqs-2">FAQ</a></li>-->
									<li class="nl-simple" aria-haspopup="true"><a href="/blog.php">Блог</a></li>
								    <li class="nl-simple" aria-haspopup="true">
								    	<a href="#content-4" class="btn btn-tra-white orange-red-hover last-link">Подключиться</a>
								    </li> 
	        					</ul>
	        				</nav>	<!-- END MAIN MENU -->


	    				</div>
	    			</div>	<!-- END NAVIGATION MENU -->


				</div>     <!-- End header-wrapper -->
			</header>	<!-- END HEADER -->	<!-- END HEADER -->




			<!-- BLOG POSTS LISTING
			============================================= -->
			<section id="blog-page" class="bg-snow wide-50 inner-page-hero blog-page-section division">
				<div class="container">


					<!-- SECTION TITLE -->	
					<div class="row justify-content-center">	
						<div class="col-md-10 col-lg-8">
							<div class="section-title title-02 mb-85">	
								<h3 class="h3-xl">Добро пожаловать в наш уютный блог</h3>
							</div>	
						</div>
					</div>


					<!-- FEATURED POST -->
					<div class="rel blog-post-wide featured-post">
	 					<div class="row d-flex align-items-center">

	 						<!-- Featured Badge -->
	 						<div class="featured-badge ico-25 bg-whitesmoke yellow-color">
	 							<span class="flaticon-star-1"></span>
	 						</div>
																		
							<!-- BLOG POST IMAGE -->
				 			<div class="col-lg-7 blog-post-img">
								<img class="img-fluid" src="images/blog/post-1-img.jpg" alt="blog-post-image" />	
							</div>

							<!-- BLOG POST TEXT -->
							<div class="col-lg-5 blog-post-txt">

								<!-- Post Link -->
								<h5 class="h5-xl">
									<a href="single-post.html">Tempor sapien donec gravida a suscipit and porta justo vitae</a>
								</h5>

								<!-- Text -->
								<p class="p-lg">Aliqum mullam blandit vitae tempor sapien and donec lipsum gravida and porta 
								   undo velna dolor in cubilia...
								</p>

								<!-- Post Meta -->
								<div class="post-meta"><p>OLMO News &ensp;|&ensp; 38 Comments</p></div>	

							</div>	<!-- END BLOG POST TEXT -->

						</div>   <!-- End row -->
				 	</div>	<!-- END FEATURED POST -->


					<!-- POSTS WRAPPER -->
					<div class="posts-wrapper">


						<!-- BLOG POSTS CATEGORY --> 
						<div class="row">
							<div class="col-md-12">
								<h5 class="h5-lg posts-category">Последние статьи</h5>
							</div>
						</div>


				 		<!-- BLOG POSTS -->
					 	<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">

							<?php
								global $db;
								$queryNews = $db->query("
									SELECT 
										nn.id, 
										nn.name, 
										nn_cstm.text_c, 
										nn_cstm.color_background_c,
										nn_cstm.color_text_c,
										nn_cstm.image_fon_c,
										nn_cstm.image_c,
										nn_cstm.publish_date_c,
										nn_cstm.type_c,
										nn_cstm.link_name_c
									FROM news_news nn
									LEFT JOIN news_news_cstm nn_cstm ON nn_cstm.id_c = nn.id AND nn.deleted = 0
									LEFT JOIN lngng_landings_news_news_1_c ll_nn ON ll_nn.lngng_landings_news_news_1news_news_idb = nn_cstm.id_c AND ll_nn.deleted = 0
									WHERE 
										ll_nn.lngng_landings_news_news_1lngng_landings_ida = '".$current_landing->id."'
									ORDER BY nn_cstm.publish_date_c DESC;
								");

								while($new = $db->fetchByAssoc($queryNews)) {
							?>
								<!-- BLOG POST #1 -->
								<div class="col">
									<div class="blog-1-post mb-50 wow fadeInUp">

										<!-- BLOG POST IMAGE -->
										<div class="blog-post-img">
											<img class="img-fluid" src="<?=NFfunctions::getSiteProtocol()?><?=$_SERVER['HTTP_HOST']?>/upload/<?=$new['id']?>_image_c" alt="blog-post-image" style="width:100%;max-height:270px;"/>
										</div>

										<!-- BLOG POST TEXT -->
										<div class="blog-post-txt">

											<!-- Post Tag -->
											<p class="post-tag"><?=date('d.m.Y H:i', strtotime($new['publish_date_c']))?></p>	

											<!-- Post Link -->
											<h5 class="h5-sm">
												<a href="single-post.html"><?=$new['name']?></a>
											</h5>

											<!-- Text -->
											<p class="p-lg"><?=mb_strimwidth(strip_tags(html_entity_decode($new['text_c'])),0, 150, "...")?></p>

											<!-- Post Meta -->
											<div class="post-meta"><p>OLMO News &ensp;|&ensp; 9 Comments</p></div>	

										</div>	<!-- END BLOG POST TEXT -->

									</div>
								</div>	<!-- END  BLOG POST #1 -->
							<? } ?>

						</div>	<!-- END BLOG POSTS -->


				 	</div>	<!-- END POSTS WRAPPER -->


				</div>     <!-- End container -->
			</section>	<!-- END BLOG POSTS LISTING -->

			<!-- NEWSLETTER-1
			============================================= -->
			<section id="newsletter-1" class="bg-snow newsletter-section division">
				<div class="container">
					<div class="newsletter-wrapper bg-white">
						<div class="row d-flex align-items-center row-cols-1 row-cols-lg-2">


							<!-- NEWSLETTER TEXT -->	
							<div class="col">
								<div class="newsletter-txt pr-20">	
									<h4 class="h4-xl">Stay up to date with our news, ideas and updates</h4>	
								</div>								
							</div>


							<!-- NEWSLETTER FORM -->
							<div class="col">
								<form class="newsletter-form">
											
									<div class="input-group">
										<input type="email" autocomplete="off" class="form-control" placeholder="Your email address" required id="s-email">								
										<span class="input-group-btn">
											<button type="submit" class="btn btn-md btn-skyblue tra-grey-hover">Subscribe Now</button>
										</span>										
									</div>

									<!-- Newsletter Form Notification -->	
									<label for="s-email" class="form-notification"></label>
												
								</form>							
							</div>	  <!-- END NEWSLETTER FORM -->


						</div>	  <!-- End row -->
					</div>    <!-- End newsletter-wrapper -->
				</div>	   <!-- End container -->	
			</section>	<!-- END NEWSLETTER-1 -->




			<!-- FOOTER-1
			============================================= -->
			<footer id="footer-1" class="footer division">
				<div class="container">


					<!-- FOOTER CONTENT -->
					<div class="row">	


						<!-- FOOTER INFO -->
						<div class="col-lg-12">
							<div class="footer-info mb-10">

								<!-- Footer Logo -->	
								<img class="footer-logo mb-15" src="images/logo-1-1.png" alt="footer-logo">

								<!-- Text -->	
								<p class="p-md">Gastronavt - платформа для доставок еды с космическими возможностями
								</p>

							</div>	
						</div>	

					</div>	<!-- END FOOTER CONTENT -->


					<hr>


					<!-- BOTTOM FOOTER -->
					<div class="bottom-footer">
						<div class="row row-cols-1 row-cols-md-2 d-flex align-items-center">


							<!-- FOOTER COPYRIGHT -->
							<div class="col">
								<div class="footer-copyright">
									<p>&copy; 2019 - 2024  Win-Technology | Gastronavt</p>
								</div>
							</div>


							<!-- BOTTOM FOOTER LINKS -->
							<div class="col">
								<ul class="bottom-footer-list text-secondary text-end">
									<li class="first-li"><p><a href="https://t.me/+7BLfgQDoaSdjZDRi">Telegram</a></p></li>
								</ul>
							</div>


						</div>  <!-- End row -->
					</div>	<!-- BOTTOM FOOTER -->


				</div>
			</footer>	<!-- END FOOTER-1 -->




		</div>	<!-- END PAGE CONTENT -->	
			



		<!-- EXTERNAL SCRIPTS
		============================================= -->	
		<script src="js/jquery-3.6.0.min.js"></script>
		<script src="js/bootstrap.min.js"></script>	
		<script src="js/modernizr.custom.js"></script>
		<script src="js/jquery.easing.js"></script>
		<script src="js/jquery.appear.js"></script>
		<script src="js/jquery.scrollto.js"></script>	
		<script src="js/menu.js"></script>
		<script src="js/owl.carousel.min.js"></script>
		<script src="js/jquery.magnific-popup.min.js"></script>
		<script src="js/quick-form.js"></script>	
		<script src="js/request-form.js"></script>	
		<script src="js/jquery.validate.min.js"></script>
		<script src="js/jquery.ajaxchimp.min.js"></script>	
		<script src="js/wow.js"></script>
				
		<!-- Custom Script -->		
		<script src="js/custom.js"></script>

	</body>



</html>