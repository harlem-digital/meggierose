<?php get_header(); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>	
	
	
	
		
		<?php the_post_thumbnail( 'image-feature' ); ?>
		<div id="grid-content">
			<?php the_content(); ?>
		</div><!-- Content -->
	
	
		<div id="grid-sidebar">
			<div id="sharebar">
				<img src="<?php bloginfo('template_url'); ?>/images/share.png" alt="" />
			</div>
		</div><!-- Share Bar -->
		
		
		
			
		
			
			
	<?php endwhile; ?>		
	<?php else : ?>	
	<?php endif; ?>    
<?php get_footer(); ?>