<?php

/**
 * Child Setup
 */
if ( ! function_exists( 'gschichtn_child_setup' ) ) :

	function gschichtn_child_setup() {
		/** Remove auto title tag */
		remove_theme_support( 'title-tag' );
		
		/** Remove feed links */
		// remove_theme_support( 'automatic-feed-links' );

		/** Add new custom image sizes */
		add_image_size( 'thumbnail-200', 9999, 200, false );

		/** Add custom image sizes to dropdown in backend */
		add_filter( 'image_size_names_choose', function( $sizes ) {
			return array_merge( $sizes, array(
				'thumbnail-200'  => __( 'Thumbnail Medium' ),
			) );
		});

		/** Modify support for post formats */
		/* remove_theme_support( 'post-formats' );
		add_theme_support( 'post-formats', array(
			'audio',
			'gallery',
			'link',
			'quote',
			'video'
		) ); */

		/** Remove specific templates from backend */
		add_filter( 'theme_page_templates', function ( $page_templates ) {
			unset( $page_templates['page-templates/page-full-width.php'] );

			return $page_templates;
		});

		/** Add shortcode for grid layout row */
		add_shortcode( 'row', function( $atts, $content ) {
			if ( ! $atts ) $atts['size'] = 1;

			return '<div class="row row-' . $atts['size'] . ' clearfix">' . do_shortcode( $content ) . '</div>';
 		} );

		/** Add shortcode for grid layout column */
		add_shortcode( 'col', function( $atts, $content ) {
			return '<div class="col">' . $content . '</div>';
		} );

		/** Add shortcode for contribute form */
		add_shortcode( 'mitmachen-form', function( $atts, $content ) {
			$msg = array();

			if ( isset( $_POST['contribute_submit'] ) ) {
				$name     = trim( $_POST['contribute_name']     );
				$mail     = trim( $_POST['contribute_mail']     );
				$phone    = trim( $_POST['contribute_phone']    );
				$info     = trim( $_POST['contribute_info']     );
				$material = $_POST['contribute_material'];

				if ( ( empty( $name ) )
					|| ( empty( $mail ) && empty( $phone ) )
					|| ( $material === null )
				) {
					$msg[] = array(
						'type' => 'error',
						'text' => 'Eine oder mehrere Angaben fehlen. Pflichtfelder: Name, Material, E-Mail oder Telefon'
					);
				} else {
					$msg[] = array(
						'type' => 'success',
						'text' => 'Vielen Dank für Ihren Beitrag. Wir kümmern uns sobald wie möglich darum!'
					);

					$headers = 'From: ' . $mail;
					$to      = 'info@grabernergeschichten.at';
					$subject = 'Graberner GeschichteN Material Beitrag';
					
					$message  = 'Liebes Graberner GeschichteN-Team,';
					$message .= "\n";
					$message .= 'mein Name ist ' . $name . '.';
					$message .= "\n";
					$message .= 'Ich möchte gerne beim Projekt Graberner GeschichteN mithelfen und kann folgende Materialien zur Verfügung stellen:';
					$message .= "\n";

					foreach ( $material as $mat ) {
						$message .= "\n";
						$message .= $mat;
					}

					$message .= "\n\n";
					$message .= 'Was ich noch dazu sagen will:';
					$message .= "\n";
					$message .= $info;

					$message .= "\n\n";
					$message .= 'Ihr könnt mich unter folgender E-Mail-Adresse ' . $mail . ' oder dieser Telefonnummer ' . $phone . ' erreichen.';
					$message .= "\n";
					$message .= 'Ich freue mich auf eure Antwort. Schöne Grüße.';

					mail( $to, $subject, $message, $headers );

					unset( $name, $mail, $phone, $info, $material );
				}

				include_once 'partials/content/contribute-msg.php';
			}

			ob_start();
			
			include_once 'partials/content/contribute-form.php';

			$output = ob_get_clean();
			$content .= $output;
			
			return $content;
		});

	}

endif;

/**
 * Load stylesheets and scripts
 */
