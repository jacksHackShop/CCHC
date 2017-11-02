<?php
/*
 * CUSTOM POST TYPE TEMPLATE
 *
 * This is the custom post type post template. If you edit the post type name, you've got
 * to change the name of this template to reflect that name change.
 *
 * For Example, if your custom post type is "register_post_type( 'bookmarks')",
 * then your single template should be single-bookmarks.php
 *
 * Be aware that you should rename 'custom_cat' and 'custom_tag' to the appropiate custom
 * category and taxonomy slugs, or this template will not finish to load properly.
 *
 * For more info: http://codex.wordpress.org/Post_Type_Templates
*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<main id="main" class="cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<?php if (have_posts()) : while (have_posts()) : the_post(); 
								// this will capitalize the first word (the month's name)
								$date = preg_replace_callback(
									'/(^\S*)/', function($matches){
										return strtoupper($matches[0]);},
									get_field('date_of_event')
								);
								$general_location = strtoupper(get_field('general_location'));
 ?>

							<article id="post-<?php the_ID(); ?>" class="event-single" role="article">

								<header class="article-header">

									<h1 class="single-title event-date custom-post-type-title"><?php echo $date; ?></h1>
									<p class="single-title event-title custom-post-type-title">
										<?php echo strtoupper(get_field('title')); 
											$subtitle = get_field('subtitle');
											if(isset($subtitle)){
												echo ":";
											}
										?>
										<br>
										<?php echo strtoupper(get_field('subtitle')); ?>
									</p>
									
									<div id="location">
										<p class="single-title event-general-location custom-post-type-title"><?php echo $general_location; ?></p>
										<p class="single-title event-specific-location custom-post-type-title"><?php the_field('specific_location'); ?></p>
									</div>

								</header>

								<div class="image-gallery">
									<span class="gal_nav prev" 
												onclick="this.parentElement.children[1].scrollLeft -= this.parentElement.children[1].clientWidth;">
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
									<span class="gal_nav next"
												onclick="console.log(this);this.parentElement.children[1].scrollLeft += this.parentElement.children[1].clientWidth;">
										&#62;
									</span>
								</div>
							
							</article>

							<?php endwhile; ?>

							<?php else : ?>

									<article id="post-not-found" class="hentry cf">
										<header class="article-header">
											<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
										</header>
										<section class="entry-content">
											<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
											<p><?php _e( 'This is the error message in the single-custom_type.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						</main>

				</div>

			</div>

<?php get_footer(); ?>
