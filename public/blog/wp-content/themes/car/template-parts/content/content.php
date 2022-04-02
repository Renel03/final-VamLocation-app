<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) :
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail-image' );

	?>
	<div class="post-media">
		<a href="<?= get_permalink() ?>">
			<img src="<?= esc_url( $thumbnail[0] ) ?>" alt="<?= $post->post_title ?>">
		</a>
	</div>
	<?php endif; ?>
	<h3 class="post-title">
		<a href="<?= get_permalink() ?>"><?= $post->post_title ?></a>
	</h3>
	<div class="post-category">
		<?php the_category(', ') ?>
	</div>
	<div class="post-content">
	<?php the_excerpt(); ?>
	</div>
</div>