function gschichtn_enqueue_stylesnscripts() {
	wp_enqueue_style( 'parent_style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'fonts', get_stylesheet_directory_uri() . '/css/fonts.css' );
}

/**
 * Remove unnecessary styles and scripts
 */
function gschichtn_dequeue_stylesnscripts() {
	/** Remove unneeded styles */
	wp_dequeue_style( 'lte8-fix' );
	wp_dequeue_style( 'lte7-fix' );

	/** Remove unneeded scripts in header */
	wp_dequeue_script( 'fouc' );

	/** Add lte IE8 fix */
	wp_enqueue_style( 'lte8-fix-custom', get_stylesheet_directory_uri() . '/css/lte8-fix.css' );
	wp_style_add_data( 'lte8-fix-custom', 'conditional', 'lte IE 8' );

	/** Add lte IE7 fix */
	wp_enqueue_style( 'lte7-fix-custom', get_stylesheet_directory_uri() . '/css/lte7-fix.css' );
	wp_style_add_data( 'lte7-fix-custom', 'conditional', 'lte IE 7' );

	/** Remove unneeded scripts in footer */
	wp_dequeue_script( 'jquery-blankbase' );
	wp_dequeue_script( 'main-js' );
	wp_dequeue_script( 'plugins-js' );
}

/**
 * Add child theme scripts to footer
 */
function gschichtn_enqueue_scripts_footer() {
	wp_enqueue_script( 'plugins-js-gschichtn', get_stylesheet_directory_uri() . '/js/vendor/plugins.js', array ('jquery' ) );
	wp_enqueue_script( 'main-js-gschichtn', get_stylesheet_directory_uri() . '/js/main.js', array( 'plugins-js-gschichtn' ) );

	if ( is_page( 'geschichten' ) ) {
		wp_enqueue_script( 'filter-js-gschichtn', get_stylesheet_directory_uri() . '/js/filter.js', array( 'jquery' ) );
	}
}

/**
 * Create custom post type "Stories"
 */
function gschichtn_register_stories() {
	register_post_type( 'story', array(
			'labels'       => array(
				'name'          => __( 'Geschichten' ),
				'singular_name' => __( 'Geschichte' ),
				'menu_name'     => __( 'Geschichten' ),
				'all_items'     => __( 'Alle Geschichten' ),
				'add_new_item'  => __( 'Neue Geschichte erstellen' )
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 
				'slug' => 'geschichte'
			),
			'supports'     => array(
				'title', 
				'editor',
				'author',
				'thumbnail',
				'revisions'
			),
			'show_in_nav_menus' => true,
			'menu_position'     => 20
		)
	);

	$capabilities = array(
		'assign_terms' => 'manage_categories',
		'edit_terms'   => 'manage_options',
		'manage_terms' => 'manage_options',
		'delete_terms' => 'manage_options'
	);

	register_taxonomy(
		'story-category',
		'story',
		array(
			'hierarchical' => true,
			'label'        => __( 'Kategorien' ),
			'rewrite'      => array( 'slug' => 'story-category' ),
			'capabilities' => $capabilities
		)
	);

	register_taxonomy(
		'story-year',
		'story',
		array(
			'hierarchical' => true,
			'label'        => __( 'Jahre' ),
			'rewrite'      => array( 'slug' => 'story-year' ),
			'capabilities' => $capabilities
		)
	);

	register_taxonomy(
		'story-format',
		'story',
		array(
			'hierarchical' => true,
			'label'        => __( 'Formate' ),
			'rewrite'      => array( 'slug' => 'story-format' ),
			'capabilities' => $capabilities
		)
	);

	register_taxonomy(
		'story-contributor',
		'story',
		array(
			'hierarchical' => true,
			'label'        => __( 'Beitragende' ),
			'rewrite'      => array( 'slug' => 'story-contributor' ),
			'capabilities' => $capabilities
		)
	);

	register_taxonomy(
		'story-tag',
		'story',
		array(
			'hierachical'  => false,
			'label'        => __( 'Schlagwörter' ),
			'rewrite'      => array( 'slug' => 'story-tag' ),
			'capabilities' => $capabilities
		)
	);

	/* remove_post_type_support( 'post', 'post-formats' );
	add_post_type_support( 'story', 'post-formats' ); */
	add_post_type_support( 'page', 'excerpt' );
}

/**
 * Add new meta boxes
 */
function gschichtn_add_meta_boxes() {
	$postId = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;;

	$currentPageTemplate = get_post_meta( $postId, '_wp_page_template', true );

	add_meta_box(
		'story-video',
		__( 'Video' ),
		'gschichtn_meta_box_video',
		'story',
		'advanced',
		'high'
	);

	if ( 'page-templates/index-intro.php' == $currentPageTemplate ) {
		add_meta_box(
			'intro-video',
			__( 'Video' ),
			'gschichtn_meta_box_video',
			'page',
			'advanced',
			'high'
		);

		add_meta_box(
			'intro',
			__( 'Intro' ),
			'gschichtn_meta_box_intro',
			'page',
			'advanced',
			'high'
		);
	}
}

/**
 * Meta box for video id.
 */
function gschichtn_meta_box_video( $post ) {
	wp_nonce_field( 'gschichtn_save_meta_boxes', 'gschichtn-story-nonce' );

	$video = get_post_meta( $post->ID, 'gschichtn-video', true );

	include 'partials/admin/video.php';
}

/**
 * Meta box for introduction.
 */
function gschichtn_meta_box_intro( $post ) {
	wp_nonce_field( 'gschichtn_save_meta_boxes', 'gschichtn-story-nonce' );

	$introduction = get_post_meta( $post->ID, 'gschichtn-introduction', true );

	include 'partials/admin/intro.php';
}

/**
 * Save data form meta boxes.
 */
function gschichtn_save_meta_boxes( $post_id ) {
	if (   ! isset( $_POST['gschichtn-story-nonce'] )
		|| ! wp_verify_nonce( $_POST['gschichtn-story-nonce'], 'gschichtn_save_meta_boxes' )
	) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) )
		return;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if ( isset( $_POST['gschichtn_video'] ) ) {
		$video = sanitize_text_field( $_POST['gschichtn_video'] );
		update_post_meta( $post_id, 'gschichtn-video', $video );
	}

	if ( isset( $_POST['gschichtn_intro'] ) ) {
		$introduction = sanitize_text_field( $_POST['gschichtn_introduction'] );
		update_post_meta( $post_id, 'gschichtn-introduction', $introduction );
	}

	return true;
}

