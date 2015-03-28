<?php if ( function_exists( 'wptouch_related_posts' ) ) { ?>
		<div class="related-posts swipe" id="slider">
			<div class="related swipe-wrap">
				<?php if ( wptouch_has_related_posts() ) { ?>
					<?php $related_posts = wptouch_related_posts(); ?>
					<?php foreach( $related_posts as $related_post ) { ?>
					<div class="one-swipe-image" style="visibility: hidden">
						<small><?php _e( 'Read Next', 'wptouch-pro' ); ?></small>
						<h2><a href="<?php echo $related_post->link; ?>"><?php echo $related_post->title; ?></a></h2>
						<?php echo prose_get_excerpt( $related_post->id ); ?>
					</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
<?php } ?>