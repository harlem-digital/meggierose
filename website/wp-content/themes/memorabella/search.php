<?php
/**
 * The template for displaying search results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post(); ?>

				<?php
				/*
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'content', 'search' );

			// End the loop.
			endwhile; ?>

			<div class="pagination">
				<span class="prev"><?php next_posts_link( 'Previous' ); ?></span>
				<span class="divider"></span>
				<span class="next"><?php previous_posts_link( 'Next' ); ?></span>
			</div>

		<?php else : ?>
			<section>
				<p class="notfound">BUMMER. I DIDN'T TAKE ANY PHOTOS OF THAT.<br>TRY SOMETHING ELSE!</p>
			</section>

		<?php endif; ?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
