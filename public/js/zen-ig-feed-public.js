(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$( document ).ready(function() {
		if ($('.zigfeed-container') == 0) {
			return;
		}
		var zigfeed = $('.zigfeed-container'), 
			fired = false;
		window.addEventListener("scroll", function() {
			if ((document.documentElement.scrollTop != 0 && fired === false) || (document.body.scrollTop != 0 && fired === false)) {
				fired = true;
				zigfeed.each(function(){
					var this_ig = $(this);
					var this_imgs = this_ig.find(".ig-img");
					this_imgs.each(function(){
						var this_img_n = $(this);
						var src = this_img_n.attr("xsrc");
						this_img_n.attr("src",src);
					});
					var this_ig_row = this_ig.find(".zrow");
					$(this_ig_row).slick({
						dots: false,
						autoplay: false,
						autoplaySpeed: 6000,
						speed: 500,
						arrows: true,
						nextArrow: '<div class="next"></div>',
						prevArrow: '<div class="prev"></div>',
						slidesToShow: 3,
						centerMode: true,
						centerPadding: '17%',
						responsive: [
							{
								breakpoint: 769,
								settings: {
									slidesToShow: 1,
								}
							},
						],
					});

				});
			}
		}, true);
	});

})( jQuery );
