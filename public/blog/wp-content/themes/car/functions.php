<?php 

if ( ! function_exists( 'allocar_setup' ) ) :
	function allocar_setup() {
		add_theme_support( 'title-tag' );

		add_theme_support( 'post-thumbnails' );
		add_image_size( 'post-featured-image', 1024, 450, true );
		add_image_size( 'post-thumbnail-image', 272, 64, true );

		register_nav_menus(
			array(
				'main-menu' => "Menu principal",
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'allocar_setup' );

function allocar_excerpt_length( $length ) {
 	return 20;
}
add_filter( 'excerpt_length', 'allocar_excerpt_length' );

function allocar_excerpt_more($more) {
 	return '&hellip; <a href="'. get_permalink() . '" class="txt-primary">' . 'En savoir plus &rarr;' . '</a>';
}
add_filter( 'excerpt_more', 'allocar_excerpt_more' );

function allocar_add_thumbnail_to_JSON(){
	register_rest_field(
		'post',
		'featured_image_src',
		array(
			'get_callback' => 'get_image_src',
			'update_callback' => null,
			'schema' => null
		)
	);
}
add_action( 'rest_api_init', 'allocar_add_thumbnail_to_JSON' );

function get_image_src($obj, $field, $request){
	$featured_image_array = wp_get_attachment_image_src(
		$obj['featured_media'],
		'post-thumbnail-image',
		true
	);

	return $featured_image_array[0];
}

function allocar_add_category_to_JSON(){
	register_rest_field(
		'post',
		'categories',
		array(
			'get_callback' => 'get_category_info',
			'update_callback' => null,
			'schema' => null
		)
	);
}

add_action('rest_api_init', 'allocar_add_category_to_JSON');

function get_category_info($obj, $field, $request){
	$formatted_categories[] = array();
	$categories = get_the_category($obj['id']);
	$i = 0;
	foreach($categories as $category){
		$formatted_categories[$i]['category_link'] = get_category_link($category->term_id);
		$formatted_categories[$i]['category_name'] = $category->name;
		$i++;
	}
	return $formatted_categories;
}

function allocar_widgets_init() {

	register_sidebar(
		array(
			'name'          => __( 'Aside', 'allocar-aside' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your aside.', 'allocar' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Contact', 'allocar-contact' ),
			'id'            => 'sidebar-2',
			'description'   => __( 'Add widgets here to appear in your aside.', 'allocar' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'allocar_widgets_init' );

function allocar_scripts() {
	wp_enqueue_style( 'allocar-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
	
    wp_enqueue_style( 'allocar-font', 'http://dev.allocar.mg/fonts/proxima/proxima.css', array(),  wp_get_theme()->get( 'Version' ) );

	wp_enqueue_script( 'allocar-jquery', 'http://dev.allocar.mg/js/jquery-3.3.1.min.js', array(), '3.3.1', true );

	wp_enqueue_script( 'allocar-bootstrap', 'http://dev.allocar.mg/dist/js/bootstrap.min.js', array('allocar-jquery'), '4.1.3', true );
	
	wp_enqueue_script( 'allocar-fontawesome', 'http://dev.allocar.mg/fontawesome/js/all.min.js', array('allocar-jquery'), '5.8.2', true );

	wp_enqueue_script( 'allocar-script', get_theme_file_uri( '/js/script.js' ), array('allocar-jquery'), wp_get_theme()->get( 'Version' ), true );
}
add_action( 'wp_enqueue_scripts', 'allocar_scripts' );