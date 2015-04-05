<?php

/*
Template Name: About
*/

get_header(); ?>

<div id="about">
	<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( has_post_thumbnail() ) { ?>
			<article><?php the_post_thumbnail('full'); ?></article>
		<?php } ?>
		<section>
			<?php the_content(); ?>
		</section>

	<?php endwhile; ?>
</div>

<?php get_footer(); ?>