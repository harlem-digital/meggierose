<!-- Prose post loop -->
<a href="<?php wptouch_the_permalink(); ?>" class="tappable">
	<h2 class="post-title heading-font">
		<?php the_title(); ?>
	</h2>

	<div class="post-meta">
		<?php wptouch_the_time(); ?><?php prose_the_author(); ?>
	</div>

	<div class="post-content">
		<?php wptouch_the_excerpt(); ?>
	</div>
</a>