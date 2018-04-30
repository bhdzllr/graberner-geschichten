<?php
/** 
 * Template Name: Stories
 */
?>
<?php get_header(); ?>
	<?php

	$ppp       = (int) get_option( 'posts_per_page' ); 
	$postCount = (int) wp_count_posts( 'story' )->publish;

	$termsCategory = get_terms( 'story-category' );
	$termsFormat   = get_terms( 'story-format' );
	$termsYear     = get_terms( 'story-year', array( 'hide_empty' => false ) );

	$audio = new WP_Query( array( 'story-format' => 'audio' ) );
	$bild  = new WP_Query( array( 'story-format' => 'bild'  ) );
	$text  = new WP_Query( array( 'story-format' => 'text'  ) );
	$video = new WP_Query( array( 'story-format' => 'video' ) );

	?>
	<nav class="filter">
		<div class="site-width clearfix">
			<form class="filter-form">
				<h1 class="filter-form__title"><a href="#" title="Kategorie" class="icon-after icon-after--arrow js-dropdown-category js-dropdown">Kategorie</a></h1>
				<ul class="filter-form__list js-list">
				<?php if ( empty( $termsCategory ) ) : ?>
					<li>Noch keine Kategorien vorhanden.</li>
				<?php endif; ?>
				<?php foreach ($termsCategory as $term) : ?>
					<li>
						<input type="checkbox" name="<?php echo $term->taxonomy . '-' . $term->slug; ?>" id="<?php echo $term->taxonomy . '-' . $term->slug; ?>" class="filter-form__input js-input" />
						<label for="<?php echo $term->taxonomy . '-' . $term->slug; ?>" class="filter-form__label js-filter-story-category js-label" data-filter-taxonomy="<?php echo str_replace('story-', '', $term->taxonomy); ?>" data-filter-slug="<?php echo $term->slug; ?>" data-filter-term-id="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></label>
					</li>
				<?php endforeach; ?>
				</ul>
			</form>

			<form class="filter-form">
				<h1 class="filter-form__title"><a href="#" title="Format" class="icon-after icon-after--arrow js-dropdown-format js-dropdown">Format</a></h1>
				<ul class="filter-form__list js-list">
				<?php if ( empty( $termsFormat ) ) : ?>
					<li>Noch keine Formate vorhanden.</li>
				<?php endif; ?>
				<?php foreach ($termsFormat as $term) : ?>
					<li>
						<input type="checkbox" name="<?php echo $term->taxonomy . '-' . $term->slug; ?>" id="<?php echo $term->taxonomy . '-' . $term->slug; ?>" class="filter-form__input js-input" />
						<label for="<?php echo $term->taxonomy . '-' . $term->slug; ?>" class="filter-form__label js-filter-story-format js-label" data-filter-taxonomy="<?php echo str_replace('story-', '', $term->taxonomy); ?>" data-filter-slug="<?php echo $term->slug; ?>" data-filter-term-id="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></label>
					</li>
				<?php endforeach; ?>
				</ul>
			</form>

			<form class="filter-form filter-form--year">
				<h1 class="filter-form__title filter-form__title--year"><a href="#" title="Jahr" class="icon-after icon-after--arrow js-dropdown-year js-dropdown">Jahr</a></h1>
				<ul class="filter-form__list">
				<?php if ( empty( $termsYear ) ) : ?>
					<li>Noch keine Jahreszahlen vorhanden.</li>
				<?php endif; ?>
				<?php foreach ($termsYear as $term) : ?>
					<li>
						<input type="checkbox" name="<?php echo $term->taxonomy . '-' . $term->slug; ?>" id="<?php echo $term->taxonomy . '-' . $term->slug; ?>" class="filter-form__input js-input" />
						<label for="<?php echo $term->taxonomy . '-' . $term->slug; ?>" class="filter-form__label js-filter-story-year" data-filter-taxonomy="<?php echo str_replace('story-', '', $term->taxonomy); ?>" data-filter-slug="<?php echo $term->slug; ?>" data-filter-term-id="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></label>	
					</li>
				<?php endforeach; ?>
				</ul>
			</form>

			<form class="filter-form filter-form--year-responsive">
				<h1 class="filter-form__title"><a href="#" title="Jahr" class="icon-after icon-after--arrow js-dropdown-year js-dropdown">Jahr</a></h1>
				<ul class="filter-form__list js-list">
				<?php if ( empty( $termsYear ) ) : ?>
					<li>Noch keine Jahreszahlen vorhanden.</li>
				<?php endif; ?>
				<?php foreach ($termsYear as $term) : ?>
					<li>
						<label for="<?php echo $term->taxonomy . '-' . $term->slug; ?>" class="filter-form__label js-filter-story-year js-label" data-filter-taxonomy="<?php echo str_replace('story-', '', $term->taxonomy); ?>" data-filter-slug="<?php echo $term->slug; ?>" data-filter-term-id="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></label>	
					</li>
				<?php endforeach; ?>
				</ul>
			</form>
		</div>
	</nav>
	
	<main role="main" id="main" class="main main--filter-page site-width clearfix">
		<div>
			<ul class="result-count">
				<li class="result-count__item icon-before icon-before--video js-video-count"><?php echo $video->found_posts; ?></li>
				<li class="result-count__item icon-before icon-before--bild js-bild-count"><?php echo $bild->found_posts; ?></li>
				<li class="result-count__item icon-before icon-before--text js-text-count"><?php echo $text->found_posts; ?></li>
				<li class="result-count__item icon-before icon-before--audio js-audio-count"><?php echo $audio->found_posts; ?></li>
				<li class="result-count__item"><a href="<?php echo get_permalink(6); ?>" class="js-reset">Reset</a></li>
			</ul>

			<p class="result-count js-noresults">Zu Ihren Suchkriterien gibt es keine Ergebnisse, bitte wählen sie andere Filter.</p>
		</div>

		<div class="grid clearfix js-grid">
			<article id="js-story" class="story story--index" data-category="" data-format="" data-year="">
				<a href="" title="" rel="bookmark" class="story__link story__link--index js-story-link">
					<h1 class="story__title story__title--index js-story-title"></h1>
					<p class="story__category story__category--index js-story-category"></p>
					<p class="story__years story__years--index js-story-years"></p>
				</a>
			</article>
		<?php

		$story_loop = new WP_Query( array(
			'post_type'   => 'story',
			'post_status' => 'publish',
			'orderby'     => 'date',
			'order'       => 'DESC'
		) );

		if ( $story_loop->have_posts() ) :
			
			// For main loop: query_posts( array( 'post_type' => 'story' ) );

			/** the story loop */
			while ( $story_loop->have_posts() ) : $story_loop->the_post();
				
				get_template_part( 'partials/content/content', 'story' );
				
			endwhile;

			wp_reset_postdata();

			// If no js
			// the_posts_navigation();
			the_posts_pagination( array(
				'mid_size'           => 1,
				'prev_text'          => __( 'Previous', 'blankbase' ),
				'next_text'          => __( 'Next', 'blankbase' ), 
				'screen_reader_text' => __( 'Posts navigation', 'blankbase') 
			) );

			// else button
			
		else:
			
			get_template_part( 'partials/content/content', 'none' );
			
		endif;
		
		?>
		</div>

		<?php if ( $ppp < $postCount ) : ?>
		<button class="btn btn--more js-more" data-lmd="Mehr Zeigen..." data-lod="Laden..." data-eod="Alles geladen">Mehr Zeigen...</button>
		<?php endif; ?>
	</main>

	<?php get_template_part( 'partials/sidebar/sidebar-content' ); // get_sidebar( 'content' ); ?>
	
	<?php #get_sidebar(); ?>

<?php get_footer(); ?>