function gschichtn_meta_tags() {
	global $post;

	if ( $excerpt = $post->post_excerpt ) {
		$excerpt = strip_tags( $post->post_excerpt );
	} else {
		$excerpt = get_bloginfo( 'description' );
	}

	?>
	<meta name="description" content="<?php echo $excerpt; ?>" />
	<meta name="author" content="Manuel Köllner, Bernhard Zeller" />
	<meta name="designer" content="Bernhard Zeller" />
	<meta name="publisher" content="Gemeinde Grabern" />
	<meta name="robots" content="index, follow" />
	<?php
}

function gschichtn_opg_tags() {
	global $post;

	if ( has_post_thumbnail( $post->ID ) ) {
		$imgSrc = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
		$imgSrc = $imgSrc[0];
		$twitterCardType = 'summary';
	} else {
		$imgSrc = get_stylesheet_directory_uri() . '/img/opengraph.png';
		$twitterCardType = 'summary_large_image';
	}

	if ( is_front_page() ) {
		$imgSrc = get_stylesheet_directory_uri() . '/img/opengraph.png';
		$twitterCardType = 'summary_large_image';
	}

	$title = strip_tags( get_the_title() );

	if ( $excerpt = $post->post_excerpt ) {
		$excerpt = strip_tags( $post->post_excerpt );
	} else {
		$content = $post->post_content;
		$content = strip_tags( $content );
		$excerpt = substr( $content, 0, 255 );
		$excerpt = preg_replace( '/\r|\n/', ' ', $excerpt );
	}

	if ( empty( $excerpt ) ) $excerpt = get_bloginfo( 'description' );

	if ( is_single() ) {
		$type = 'article';
	} else {
		$type = 'website';
	}
		
	?>
	<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
	<meta property="og:title" content="<?php echo $title; ?>" />
	<meta property="og:description" content="<?php echo $excerpt; ?>" />
	<meta property="og:type" content="<?php echo $type; ?>" />
	<meta property="og:url" content="<?php the_permalink(); ?>" />
	<meta property="og:image" content="<?php echo $imgSrc; ?>" />
	<meta property="og:image:width" content="<?php echo getimagesize($imgSrc)[0]; ?>" />
	<meta property="og:image:height" content="<?php echo getimagesize($imgSrc)[1]; ?>" />
	<meta property="fb:app_id" content="2085705815026208"/>
	<meta name="twitter:card" content="<?php echo $twitterCardType; ?>" />
	<!-- <meta name="twitter:site" content="@" /> -->
	<meta name="twitter:title" content="<?php the_title() ?>" />
	<meta name="twitter:description" content="<?php echo $excerpt; ?>" />
	<meta name="twitter:image" content="<?php echo $imgSrc; ?>" />
	<?php
}

