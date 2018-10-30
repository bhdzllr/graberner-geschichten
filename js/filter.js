/**
 * Filter module
 *
 * Filter stories for "Graberner GeschichteN"
 * and handles user interface for filter page.
 */
GSCHICHTN.Filter = (function(window, document, $) {
	/** Fixed filter element */
	var $filter   = $('.filter').length ? $('.filter')         : false;
	var offsetTop = $filter             ? $filter.offset().top : false;

	/** WordPress AJAX */
	var url,            // WP AJAX url.
		page      = 1,  // Counts pages.
		ppp       = 12, // Amount of posts to load, initial value for fallback.
		postCount = 0;  // Amount of all posts in the database.

	/** Filters */
	var latestScrollY     = 0,
		ticking           = false,
		activeFilters     = {},
		activeFors        = [],
		activeFilterNames = {},
		combinedFilters   = [],
		selector          = null,
		formatsCount      = {},
		resultCount       = 0;

	/** Load more */
	var loadMore      = loadMore(); // Closure, to only run once at a time
	var loadMoreCount = 1;          // Count to load always a minimum of n (n = ppp) stories

	/**
	 * Prepare DOM,
	 * hide and show different containers on initializing.
	 */
	function initDOM() {
		$('.js-more').prop('disabled', false); // Sometimes refresh comes with disabled button

		var loadingImage = new Image();
		loadingImage.src = '/system/wp-content/themes/gschichtn-child/img/loader.gif';
	}

	/**
	 * Set-up event listeners.
	 */
	function initListeners() {
		/** Dropdown */
		$('.js-dropdown').click(toggleDropdown);
		$('body').click(closeDropdownOnBodyClick);
		$('.js-list').mouseleave(function(e) {
			// mouseleave is fired on touch devices and then the click event,
			// it must be prevented that the dropdown fades out and reopens because click (toggle) is fired.
			// A timestamp is used for this check. Stupid but it works.
			$(this).data('last-closed', Date.now());
			$(this).fadeOut('slow');
		});

		/** Filter */
		$(window).scroll(onScroll);
		$('.js-filter-story-category').click(selectFilter);
		$('.js-filter-story-format').click(selectFilter);
		$('.js-filter-story-year').click(selectFilter);
		$('.js-reset').click(resetFilter);

		/** Stories */
		// $(document).on('click', '.js-story-link', setViewedMarker);

		/** Other UI elements */
		$('.js-more').click(function() {
			loadMore();
			loadMoreCount++;
			filter();
		});
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
			requestAnimationFrame(stickFilterElement);
		}

		ticking = true;
	}

	/**
	 * Fix filter on top of page.
	 */
	function stickFilterElement() {
		ticking = false;

		if ($filter) {
			if (latestScrollY >= offsetTop && !$filter.hasClass('filter--is-fixed')) {
				$filter.addClass('filter--is-fixed');
			}

			if (latestScrollY < offsetTop && $filter.hasClass('filter--is-fixed')) {
				$filter.removeClass('filter--is-fixed');
			}
		}
	}

	/**
	 * Toggle filter dropdown.
	 *
	 * @returns {boolean} False (prevent default event).
	 */
	function toggleDropdown() {
		var $list = $(this)
			.parent()
			.parent()
			.find('.js-list');

		if ((Date.now() - $list.data('last-closed')) < 500) return false;

		$list.data('last-closed', Date.now());
		$list.fadeToggle('fast');

		return false;
	}

	/**
	 * Close filter dropdowns on body click.
	 *
	 * @param   {Object}  e Event.
	 * @returns {boolean}   False (prevent default event) if a list is open, True if no list is open.
	 */
	function closeDropdownOnBodyClick(e) {
		var target = e.target || e.srcElement;

		if ($('.js-list').is(':visible') && !$(target).is('.js-list, .js-list li, .js-label, .js-input')) {
			$('.js-list').fadeOut('fast');

			return false;
		}

		return true;
	}

	/**
	 * Set cookie on clicking a story.
	 */
	function setViewedMarker() {
		var postId = $(this).parent().attr('id');

		if (!Cookies.get(postId))
			Cookies.set(postId, 'true', { expires: 3650 });

		return true;
	}

	/**
	 * Check if the initial stories are viewed.
	 */
	function showViewedMarker() {
		var $initStories = $('.js-story');

		$.each($initStories, function(index, element) {
			if (Cookies.get($(element).attr('id')))
				$(element).find('.js-story-link').addClass('story__link--viewed');
		});
	}

	/**
	 * Get and set posts per page
	 *
	 * @param {string} WPUrl WordPress Admin url.
	 */
	function initWPQuery(WPUrl) {
		if (WPUrl === undefined) return false;

		url = WPUrl;

		$.post(url, {
			action: 'init_WP_Query'
		}).success(function(result) {
			ppp       = parseInt(result.ppp, 10);
			postCount = parseInt(result.count, 10);

			if (ppp > postCount)
				$('.js-more').fadeOut();
		});

		// formatsCount['video'] = $('.js-video-count').text();
		// formatsCount['bild']  = $('.js-bild-count').text();
		// formatsCount['text']  = $('.js-text-count').text();
		// formatsCount['audio'] = $('.js-audio-count').text();

		updateFormatsCount();
		showViewedMarker();

		if (Cookies.get('filter-ids')) {
			var filterIds = JSON.parse(Cookies.get('filter-ids'));

			$.each(filterIds, function(i, id) {
				prepareFilter(id);
			});
		}
	}

	/**
	 * If a filter is selected in the user interface.
	 * 
	 * @returns {boolean} True (do NOT prevent default event!).
	 */
	function selectFilter() {
		var id = $(this).attr('for');

		if (Cookies.get('filter-ids')) {
			var filterIds = JSON.parse(Cookies.get('filter-ids'));
			var i = $.inArray(id, filterIds);

			if (i >= 0) {
				filterIds.splice(i, 1);
			} else {
				filterIds.push(id);
			}

			Cookies.set('filter-ids', JSON.stringify(filterIds));
		} else {
			Cookies.set('filter-ids', JSON.stringify([id]));
		}

		prepareFilter(id);

		return true;
	}

	/**
	 * Reset filter
	 *
	 * @returns {boolean} False (prevent default event).
	 */
	function resetFilter() {
		activeFilters = {};
		activeFors = [];
		combinedFilters = [];
		selector = null;

		$('.filter-form__label--active').removeClass('filter-form__label--active');
		$('.js-input').prop('checked', false);
		$('.js-dropdown span').text(' (0)');
		$('.js-noresults').fadeOut('fast');
		Cookies.remove('filter-ids');

		filter();

		return false;
	}

	/**
	 * Prepare filter and set interface states.
	 * 
	 * @returns {boolean} True (do not prevent default event).
	 */
	function prepareFilter(id) {
		var $this = $('[for="' + id + '"]');

		var id     = id;
		var name   = $this.text();
		var tax    = $this.data('filter-taxonomy');
		var slug   = $this.data('filter-slug');
		var termId = $this.data('filter-term-id');

		var slugSelector;

		var $noresults = $('.js-noresults');

		if ($noresults.is(':visible')) {
			$noresults.fadeOut('fast');
		}

		$this.toggleClass('filter-form__label--active');
		$('#' + id).prop('checked', true);

		if (tax == 'year') {
			slugSelector = '.story-year-' + termId;
		} else {
			slugSelector = '[data-' + tax + '="' + slug + '"]';
		}

		/** Create object with taxonomies as keys and slugs as values */
		if (typeof activeFilters[tax] !== 'undefined') {
			var i = $.inArray(slugSelector, activeFilters[tax]);

			if (i >= 0) {
				activeFilters[tax].splice(i, 1);
				activeFilterNames[tax].splice(i, 1);
			} else {
				activeFilters[tax].push(slugSelector);
				activeFilterNames[tax].push(name);
			}
		} else { 
			activeFilters[tax] = [];
			activeFilters[tax].push(slugSelector);

			activeFilterNames[tax] = [];
			activeFilterNames[tax].push(name);
		}

		/** Create array from object */
		var filterArray = [];
		$.each(activeFilters, function(key, arr) {
			if (arr.length > 0)
				filterArray.push(arr);
		});

		/** Combine to selector */
		combinedFilters = combine(filterArray);
		selector        = null;

		$.each(combinedFilters, function(i, arr) {
			if (selector === null) {
				selector = arr.join('');
			} else {
				selector += ',' + arr.join('');
			}
		});

		filter();
		updateFiltersCount();

		return true;
	}

	/**
	 * Create all possible combination of array.
	 *
	 * @author  Bergi (http://stackoverflow.com/users/1048572/bergi)
	 * @see     {@link http://stackoverflow.com/questions/15298912/javascript-generating-combinations-from-n-arrays-with-m-elements}
	 * 
	 * @param   {Array} arg Filter Array (multidimensional).
	 * @returns {Array}     Array with all combinations or empty array.
	 */
	function combine(arg) {
		if (arg.length < 1) return [];

		var r = [];
		// var arg = arguments
		var max = arg.length - 1;

		function helper(arr, i) {
			for (var j=0, l=arg[i].length; j < l; j++) {
				var a = arr.slice(0); // clone arr
				a.push(arg[i][j]);
				
				if (i == max)
					r.push(a);
				else
					helper(a, i + 1);
			}
		}

		helper([], 0);

		return r;
	}

	/**
	 * Filter objects via selector.
	 *
	 * @return {boolean} True or "undefined" if there is no selector.
	 */
	function filter() {
		var $stories = $('.js-story');
		var $btnMore = $('.js-more');
		var results = [];

		if (selector === null) {
			$stories.fadeIn('slow', updateFormatsCount);
			results = $.makeArray($stories);
		} else {
			$stories.filter(function() {
				if ($(this).is(selector)) {
					$(this).fadeIn('slow', updateFormatsCount);
					results.push(this);
				} else {
					$(this).fadeOut('slow', updateFormatsCount);
				}
			});
		}

		$stories.promise()
			.done(function() {
				resultCount = results.length;

				if (resultCount < (ppp * loadMoreCount) && ((ppp * page) < postCount)) {
					/** Just a few stories loaded, there is more, load it */
					loadMore();
				} else if ((ppp * page) >= postCount) {
					/** Nothing more to load */
					$btnMore
						.addClass('btn--disabled')
						.removeClass('btn--loader')
						.prop('disabled', true)
						.text($btnMore.data('eod'));

					if (resultCount == 0) {
						var $noresults = $('.js-noresults');
						$noresults.fadeIn('slow');
					}
				} else {
					/** Enough stories loaded, there is more */
					$btnMore
						.removeClass('btn--disabled')
						.removeClass('btn--loader')
						.prop('disabled', false)
						.text($btnMore.data('lmd'));
				}
			});

		return true;
	}

	/**
	 * Load more stories
	 * and render it.
	 *
	 * @returns {Boolean} False (prevent default event)
	 */
	function loadMore() {
		var isLoading = false;

		return function() {
			if (!isLoading) {
				isLoading = true;

				if ((page * ppp) < postCount) {
					var $btnMore = $('.js-more');

					$btnMore
						.addClass('btn--disabled')
						.addClass('btn--loader')
						.prop('disabled', true)
						.text('');

					$.post(url, {
						action: 'more_posts',
						offset: (page * ppp),
						ppp:    ppp
					}).done(function(posts) {
						/** Render each new article */
						$.each(posts, function( index, post ) {
							var $article = $('#js-story').clone();
							var $link = $article.children('.js-story-link');

							$article.css('background-image', 'url(' + post['image'][0] +  ')');
							$article.addClass('js-story');
							$article.attr('id', 'post-' + post['id']);
							$article.attr('data-category', post['story-category']['slug']);
							$article.attr('data-format', post['story-format']['slug']);

							$link.attr('href', post['permalink']);
							$link.children('.js-story-title').html(post['title']).text();
							$link.children('.js-story-category').html(post['story-category']['name']).text();

							var years = [];

							if (post['story-year'].length > 2) {
								var $span = $(document.createElement('span'));
								$span.addClass('story__year');
								$span.text(
									$(post['story-year']).first()[0]['name'] 
									+ ' - ' 
									+ $(post['story-year']).last()[0]['name']
								);

								$link.children('.js-story-years').append($span);
							} else {
								$.each(post['story-year'], function(i, year) {
									var $span = $(document.createElement('span'));
									$span.addClass('story__year');
									$span.text(year['name']);

									$link.children('.js-story-years').append($span);
								});
							}

							$.each(post['story-year'], function(i, year) {
								$article.addClass('story-year-' + year['term_id']);
								years.push(year['slug']);
							});

							if (Cookies.get('post-' + post['id']))
								$link.addClass('story__link--viewed');

							$article
								.attr('data-year', years)
								.hide()
								.append($link);

							if ($article.is(selector)) {
								$article.fadeIn('slow');
							}

							$('.js-grid').append($article);
						});

						page++;

						isLoading = false;
					}).always(function() {
						filter();
						isLoading = false;
					});
				}

				return false;
			} else {
				// console.debug('oh no');
			}
		}
	}

	/**
	 * Count formats and update in user interface.
	 */
	function updateFormatsCount() {
		var formats = {};
		var format;

		$('[data-format]:visible').each(function(i, element) {
			format = $(element).data('format');

			if (formats.hasOwnProperty(format)) {
				formats[format]++;
			} else {
				formats[format] = 1;
			}
		});

		// (formats['video']) ? $('.js-video-count').text(formats['video'] + '/' + formatsCount['video']) : $('.js-video-count').text('0' + '/' + formatsCount['video']);
		// (formats['bild'])  ? $('.js-bild-count').text(formats['bild']   + '/' + formatsCount['bild'])  : $('.js-bild-count').text('0'  + '/' + formatsCount['bild']);
		// (formats['text'])  ? $('.js-text-count').text(formats['text']   + '/' + formatsCount['text'])  : $('.js-text-count').text('0'  + '/' + formatsCount['text']);
		// (formats['audio']) ? $('.js-audio-count').text(formats['audio'] + '/' + formatsCount['audio']) : $('.js-audio-count').text('0' + '/' + formatsCount['audio']);
	}

	/**
	 * Update active filter names, with number of selected filters
	 * for each category.
	 */
	function updateFiltersCount() {
		$.each(activeFilterNames, function(i, arr) {
			var $span = $('.js-dropdown-' + i + ' span');

			if (!$span.is('span')) {
				$span = $(document.createElement('span'));
				$('.js-dropdown-' + i).append($span);
			}

			$span.text(' (' + arr.length + ')');
		});
	}

	/** Public */
	return {
		/**
		 * Initialize
		 * Call DOM preparation and call event listener setup
		 */
		init: function(WPUrl) {
			initDOM();
			initListeners();
			initWPQuery(WPUrl);
		}
	}
}(window, document, jQuery));

/**
 * On document ready...
 */
jQuery(function() {
	GSCHICHTN.Filter.init('/system/wp-admin/admin-ajax.php');
});