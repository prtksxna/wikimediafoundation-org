<?php
/**
 * Fieldmanager Fields for Landing page template
 *
 * @package wmfoundation
 */

/**
 * Add landing page options.
 */
function wmf_landing_fields() {
	$is_landing_page = wmf_using_template( 'page-landing' );
	$is_home         = (int) get_option( 'page_on_front' ) === (int) wmf_get_fields_post_id();

	if ( $is_landing_page ) {
		$social = new Fieldmanager_Group(
			array(
				'name'     => 'social_share',
				'children' => array(
					'heading'  => new Fieldmanager_Textfield( __( 'Section Heading', 'wmfoundation' ) ),
					'uri'      => new Fieldmanager_Link( __( 'Share URI', 'wmfoundation' ) ),
					'message'  => new Fieldmanager_Textfield( __( 'Message', 'wmfoundation' ) ),
					'services' => new Fieldmanager_Checkboxes(
						array(
							'label'   => __( 'Services', 'wmfoundation' ),
							'options' => array(
								'twitter'  => __( 'Twitter', 'wmfoundation' ),
								'facebook' => __( 'Facebook', 'wmfoundation' ),
							),
						)
					),
				),
			)
		);
		$social->add_meta_box( __( 'Social Share', 'wmfoundation' ), 'page' );
	}

	if ( $is_landing_page || $is_home ) {
		$framing_copy = new Fieldmanager_Group(
			array(
				'name'     => 'framing_copy',
				'children' => array(
					'pre_heading' => new Fieldmanager_Textfield( __( 'Section Pre-heading', 'wmfoundation' ) ),
					'heading'     => new Fieldmanager_Textfield( __( 'Section Heading', 'wmfoundation' ) ),
					'copy'        => new Fieldmanager_Group(
						array(
							'add_more_label' => __( 'Add Framing Copy', 'wmfoundation' ),
							'sortable'       => true,
							'limit'          => 0,
							'children'       => array(
								'image'     => new Fieldmanager_Media( __( 'Image', 'wmfoundation' ) ),
								'heading'   => new Fieldmanager_Textfield( __( 'Copy Heading', 'wmfoundation' ) ),
								'copy'      => new Fieldmanager_RichTextArea( __( 'Content', 'wmfoundation' ) ),
								'link_url'  => new Fieldmanager_Link( __( 'Link URI', 'wmfoundation' ) ),
								'link_text' => new Fieldmanager_Textfield( __( 'Link Text', 'wmfoundation' ) ),
								'links'     => new Fieldmanager_Group(
									array(
										'add_more_label' => __( 'Add Link', 'wmfoundation' ),
										'limit'          => 2,
										'children'       => array(
											'link_url'  => new Fieldmanager_Link( __( 'Link URI', 'wmfoundation' ) ),
											'link_text' => new Fieldmanager_Textfield( __( 'Link Text', 'wmfoundation' ) ),
										),
									)
								),
							),
						)
					),
				),
			)
		);
		$framing_copy->add_meta_box( __( 'Framing Copy', 'wmfoundation' ), 'page' );
	}

	$facts = new Fieldmanager_Group(
		array(
			'name'     => 'page_facts',
			'children' => array(
				'image' => new Fieldmanager_Media(
					array(
						'label'       => __( 'Background Image*', 'wmfoundation' ),
						'description' => __( '*This is a required element for the facts to show properly.', 'wmfoundation' ),
					)
				),
				'facts' => new Fieldmanager_Group(
					array(
						'add_more_label' => __( 'Add Fact', 'wmfoundation' ),
						'sortable'       => true,
						'limit'          => 3,
						'children'       => array(
							'heading' => new Fieldmanager_Textfield( __( 'Heading', 'wmfoundation' ) ),
							'content' => new Fieldmanager_Textfield( __( 'Content', 'wmfoundation' ) ),
						),
					)
				),
			),
		)
	);
	$facts->add_meta_box( __( 'Facts', 'wmfoundation' ), 'page' );

	$featured_post = new Fieldmanager_Textfield(
		array(
			'name' => 'featured_post_sub_title',
		)
	);
	$featured_post->add_meta_box( __( 'Featured Post Subtitle', 'wmfoundation' ), 'page' );
}
add_action( 'fm_post_page', 'wmf_landing_fields' );
