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
				<?php the_content(); ?>
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
