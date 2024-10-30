<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Share
 * @subpackage Buddypress_Share/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Buddypress_Share
 * @subpackage Buddypress_Share/admin
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Buddypress_Share_Options_Page {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   string $plugin_name The name of this plugin.
	 * @param   string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Function for add plugin menu.
	 *
	 * @access public
	 * @author  Wbcom Designs
	 * @since    1.0.0
	 */
	public function bp_share_plugin_menu() {
		if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
			add_menu_page( esc_html__( 'WB Plugins', 'buddypress-share' ), esc_html__( 'WB Plugins', 'buddypress-share' ), 'manage_options', 'wbcomplugins', array( $this, 'bp_share_plugin_options' ), 'dashicons-lightbulb', 59 );
			add_submenu_page( 'wbcomplugins', esc_html__( 'General', 'buddypress-share' ), esc_html__( 'General', 'buddypress-share' ), 'manage_options', 'wbcomplugins' );
		}
		add_submenu_page( 'wbcomplugins', esc_html__( 'BuddyPress Share', 'buddypress-share' ), esc_html__( 'BuddyPress Share', 'buddypress-share' ), 'manage_options', $this->plugin_name, array( $this, 'bp_share_plugin_options' ) );
	}

	/**
	 * Intialize plugin admin settings.
	 *
	 * @access public
	 * @author  Wbcom Designs
	 * @since    1.0.0
	 */
	public function bp_share_settings_init() {
		register_setting( 'bp_share_services_extra', 'bp_share_services_extra' );
		add_settings_section(
			'bp_share_extra_options',
			esc_html__( 'Extra Options', 'buddypress-share' ),
			array( $this, 'bp_share_settings_section_callback' ),
			'bp_share_services_extra'
		);
		add_settings_field(
			'bp_share_services_open',
			esc_html__( 'Open as popup window', 'buddypress-share' ),
			array( $this, 'bp_share_checkbox_open_services_render' ),
			'bp_share_services_extra',
			'bp_share_extra_options'
		);
	}

	/**
	 * Intialize setting to show share in popup or new page.
	 *
	 * @access public
	 * @author  Wbcom Designs
	 * @since    1.0.0
	 */
	public function bp_share_checkbox_open_services_render() {
		$extra_options = get_site_option( 'bp_share_services_extra' );
		?>
		<input type='checkbox' name='bp_share_services_open'
		<?php
		if ( isset( $extra_options['bp_share_services_open'] ) && 1 === $extra_options['bp_share_services_open'] ) {
			echo 'checked="checked"'; }
		?>
		value='1'>
		<?php
	}


	/**
	 * Intialize bp_share_settings_section_callback.
	 *
	 * @access public
	 * @author  Wbcom Designs
	 * @since    1.0.0
	 */
	public function bp_share_settings_section_callback() {
		echo '<div class="bp_share_settings_section_callback_class">';
		echo '<div class="title-bp-share-view">'; esc_html_e( 'Default is set to open window in popup. If this option is disabled then services open in new tab instead popup.  ', 'buddypress-share' ); echo '</div>';
	}

	/**
	 * Build the admin options page.
	 *
	 * @access public
	 * @author  Wbcom Designs
	 * @since    1.0.0
	 */
	public function bp_share_plugin_options() {
		$tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'bpas_welcome';
		// admin check.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'buddypress-share' ) );
		}
		?>
			<div class="wrap">
							<hr class="wp-header-end">
							<div class="wbcom-wrap">
				<div class="bpss-header">
				<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
					<h1 class="wbcom-plugin-heading">
					<?php esc_html_e( 'BuddyPress Activity Social Share Settings', 'buddypress-share' ); ?>
					</h1>
				</div>
				<div class="wbcom-admin-settings-page">
				<?php
				settings_errors();
				$this->bpas_plugin_settings_tabs( $tab );
				settings_fields( $tab );
				do_settings_sections( $tab );
				?>
				</div>
							</div>
			</div>
			<?php
	}

	/**
	 * Tab listing
	 *
	 * @param current $current the current tab.
	 * @since    1.0.0
	 */
	public function bpas_plugin_settings_tabs( $current ) {
		$bpas_tabs = array(
			'bpas_welcome'          => esc_html__( 'Welcome', 'buddypress-share' ),
			'bpas_general_settings' => esc_html__( 'General Settings', 'buddypress-share' ),
		);
		$tab_html  = '<div class="wbcom-tabs-section"><div class="nav-tab-wrapper"><div class="wb-responsive-menu"><span>' . esc_html( 'Menu' ) . '</span><input class="wb-toggle-btn" type="checkbox" id="wb-toggle-btn"><label class="wb-toggle-icon" for="wb-toggle-btn"><span class="wb-icon-bars"></span></label></div><ul>';
		foreach ( $bpas_tabs as $bpas_tab => $bpas_name ) {
			$class     = ( $bpas_tab === $current ) ? 'nav-tab-active' : '';
			$tab_html .= '<li><a class="nav-tab ' . esc_attr( $class ) . '" href="admin.php?page=buddypress-share&tab=' . esc_attr( $bpas_tab ) . '">' . esc_html( $bpas_name ) . '</a></li>';
		}
		$tab_html .= '</div></ul></div>';
		echo wp_kses_post( $tab_html );
		$this->bpas_include_admin_setting_tabs( $current );
	}

	/**
	 * Tab listing
	 *
	 * @param bpas_tab $bpas_tab the current tab.
	 * @since    1.0.0
	 */
	public function bpas_include_admin_setting_tabs( $bpas_tab ) {
		$bpas_tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'bpas_welcome';
		switch ( $bpas_tab ) {
			case 'bpas_welcome':
				$this->bpas_welcome_section();
				break;
			case 'bpas_general_settings':
				$this->bpas_general_setting_section();
				break;
			default:
				$this->bpas_welcome_section();
				break;
		}
	}

	/**
	 * Welcome template
	 *
	 * @since    1.0.0
	 */
	public function bpas_welcome_section() {

		if ( file_exists( BP_ACTIVITY_SHARE_PLUGIN_PATH . 'admin/bp-welcome-page.php' ) ) {
			require_once BP_ACTIVITY_SHARE_PLUGIN_PATH . 'admin/bp-welcome-page.php';
		}
	}

	/**
	 * Social service settig template
	 *
	 * @since    1.0.0
	 */
	public function bpas_general_setting_section() {
		?>
		<div class="wbcom-tab-content">
			<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" id="bp_share_form">
			<?php wp_nonce_field( 'update-options' ); ?>
					<h3><?php esc_html_e( 'Add Social Services', 'buddypress-share' ); ?></h3>
					<table cellspacing="0" class="add_share_services widefat fixed plugins">
						<thead>
							<tr>
								<th class="manage-column column-name" id="name" scope="col" style="width: 190px;"><?php esc_html_e( 'Component', 'buddypress-share' ); ?></th>
								<th class="manage-column column-select_services" id="select_services" scope="col"><?php esc_html_e( 'Select Service', 'buddypress-share' ); ?></th>
							</tr>
						</thead>
						<tbody id="the-list">
							<tr>
								<td class="plugin-title" style="width: 190px;">
									<strong style="margin-top: 3px; float: left;"><?php esc_html_e( 'Social Sites', 'buddypress-share' ); ?></strong><span class="bp_share_req">*</span></td>
								<td class="column-description desc">
									<div class="plugin-description">
										<select name="social_services_selector" id="social_services_selector_id" class="social_services_selector">
											<option value="">-<?php esc_html_e( 'select', 'buddypress-share' ); ?>-</option>
											<option value="bp_share_facebook"><?php esc_html_e( 'Facebook', 'buddypress-share' ); ?></option>
											<option value="bp_share_twitter"><?php esc_html_e( 'Twitter', 'buddypress-share' ); ?></option>
											<option value="bp_share_pinterest"><?php esc_html_e( 'Pinterest', 'buddypress-share' ); ?></option>
											<option value="bp_share_linkedin"><?php esc_html_e( 'Linkedin', 'buddypress-share' ); ?></option>
											<option value="bp_share_reddit"><?php esc_html_e( 'Reddit', 'buddypress-share' ); ?></option>
											<option value="bp_share_wordpress"><?php esc_html_e( 'WordPress', 'buddypress-share' ); ?></option>
											<option value="bp_share_pocket"><?php esc_html_e( 'Pocket', 'buddypress-share' ); ?></option>
											<option value="bp_share_email"><?php esc_html_e( 'Email', 'buddypress-share' ); ?></option>
											<option value="bp_share_whatsapp"><?php esc_html_e( 'Whatsapp', 'buddypress-share' ); ?></option>
											<option value="bp_copy_activity"><?php esc_html_e( 'Copy', 'buddypress-share' ); ?></option>
										</select>
									</div>
									<p class="error_service error_service_selector"><?php esc_html_e( 'This field is required!', 'buddypress-share' ); ?></p>
								</td>
							</tr>
							<tr>
								<td class="plugin-title" style="width: 190px;">
									<strong style="margin-top: 3px; float: left;"><?php esc_html_e( 'Font Awesome Icon Class', 'buddypress-share' ); ?></strong><span class="bp_share_req">*</span></td>
								<td class="column-faw-icon desc">
									<div class="plugin-faw-icon">
										<input class="faw_class_input" name="faw_class_input" type="text">
										<p class="error_service error_service_faw-icon"><?php esc_html_e( 'This field is required!', 'buddypress-share' ); ?></p>
									</div>
								</td>
							</tr>
							<tr>
								<td class="plugin-title" style="width: 190px;">
									<strong style="margin-top: 3px; float: left;"><?php esc_html_e( 'Description', 'buddypress-share' ); ?></strong><span class="bp_share_req">*</span></td>
								<td class="column-description desc">
									<div class="plugin-description">
										<textarea name="bp_share_description" class="bp_share_description"></textarea>
										<p class="error_service error_service_description"><?php esc_html_e( 'This field is required!', 'buddypress-share' ); ?></p>
									</div>
								</td>
							</tr>
							<tr>
								<td class="plugin-title" style="width: 190px;">
								</td>
								<td class="add_services_btn_td">
									<input type="button" class="add_services_btn" name="add_services_btn" value="<?php esc_html_e( 'Add Services', 'buddypress-share' ); ?>">
									<p class="spint_action"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></p>
								</td>
							</tr>
						</tbody>
					</table><!--END: add_share_services table-->
					<br/>
					<table cellspacing="0" class="widefat fixed plugins">
						<thead>
							<tr>
								<th class="manage-column column-name" id="name" scope="col"
									style="width: 190px;"><?php esc_html_e( 'Social Sites', 'buddypress-share' ); ?></th>
								<th class="manage-column column-description" id=
									"description" scope="col"><?php esc_html_e( 'Description', 'buddypress-share' ); ?></th>
								<th class="manage-column column-services-action" id="services-action" scope="col"><?php esc_html_e( 'Action', 'buddypress-share' ); ?></th>
							</tr>
						</thead>
						<tbody id="the-list" class="bp_share_social_list">
						<?php
						$social_options = get_site_option( 'bp_share_services' );
						if ( ! empty( $social_options ) ) {
							foreach ( $social_options as $service_key => $social_option ) {
								?>
								<tr class="bp-share-services-row" id="<?php echo 'tr_' . esc_attr( $service_key ); ?>">
									<th scope="row" id="bp_share_chb" class="bp-share-td">
										<input type="checkbox" name="<?php echo 'chb_' . esc_attr( $service_key ); ?>" value="1" <?php	echo ( 1 === $social_options[ $service_key ][ 'chb_' . $service_key ] ) ? 'checked="checked"' : ''; ?>
										/>
									</th>
									<td class="bp-share-title bp-share-td" style="width: 190px;">
										<strong style="margin-top: 3px;"><i class="<?php echo esc_attr( $social_option['service_icon'] ); ?> fa-lg"></i> <?php echo esc_html( $social_option['service_name'] ); ?></strong>
										<div class="row-actions-visible"></div>
									</td>
									<td class="bp-share-column-description desc bp-share-td">
										<div class="plugin-description">
											<p><?php echo esc_html( $social_option['service_description'] ); ?></p>
										</div>
										<div class="active second plugin-version-author-uri">
										</div>
									</td>
								<?php if ( 'bp_copy_activity' !== $service_key ) : ?>
									<td class="service_delete bp-share-td"><p class="service_delete_icon" data-bind="<?php echo esc_attr( $service_key ); ?>"><i class="fa fa-times"></i></p></td>
								<?php endif; ?>
								</tr>
								<?php
							}
						}
						?>
						</tbody>
					</table><!--END:social options table-->
					<div class="bp-share-services-extra">
							<?php
							do_settings_sections( 'bp_share_services_extra' );
							echo '</div>';
							?>
					</div>
				<!--save the settings-->
				<input type="hidden" name="action" value="update" />
							<?php
							$social_options = get_site_option( 'bp_share_services' );
							if ( ! empty( $social_options ) ) {
								$social_key_string = '';
								foreach ( $social_options as $service_key => $social_option ) {
									if ( count( $social_options ) != 1 ) {
										$social_key_string .= $service_key . ',';
									} else {
										$social_key_string = $service_key;
									}
								}
								if ( count( $social_options ) != 1 ) {
									$social_key_string = rtrim( $social_key_string, ', ' );
								}
								?>
					<input type="hidden" name="page_options" value="<?php echo esc_attr( $social_key_string ); ?>" />
								<?php
							}
							?>
				<p class="submit">
					<input type="submit" class="button-primary bp_share_option_save" value="<?php esc_html_e( 'Save Changes', 'buddypress-share' ); ?>" />
				</p>
			</form>
				<?php do_action( 'bp_share_add_services_options', $arg1 = '', $arg2 = '' ); ?>
		</div>
		<?php
	}


	/**
	 * BP share services ajax.
	 *
	 * @access public
	 * @author   Wbcom Designs
	 * @since    1.0.0
	 */
	public function bp_share_chb_services_ajax() {
		if ( ! empty( $_POST ) && check_admin_referer( 'bp_share_nonce', 'nonce' ) && current_user_can( 'manage_options' ) ) {
			$option_name      = 'bp_share_services';
			$active_services  = isset( $_POST['active_chb_array'] ) ? map_deep( wp_unslash( $_POST['active_chb_array'] ), 'sanitize_text_field' ) : array();
			$extras_options   = isset( $_POST['active_chb_extras'] ) ? map_deep( wp_unslash( $_POST['active_chb_extras'] ), 'sanitize_text_field' ) : array();
			$extra_option_new = array();

			if ( ! empty( $extras_options ) ) {
				if ( in_array( 'bp_share_services_open', $extras_options ) ) {
					$extra_option_new['bp_share_services_open'] = 1;
				}
			} else {
				$extra_option_new['bp_share_services_open'] = 0;
			}
			update_site_option( 'bp_share_services_extra', $extra_option_new );
			$services = get_site_option( 'bp_share_services' );
			if ( ! empty( $services ) ) {
				if ( ! empty( $active_services ) ) {
					foreach ( $services as $service_key => $value ) {
						if ( in_array( 'chb_' . $service_key, $active_services ) ) {
							$services[ $service_key ][ 'chb_' . $service_key ] = 1;
							update_site_option( $option_name, $services );
						} else {
							$services[ $service_key ][ 'chb_' . $service_key ] = 0;
							update_site_option( $option_name, $services );
						}
					}
				} else {
					foreach ( $services as $service_key => $value ) {
						$services[ $service_key ][ 'chb_' . $service_key ] = 0;
						update_site_option( $option_name, $services );
					}
				}
			}
		}
		die();
	}
}
