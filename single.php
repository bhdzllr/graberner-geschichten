<?php get_header(); ?>
	
		<main role="main" id="main" class="main site-width clearfix">
		<?php
		
		if ( have_posts() ) {
		
			/** the loop */
			while ( have_posts() ) : the_post();
			
				get_template_part( 'partials/content/content', get_post_format() );
				
			endwhile;

			the_post_navigation( array(
				'prev_text'          => __( 'Previous Post', 'blankbase' ),
				'next_text'          => __( 'Next Post', 'blankbase' ), 
				'screen_reader_text' => __( 'Post navigation', 'blankbase')
			) );
			
			if ( comments_open() || get_comments_number() ) 
				comments_template();
			
		} else {
			
			get_template_part( 'partials/content/content', 'none' );
			
		}
		
		?>
		</main>

<?php get_footer(); ?>