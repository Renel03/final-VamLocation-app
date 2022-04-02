<div id="post-<?php the_ID(); ?>" class="search-result">
	<?php if ( has_post_thumbnail() ) :
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );

	?>
	<div class="search-result-media">
		<a href="<?= get_permalink() ?>">
			<img src="<?= esc_url( $thumbnail[0] ) ?>" alt="<?= $post->post_title ?>">
		</a>
	</div>
	<?php endif; ?>
	<div class="search-result-infos">
		<h3 class="search-result-title">
			<a href="<?= get_permalink() ?>"><?= $post->post_title ?></a>
		</h3>
		<div class="search-result-category">
			<?php the_category(', ') ?>
		</div>
		<div class="search-result-content">
		<?php the_excerpt(); ?>
		</div>
	</div>
</div>