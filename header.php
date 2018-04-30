<?php header('content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />

		<title><?php wp_title( '&#124;', true, 'right' ); bloginfo( 'name' ); ?></title>

		<meta name="description" content="<?php bloginfo( 'description' ); ?>" />
		<meta name="author" content="Manuel Köllner, Bernhard Zeller" />
		<meta name="designer" content="Bernhard Zeller" />
		<meta name="publisher" content="Gemeinde Grabern" />
		<meta name="robots" content="index, follow" />

		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<script type="text/javascript">/** FOUC */ document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/, 'js');</script>
		<!--[if lt IE 9]><script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/js/createHTML5Elements.js"></script><![endif]-->

		<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri() ?>/img/favicon.ico" type="image/x-icon" />
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<!--[if lt IE 8]>
			<p class="browsehappy">
				Sie verwenden einen <strong>veralteten</strong> Browser. Bitte <a href="http://browsehappy.com/" title="Browser Upgrade">aktualisieren Sie Ihren Browser</a> um ein besseres Surferlebnis zu erhalten.<br />
				You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.
			</p>
		<![endif]-->
		
		<header id="site-header" class="site-header">
			<div class="site-width clearfix">
				<?php if ( get_theme_mod( 'blankbase_logo' ) ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?> - <?php esc_attr_e( 'Home', 'blankbase' ); ?>" rel="home" class="site-header__title--logo">
					<img src="<?php echo esc_url( get_theme_mod( 'blankbase_logo' ) ) ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> - <?php esc_attr_e( 'Home', 'blankbase' ); ?>" />
				</a>
				<?php endif; ?>
			
				<?php if ( display_header_text() ) : ?>
				<h1 class="site-header__title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo( 'name' ); ?> - <?php esc_attr_e( 'Home', 'blankbase' ); ?>" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				</h1>
				<p class="site-header__tagline"><?php bloginfo( 'description', 'display' ); ?></p>
				<?php endif; ?>
				
				<a href="#main" title="<?php esc_attr_e( 'Skip to content', 'blankbase' ); ?>" class="screen-reader-text">
					<?php _e( 'Skip to content', 'blankbase' ); ?>
				</a>
				<a href="#site-nav" title="<?php esc_attr_e( 'Skip to menu', 'blankbase' ); ?>" class="screen-reader-text">
					<?php _e( 'Skip to menu', 'blankbase' ); ?>
				</a>
				
				<?php if ( get_header_image() ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo( 'name' ); ?> - <?php esc_attr_e( 'Home', 'blankbase' ); ?>" rel="home">
					<img src="<?php header_image(); ?>" alt="<?php bloginfo( 'name' ); ?> - <?php esc_attr_e( 'Home', 'blankbase' ); ?>" />
				</a>
				<?php endif; ?>

				<nav id="site-nav" class="site-nav">
					<?php wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false
					) ); ?>
				</nav>
			</div>
		</header>