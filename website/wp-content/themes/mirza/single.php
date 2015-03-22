<?php get_header(); ?>			
			

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="grid-post">
			<h1 class="grid-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			<span class="grid-date">{<?php the_time('F jS, Y'); ?>}</span>
			<?php the_content(); ?>
		</div>
	<?php endwhile; ?>			
	<?php else : ?>
	    <header>
	    	<h1>Not Found</h1>
	    </header>
	    <section class="post_content">
	    	<p>Sorry, but the requested resource was not found on this site.</p>
	    </section>
	    <footer>
	    </footer>
	<?php endif; ?>
			
    
    

<?php get_footer(); ?>