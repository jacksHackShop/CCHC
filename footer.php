			<footer class="footer" role="contentinfo" data-template="<?php echo get_page_template(); ?>" itemscope itemtype="http://schema.org/WPFooter">

				<div id="inner-footer" class="wrap cf">
					<div class="newsletter_wrapper">
						<div class="subscribe_wrapper">
							<span>
								Subscribe to our newsletter:
							</span>
							<input type="email" placeholder="Email Address">
							<a class="subscribe_button" href="google.com">
								Sign Up
							</a>
							

						</div>
					</div>
					<div class="bottom_wrapper">
						<div class="social_wrapper">
							<p class="social_buttons_text">
								JOIN US ON
							</p>
							<div class="social_media_buttons">
								<a href="www.facebook.com">
									<img src="<?php echo get_template_directory_uri(); ?>/library/images/fb.svg">
								</a>
								<a href="www.twitter.com">
									<img src="<?php echo get_template_directory_uri(); ?>/library/images/twtr.svg">
								</a>
								<a href="www.instagram.com">
									<img src="<?php echo get_template_directory_uri(); ?>/library/images/insta.svg">
								</a>
								<a href="www.gmail.com">
									<img src="<?php echo get_template_directory_uri(); ?>/library/images/email.svg">
								</a>
							</div>
						</div>
						<div class="sponsers_wrapper"> 
							<?php 
								$query_image_args = array(
								    'post_type' => 'attachment',
								    'post_mime_type' => 'image',
								    'posts_per_page' => -1,
								    'category_name' => 'sponsor-image'
								);

								$sponsor_images = get_posts($query_image_args);
								foreach($sponsor_images as $sponsor_image) {
									echo '<a><img src="';
									echo wp_get_attachment_url($sponsor_image->ID);
									echo '"></a>';
								}
							?>

						</div>
					</div>	
				</div>

			</footer>

		</div>

		<?php // all js scripts are loaded in library/bones.php ?>
		<?php wp_footer(); ?>

	</body>

</html> <!-- end of site. what a ride! -->
