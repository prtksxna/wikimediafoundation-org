/**
 * Generic site JavaScript.
 */

/* eslint-disable */
jQuery(window).on('scroll', function () {
  if ( jQuery(this).scrollTop() > 0 ) {
    jQuery('.top-nav').addClass('pinned');
  } else {
    jQuery('.top-nav').removeClass('pinned');
  }
})
/* eslint-enable */
