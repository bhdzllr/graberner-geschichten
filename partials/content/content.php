<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="post-thumbnail">
		<a href="<?php esc_url( the_permalink() ); ?>" title="<?php _e( 'Permalink to ', 'blankbase' ); the_title(); ?>" rel="bookmark">
			<?php the_post_thumbnail( 'large' ); ?>
		</a>
	</div>
	<?php endif; ?>

	<header class="post-width">
		<div class="meta-top">
			<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			
				<?php _e( 'Featured', 'blankbase' ); ?>
					
			<?php endif; ?>
			
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'd.m.Y, H:m' ) ); ?></time>

			<?php // the_category( ', ' ); ?>
			<?php // the_tags( ', ' ); ?>
		</div>

		<?php if ( is_single() ) : ?>
		
			<h1><?php the_title(); ?></h1>
			
		<?php else : ?>
		
			<h1>
				<a href="<?php esc_url( the_permalink() ); ?>" title="<?php _e( 'Permalink to ', 'blankbase' ); the_title(); ?>" rel="bookmark">
					<?php the_title(); ?>
				</a>
			</h1>
			
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'blankbase' ) ); ?>
	</header>
	
	<?php if ( is_search() ) : ?>
	
		<div class="post-width">
			<?php the_excerpt(); ?>
		</div>
		
	<?php else : ?>
	
		<div class="post-width">
			<?php 
			
			the_content( __( 'Continue reading', 'blankbase' ) );

			wp_link_pages();
			
			?>
		</div>
		
	<?php endif; ?>		
</article>