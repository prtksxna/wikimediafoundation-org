<?php
/**
 * Setup Social share module
 *
 * @package wmfoundation
 */

if (
	is_front_page() ||
	is_home() ||
	is_404() ||
	is_search() ||
	is_singular( 'profile' ) ||
	( is_page() && 'page-landing.php' === basename( get_page_template() ) )
) {
	return;
}

if ( is_singular() && get_post_meta( get_the_ID(), 'sharing_disabled', true ) ) {
	return;
}

$services      = get_post_meta( get_the_ID(), 'share_links', true );
$template_data = array(
	'list_class' => '',
	'title' => '',
);

if ( ! empty( $services ) ) {
	$template_data['services'] = $services;
}

?><div class="social-share-aside"><?php
wmf_get_template_part( 'template-parts/modules/social/share-vertical', $template_data );
?></div>