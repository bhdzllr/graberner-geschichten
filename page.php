<?php get_header(); ?>
	
		<main role="main" id="main" class="main site-width clearfix">
		<?php
		
		if ( have_posts() ) {
		
			/** the loop */
			while ( have_posts() ) : the_post();
			
				get_template_part( 'partials/content/content', 'page' );
				
			endwhile;
			
			if ( comments_open() || get_comments_number() )
				comments_template();
			
		} else {
			
			get_template_part( 'partials/content/content', 'none' );
			
		}
		
		?>
		</main>

<?php get_footer(); ?>