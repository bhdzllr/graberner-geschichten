<?php get_header(); ?>

		<nav class="link-back site-width">
			<a href="<?php echo get_permalink(6); ?>" title="Zurück zu den Geschichten" class="nav-previous">Zurück zur Übersicht</a>
		</nav>
		
		<main role="main" id="main" class="main site-width clearfix">
		<?php
		
		if ( have_posts() ) {
		
			/** the loop */
			while ( have_posts() ) : the_post();
			
				get_template_part( 'partials/content/content', 'story' );
				
			endwhile;

			// the_post_navigation( array(
			// 	'prev_text'          => __( 'Vorherige Geschichte', 'blankbase' ),
			// 	'next_text'          => __( 'Nächste Geschichte', 'blankbase' ), 
			// 	'screen_reader_text' => __( 'Post navigation', 'blankbase')
			// ) );
			
			if ( comments_open() || get_comments_number() ) 
				comments_template();
			
		} else {
			
			get_template_part( 'partials/content/content', 'none' );
			
		}
		
		?>
		</main>

<?php get_footer(); ?>