<?php
/**
 * Setup Vision module
 *
 * @package shiro
 */

$visions = [
  get_theme_mod('wmf_vision_lang1'),
  get_theme_mod('wmf_vision_lang2'),
  get_theme_mod('wmf_vision_lang3'),
  get_theme_mod('wmf_vision_lang4'),
  get_theme_mod('wmf_vision_lang5')
];
$visions = array_filter($visions);
$is_visible = 'is_visible';
foreach( $visions as $vision ) {
  echo '<h1 class="vision '. $is_visible .'">' . $vision . '</h1>';
  $is_visible = '';
}
?>
