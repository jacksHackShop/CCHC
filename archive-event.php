<?php
/*
 * CUSTOM POST TYPE ARCHIVE TEMPLATE
 *
 * This is the custom post type archive template. If you edit the custom post type name,
 * you've got to change the name of this template to reflect that name change.
 *
 * For Example, if your custom post type is called "register_post_type( 'bookmarks')",
 * then your template name should be archive-bookmarks.php
 *
 * For more info: http://codex.wordpress.org/Post_Type_Templates
*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

					<main id="main" class="cf event-archive" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						<?php if (have_posts()) : while (have_posts()) : the_post(); 
							$date = get_field('date_of_event');
							preg_match('/\d{4}/', $date, $year_match);

							if (isset($current_year) && $year_match[0] == $current_year){
								$year = "";
							} else{
								// set both year and current year to our match
								$year = $current_year = $year_match[0];

							} ?>

						<article id="post-<?php the_ID(); ?>" role="article" class="m-all t-1of2 d-1of3 centered">
								<div class="event-item">
									<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
										<p class="h2 <?php if (!$year){echo "empty-year";} ?>"><?php echo $year; ?></p>
										<img src="<?php the_field('thumbnail'); ?>">
										<p class="general_location"><?php echo strtoupper(get_field("general_location")); ?></p>
										<p class="specific_location"><?php the_field("specific_location"); ?></p>
									</a>
								</div>
						</article>

						<?php endwhile; ?>

							

						<?php else : ?>

								<article id="post-not-found" class="cf">
									<header class="article-header">
										<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
									</header>
									<section class="entry-content">
										<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
									</section>
									<footer class="article-footer">
											<p><?php _e( 'This is the error message in the custom posty type archive template.', 'bonestheme' ); ?></p>
									</footer>
								</article>

						<?php endif; ?>

					</main>				

				</div>

			</div>

<?php get_footer(); ?>
