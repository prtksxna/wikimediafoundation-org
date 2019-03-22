<?php
/**
 * Common Header partial
 *
 * @package shiro
 */

$page_header_data = wmf_get_template_data();

$h4_link              = ! empty( $page_header_data['h4_link'] ) ? $page_header_data['h4_link'] : '';
$h4_title             = ! empty( $page_header_data['h4_title'] ) ? $page_header_data['h4_title'] : '';
$h2_link              = ! empty( $page_header_data['h2_link'] ) ? $page_header_data['h2_link'] : '';
$h2_title             = ! empty( $page_header_data['h2_title'] ) ? $page_header_data['h2_title'] : '';
$title                = ! empty( $page_header_data['h1_title'] ) ? $page_header_data['h1_title'] : '';
$alt_title            = ! empty( $page_header_data['h1_alt_title'] ) ? $page_header_data['h1_alt_title'] : '';
$meta                 = ! empty( $page_header_data['page_meta'] ) ? $page_header_data['page_meta'] : '';
$allowed_tags         = wp_kses_allowed_html( 'post' );
$allowed_tags['time'] = true;

$wmf_translation_selected = get_theme_mod( 'wmf_selected_translation_copy', __( 'Languages', 'shiro' ) );
$wmf_translations         = wmf_get_translations();


?>

<div class="header-content mar-bottom_lg">
	<?php if ( ! empty( $h4_title ) ) : ?>
	<h2 class="h4 eyebrow">
		<?php if ( ! empty( $h4_link ) ) : ?>
		<a class="back-arrow-link" href="<?php echo esc_url( $h4_link ); ?>">
		<?php endif; ?>
			<?php echo esc_html( $h4_title ); ?>
		<?php if ( ! empty( $h4_link ) ) : ?>
		</a>
		<?php endif; ?>
	</h2>
	<?php endif; ?>

	<?php if ( is_home() && ! empty( $h2_title ) ) : ?>
		<h2 class="h1 eyebrow"><?php echo esc_html( $h2_title ); ?></h2>
	<?php elseif ( ! empty( $h2_title ) ) : ?>
		<h2 class="h2 eyebrow">
			<?php if ( ! empty( $h2_link ) ) : ?>
			<a href="<?php echo esc_url( $h2_link ); ?>">
				<?php endif; ?>
				<?php echo esc_html( $h2_title ); ?>
				<?php if ( ! empty( $h2_link ) ) : ?>
			</a>
		<?php endif; ?>
		</h2>
	<?php endif; ?>

	<?php if ( is_front_page() ) { ?>
		<?php if ( ! empty( $title ) ) : ?>
			<h1 class="mar-bottom w-50p"><?php echo wp_kses( $title, array( 'span' => array( 'class' ) ) ); ?></h1>
		<?php endif; ?>
	<?php } else { ?>
		<div class="flex flex-medium page-landing fifty-fifty">
			<div class="module-mu w-50p">
				<h1><?php echo wp_kses( $title, array( 'span' => array( 'class' ) ) ); ?></h1>
			</div>
			<div class="page-intro-text module-mu w-50p">
				<!-- <?php the_content(); ?> -->
			</div>
		</div>
	<?php } ?>

	<?php if ( ! empty( $alt_title ) ) : ?>
		<h2 class="h1 mar-bottom"><?php echo wp_kses( $alt_title, array( 'span' => array( 'class' ) ) ); ?></h2>
	<?php endif; ?>

	<?php if ( ! empty( $meta ) ) : ?>
	<div class="post-meta h4">
		<?php echo wp_kses( $meta, $allowed_tags ); ?>
	</div>
	<?php endif; ?>

	<?php if ( is_front_page() ) : ?>
		<div class="page-intro mod-margin-bottom wysiwyg w-75p alignright">
			<div>
				<h2><?php echo esc_html( get_post_meta( get_the_ID(), 'sub_title', true ) ); ?></h2>

				<div class="page-intro-text">
					<?php the_content(); ?>
				</div>

			</div>
		</div>
	<?php endif; ?>
</div>

<?php if ( is_front_page() ) : ?>
	<?php if ( false !== $wmf_translations ) : ?>
		<div class="translation-bar">
		<div class="translation-bar-inner mw-980">
			<ul class="list-inline">
			<?php foreach ( $wmf_translations as $wmf_index => $wmf_translation ) : ?>
				<?php
				if ( false !== strpos( $wmf_translation['uri'], '/master-translation/' ) ) {
					continue; // This site shouldn't show. It's for admin functionality only.
				}
				?>
				<?php if ( 0 !== $wmf_index ) : ?>
				<li class="divider">&middot;</li>
				<?php endif; ?>
				<li>
					<?php if ( $wmf_translation['selected'] ) : ?>
					<span><?php echo esc_html( $wmf_translation['name'] ); ?></span>
					<?php else : ?>
					<a href="<?php echo esc_url( $wmf_translation['uri'] ); ?>"><?php echo esc_html( $wmf_translation['name'] ); ?></a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
			</ul>

			<?php if ( count( $wmf_translations ) > 10 ) : ?>
			<div class="arrow-wrap">
				<span>
					<span class="elipsis">...</span>
					<?php wmf_show_icon( 'trending', 'icon-turquoise material' ); ?>
				</span>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
<?php endif; ?>
