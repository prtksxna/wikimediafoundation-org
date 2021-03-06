<?php
/**
 * Adds publish actions and controls translation status.
 *
 * @package wmfoundation
 */

namespace WMF\Translations;

/**
 * Adds publish actions and controls translation status.
 */
class Flow {
	/**
	 * The translation status taxonomy.
	 *
	 * @var string
	 */
	public $taxonomy = 'translation-status';

	/**
	 * The term options key.
	 *
	 * @var string
	 */
	public $term_option = 'wmf_translation_status_terms';

	/**
	 * Status terms array for the site.
	 *
	 * @var array
	 */
	public $status_terms = array();

	/**
	 * Array of IDs for the new translations.
	 *
	 * @var array
	 */
	public $new_translations = array();

	/**
	 * The post meta.
	 *
	 * @var array
	 */
	public $meta = array();

	/**
	 * The object instance.
	 *
	 * @var Flow
	 */
	public static $instance;

	/**
	 * Gets the current instance of the object.
	 *
	 * @return Flow
	 */
	public static function get_instance() {
		if ( empty( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Flow constructor.
	 */
	public function __construct() {
		$this->maybe_register_translation_status_terms();
	}

	/**
	 * Sets the status terms and optionally creates them.
	 */
	public function maybe_register_translation_status_terms() {
		if ( wmf_is_main_site() ) {
			return;
		}

		$this->status_terms = get_option( $this->term_option );

		if ( empty( $this->status_terms ) ) {
			$this->register_translation_status_terms();
		}
	}

	/**
	 * Creates status terms if they were not previously set.
	 */
	public function register_translation_status_terms() {
		$terms = array(
			'new'      => __( 'Needs Translation (new)', 'wmfoundation' ),
			'update'   => __( 'Needs Translation (update)', 'wmfoundation' ),
			'progress' => __( 'In Progress', 'wmfoundation' ),
			'complete' => __( 'Complete', 'wmfoundation' ),
		);

		foreach ( $terms as $key => $term ) {
			$this->status_terms[ $key ] = wp_insert_term( $term, $this->taxonomy );
		}

		add_option( $this->term_option, $this->status_terms );
	}

	/**
	 * Publish actions callback.
	 *
	 * @param bool $pre Indicates if the translation complete checkbox should show.
	 *
	 * @return bool
	 */
	public static function publish_actions_callback( $pre ) {
		return static::get_instance()->publish_actions( $pre );
	}

	/**
	 * Outputs the actions to indicate post should be translated or translation is in progress.
	 *
	 * @param bool $pre Indicates if the translation complete checkbox should show.
	 *
	 * @return bool
	 */
	public function publish_actions( $pre ) {

		?>
		<div class="misc-pub-section">
			<?php
			wp_nonce_field( 'wmf-translation-flow-publish-actions', 'wmf_publish_actions' );

			if ( wmf_is_main_site() ) {
				$this->translate_post_action();
				$pre = false;
			} else {
				$this->in_progress_action();
			}
			?>
		</div>
		<?php
		return $pre;
	}

	/**
	 * HTML for the translate post (global) option.
	 */
	public function translate_post_action() {
		?>
		<label for="translate_post_global">
			<input type="checkbox" name="_translate_post_global" value="1" id="translate_post_global" />
			<?php esc_html_e( 'Translate post', 'wmfoundation' ); ?>
		</label>
		<?php
	}

	/**
	 * HTML for the translation status and checkbox to indicate translation is in progress.
	 */
	public function in_progress_action() {
		?>
<p><strong><?php esc_html_e( 'Translation Status:', 'wmfoundation' ); ?></strong> <?php echo esc_html( $this->translation_status() ); ?></p>
<label for="translation_in_progress">
	<input type="checkbox" name="_translation_in_progress" value="1" id="translation_in_progress"
		<?php checked( 1, get_post_meta( get_the_ID(), '_translation_in_progress', true ) ); ?>>
		<?php esc_html_e( 'Translation in progress', 'wmfoundation' ); ?>
</label>
		<?php
	}

	/**
	 * Returns the post translation status label.
	 *
	 * @return string
	 */
	public function translation_status() {
		$terms       = wp_get_post_terms( get_the_ID(), $this->taxonomy );
		$status_term = isset( $terms[0] ) ? $terms[0] : '';

		return empty( $status_term->name ) ? $this->status_terms['new'] : $status_term->name;
	}

	/**
	 * Update the post meta when cloning a post for translation.
	 *
	 * @param array $meta_array   The meta values to copy to the new post.
	 * @param array $save_context The context of the existing post.
	 *
	 * @return array
	 */
	public static function pre_post_meta_callback( $meta_array, $save_context ) {
		return array_merge( $meta_array, static::get_instance()->get_post_meta( $save_context ) );
	}

	/**
	 * Build meta content for cloning to new posts..
	 *
	 * @param mixed|int|array $save_context The context of the existing post.
	 *
	 * @return array
	 */
	public function get_post_meta( $save_context ) {
		$post_id = is_numeric( $save_context ) ? $save_context : $save_context['real_post_id'];

		add_filter( 'wp_insert_post', array( $this, 'set_new_translate_term' ) );

		$meta = get_post_custom( $post_id );

		$meta = $this->meta_build( $meta );

		$meta = $this->remove_numeric_r( $meta );

		return $meta;
	}

	/**
	 * Gets the array values so they save correctly.
	 *
	 * @param array $meta The meta array.
	 *
	 * @return array
	 */
	public function meta_build( $meta ) {
		$ret_meta = array();

		foreach ( $meta as $key => $array ) {
			if ( '_edit_lock' === $key ) {
				continue; // We don't want this value.
			}

			if ( ! empty( $array[0] ) ) {
				$ret_meta[ $key ] = $array[0];
			}
		}

		return $ret_meta;
	}

	/**
	 * Remove any values that are numeric.
	 *
	 * @param array $array The array to parse.
	 *
	 * @return array
	 */
	public function remove_numeric_r( $array ) {
		if ( ! is_array( $array ) ) {
			return $array;
		}

		foreach ( $array as $key => $value ) {
			$value = maybe_unserialize( $value );

			if ( is_array( $value ) ) {
				$array[ $key ] = $this->remove_numeric_r( $value );
			} elseif ( is_numeric( $value ) ) {
				unset( $array[ $key ] );
			}
		}

		return $array;
	}

	/**
	 * Callback on insert post. Sets the new translation status.
	 *
	 * @param int $post_id The post id.
	 */
	public function set_new_translate_term( $post_id ) {
		$this->new_translations[] = absint( $post_id );

		// We need to update this for each site here.
		$this->maybe_register_translation_status_terms();
		$this->set_translate_term( $post_id );
	}

	/**
	 * Sets the translation term.
	 *
	 * @param int    $post_id The post ID.
	 * @param string $context The translation status to set.
	 */
	public function set_translate_term( $post_id, $context = 'new' ) {
		$term_id = is_array( $this->status_terms[ $context ] ) ? $this->status_terms[ $context ]['term_id'] : $this->status_terms[ $context ];

		wp_set_object_terms( $post_id, $term_id, $this->taxonomy, false );
	}

	/**
	 * Static callback for save post.
	 *
	 * @param int $post_id The post ID.
	 */
	public static function save_post_callback( $post_id ) {
		static::get_instance()->save_post( $post_id );
	}

	/**
	 * Handles controls for translation status changes when updating a post.
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_post( $post_id ) {
		if ( empty( $_POST['wmf_publish_actions'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wmf_publish_actions'] ) ), 'wmf-translation-flow-publish-actions' ) ) { // Input var okay.
			return;
		}

		if ( ! empty( $_POST['_translation_in_progress'] ) ) { // Input var okay.
			$this->set_translate_term( $post_id, 'progress' );
			update_post_meta( $post_id, '_translation_in_progress', 1 );
		} else {
			delete_post_meta( $post_id, '_translation_in_progress' );
		}

		if ( ! empty( $_POST['_post_is_translated'] ) ) { // Input var okay.
			delete_post_meta( $post_id, '_translation_in_progress' );
			update_post_meta( $post_id, '_translation_complete', 1 );
			$this->set_translate_term( $post_id, 'complete' );
		}

		if ( ! empty( $_POST['_translate_post_global'] ) ) { // Input var okay.
			$this->set_post_meta( $post_id );

			$remote_posts = wmf_get_translations( false, $post_id, 'post' );

			foreach ( $remote_posts as $remote_post ) {
				if ( wmf_is_main_site( $remote_post['site_id'] ) ) {
					continue;
				}

				if ( in_array( (int) $remote_post['content_id'], $this->new_translations, true ) ) {
					$this->clone_meta( $remote_post['content_id'], $remote_post['site_id'] );
					continue;
				}

				switch_to_blog( $remote_post['site_id'] );

				// We need to update this for each site here.
				$this->maybe_register_translation_status_terms();

				$this->set_translate_term( $remote_post['content_id'], 'update' );

				restore_current_blog();
			}
		}
	}

	/**
	 * Sets the $meta property.
	 *
	 * @param int $post_id The post ID.
	 */
	public function set_post_meta( $post_id ) {
		wp_cache_delete( $post_id, 'post_meta' );

		$this->meta = $this->get_post_meta( $post_id );
	}

	/**
	 * Set the post meta for the remote post.
	 *
	 * @param int $remote_post_id The remote post ID.
	 * @param int $remote_site_id The remote site ID.
	 */
	public function clone_meta( $remote_post_id, $remote_site_id ) {
		switch_to_blog( $remote_site_id );

		foreach ( $this->meta as $key => $value ) {
			update_post_meta( $remote_post_id, $key, $value );
		}

		restore_current_blog();
	}
}
