/**
 * Gschichtn modules
 */

/** Namespace */
var GSCHICHTN = GSCHICHTN || {};

/**
 * Main module
 * 
 * Handle actions and user interface which are valid for whole website.
 */
GSCHICHTN.Main = (function(window, document, $) {
	'use strict';
	
	/** Private */

	/** Debounce animations */
	var latestScrollY = 0;
	var ticking       = false;

	/** Facts */
	var $fold   = $(window).height() + latestScrollY;
	var $facts  = $('.intro__fact');
	var $view   = 0;
	var counted = false;

	/**
	 * Prepare DOM,
	 * hide and show different containers at startup.
	 */
	function initDOM() {
		/** FOUC */
		$('html').removeClass('no-js');

		/** FOIT */
		var observer = new FontFaceObserver('Open Sans', {
			weight: 300,
			style: 'normal'
		});
		observer.check().then(function () {
			document.documentElement.className += ' fonts-loaded';
		});

		/** Facts */
		if ( $facts.length > 0) {
			$view = $facts.offset().top;
			countUpFacts(false);
		}
	}

	/**
	 * Set-up event listeners.
	 */
	function initListeners() {
		if ($facts.length > 0) {
			$(window).scroll(onScroll);
		}

		$('[href="#site-header"]').click(elevator);
	}

	/**
	 * Scroll event
	 */
	function onScroll() {
		latestScrollY = $(window).scrollTop();
		requestTick();
	}

	/**
	 * Only call rAF if there is not one running.
	 */
	function requestTick() {
		if(!ticking) {
			if (counted == false) requestAnimationFrame(checkViewport);
		}

		ticking = true;
	}

	/**
	 * Check if facts are in viewport
	 */
	function checkViewport() {
		ticking = false;

		if ( $facts.length > 0) {
			$fold = $(window).height() + latestScrollY;

			if ( $fold > $view && counted == false ) {
				countUpFacts(true);
			}
		}
	}

	/**
	 * Count numbers up
	 *
	 * @param {boolean} countUp True to start the counter, false to initialize.
	 */
	function countUpFacts(countUp) {
		if (countUp == false) {
			$facts.each(function() {			
				var num = $(this).text()
				
				$(this).data('count-to', num);
				$(this).text('0');
			});
		} else {
			counted = true;
					
			$facts.each(function() {			
				$({ count: 0, el: this }).animate({ 
					count: $(this).data('count-to')
				}, {
					duration: 1500,
					progress: function(animation, progress, remainingMs) {
						return $(this.el).text(Math.floor(parseInt(this.count) * progress));
					}
				});
			});
		}
	}

	/**
	 * Scroll page to top.
	 *
	 * @returns {boolean} False (prevent default event).
	 */
	function elevator() {
		var body = $('html, body');

		body.animate({
			scrollTop: 0
		}, '500', 'swing');

		return false;
	}

	/** Public */
	
	return {
		/**
		 * Initialize,
		 * call DOM preparation and call event listener setup.
		 */
		init: function() {
			initDOM();
			initListeners();
		}
	}
}(window, document, jQuery));

/**
 * On document ready...
 */
jQuery(function() {
	GSCHICHTN.Main.init();
});
