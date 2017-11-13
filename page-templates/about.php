<?php
/*
 Template Name: about
*/
 ?>

 <?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<main id="main" class="m-all t-3of3 d-7of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<?php the_post(); ?>
							<div class="side_by_side_row blue cf">
								<div class="side_by_side image left" style='background-image:url("<?php echo get_field("side_by_side_image_1"); ?>");'></div><div class="side_by_side text right"><?php echo get_field("side_by_side_text_1"); ?></div>
							</div>
							<div class="side_by_side_row blue cf">
								<div class="side_by_side image right" style='background-image:url("<?php echo get_field("side_by_side_image_2"); ?>");'></div>
								<div class="side_by_side text left"><?php echo get_field("side_by_side_text_2"); ?></div>
							</div>
							<div class="button_row cf">
								<a href="<?php echo get_field("button_1_url"); ?>" target="_blank" class="button white"><?php echo get_field("button_1_label"); ?></a>
								<a href="<?php echo get_field("button_2_url"); ?>" target="_blank" class="button white"><?php echo get_field("button_2_label"); ?></a>
							</div>
							<div class="image-gallery about" data-image-target='0'>
									<span class="gal_nav prev">
										&#60;
									</span>
									<ul class="gallery_images">
										<?php 
											$images = get_field('image_gallery'); 
											foreach( $images as $image ){
											?>
												<li class="gallery_image"><img src='<?php echo $image["url"];?>'></li>	
											<?php
											}
										?>
									</ul>
									<span class="gal_nav next">
										&#62;
									</span>
								</div>
							<div class="about_text_row">
								<h4> <?php echo get_field("about_heading_1"); ?> </h4>
								<p> <?php echo get_field("about_text_1"); ?> </p>
							</div>
							<div class="about_text_row">
								<h4> <?php echo get_field("about_heading_2"); ?> </h4>
								<p> <?php echo get_field("about_text_2"); ?> </p>
							</div>
							<div class="committee_list_row">
								<?php 
								if(have_rows("committee_list")): ?>
									<?php
									while(have_rows("committee_list")): the_row(); ?>
										<div class="committee_list">	
											<h3 style='margin-bottom: 0;'><?php the_sub_field("heading"); ?></h3>
											<?php 
											if(have_rows("members")): ?>
												<ul style='margin: 0;'>
												<?php
												while(have_rows("members")): the_row(); ?>
													<li> <?php the_sub_field("member_name") ?> </li>
												<?php endwhile; ?>
												</ul>
											<?php endif; ?>
										</div>
									<?php endwhile; ?>
								<?php endif; ?>
							</div>

						</main>

				</div>

			</div>

<?php get_footer(); ?>
