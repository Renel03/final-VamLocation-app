<?php get_header() ?>

<section class="section">
    <h1>ABC</h1>
	<div class="wp">
		<div class="row">
			<div class="col-12 col-md-8">
			<?php if ( have_posts() ): ?>
				<div class="row">
				<?php while ( have_posts() ):  the_post(); ?>
					<div class="col-12 col-md-6">
					<?php get_template_part( 'template-parts/content/content' ); ?>
					</div>
				<?php endwhile ?>
				</div>
			<?php else :
				get_template_part( 'template-parts/content/content', 'none' );
			endif ?>
			</div>
			<div class="col-12 col-md-4">
				<aside class="aside">
                    <?php dynamic_sidebar('sidebar-1') ?>
                    <?php #dynamic_sidebar('sidebar-2') ?>
				</aside>
			</div>
		</div>
	</div>
</section>

<?php get_footer() ?>