<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
        <div id="wp">
            <header id="header">
                <div class='wp d-flex align-items-center'>
                    <div class="logo">
                        <a href="<?php bloginfo( 'url' ); ?>">
                            <img src="http://dev.allocar.mg//images/lecomparateurassurance.svg" alt="" />
                        </a>
                    </div>
                    <?php if ( has_nav_menu( 'main-menu' ) ) : ?>
                    <nav class="menu">
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'main-menu',
                                'menu_class'     => 'main-menu',
                                'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                            )
                        );
                        ?>
                    </nav>
                    <?php endif; ?>
                    <span class="flex-grow-1"></span>
                    <a href="http://dev.allocar.mg" class="btn btn-primary">Petites annonces auto</a>
                </div>
            </header>
            <main id="main">