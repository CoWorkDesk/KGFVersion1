<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Fusion_Builder_Admin {

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 1 );
		add_action( 'admin_post_save_fb_settings', array( $this, 'settings_save' ) );
		add_filter( 'custom_menu_order', array( $this, 'reorder_submenus' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer' ), 1 );
	}

	/**
	 * Bottom update buttons on edit screen.
	 *
	 * @access public
	 */
	public function admin_footer() {
		global $post, $pagenow;

		$post_type = isset( $post->post_type ) ? $post->post_type : false;

		if ( ( 'post.php' == $pagenow || 'post-new.php' == $pagenow ) && post_type_supports( $post_type, 'editor' ) ) {
			$publish_button_text     = ( isset( $post->post_status ) && ( 'publish' === $post->post_status || 'private' === $post->post_status ) ) ? esc_attr__( 'Update', 'fusion-builder' ) : esc_attr__( 'Publish', 'fusion-builder' );
			$fusion_builder_settings = get_option( 'fusion_builder_settings', array() );

			$enable_builder_sticky_publish_buttons = true;

			if ( isset( $fusion_builder_settings['enable_builder_sticky_publish_buttons'] ) ) {
				$enable_builder_sticky_publish_buttons = $fusion_builder_settings['enable_builder_sticky_publish_buttons'];
			}

			if ( ! isset( $post->ID ) || ! $enable_builder_sticky_publish_buttons ) {
				return;
			}
			?>
			<div class="fusion-builder-update-buttons <?php echo ( 'publish' !== $post->post_status && 'future' !== $post->post_status && 'pending' !== $post->post_status && 'private' !== $post->post_status ) ? 'fusion-draft-button' : ''; ?>">
				<a href="#" class="button button-secondary fusion-preview" target="wp-preview-<?php echo esc_attr( $post->ID ); ?>"><?php esc_attr_e( 'Preview', 'fusion-builder' ); ?></a>
				<?php if ( 'publish' != $post->post_status && 'future' != $post->post_status && 'pending' != $post->post_status ) { ?>
				<a href="#"<?php echo ( 'private' === $post->post_status ) ? ' style="display:none"' : ''; ?> class="button button-secondary fusion-save-draft"><?php esc_attr_e( 'Save Draft', 'fusion-builder' ); ?></a>
			<?php } ?>
				<a href="#" class="button button-primary fusion-update"><?php echo esc_attr( $publish_button_text ); ?></a>
			</div>
			<?php
		}
	}

	/**
	 * Admin Menu.
	 *
	 * @access public
	 */
	public function admin_menu() {
		global $submenu;

		$whatsnew = add_menu_page( esc_attr__( 'Fusion Builder', 'fusion-builder' ), esc_attr__( 'Fusion Builder', 'fusion-builder' ), 'manage_options', 'fusion-builder-options', array( $this, 'whatsnew' ), 'dashicons-fusiona-logo', '2.222222' );
		$library   = add_submenu_page( 'fusion-builder-options', esc_attr__( 'Library', 'fusion-builder' ), esc_attr__( 'Library', 'fusion-builder' ), 'manage_options', 'fusion-builder-library', array( $this, 'library' ) );
		$addons   = add_submenu_page( 'fusion-builder-options', esc_attr__( 'Add-ons', 'fusion-builder' ), esc_attr__( 'Add-ons', 'fusion-builder' ), 'manage_options', 'fusion-builder-addons', array( $this, 'addons' ) );

		if ( current_user_can( 'switch_themes' ) ) {
			$submenu['fusion-builder-options'][0][0] = esc_attr__( 'Welcome', 'fusion-builder' );
		}

		if ( ! class_exists( 'Avada' ) ) {
			add_action( 'admin_print_scripts-' . $whatsnew, array( $this, 'admin_scripts_with_js' ) );
		} else {
			add_action( 'admin_print_scripts-' . $whatsnew, array( $this, 'admin_scripts' ) );

			// Add menu items if Avada is active.
			if ( ! defined( 'ENVATO_HOSTED_SITE' ) ) {
				$support  = add_submenu_page( 'fusion-builder-options', esc_attr__( 'Support', 'fusion-builder' ), esc_attr__( 'Support', 'fusion-builder' ), 'manage_options', 'fusion-builder-support', array( $this, 'support_tab' ) );
			}
			$faq      = add_submenu_page( 'fusion-builder-options', esc_attr__( 'FAQ', 'fusion-builder' ), esc_attr__( 'FAQ', 'fusion-builder' ), 'manage_options', 'fusion-builder-faq', array( $this, 'faq_tab' ) );
			$settings = add_submenu_page( 'fusion-builder-options', esc_attr__( 'Settings', 'fusion-builder' ), esc_attr__( 'Settings', 'fusion-builder' ), 'manage_options', 'fusion-builder-settings', array( $this, 'settings' ) );

			if ( ! defined( 'ENVATO_HOSTED_SITE' ) ) {
				add_action( 'admin_print_scripts-' . $support, array( $this, 'admin_scripts' ) );
			}
			add_action( 'admin_print_scripts-' . $faq, array( $this, 'admin_scripts_with_js' ) );
			add_action( 'admin_print_scripts-' . $settings, array( $this, 'admin_scripts_with_js' ) );
		}

		add_action( 'admin_print_scripts-' . $addons, array( $this, 'admin_scripts' ) );
		add_action( 'admin_print_scripts-' . $library, array( $this, 'admin_scripts' ) );
	}

	/**
	 * Admin scripts.
	 *
	 * @access public
	 */
	public function admin_scripts() {
		wp_enqueue_style( 'fusion_builder_admin_css', FUSION_BUILDER_PLUGIN_URL . 'css/fusion-builder-admin.css' );
	}

	/**
	 * Admin scripts including js.
	 *
	 * @access public
	 */
	public function admin_scripts_with_js() {
		wp_enqueue_style( 'fusion_builder_admin_css', FUSION_BUILDER_PLUGIN_URL . 'css/fusion-builder-admin.css' );
		wp_enqueue_script( 'fusion_builder_admin_faq_js', FUSION_BUILDER_PLUGIN_URL . 'js/admin/fusion-builder-admin.js' );
	}

	/**
	 * Loads the template file.
	 *
	 * @access public
	 */
	public function whatsnew() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/whatsnew.php' );
	}

	/**
	 * Loads the template file.
	 *
	 * @access public
	 */
	public function support_tab() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/support.php' );
	}

	/**
	 * Loads the template file.
	 *
	 * @access public
	 */
	public function faq_tab() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/faq.php' );
	}

	/**
	 * Loads the template file.
	 *
	 * @access public
	 */
	public function settings() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/settings.php' );
	}

	/**
	 * Loads the template file.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function addons() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/addons.php' );
	}

	/**
	 * Loads the template file.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function library() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/library.php' );
	}
	/**
	 * Add the title.
	 *
	 * @static
	 * @access protected
	 * @since 1.0
	 * @param string $title The title.
	 * @param string $page  The page slug.
	 */
	protected static function admin_tab( $title, $page ) {

		if ( isset( $_GET['page'] ) ) {
			$active_page = $_GET['page'];
		}

		if ( $active_page == $page ) {
			$link = 'javascript:void(0);';
			$active_tab = ' nav-tab-active';
		} else {
			$link = 'admin.php?page=' . $page;
			$active_tab = '';
		}

		echo '<a href="' . $link . '" class="nav-tab' . $active_tab . '">' . $title . '</a>'; // WPCS: XSS ok.

	}

	/**
	 * Adds the footer.
	 *
	 * @static
	 * @access public
	 */
	public static function footer() {
		?>
		<div class="fusion-builder-thanks">
			<p class="description"><?php esc_html_e( 'Thank you for choosing Fusion Builder. We are honored and are fully dedicated to making your experience perfect.', 'fusion-builder' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Adds the header.
	 *
	 * @static
	 * @access public
	 */
	public static function header() {
		?>
		<h1><?php echo esc_html( apply_filters( 'fusion_builder_admin_welcome_title', __( 'Welcome to Fusion Builder!', 'fusion-builder' ) ) ); ?></h1>
		<div class="updated registration-notice-1" style="display: none;">
			<p><strong><?php esc_attr_e( 'Thanks for registering your purchase. You will now receive the automatic updates.', 'fusion-builder' ); ?></strong></p>
		</div>
		<div class="updated error registration-notice-2" style="display: none;">
			<p><strong><?php esc_attr_e( 'Please provide all the three details for registering your copy of Fusion Builder.', 'fusion-builder' ); ?>.</strong></p>
		</div>
		<div class="updated error registration-notice-3" style="display: none;">
			<p><strong><?php esc_attr_e( 'Something went wrong. Please verify your details and try again.', 'fusion-builder' ); ?></strong></p>
		</div>
		<?php if ( ! class_exists( 'Avada' ) ) : ?>
			<div class="about-text">
				<?php printf( __( 'Currently Fusion Builder is only licensed to be used with the Avada WordPress theme. <a href="%1$s" target="%2$s">Subscribe to our newsletter</a> to find out when it will be fully be ready to use with any theme.', 'fusion-builder' ), 'http://theme-fusion.us2.list-manage2.com/subscribe?u=4345c7e8c4f2826cc52bb84cd&id=af30829ace', '_blank' ); // WPCS: XSS ok. ?>
			</div>
		<?php else : ?>
			<div class="about-text">
				<?php if ( ! defined( 'ENVATO_HOSTED_SITE' ) ) : ?>
					<?php $welcome_text = sprintf( __( 'Fusion Builder is now installed and ready to use! Get ready to build something beautiful. Please <a href="%1$s" target="%2$s">register your purchase</a> to receive automatic updates and single page Fusion Builder Demo imports. We hope you enjoy it!', 'fusion-builder' ), admin_url( 'admin.php?page=avada-registration' ), '_blank' ); // WPCS: XSS ok. ?>
				<?php else : ?>
					<?php $welcome_text = sprintf( __( 'Fusion Builder is now installed and ready to use! Get ready to build something beautiful. Through your registration on the Envato hosted platform, you can now get automatic updates and single page Fusion Builder Demo imports. Check out the <a href="%s" target="_blank">Envato Hosted Support Policy</a> to learn how to receive support through the Envato hosted support team. We hope you enjoy it!', 'Avada' ), esc_url( 'https://envatohosted.zendesk.com/hc/en-us/articles/115001666945-Envato-Hosted-Support-Policy' ) ); // WPCS: XSS ok. ?>
				<?php endif; ?>

				<?php echo apply_filters( 'fusion_builder_admin_welcome_text', $welcome_text ); // WPCS: XSS ok. ?>
			</div>
		<?php endif; ?>
		<div class="fusion-builder-logo">
			<span class="fusion-builder-version">
				<?php printf( esc_attr__( 'Version %s', 'fusion-builder' ), esc_attr( FUSION_BUILDER_VERSION ) ); ?>
			</span>
		</div>
		<h2 class="nav-tab-wrapper">
			<?php
			self::admin_tab( esc_attr__( 'Welcome', 'fusion-builder' ), 'fusion-builder-options' );
			if ( class_exists( 'Avada' ) ) {
				if ( ! defined( 'ENVATO_HOSTED_SITE' ) ) {
					self::admin_tab( esc_attr__( 'Support', 'fusion-builder' ), 'fusion-builder-support' );
				}
				self::admin_tab( esc_attr__( 'FAQ', 'fusion-builder' ), 'fusion-builder-faq' );
				self::admin_tab( esc_attr__( 'Settings', 'fusion-builder' ), 'fusion-builder-settings' );
			}
			self::admin_tab( esc_attr__( 'Library', 'fusion-builder' ), 'fusion-builder-library' );
			self::admin_tab( esc_attr__( 'Add-ons', 'fusion-builder' ), 'fusion-builder-addons' );
			?>
		</h2>
		<?php
	}

	/**
	 * Reorders submenus.
	 * We're using this to make sure that the addons submenu is always last.
	 * The $menu_order is not changed, what we're doing here is modify the $submenu global.
	 *
	 * @access public
	 * @since 1.1.0
	 * @param bool $menu_order See https://codex.wordpress.org/Plugin_API/Filter_Reference/custom_menu_order.
	 * @return bool
	 */
	public function reorder_submenus( $menu_order ) {
		global $submenu;
		$fb_submenus = array();
		if ( ! isset( $submenu['fusion-builder-options'] ) ) {
			return $menu_order;
		}
		foreach ( $submenu['fusion-builder-options'] as $key => $args ) {
			if ( 'fusion-builder-library' === $args[2] ) {
				unset( $submenu['fusion-builder-options'][ $key ] );
				$submenu['fusion-builder-options'][] = $args;
			}
			if ( 'fusion-builder-addons' === $args[2] ) {
				unset( $submenu['fusion-builder-options'][ $key ] );
				$submenu['fusion-builder-options'][] = $args;
			}
		}
		return $menu_order;
	}

	/**
	 * Handles the saving of settings in admin area.
	 *
	 * @access private
	 * @since 1.0
	 */
	public function settings_save() {
		check_admin_referer( 'fusion_builder_save_fb_settings', 'fusion_builder_save_fb_settings' );

		update_option( 'fusion_builder_settings', $_POST );
		wp_redirect( admin_url( 'admin.php?page=fusion-builder-settings' ) );
		exit;
	}
}
new Fusion_Builder_Admin();
