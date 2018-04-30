<?php get_header(); ?>
		
		<main role="main" id="main" class="main site-width clearfix">
		<?php
		
		if ( have_posts() ) :
		
			/** the loop */
			while ( have_posts() ) : the_post();
				
				get_template_part( 'partials/content/content', get_post_format() );
				
			endwhile;

			the_posts_pagination( array(
				'mid_size'           => 1,
				'prev_text'          => __( 'Previous', 'blankbase' ),
				'next_text'          => __( 'Next', 'blankbase' ), 
				'screen_reader_text' => __( 'Posts navigation', 'blankbase') 
			) );
			
		else :
			
			get_template_part( 'partials/content/content', 'none' );
			
		endif;
		
		?>
		</main>

<?php get_footer(); ?>