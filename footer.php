		<footer class="site-footer">
			<div class="site-width clearfix">
				<aside class="project-contributors">
					<a href="http://www.gemeinde-grabern.at/" title="Gemeinde Grabern" target="_blank">
						<img src="<?php echo get_stylesheet_directory_uri() ?>/img/footer-grabern-wappen.png" alt="Gemeinde Grabern Wappen" />
					</a>

					<a href="http://www.dorf-stadterneuerung.at/" title="NÖ Dorf- und Stadterneuerung" target="_blank">
						<img src="<?php echo get_stylesheet_directory_uri() ?>/img/footer-dorf-stadt-logo.png" alt="NÖ Dorf- und Stadterneuerung Logo" />
					</a>

					<br />

					<a href="http://www.noe.gv.at/Bildung/Wissenschaft-Forschung.html" title="Wissenschaft und Forschung Niederösterreich" target="_blank">
						<img src="<?php echo get_stylesheet_directory_uri() ?>/img/footer-wissenschaft.png" alt="Wissenschaft und Forschung Niederösterreich Logo" />
					</a>
				</aside>

				<?php get_template_part( 'partials/sidebar/sidebar-footer' ); ?>
			</div>
		</footer>
		
		<?php wp_footer(); ?>

		<!-- Analytics -->
		<script type="text/javascript">
		var _paq = _paq || [];
		_paq.push(["setDoNotTrack", true]);
		_paq.push(['trackPageView']);
		_paq.push(['enableLinkTracking']);
		(function() {
		var u="//analytics.grabernergeschichten.at/";
		_paq.push(['setTrackerUrl', u+'piwik.php']);
		_paq.push(['setSiteId', '1']);
		var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
		g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
		})();
		</script>
		<noscript><p><img src="//analytics.grabernergeschichten.at/piwik.php?idsite=1&rec=1" style="border:0;" alt="" /></p></noscript>
		<!-- End Analytics Code -->
	</body>
</html>