<?php
/*
 Template Name: home
*/
 ?>
 <?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<main id="main" class="m-all t-3of3 d-7of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<?php the_post(); ?>

							<div class="hero" style='background-image:url("<?php echo get_field("hero_image"); ?>");'>
								<div class="hero_overlay">
									<?php echo get_field("hero_text"); ?>
								</div>
							</div>

							<div class="side_by_side_row gray cf">
								<div class="side_by_side image right" style='background-image:url("<?php echo get_field("side_by_side_image_1"); ?>");'></div><div class="side_by_side text left"><?php echo get_field("side_by_side_text_1"); ?></div>
							</div>
							
							<img class="img_divider" src="<?php echo get_field("image_divider"); ?>">

							<div class="button_row cf">
								<a href="<?php echo get_field("button_1_url"); ?>" target="_blank" class="button white"><?php echo get_field("button_1_label"); ?></a>
								<a href="<?php echo get_field("button_2_url"); ?>" target="_blank" class="button white"><?php echo get_field("button_2_label"); ?></a>
							</div>

							<div class="side_by_side_row gray cf">
								<div class="side_by_side image left" style='background-image:url("<?php echo get_field("side_by_side_image_2"); ?>");'></div>
								<div class="side_by_side text right"><?php echo get_field("side_by_side_text_2"); ?></div>
							</div>

						</main>

				</div>

			</div>

<?php get_footer(); ?>
