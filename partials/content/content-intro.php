<?php

$video_id = get_post_meta( get_the_id(), 'gschichtn-video', true );
$video_url = 'https://www.youtube-nocookie.com/embed/' . $video_id . '?rel=0&amp;showinfo=0&color=white';

$imgSrc = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
$introduction = get_post_meta( get_the_id(), 'gschichtn-introduction', true );

/* style="background-image: url('<?php echo $imgSrc[0]; ?>');" */

$count_stories = wp_count_posts( 'story' );
$count_posts = wp_count_posts( 'post' );

?>
<section class="intro__wrapper intro__wrapper--video">
	<div class="intro__layer">
		<?php if ( $video_url ) : ?>
		<div class="site-width"><div class="video-wrapper video-wrapper--intro">
			<iframe
				width="853"
				height="480"
				src="<?php echo $video_url; ?>"
				frameborder="0"
				class="video-wrapper__video intro__video"
				allowfullscreen>
			</iframe>
		</div></div>
		<?php endif; ?>

		<header class="intro__header intro__text--light">
			<h1><?php the_title(); ?></h1>
		</header>

		<div>
			<p class="intro__p intro__text--light">
				Das Projekt Graberner GeschichteN ist das digitale Gedächtnis
				der Gemeinde Grabern. Ziel ist es, Geschichte und Geschichten
				der Gemeindebewohnerinnen und Gemeindebewohner zu dokumentieren,
				um diese für die Nachwelt zu erhalten.
			</p>
		</div>
	</div>
</section>

<section class="site-width clearfix">
	<header class="intro__header">
		<h1>Projekt</h1>
	</header>

	<div>
		<p class="intro__p">
			<?php echo $introduction; ?>
		</p>
	</div>
</section>

<section class="intro__wrapper intro__wrapper--action">
	<div class="intro__layer">
		<header class="intro__header intro__text--light">
			<h1>Geschichten</h1>
		</header>

		<div>
			<p class="intro__p intro__text--light">Wir haben derzeit</p>
		</div>

		<div class="site-width clearfix">
			<div class="intro__fact-object">
				<p class="intro__fact intro__text--light"><?php echo $count_stories->publish; ?></p>
				<p class="intro__text--light">spannende Geschichten.</p>
			</div>

			<div class="intro__fact-object">
				<p class="intro__fact intro__text--light"><?php echo $count_posts->publish; ?></p>
				<?php if ( $count_posts->publish == 1 ) : ?>
				<p class="intro__text--light">interessante Neuigkeit.</p>
				<?php else : ?>
				<p class="intro__text--light">interessante Neuigkeiten.</p>
				<?php endif; ?>
			</div>
		</div>

		<div class="intro__button-wrapper site-width clearfix">
			<div class="intro__fact-object intro__fact-object--break">
				<a href="<?php echo get_permalink(6); ?>" class="intro__button">Zu den GeschichteN</a>
			</div>

			<div class="intro__fact-object intro__fact-object--break">
				<a href="<?php echo get_permalink(8); ?>" class="intro__button">Zu den Neuigkeiten</a>
			</div>
		</div>
	</div>
</section>

<section class="intro__contribute site-width clearfix">
	<header class="intro__header">
		<h1>Mitmachen!</h1>
	</header>

	<div>
		<p class="intro__p">
			Graberner GeschichteN ist ein offenes Projekt. Jede und jeder soll
			daran teilhaben können. Wir freuen uns über alles, das eine
			Graberner Geschichte erzählt.<br />
			Sie haben interessantes Material? Dann machen Sie mit!
		</p>
		<a href="<?php echo get_permalink(12); ?>" class="intro__button intro__button--dark">Mitmachen</a>
	</div>
</section>