<?php
/**
 * The Framing Copy Module.
 *
 * @package shiro
 */

$template_args = wmf_get_template_data();

if ( empty( $template_args['pre_heading'] ) && empty( $template_args['heading'] ) && empty( $template_args['modules'] ) ) {
	return;
}

$rand_translation_title = empty( $template_args['rand_translation_title'] ) ? '' : $template_args['rand_translation_title'];

$has_title = ! empty( $template_args['pre_heading'] ) && ! empty( $template_args['heading'] );

$bg_opts = get_post_meta( get_the_ID(), 'page_header_background', true );
$ungrid_color = isset( $bg_opts['color'] ) && 'pink' === $bg_opts['color'] ? 'pink' : '';

$has_modules = isset($template_args['modules']) && count($template_args['modules']) > 0;
$has_many_modules = count($template_args['modules']) > 2;
$has_image = get_the_post_thumbnail_url();
?>

<?php if ( $has_title ) : ?>
<div class="mod-margin-bottom white-bg">
	<div class="mw-980">
		<?php if ( ! empty( $template_args['pre_heading'] ) ) : ?>
		<h3 class="h3 color-gray"><?php echo esc_html( $template_args['pre_heading'] ); ?> — <span><?php echo esc_html( $template_args['rand_translation_title'] ); ?></span></h3>
		<?php endif; ?>
		<?php if ( ! empty( $template_args['heading'] ) ) : ?>
		<h2 class="h2"><?php echo esc_html( $template_args['heading'] ); ?></h2>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if ( $has_many_modules && $has_image && !is_front_page() ) { ?>
	<div class="framing-copy-ungrid mw-980">
		<div class="ungrid-line <?php echo $ungrid_color; ?>"></div>
		<?php $no_of_modules = count($template_args['modules']); ?>

		<div class="ungrid-top-3">
			<?php for ($i = 0; $i < 3; $i++) { ?>
				<div class="ungrid-top-box ungrid-top-box-<?php echo $i; ?>">
					<code class="<?php echo $ungrid_color; ?>">0<?php echo $i+1; ?></code>
					<?php wmf_get_template_part( 'template-parts/modules/mu/ungrid', $template_args['modules'][$i] ); ?>
				</div>
			<?php } ?>
		</div>

		<?php if ($no_of_modules === 4) { ?>
			<div class="ungrid-bottom-1">
				<div class="ungrid-bottom-box ungrid-bottom-box-4">
					<code class="<?php echo $ungrid_color; ?>">04</code>
					<?php wmf_get_template_part( 'template-parts/modules/mu/ungrid', $template_args['modules'][3] ); ?>
				</div>
			</div>
		<?php } ?>

		<?php if ($no_of_modules === 5) { ?>
			<div class="ungrid-bottom-2">
				<?php for ($i = 3; $i < 5; $i++) { ?>
					<div class="ungrid-bottom-box ungrid-bottom-box-<?php echo $i; ?>">
						<code class="<?php echo $ungrid_color; ?>">0<?php echo $i+1; ?></code>
						<?php wmf_get_template_part( 'template-parts/modules/mu/ungrid', $template_args['modules'][$i] ); ?>
					</div>
				<?php } ?>
			</div>
		<?php } ?>

		<?php if ($no_of_modules === 6) { ?>
			<div class="ungrid-bottom-3">
				<?php for ($i = 3; $i < 6; $i++) { ?>
					<div class="ungrid-bottom-box ungrid-bottom-box-<?php echo $i; ?>">
						<code class="<?php echo $ungrid_color; ?>">0<?php echo $i+1; ?></code>
						<?php wmf_get_template_part( 'template-parts/modules/mu/ungrid', $template_args['modules'][$i] ); ?>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
<?php } ?>

<?php if ( $has_modules && (empty( $has_image) || is_front_page())) { ?>
	<div class="flex flex-medium flex-wrap mw-980 fifty-fifty mod-margin-bottom">
		<?php
			foreach ( $template_args['modules'] as $key=>$module ) {
				$module["index"] = $key;
				wmf_get_template_part( 'template-parts/modules/mu/text', $module );
			}
		?>
	</div>
<?php } ?>

<?php if ( $has_title ) : ?>
</div>
<?php endif; ?>