/**
 * The Story
 */
if ( ! function_exists( 'the_story' ) ) :

	function the_story($props) {
		?>
		<article
			id="post-<?php echo $props['id'] ?>"
			<?php post_class( $props['classes'] ); ?>
			style="background-image: url('<?php echo $props['image']; ?>');"
			data-category="<?php echo $props['categories'][0]->slug; ?>"
			data-format="<?php echo $props['formats'][0]->slug; ?>"
			data-year="<?php echo $props['years'][0]->slug; ?>"
		>
			<a href="<?php echo esc_url( $props['permalink'] ); ?>" title="Permalink zu <?php echo $props['title']; ?>" rel="bookmark" class="story__link story__link--index js-story-link">
				<h1 class="story__title story__title--index"><?php echo $props['title'] ?></h1>
				<p class="story__category story__category--index"><?php echo $props['categories'][0]->name; ?></p>
				<p class="story__years story__years--index">
				<?php if ( count( $props['years'] ) > 2 ) : ?>
					<span class="story__year"><?php echo reset( $props['years'] )->name; ?> - <?php echo end( $props['years'] )->name; ?></span>
				<?php else : ?>
				<?php foreach ($props['years'] as $year) : ?>
					<span class="story__year"><?php echo $year->name; ?></span>
				<?php endforeach; ?>
				<?php endif; ?>
				</p>
			</a>
		</article>
		<?php
	}

	/* Original Code before "the_story()"
	<article
		id="post-<?php the_ID(); ?>"
		<?php post_class( 'story--index js-story' ); ?>
		style="background-image: url('<?php echo $src[0]; ?>');"
		data-category="<?php echo $storyCategories[0]->slug; ?>"
		data-format="<?php echo $storyFormats[0]->slug; ?>"
		data-year="<?php echo $storyYears[0]->slug; ?>"
	>
		<a href="<?php esc_url( the_permalink() ); ?>" title="<?php _e( 'Permalink to ', 'blankbase' ); the_title(); ?>" rel="bookmark" class="story__link story__link--index js-story-link">
			<h1 class="story__title story__title--index"><?php the_title(); ?></h1>
			<p class="story__category story__category--index"><?php echo $storyCategories[0]->name; ?></p>
			<p class="story__years story__years--index">
			<?php if ( count( $storyYears ) > 2 ) : ?>
				<span class="story__year"><?php echo reset( $storyYears )->name; ?> - <?php echo end( $storyYears )->name; ?></span>
			<?php else : ?>
			<?php foreach ($storyYears as $year) : ?>
				<span class="story__year"><?php echo $year->name; ?></span>
			<?php endforeach; ?>
			<?php endif; ?>
			</p>
		</a>
	</article> */

