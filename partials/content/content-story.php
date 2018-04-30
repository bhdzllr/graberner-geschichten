<?php

$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail-200' );

$storyCategories = get_the_terms( $post->ID, 'story-category' );
$storyFormats    = get_the_terms( $post->ID, 'story-format'   );
$storyYears      = get_the_terms( $post->ID, 'story-year'     );

$termsCategory    = get_the_terms( get_the_id(), 'story-category' );
$termsFormat      = get_the_terms( get_the_id(), 'story-format' );
$termsYear        = get_the_terms( get_the_id(), 'story-year' );
$termsContributor = get_the_terms( get_the_id(), 'story-contributor' );
$termsTag         = get_the_terms( get_the_id(), 'story-tag' );

foreach( $termsYear as $year )               : $termYearNames[]        = $year->name;        endforeach;
foreach( $termsContributor as $contributor ) : $termContributorNames[] = $contributor->name; endforeach;
foreach( $termsTag as $tag )                 : $termTagNames[]         = $tag->name;         endforeach;

$video_id  = get_post_meta( get_the_id(), 'gschichtn-video', true );
$video_url = 'https://www.youtube-nocookie.com/embed/' . $video_id . '?rel=0&amp;showinfo=0&color=white';

?>
<?php if ( is_single() ) : ?>

<!-- <div class="hero-image" style="background-image: url('<?php echo $src[0]; ?>');"></div> -->

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story' ); ?>>
	<header class="story__header post-width">
		<h1><?php the_title(); ?></h1>

		<ul class="story__meta-short">
			<li><?php echo $termsCategory[0]->name; ?></li>
			<li><?php echo implode( ', ', $termYearNames ); ?></li>
		</ul>
	</header>

	<div class="post-width">
		<?php if ( $video_id ) : ?>
		<div class="video-wrapper">
			<iframe
				width="853"
				height="480"
				src="<?php echo $video_url; ?>"
				frameborder="0"
				class="video-wrapper__video story__video"
				allowfullscreen>
			</iframe>
		</div>
		<?php endif; ?>

		<div>		
			<?php the_content(); ?>
		</div>

		<aside class="story__meta">
			<h2>Information</h2>

			<table class="story__meta-table">
				<tbody>
					<tr>
						<th>Kategorie:</th>
						<td><?php echo $termsCategory[0]->name; ?></td>
					</tr>
					<tr>
						<th>Format:</th>
						<td><?php echo $termsFormat[0]->name; ?></td>
					</tr>
					<tr>
						<th>Jahr:</th>
						<td><?php echo implode( ', ', $termYearNames ); ?></td>
					</tr>
					<tr>
						<th>Beitragende:</th>
						<td><?php echo implode( ', ', $termContributorNames ); ?></td>
					</tr>
					<tr>
						<th>Schlagworte:</th>
						<td><?php echo implode( ', ', $termTagNames ); ?></td>
					</tr>
					<tr>
						<th>Veröffentlicht:</th>
						<td><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?></time></td>
					</tr>
				</tbody>
			</table>
		</aside>

		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
		
			<?php _e( 'Featured', 'blankbase' ); ?>
				
		<?php endif; ?>
							
		<?php
		
		if ( ! post_password_required() && comments_open() ) {
			comments_popup_link( 
				__( 'Leave a comment', 'blankbase' ), 
				__( '1 Comment', 'blankbase' ), 
				__( '% Comments', 'blankbase' ) 
			); 
		}
		
		?>
		
		<?php edit_post_link( __( 'Edit', 'blankbase' ) ); ?>
	</div>
</article>

<?php else : ?>

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
</article>

<?php endif; ?>

<!-- <?php if ( is_search() ) : ?>

	<div>
		<?php the_excerpt(); ?>
	</div>
	
<?php endif; ?> -->