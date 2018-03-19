<?php
/**
 * Adds Header background
 *
 * @package wmfoundation
 */

if ( ! is_page() && ! is_404() ) {
	return;
}

$bg_opts = wmf_get_background_image();

if ( empty( $bg_opts['image'] ) ) {
	return;
}

?>
<div class="bg-img--<?php echo isset( $bg_opts['color'] ) && 'pink' === $bg_opts['color'] ? 'pink' : 'blue'; ?> bg-pattern-container">
	<div class="bg-img-container bg-pattern">
		<div class="bg-img" style="background-image: url(<?php echo esc_url( wp_get_attachment_url( $bg_opts['image'] ) ); ?>);"></div>
	</div>
</div>
