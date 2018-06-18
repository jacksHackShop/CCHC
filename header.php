<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title><?php wp_title(''); ?></title>

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-touch-icon.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">
            <meta name="theme-color" content="#121212">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>

		<?php // drop Google Analytics Here ?>
		<?php // end analytics ?>

	</head>

	<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

		<div id="container">

			<header class="header" role="banner" itemscope itemtype="http://schema.org/WPHeader">

				<div id="inner-header" class="wrap cf">

					<a id="logo" href="<?php echo home_url(); ?>" rel="nofollow"><img src="<?php echo get_template_directory_uri();?>/library/images/cchc-logo.svg"></a>

					<?php // if you'd like to use the site description you can un-comment it below ?>
					<?php // bloginfo('description'); ?>

					<div class="nav_bar">
						<nav id="menu" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
							
							<?php $menu_items = wp_get_nav_menu_items('mainNav');?>
							<ul id="menu_mainnav" class="nav top-nav cf">
							<?php foreach ($menu_items as $item) {
							
							    echo "<li class='menu-item menu-item-type-custom menu-item-object-custom'><a href='{$item->url}'>{$item->title}</a></li>"; 
							}
							echo "<li class='nav_button menu-item menu-item-type-custom menu-item-object-custom'><div id='button_wrapper'><a href='http://www.srfcure.org/donate?view=donation' target='_blank' id ='donate_button' class='button white'>Donate</a></div>";?>
							</ul>

						</nav>
						
						<div id="ham" onclick="document.getElementById('menu').classList.toggle('show');">
							<img src="<?php echo get_template_directory_uri();?>/library/images/menu.svg">
						</div>
					</div>
				</div>

			</header>