endif;

/**
 * Initialize functions
 */
add_action( 'after_setup_theme',     'gschichtn_child_setup',              2 );
add_action( 'wp_enqueue_scripts',    'gschichtn_enqueue_stylesnscripts',   2 );
add_action( 'wp_enqueue_scripts',    'gschichtn_dequeue_stylesnscripts', 100 );
add_action( 'wp_footer',             'gschichtn_enqueue_scripts_footer'      );
add_action( 'init',                  'gschichtn_register_stories'            );
add_action( 'add_meta_boxes',        'gschichtn_add_meta_boxes'              );
add_action( 'save_post',             'gschichtn_save_meta_boxes',      10, 3 );
add_action( 'wp_head',               'gschichtn_opg_tags'                    );

/**
 * Initialize variables for query
 */
function gschichtn_ajax_init_WP_Query() {
	$count = wp_count_posts( 'story' )->publish;
	$ppp   = get_option( 'posts_per_page' );

	return wp_send_json( array(
		'count' => $count,
		'ppp'   => $ppp
	) );
}

/**
 * Load more posts via AJAX
 */
function gschichtn_ajax_more_posts() {
	$offset = $_POST['offset'];
	$ppp    = $_POST['ppp'];
	$return = array();

	$stories_reloaded = new WP_Query( array(
		'post_type'      => 'story',
		'post_status'    => 'publish',
		'posts_per_page' => $ppp,
		'offset'         => $offset,
		'orderby'        => 'date',
		'order'          => 'DESC'
	) );

	while ( $stories_reloaded->have_posts() ) {
		$stories_reloaded->the_post();

		$storyCategory = get_the_terms( get_the_id(), 'story-category' );
		$storyFormat   = get_the_terms( get_the_id(), 'story-format'   );
		$storyYear     = get_the_terms( get_the_id(), 'story-year'     );

		array_push( $return, array(
			'id'             => get_the_id(),
			'title'          => get_the_title(),
			'image'          => wp_get_attachment_image_src( get_post_thumbnail_id( $stories_reloaded->ID ), 'thumbnail-200' ),
			'story-category' => array(
				'name' => $storyCategory[0]->name,
				'slug' => $storyCategory[0]->slug
			),
			'story-format'   => array(
				'name' => $storyFormat[0]->name,
				'slug' => $storyFormat[0]->slug
			),
			'story-year'     => $storyYear, 
			'permalink'      => get_the_permalink()
		) );
	}

	return wp_send_json( $return );
}

/**
 * Get a single story
 */
function gschichtn_ajax_story() {
	$return = [];

	if ( isset( $_POST['id'] ) ) {
		$return['story'] = get_post( $_POST['id'] );
		$return['video_id'] = get_post_meta( $_POST['id'], 'gschichtn-video', true );
	}

	return wp_send_json( $return );
}

/**
 * Initialize AJAX functions
 */
add_action('wp_ajax_init_WP_Query',        'gschichtn_ajax_init_WP_Query' );
add_action('wp_ajax_nopriv_init_WP_Query', 'gschichtn_ajax_init_WP_Query' ); 
add_action('wp_ajax_more_posts',           'gschichtn_ajax_more_posts'    );
add_action('wp_ajax_nopriv_more_posts',    'gschichtn_ajax_more_posts'    );
add_action('wp_ajax_story',                'gschichtn_ajax_story'         );
add_action('wp_ajax_nopriv_story',         'gschichtn_ajax_story'         );

/**
 * Filters the content to remove any extra paragraph or break tags
 * caused by shortcodes.
 *
 * @since 1.0.0
 *
 * @param  string $content  String of HTML content.
 * @return string $content  Amended string of HTML content.
 */
function tgm_io_shortcode_empty_paragraph_fix( $content ) {
	$array = array(
		'<p>['    => '[',
		']</p>'   => ']',
		']<br />' => ']'
	);

	return strtr( $content, $array );
}

add_filter( 'the_content', 'tgm_io_shortcode_empty_paragraph_fix' );
