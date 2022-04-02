<article id="post-<?php the_ID(); ?>" class="article">
	<?php if ( has_post_thumbnail() ) :
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-featured-image' );

	?>
	<figure class="article-media">
		<img src="<?= esc_url( $thumbnail[0] ) ?>" alt="<?= $post->post_title ?>">
	</figure>
	<?php endif; ?>
	<?php the_title( '<h1 class="article-title">', '</h1>' ); ?>
	<div class="article-category">
		<?php the_category(', ') ?>
	</div>
	<div class="article-content">
	<?php the_content(); ?>
	</div>
</article>