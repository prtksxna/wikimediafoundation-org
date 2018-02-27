<?php
/**
 * Handles Featured Posts output for home and landing pages.
 *
 * @package wmfoundation
 */

$template_args = wmf_get_template_data();

$context  = empty( $template_args['context'] ) ? '' : $template_args['context'];
$subtitle = empty( $template_args['subtitle'] ) ? '' : $template_args['subtitle'];
$class    = empty( $template_args['class'] ) ? 'white-bg' : $template_args['class'];
$title    = get_theme_mod( 'wmf_featured_post_pre_heading', __( 'NEWS', 'wmfoundation' ) );

if ( empty( $context ) ) {
	return;
}

$cache_key      = md5( 'wmf_featured_posts_for' . $context );
$featured_posts = wp_cache_get( $cache_key );

if ( empty( $featured_posts ) ) {
	$featured_posts = new WP_Query(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 2,
			'no_found_rows'  => true,
			'meta_query'     => array(
				array(
					'key'     => 'featured_on',
					'value'   => $context,
					'compare' => 'LIKE',
				),
			),
		)
	); // WPCS: slow query ok.
	wp_cache_add( $cache_key, $featured_posts );
}

if ( ! $featured_posts->have_posts() ) {
	return;
}
?>
<div class="w-100p <?php echo esc_attr( $class ); ?> related-news-container mod-margin-bottom">
	<div class="mw-1360 std-mod">
		<h3 class="h3 color-gray"><?php echo esc_html( $title ); // Todo: magic method to get a random translation for this title. ?> —&nbsp;<span>HABER&nbsp;</span></h3>
		<?php if ( ! empty( $subtitle ) ) : ?>
		<h2><?php echo esc_html( $subtitle ); ?></h2>
		<?php endif; ?>
	</div>
	<div class="mw-1360 std-mod people-container">
		<div class="people slider-on-mobile flex flex-medium">
			<?php
			while ( $featured_posts->have_posts() ) {
				$featured_posts->the_post();
				get_template_part( 'template-parts/modules/featured/post', 'card' );
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</div>
