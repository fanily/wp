(function ($) {
	/**
	 * equal heights plugin
	 * equalize all boxes in a row
	 */
	$.fn.equalH = function() {
		var count = this.length;
		var firstItem = true;
		var eltop = 0;
		var prevtop = 0;
		var tallest = 0;
		var $this = this;
		var first = 1;
		var i = 0;
		var equalize = function (start, end, h, c) {
			for (j = start; j <= end; j++) {
				$this.eq(j - 1).height(h);
			}
			prevtop = c.offset().top;
			tallest = 0;
			first = end + 1;
		}
		return this.each(function () {
			var el = $(this);
			el.height('auto');
			if (firstItem) {
				firstItem = false;
				prevtop = el.offset().top
			}
			i++;
			eltop = el.offset().top;
			if (prevtop == eltop) {
				if (el.height() > tallest) {
					tallest = el.height();
				}
			} else {
				equalize(first, i - 1, tallest, el);
				tallest = 0;
				if (el.height() > tallest) {
					tallest = el.height();
				}
			}
			if (i == count) {
				equalize(first, i, tallest, el);
			}
		});
	};

	/**
	 * tabs plugin
	 */
	$.fn.g7_tabs = function() {
		return this.each(function() {
			//Get all tabs
			var tab = $(this).find('> li > a');
			var active_tab = $(this).find('> li:first > a');
			tab.click(function(e) {
				//Get Location of tab's content
				var contentLocation = $(this).attr('href');
				//Let go if not a hashed one
				if (contentLocation.charAt(0)=="#") {
					e.preventDefault();
					//Make Tab Active
					tab.removeClass('active');
					$(this).addClass('active');
					//Show Tab Content & add active class
					$(contentLocation)
						.show()
						.addClass('active')
						.siblings()
							.hide()
							.removeClass('active');
				}
			});
			active_tab.click();
		});
	};

    /**
     * accordion plugin
     */
	$.fn.g7_accordion = function() {
		return this.each(function() {
			var dd_width = $(this).width() - 20;
			var all_dt = $(this).find('> dt');
			var all_dd = $(this).find('> dd');
			var found = $(this).find('> dt.active');
			if (found.length == 0) {
				var active_dt = $(this).find('> dt:first');
			} else {
				var active_dt = found;
			}
			all_dd.width(dd_width); //fix animation jump
			all_dt.click(function() {
				var target_dd = $(this).next();
				if (!target_dd.hasClass('active')) {
					target_dd
						.slideDown()
						.addClass('active')
						.siblings('dd')
							.slideUp()
							.removeClass('active');
					$(this)
						.addClass('active')
						.siblings('dt')
							.removeClass('active');
				}
			});
			active_dt.click();
		});
	};

    /**
     * toggle plugin
     */
	$.fn.g7_toggle = function() {
		return this.each(function() {
			var dd_width = $(this).width() - 10;
			var dt = $(this).find('> dt');
			var dd = $(this).find('> dd');
			dd.width(dd_width).hide();
			dt.click(function() {
				var dt_active = dt.hasClass('active');
				if (dt_active) {
					dd.slideUp();
					dt.removeClass('active');
				} else {
					dd.slideDown();
					dt.addClass('active');
				}
			});
		});
	};

	$(function() {

		/**
		 * submenu indicator
		 */
		$('#mainmenu li:has(ul) > a').addClass('with-ul').append('<span class="sub"></span>');
		$('#topmenu li:has(ul) > a').addClass('with-ul').append('<span class="sub"></span>');
		/**
		 * add select menu for topmenu and mainmenu
		 * used if the screen width is below 768px
		 */
		if ($.fn.mobileMenu) {
			$('#topmenu').mobileMenu({
				defaultText: g7.navigate_text,
				className: 'topmenu'
			});
			$('#mainmenu').mobileMenu({
				defaultText: g7.navigate_text,
				className: 'mainmenu'
			});
		}

		/**
		 * add a placeholder for unsupported browser
		 */
		$('input, textarea').placeholder();

		/**
		 * add masonry layout
		 * and adjust box width in masonry container
		 */
		var mcontainer = $('.masonry-container');
		var masonry_rtl = false;
		if (g7.rtl) {
			masonry_rtl = true;
		}
		mcontainer.each(function() {
			var mc = $(this);
			mc.imagesLoaded(function() {
				mc.masonry({
					itemSelector: '.masonry-item',
					isAnimated: true,
					gutterWidth: 20,
					isRTL: masonry_rtl,
					columnWidth: function(containerWidth) {
						var box_width = mc.children('.masonry-item').width();
						return box_width;
					}
				});
			})
		});

		/**
		 * equalize all boxes in grid container
		 */
		var gcontainer = $('.grid-container');
		if (gcontainer.length) {
			gcontainer.imagesLoaded(function() {
				gcontainer.find('.box').equalH();
			});
		}

		/**
		 * close action for message boxes
		 */
		$('.msg').click(function() {
			var msgbox = $(this);
			msgbox.fadeTo('slow', 0);
			msgbox.slideUp(341);
		});

		/**
		 * activate accordion, toggle and tabs
		 */
		$('.accordion').g7_accordion();
		$('.toggle').g7_toggle();
		$('.tabs').g7_tabs();

		/**
		 * add prettyPhoto call if plugin included
		 */
		if ($.fn.prettyPhoto) {
			$("a[rel^='prettyPhoto']").prettyPhoto({
				theme: 'pp_default',
				social_tools: false
			});
		}

		/**
		 * contact widget validation and submit action
		 */
		$('[name="contact_name"], [name="contact_email"], [name="contact_message"]').keyup(function() {
			if ($(this).val() != '') {
				$(this).removeClass('err');
			}
		});
		$('.widget_g7_contact form').submit(function(e) {
			e.preventDefault();
			var f = $(this);
			var loading = f.find('.loading');
			var contact_msg = f.prev('.contact-msg');
			var contact_name = f.find('[name="contact_name"]');
			var contact_email = f.find('[name="contact_email"]');
			var contact_message = f.find('[name="contact_message"]');
			loading.show();
			contact_msg.html('');
			$.ajax({
				type: 'POST',
				url: g7.ajaxurl,
				data: $(this).serialize(),
				datatype: 'json',
				timeout: 30000,
				error: function() {
					loading.hide();
				},
				success: function (response) {
					loading.hide();
					switch (response.status) {
						case 1:
							contact_msg.html(response.msg);
							f.hide();
							break;
						case 2:
							contact_msg.html(response.msg);
							break;
						case 3:
							if (typeof response.error.name != 'undefined') {
								contact_name.addClass('err');
							}
							if (typeof response.error.email != 'undefined') {
								contact_email.addClass('err');
							}
							if (typeof response.error.message != 'undefined') {
								contact_message.addClass('err');
							}
							if (typeof response.error.body != 'undefined') {
								contact_msg.html(response.error.body);
								f.hide();
							}
							break;
					}
				}
			});
			return false;
		});

		/**
		 * add fitvids for video inside content and widgets
		 */
		$('.entry-content, .widget').fitVids();

	});

	/**
	 * add flex slider call if plugin included
	 */
	if ($.fn.flexslider) {
		$(window).load(function() {
			$('.flexslider').flexslider({
				animation: g7.slider_animation,
				slideshowSpeed: parseInt(g7.slider_slideshowSpeed),
				animationSpeed: parseInt(g7.slider_animationSpeed),
				pauseOnHover: g7.slider_pauseOnHover,
				smoothHeight: true,
				directionNav: false
			});
		});
	}

	/**
	 * equal heights for grid layout when browser resized
	 */
	$(window).resize(function() {
		$('.grid-container .box').equalH();
	});

})(jQuery);
