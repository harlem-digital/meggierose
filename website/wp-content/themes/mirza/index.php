<?php get_header(); ?>
			
			

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="grid-post">		
			<h1 class="grid-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			<span class="grid-date">{<?php the_time('F jS, Y'); ?>}</span>
			<?php the_content(); ?>
			<div class="clear"></div>
		</div>
	<?php endwhile; ?>	
	
	<?php if (function_exists('page_navi')) { // if expirimental feature is active ?>
		
		<?php page_navi(); // use the page navi function ?>
		
	<?php } else { // if it is disabled, display regular wp prev & next links ?>
		<nav class="wp-prev-next">
			<ul class="clearfix">
				<li class="prev-link"><?php next_posts_link(_e('&laquo; Older Entries', "bonestheme")) ?></li>
				<li class="next-link"><?php previous_posts_link(_e('Newer Entries &raquo;', "bonestheme")) ?></li>
			</ul>
		</nav>
	<?php } ?>		
	
	<?php else : ?>
	
	    <header>
	    	<h1>Not Found</h1>
	    </header>
	    <section class="post_content">
	    	<p>Sorry, but the requested resource was not found on this site.</p>
	    </section>

	<?php endif; ?>
			
    
    

<?php get_footer(); ?>