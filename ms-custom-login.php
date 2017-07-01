<?php
/**
 * Plugin Name: MS Custom Login
 * Plugin URI: https://wordpress.org/plugins/ms-custom-login/
 * Description: Customize login page of your WordPress with images, colors and more.
 * Text Domain: ms-custom-login
 * Domain Path: /languages
 * Version: 1.0
 * Author: Mignon Style
 * Author URI: http://mignonstyle.com
 * License: GNU General Public License v2.0
 *
 * @package MS Custom Login
 */

/**
 * Copyright 2014 Mignon Style (email : mignonxstyle@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * ------------------------------------------------------------
 * 0.0 - define
 * ------------------------------------------------------------
 */
define( 'MS_CUSTOM_LOGIN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MS_CUSTOM_LOGIN_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'MS_CUSTOM_LOGIN_DOMAIN', dirname( plugin_basename( __FILE__ ) ) );
define( 'MS_CUSTOM_LOGIN_TEXTDOMAIN', 'ms-custom-login' );
define( 'MS_CUSTOM_LOGIN_TITLE', __( 'MS Custom Login', MS_CUSTOM_LOGIN_TEXTDOMAIN ) );

/* ----------------------------------------------
 * 0.0.1 - I18n of Plugin Description
 * There is no sense to return
 * --------------------------------------------*/
function ms_custom_login_plugin_description() {
	$plugin_description = __( 'Customize login page of your WordPress with images, colors and more.', 'ms-custom-login' );
	return $plugin_description;
}

/**
 * ------------------------------------------------------------
 * 0.0.2 - plugin setting links
 * ------------------------------------------------------------
 */
function ms_custom_login_action_links( $links, $file ) {
	if ( plugin_basename( __FILE__ ) == $file ) {
		$settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'options-general.php?page=ms-custom-login' ), __( 'Settings' , 'ms-custom-login' ) );
		array_unshift( $links, $settings_link );
	}

	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ms_custom_login_action_links', 10, 2 );

/**
 * ------------------------------------------------------------
 * 0.0.3 - Initialize plugin links
 * ------------------------------------------------------------
 */
function ms_custom_login_plugin_row_meta( $links, $file ) {

	if ( plugin_basename( __FILE__ ) == $file ) {
		$new_links = array(
			'home'    => array(
				'url'   => 'https://wordpress.org/plugins/ms-custom-login/',
				'label' => __( 'Home', 'ms-custom-login' ),
			),
			'support' => array(
				'url'   => 'https://wordpress.org/support/plugin/ms-custom-login',
				'label' => __( 'Support', 'ms-custom-login' ),
			),
			'reviews' => array(
				'url'   => 'https://wordpress.org/support/view/plugin-reviews/ms-custom-login',
				'label' => __( 'Reviews', 'ms-custom-login' ),
			),
			'donate'  => array(
				'url'   => 'https://www.amazon.co.jp/registry/wishlist/34JPDJL2WWR3O',
				'label' => __( 'Donate', 'ms-custom-login' ),
			),
		);

		foreach( $new_links as $key => $value ) {
			$links[$key] = '<a href="' . esc_url( $value['url'] ) . '" target="_blank">' . esc_html( $value['label'] ) . '</a>';
		}
	}

	return $links;
}
add_filter( 'plugin_row_meta', 'ms_custom_login_plugin_row_meta', 10, 2 );

/**
 * ------------------------------------------------------------
 * 0.1 - Load plugin textdomain
 * ------------------------------------------------------------
 */
function ms_custom_login_load_textdomain() {
	load_plugin_textdomain( 'ms-custom-login', false, MS_CUSTOM_LOGIN_DOMAIN . '/languages' );
}
add_action( 'plugins_loaded', 'ms_custom_login_load_textdomain' );

/**
 * ------------------------------------------------------------
 * 0.2 - Read css file
 * ------------------------------------------------------------
 */
function ms_custom_login_admin_enqueue_style( $hook ) {
	if ( 'settings_page_ms-custom-login' == $hook ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'ms_custom_login_style', MS_CUSTOM_LOGIN_PLUGIN_URL . 'css/ms-custom-login.css', array(), null );

		// CodeMirror.
		wp_enqueue_style( 'mcl-codemirror', MS_CUSTOM_LOGIN_PLUGIN_URL . 'inc/codemirror/lib/codemirror.css', array(), null );
		wp_enqueue_script( 'mcl-codemirror-js', MS_CUSTOM_LOGIN_PLUGIN_URL . 'inc/codemirror/lib/codemirror.js', array(), false, true );
		wp_enqueue_script( 'mcl-codemirror-css-js', MS_CUSTOM_LOGIN_PLUGIN_URL . 'inc/codemirror/mode/css/css.js', array( 'mcl-codemirror-js' ), false, true );
	}
}
add_action( 'admin_enqueue_scripts', 'ms_custom_login_admin_enqueue_style' );

/**
 * ------------------------------------------------------------
 * 0.3 - Read javascript file
 * ------------------------------------------------------------
 */
function ms_custom_login_admin_print_scripts() {
	wp_enqueue_script( 'ms_custom_login_cookie', MS_CUSTOM_LOGIN_PLUGIN_URL . 'js/jquery.cookie.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'ms_custom_login_js', MS_CUSTOM_LOGIN_PLUGIN_URL . 'js/ms-custom-login.js', array( 'jquery', 'ms_custom_login_cookie', 'jquery-ui-tabs', 'mcl-codemirror-js' ), false, true );

	// color picker.
	wp_enqueue_script( 'ms_custom_login_colorpicker_js', MS_CUSTOM_LOGIN_PLUGIN_URL . 'js/color-picer.js', array( 'wp-color-picker' ), false, true );

	// media uploader.
	if ( function_exists( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
		wp_register_script( 'ms_custom_login_media_uploader_js', MS_CUSTOM_LOGIN_PLUGIN_URL . 'js/media-uploader.js', array( 'jquery' ), false, true );
		$translation_array = array(
			'title'  => __( 'Select Image', 'ms-custom-login' ),
			'button' => __( 'Set up Image', 'ms-custom-login' ),
		);
		wp_localize_script( 'ms_custom_login_media_uploader_js', 'option_media_text', $translation_array );
		wp_enqueue_script( 'ms_custom_login_media_uploader_js' );
	}
}

/**
 * ------------------------------------------------------------
 * 1.0 - Register options setting page
 * ------------------------------------------------------------
 */
function ms_custom_login_add_page() {
	$page_hook = add_options_page( MS_CUSTOM_LOGIN_TITLE, MS_CUSTOM_LOGIN_TITLE, 'manage_options', MS_CUSTOM_LOGIN_DOMAIN, 'ms_custom_login_options' );

	// Read the script only to options page.
	add_action( 'admin_print_scripts-'.$page_hook, 'ms_custom_login_admin_print_scripts' );
}
add_action( 'admin_menu', 'ms_custom_login_add_page' );

/**
 * ------------------------------------------------------------
 * 2.0 - To register the setting options
 * ------------------------------------------------------------
 */
function ms_custom_login_options_init() {
	register_setting( 'ms_custom_login_options', 'ms_custom_login_options', 'ms_custom_login_validate' );
	include_once( MS_CUSTOM_LOGIN_PLUGIN_PATH . 'inc/login-register.php' );
	register_uninstall_hook( __FILE__, 'ms_custom_login_uninstall' );
}
add_action( 'admin_init', 'ms_custom_login_options_init' );

/**
 * ------------------------------------------------------------
 * 2.1 - uninstall setting options
 * ------------------------------------------------------------
 */
function ms_custom_login_uninstall() {
	$option_name = 'ms_custom_login_options';
	delete_option( $option_name );
}

/**
 * ------------------------------------------------------------
 * 3.0 - Create an array of default options
 * ------------------------------------------------------------
 */
function ms_custom_login_default_options() {
	$default_options = array(
		// Default.
		'mcl_default' => '',

		// Options.
		'mcl_option_chocolat' => 0,

		// Page Setting.
		'mcl_page_bg_color'    => '#f1f1f1',
		'mcl_page_bg_url'      => '',
		'mcl_bg_x_select'      => 'left',
		'mcl_bg_y_select'      => 'top',
		'mcl_bg_repeat_select' => 'repeat',
		'mcl_bg_attach_select' => 'scroll',
		'mcl_bg_size_select'   => 'auto',
		'mcl_bg_size_value'    => '',

		'mcl_text_color'       => '#777777',
		'mcl_link_color'       => '#999999',
		'mcl_link_color_hover' => '#2ea2cc',

		// Logo Setting.
		'mcl_show_logo'       => 1,
		'mcl_logo_link_attr'  => 0,
		'mcl_logo_link_url'   => '',
		'mcl_logo_link_title' => '',
		'mcl_show_logo_img'   => 1,
		'mcl_logo_url'        => '',
		'mcl_show_logo_text'  => 0,
		'mcl_text_size'       =>  20,
		'mcl_logo_text_color' => '#999999',
		'mcl_logo_text_hover' => '#2ea2cc',
		'mcl_text_family'     =>  '',
		'mcl_text_webfont'    =>  '',

		// Form Setting.
		'mcl_form_bg_color'         => '#ffffff',
		'mcl_form_bg_alpha'         => 1,
		'mcl_form_bg_url'           => '',
		'mcl_form_bg_x_select'      => 'left',
		'mcl_form_bg_y_select'      => 'top',
		'mcl_form_bg_repeat_select' => 'repeat',
		'mcl_form_radius'           => 0,
		'mcl_form_boxshadow_radio'  => 'boxshadow_true',
		'mcl_form_x_select'         => 'center',
		'mcl_form_y_select'         => 'top',
		'mcl_form_x_pos'            => 5,
		'mcl_form_y_pos'            => 0,

		// Button Setting.
		'mcl_btn_text_color'   => '#ffffff',
		'mcl_btn_border_color' => '#0074a2',
		'mcl_btn_bg_color'     => '#2ea2cc',
		'mcl_btn_bg_hover'     => '#1e8cbe',

		// Links Setting.
		'mcl_hide_nav'      => 0,
		'mcl_hide_backlink' => 0,

		//Custom CSS.
		'mcl_custom_css' => '',
	);
	return $default_options;
}

/**
 * ------------------------------------------------------------
 * 3.1 - Create an array of options - form boxshadow
 * ------------------------------------------------------------
 */
function ms_custom_login_form_boxshadow() {
	$form_boxshadow = array(
		'boxshadow_true' => array(
			'value'  => 'boxshadow_true',
			'id'     => 'boxshadow',
			'label'  => __( 'Add drop shadow', 'ms-custom-login' ),
		),
		'boxshadow_false' => array(
			'value'  => 'boxshadow_false',
			'id'     => 'no-boxshadow',
			'label'  => __( 'Remove drop shadow', 'ms-custom-login' ),
		),
	);
	return $form_boxshadow;
}

/**
 * ------------------------------------------------------------
 * 3.2.1 - Create an array of options - background position x
 * ------------------------------------------------------------
 */
function ms_custom_login_bg_position_x() {
	$bg_position_x = array(
		'left' => array(
			'value' => 'left',
			'label' => __( 'Left', 'ms-custom-login' ),
		),
		'center' => array(
			'value' => 'center',
			'label' => __( 'Center', 'ms-custom-login' ),
		),
		'right' => array(
			'value' => 'right',
			'label' => __( 'Right', 'ms-custom-login' ),
		),
	);
	return $bg_position_x;
}

/**
 * ------------------------------------------------------------
 * 3.2.2 - Create an array of options - background position y
 * ------------------------------------------------------------
 */
function ms_custom_login_bg_position_y() {
	$bg_position_y = array(
		'top' => array(
			'value' => 'top',
			'label' => __( 'Top', 'ms-custom-login' ),
		),
		'center' => array(
			'value' => 'center',
			'label' => __( 'Center', 'ms-custom-login' ),
		),
		'bottom' => array(
			'value' => 'bottom',
			'label' => __( 'Bottom', 'ms-custom-login' ),
		),
	);
	return $bg_position_y;
}

/**
 * ------------------------------------------------------------
 * 3.3.3 - Create an array of options - background repeat
 * ------------------------------------------------------------
 */
function ms_custom_login_bg_repeat() {
	$bg_repeat = array(
		'repeat' => array(
			'value' => 'repeat',
			'label' => __( 'Tile', 'ms-custom-login' ),
		),
		'repeat-x' => array(
			'value' => 'repeat-x',
			'label' => __( 'Tile Horizontally', 'ms-custom-login' ),
		),
		'repeat-y' => array(
			'value' => 'repeat-y',
			'label' => __( 'Tile Vertically', 'ms-custom-login' ),
		),
		'no-repeat' => array(
			'value' => 'no-repeat',
			'label' => __( 'No Repeat', 'ms-custom-login' ),
		),
	);
	return $bg_repeat;
}

/**
 * ------------------------------------------------------------
 * 3.3.4 - Create an array of options - background attachment
 * ------------------------------------------------------------
 */
function ms_custom_login_bg_attach() {
	$bg_attach = array(
		'scroll' => array(
			'value' => 'scroll',
			'label' => __( 'Scroll', 'ms-custom-login' ),
		),
		'fixed' => array(
			'value' => 'fixed',
			'label' => __( 'Fixed', 'ms-custom-login' ),
		),
	);
	return $bg_attach;
}

/**
 * ------------------------------------------------------------
 * 3.3.5 - Create an array of options - background size
 * ------------------------------------------------------------
 */
function ms_custom_login_bg_size() {
	$bg_size = array(
		'auto' => array(
			'value' => 'auto',
			'label' => __( 'Auto', 'ms-custom-login' ),
		),
		'contain' => array(
			'value' => 'contain',
			'label' => __( 'Contain', 'ms-custom-login' ),
		),
		'cover' => array(
			'value' => 'cover',
			'label' => __( 'Cover', 'ms-custom-login' ),
		),
	);
	return $bg_size;
}

/**
 * ------------------------------------------------------------
 * 3.3.6 - Create an array of options - form background alpha
 * ------------------------------------------------------------
 */
function ms_custom_login_bg_alpha() {
	$bg_alpha = array(
		'1' => array(
			'value' => '1',
			'label' => __( '1', 'ms-custom-login' ),
		),
		'0.9' => array(
			'value' => '0.9',
			'label' => __( '0.9', 'ms-custom-login' ),
		),
		'0.8' => array(
			'value' => '0.8',
			'label' => __( '0.8', 'ms-custom-login' ),
		),
		'0.7' => array(
			'value' => '0.7',
			'label' => __( '0.7', 'ms-custom-login' ),
		),
		'0.6' => array(
			'value' => '0.6',
			'label' => __( '0.6', 'ms-custom-login' ),
		),
		'0.5' => array(
			'value' => '0.5',
			'label' => __( '0.5', 'ms-custom-login' ),
		),
		'0.4' => array(
			'value' => '0.4',
			'label' => __( '0.4', 'ms-custom-login' ),
		),
		'0.3' => array(
			'value' => '0.3',
			'label' => __( '0.3', 'ms-custom-login' ),
		),
		'0.2' => array(
			'value' => '0.2',
			'label' => __( '0.2', 'ms-custom-login' ),
		),
		'0.1' => array(
			'value' => '0.1',
			'label' => __( '0.1', 'ms-custom-login' ),
		),
		'0' => array(
			'value' => '0',
			'label' => __( '0', 'ms-custom-login' ),
		),
	);
	return $bg_alpha;
}

/**
 * ------------------------------------------------------------
 * 3.3.7 - Tabs Title
 * ------------------------------------------------------------
 */
function ms_custom_login_tab_title() {
	$tab_title = array(
		'settings' => array(
			'id'    => 'mcl-settings',
			'title' => __( 'Login Page Settings', 'ms-custom-login' ),
		),
		'css' => array(
			'id'    => 'mcl-css',
			'title' => __( 'Custom CSS', 'ms-custom-login' ),
		),
	);

	if ( strcmp( get_template(), 'chocolat' ) == 0 ) {
		$tab_option = array(
			'option' => array(
				'id'    => 'mcl-option',
				'title' => __( 'Option', 'ms-custom-login' ),
			),
		);
		$tab_title = array_merge( $tab_title, $tab_option );
	}

	return $tab_title;
}

/**
 * ------------------------------------------------------------
 * 4.0 - Get the value options
 * ------------------------------------------------------------
 */
function ms_custom_login_get_option() {
	return get_option( 'ms_custom_login_options', ms_custom_login_default_options() );
}

/**
 * ------------------------------------------------------------
 * 5.0 - Creating options page
 * ------------------------------------------------------------
 */
function ms_custom_login_options() {
	$default_option = ms_custom_login_default_options();
	$options = ms_custom_login_get_option();
	$tab_title = ms_custom_login_tab_title();

	if ( !current_user_can( 'manage_options' ) )
		wp_die( _e( 'You do not have sufficient permissions to access this page.', 'ms-custom-login' ) );
	?>
	<div id="ms-custom-login" class="wrap">
		<h2><?php _e( MS_CUSTOM_LOGIN_TITLE ); ?></h2>

		<form method="post" action="options.php" enctype="multipart/form-data">
		<?php settings_fields( 'ms_custom_login_options' );
			if ( ! is_multisite() && is_user_logged_in() ) {
				add_thickbox();
			}

			$option_name = 'mcl_default';
			$option_type = 'hidden';
			ms_custom_login_textfield( $options, $option_name, '', $option_type );
		?>

	<div id="tabset"><?php /* tabset */ ?>
		<ul class="tabs clearfix"><?php /* tabs */ ?>
		<?php if ( is_array( $tab_title ) ) :
			foreach( $tab_title as $tabs ) :
				echo '<li><h3 class="title"><a href="#'.$tabs['id'].'" id="tab-'.$tabs['id'].'">'.$tabs['title'].'</a></h3></li>'."\n";
		endforeach; endif; ?>
		</ul>

		<div id="<?php echo $tab_title['settings']['id']; ?>" class="panel"><?php /* panel */ ?>
			<div id="page-setting" class="option-box option-check"><?php /* Login Page Setting */ ?>
				<h3><?php _e( 'Login Page Setting', 'ms-custom-login' ); ?></h3>
				<div class="inside">
					<table class="form-table">
						<tr><?php /* Page Background Color */ ?>
							<th scope="row"><?php printf( __( '%s Background Color', 'ms-custom-login' ), __( 'Page', 'ms-custom-login' ) ); ?></th>
							<td><?php
								$option_name = 'mcl_page_bg_color';
								$default_color = $default_option['mcl_page_bg_color'];
								ms_custom_login_color_picker( $options, $option_name, $default_color );
							?></td>
						</tr>

						<tr><?php /* Page Background Image */ ?>
							<th scope="row"><?php printf( __( '%s Background Image', 'ms-custom-login' ), __( 'Page', 'ms-custom-login' ) ); ?></th>
							<td><fieldset><?php
								$option_id = 'page-bg';
								$option_name = 'mcl_page_bg_url';
								$option_desc = __( 'The image you set will be used for the backgrounds of the login page.', 'ms-custom-login' );
								$option_desc2 = sprintf( __( 'Recommendation: %s.', 'ms-custom-login' ), __( 'png, jpg or gif', 'ms-custom-login' ) );

								ms_custom_login_media_uploader( $options, 'ms-custom-login', $option_id, $option_name, $option_desc, $option_desc2 );
							?></fieldset></td>
						</tr>

						<tr class="<?php echo esc_attr( ms_custom_login_upload_children( $options['mcl_page_bg_url'] ) ); ?>"><?php /* Page Background Position */ ?>
							<th scope="row"><?php printf( __( '%s Background Position', 'ms-custom-login' ), __( 'Page', 'ms-custom-login' ) ); ?></th>
							<td><table class="nest"><tr>
								<td><p><?php _e( 'Horizontal direction', 'ms-custom-login' ); ?></p><?php
									$option_array = ms_custom_login_bg_position_x();
									$option_name = 'mcl_bg_x_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
								<td><p><?php _e( 'Vertical direction', 'ms-custom-login' ); ?></p><?php
									$option_array = ms_custom_login_bg_position_y();
									$option_name = 'mcl_bg_y_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
								<td><p><?php _e( 'Repeat', 'ms-custom-login' ); ?></p><?php
									$option_array = ms_custom_login_bg_repeat();
									$option_name = 'mcl_bg_repeat_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
								<td><p><?php _e( 'Attachment', 'ms-custom-login' ); ?></p><?php
									$option_array = ms_custom_login_bg_attach();
									$option_name = 'mcl_bg_attach_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
							</tr></table></td>
						</tr>

						<tr class="<?php echo esc_attr( ms_custom_login_upload_children( $options['mcl_page_bg_url'] ) ); ?>"><?php /* Page Background Size */ ?>
							<th scope="row"><?php printf( __( '%s Background Size', 'ms-custom-login' ), __( 'Page', 'ms-custom-login' ) ); ?></th>
							<td><table class="nest"><tr>
								<td colspan="2"><p><?php printf( __( 'Please Select a %s background size or enter a value.', 'ms-custom-login' ), __( 'Page', 'ms-custom-login' ) ); ?></p></td>
							</tr><tr>
								<td><?php
									$option_array = ms_custom_login_bg_size();
									$option_name = 'mcl_bg_size_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
								<td><?php
									$option_name = 'mcl_bg_size_value';
									$option_type = 'text';
									$option_class = 'regular-text';
									$placeholder = __( 'Enter a value', 'ms-custom-login' );
									ms_custom_login_textfield( $options, $option_name, '', $option_type, $option_class, '', $placeholder );
								?></td>
							</tr></table></td>
						</tr>

						<tr><?php /* Page Text Color */ ?>
							<th scope="row"><?php printf( __( '%s Text Color', 'ms-custom-login' ), __( 'Page', 'ms-custom-login' ) ); ?></th>
							<td><table class="nest"><tr>
								<td><p><?php _e( 'Text color', 'ms-custom-login' ); ?></p><?php
									$option_name = 'mcl_text_color';
									$default_color = $default_option['mcl_text_color'];
									ms_custom_login_color_picker( $options, $option_name, $default_color );
								?></td>
								<td><p><?php _e( 'Link color', 'ms-custom-login' ); ?></p><?php
									$option_name = 'mcl_link_color';
									$default_color = $default_option['mcl_link_color'];
									ms_custom_login_color_picker( $options, $option_name, $default_color );
								?></td>
								<td><p><?php _e( 'Hover color', 'ms-custom-login' ); ?></p><?php
									$option_name = 'mcl_link_color_hover';
									$default_color = $default_option['mcl_link_color_hover'];
									ms_custom_login_color_picker( $options, $option_name, $default_color );
								?></td>
							</tr></table></td>
						</tr>
					</table>
				</div>
			</div><!-- /#page-setting -->

			<div id="logo-setting" class="option-box option-check"><?php /* Login Page Logo Setting */ ?>
				<h3><?php _e( 'Login Page Logo Setting', 'ms-custom-login' ); ?></h3>
				<div class="inside">
					<table class="form-table">
						<tr id="show-logo" class="target"><?php /* Logo Display */ ?>
							<th scope="row"><?php _e( 'Logo Display', 'ms-custom-login' ); ?></th>
							<td><fieldset><?php
								$option_name = 'mcl_show_logo';
								$option_text = __( 'Display a logo.', 'ms-custom-login' );
								ms_custom_login_checkbox( $options, $option_name, $option_text );
							?></fieldset></td>
						</tr>
					</table>

					<div id="show-logo-attr" class="option-check hidebox show-logo">
					<table class="form-table">
						<tr><?php /* Logo Link Attribute */ ?>
							<th scope="row"><?php _e( 'Link Attribute', 'ms-custom-login' ); ?></th>
							<td id="logo-link-attr" class="target"><fieldset><?php
								$option_name = 'mcl_logo_link_attr';
								$option_text = __( 'Use site name and URL for the logo.', 'ms-custom-login' );
								ms_custom_login_checkbox( $options, $option_name, $option_text );
							?>
							<div class="hidebox logo-link-attr">
							<p><?php _e( 'Use the URL and title of the Web site to logo of link attributes.', 'ms-custom-login' ); ?><br /><?php _e( 'Please enter if you want to change the URL and title of the link attribute.', 'ms-custom-login' ); ?></p><br />
							<p><?php _e( 'URL of the link attributes', 'ms-custom-login' ); ?></p><?php
								$option_name = 'mcl_logo_link_url';
								$option_type = 'url';
								ms_custom_login_textfield( $options, $option_name, '', $option_type );
							?><p><?php _e( 'Title of the link attributes', 'ms-custom-login' ); ?></p><?php
							$option_name = 'mcl_logo_link_title';
								ms_custom_login_textfield( $options, $option_name );
							?></div></fieldset></td>
						</tr>

						<tr id="show-logo-img" class="target"><?php /* Logo Image Display */ ?>
							<th scope="row"><?php _e( 'Logo Image Display', 'ms-custom-login' ); ?></th>
							<td><fieldset><?php
								$option_name = 'mcl_show_logo_img';
								$option_text = __( 'Display the logo image.', 'ms-custom-login' );
								ms_custom_login_checkbox( $options, $option_name, $option_text );
							?></fieldset></td>
						</tr>

						<tr class="hidebox show-logo-img"><?php /* Logo Image */ ?>
							<th scope="row"><?php _e( 'Logo Image', 'ms-custom-login' ); ?></th>
							<td><fieldset><?php
								$option_id = 'mcl-logo-img';
								$option_name = 'mcl_logo_url';
								$option_desc = __( 'The image you set will be used for the logo of the login page.', 'ms-custom-login' );
								$option_desc2 = sprintf( __( 'Recommendation: %s.', 'ms-custom-login' ), __( 'a png, jpg or gif file of width 320px', 'ms-custom-login' ) );
								ms_custom_login_media_uploader( $options, 'ms-custom-login', $option_id, $option_name, $option_desc, $option_desc2 );
								?></fieldset></td>
						</tr>
					</table>
					</div>

					<div id="show-text" class="option-check hidebox show-logo">
					<table class="form-table">
						<tr id="show-logo-text" class="target"><?php /* Logo Text */ ?>
							<th scope="row"><?php _e( 'Logo Text', 'ms-custom-login' ); ?></th>
							<td><fieldset><?php
								$option_name = 'mcl_show_logo_text';
								$option_text = __( 'Display the logo text.', 'ms-custom-login' );
								ms_custom_login_checkbox( $options, $option_name, $option_text );
							?></fieldset></td>
						</tr>

						<tr class="hidebox show-logo-text"><?php /* Logo Text Font Size */ ?>
							<th scope="row"><?php _e( 'Font Size', 'ms-custom-login' ); ?></th>
							<td><fieldset>
								<p><?php _e( 'Set font size of the logo. The default is 20px.', 'ms-custom-login' ); ?></p><?php
								$option_name = 'mcl_text_size';
								$option_type = 'number';
								$option_class = 'small-text';
								$label_after = __( 'px', 'ms-custom-login' );
								ms_custom_login_textfield( $options, $option_name, '', $option_type, $option_class, $label_after );
								?></fieldset></td>
						</tr>

						<tr class="hidebox show-logo-text"><?php /* Logo Text Color */ ?>
							<th scope="row"><?php _e( 'Text Color', 'ms-custom-login' ); ?></th>
							<td><table class="nest"><tr>
								<td><p><?php _e( 'Text color', 'ms-custom-login' ); ?></p><?php
									$option_name = 'mcl_logo_text_color';
									$default_color = $default_option['mcl_logo_text_color'];
									ms_custom_login_color_picker( $options, $option_name, $default_color );
								?></td>
								<td><p><?php _e( 'Hover color', 'ms-custom-login' ); ?></p><?php
									$option_name = 'mcl_logo_text_hover';
									$default_color = $default_option['mcl_logo_text_hover'];
									ms_custom_login_color_picker( $options, $option_name, $default_color );
								?></td>
							</tr></table></td>
						</tr>

						<tr class="hidebox show-logo-text"><?php /* Logo Text Font Family */ ?>
							<th scope="row"><?php _e( 'Font Family', 'ms-custom-login' ); ?></th>
							<td><?php
								$option_name = 'mcl_text_family';
								$option_cols = '50';
								$option_rows = '2';
								ms_custom_login_textarea( $options, $option_name, $option_cols, $option_rows );
							?><p class="example">'Josefin Sans', sans-serif</p>
							</td>
						</tr>

						<tr class="hidebox show-logo-text"><?php /* Logo Text Web Font */ ?>
							<th scope="row"><?php _e( 'Web Font', 'ms-custom-login' ); ?></th>
							<td><?php
								$option_name = 'mcl_text_webfont';
								$option_cols = '50';
								$option_rows = '2';
								ms_custom_login_textarea( $options, $option_name, $option_cols, $option_rows );
							?><p class="example">@import url(http://fonts.googleapis.com/css?family=Josefin+Sans);</p>
							</td>
						</tr>
					</table>
					</div>
				</div>
			</div><!-- /#logo-setting -->

			<div id="form-setting" class="option-box option-check"><?php /* Login Form Setting */ ?>
				<h3><?php _e( 'Login Form Setting', 'ms-custom-login' ); ?></h3>
				<div class="inside">
					<table class="form-table">
						<tr><?php /* Form Background Color */ ?>
							<th scope="row"><?php printf( __( '%s Background Color', 'ms-custom-login' ), __( 'Form', 'ms-custom-login' ) ); ?></th>
							<td><table class="nest"><tr>
								<td colspan="2"><p><?php _e( 'Select the transparency if you want to make the color of the background transparent. The default is Opacity.', 'ms-custom-login' ); ?></p></td>
								</tr><tr>
								<td><p><?php _e( 'Background color', 'ms-custom-login' ); ?></p><?php
									$option_name = 'mcl_form_bg_color';
									$default_color = $default_option['mcl_form_bg_color'];
									ms_custom_login_color_picker( $options, $option_name, $default_color );
								?></td>
								<td><p><?php echo __( 'Background Transparency', 'ms-custom-login' ) . ' ' . __( '( Transparency : 0 - Opacity : 1 )', 'ms-custom-login' ) ?></p><?php
									$option_array = ms_custom_login_bg_alpha();
									$option_name = 'mcl_form_bg_alpha';
									ms_custom_login_select( $options, $option_array, $option_name );
									?></td>
							</tr></table></td>
						</tr>

						<tr><?php /* Form Background Image */ ?>
							<th scope="row"><?php printf( __( '%s Background Image', 'ms-custom-login' ), __( 'Form', 'ms-custom-login' ) ); ?></th>
							<td><fieldset><?php
								$option_id = 'mcl-form-bg';
								$option_name = 'mcl_form_bg_url';
								$option_desc = __( 'The image you set will be used as a background image of the login form.', 'ms-custom-login' );
								$option_desc2 = sprintf( __( 'Recommendation: %s.', 'ms-custom-login' ), __( 'a png, jpg or gif file of width 320px, height 275px', 'ms-custom-login' ) );
								ms_custom_login_media_uploader( $options, 'ms-custom-login', $option_id, $option_name, $option_desc, $option_desc2 );
							?></fieldset></td>
						</tr>

						<tr class="<?php echo esc_attr( ms_custom_login_upload_children( $options['mcl_form_bg_url'] ) ); ?>"><?php /* Form Background Position */ ?>
							<th scope="row"><?php printf( __( '%s Background Position', 'ms-custom-login' ), __( 'Form', 'ms-custom-login' ) ); ?></th>
							<td><table class="nest"><tr>
								<td><p><?php _e( 'Horizontal direction', 'ms-custom-login' ); ?></p><?php
									$option_array = ms_custom_login_bg_position_x();
									$option_name = 'mcl_form_bg_x_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
								<td><p><?php _e( 'Vertical direction', 'ms-custom-login' ); ?></p><?php
									$option_array = ms_custom_login_bg_position_y();
									$option_name = 'mcl_form_bg_y_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
								<td><p><?php _e( 'Repeat', 'ms-custom-login' ); ?></p><?php
									$option_array = ms_custom_login_bg_repeat();
									$option_name = 'mcl_form_bg_repeat_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
							</tr></table></td>
						</tr>

						<tr><?php /* Form Rounded Rectangle Size */ ?>
							<th scope="row"><?php printf( __( '%s Rounded Rectangle Size', 'ms-custom-login' ), __( 'Form', 'ms-custom-login' ) ); ?></th>
							<td><fieldset>
								<p><?php _e( 'Set the size of the rounded corners in px. The default is 0px.', 'ms-custom-login' ); ?></p><?php
								$option_name = 'mcl_form_radius';
								$option_type = 'number';
								$option_class = 'small-text';
								$label_after = __( 'px', 'ms-custom-login' );
								ms_custom_login_textfield( $options, $option_name, '', $option_type, $option_class, $label_after );
								?></fieldset></td>
						</tr>

						<tr><?php /* Form Box Shadow */ ?>
							<th scope="row"><?php printf( __( '%s Box Shadow', 'ms-custom-login' ), __( 'Form', 'ms-custom-login' ) ); ?></th>
							<td><fieldset><?php
								$option_array = ms_custom_login_form_boxshadow();
								$option_id = 'form-boxshadow';
								$option_name = 'mcl_form_boxshadow_radio';
								ms_custom_login_radio( $options, $option_array, $option_id, $option_name );
							?></fieldset></td>
						</tr>

						<tr><?php /* Form Box Shadow */ ?>
							<th scope="row"><?php printf( __( '%s Position', 'ms-custom-login' ), __( 'Form', 'ms-custom-login' ) ); ?></th>
							<td>
							<table class="nest inline">
								<tr>
									<td><p><?php _e( 'Horizontal direction', 'ms-custom-login' ); ?></p></td>
									<td><p><?php _e( 'Vertical direction', 'ms-custom-login' ); ?></p></td>
								</tr>
								<tr>
									<td><?php
									$option_array = ms_custom_login_bg_position_x();
									$option_name = 'mcl_form_x_select';

									$option_name_pos = 'mcl_form_x_pos';
									$option_label = '';
									$option_type = 'tnumber';
									$option_class = 'small-text';
									$label_after = __( '%', 'ms-custom-login' );

									if ( strtoupper( get_locale() ) == 'JA' ) {
										ms_custom_login_select( $options, $option_array, $option_name );
									} else {

										echo '<div class="form-x-pos">';
										ms_custom_login_textfield( $options, $option_name_pos, $option_label, $option_type, $option_class, $label_after );
										echo '</div>';
									}
									echo '<div class="form-x-pos">' . __( 'From', 'ms-custom-login' ) . '</div>';
									if ( strtoupper( get_locale() ) == 'JA' ) {
										echo '<div class="form-x-pos">';
										ms_custom_login_textfield( $options, $option_name_pos, $option_label, $option_type, $option_class, $label_after );
										echo '</div>';

									} else {
										ms_custom_login_select( $options, $option_array, $option_name );
									} ?></td>

									<td><?php
									$option_array = ms_custom_login_bg_position_y();
									$option_name = 'mcl_form_y_select';

									$option_name_pos = 'mcl_form_y_pos';
									$option_label = '';
									$option_type = 'tnumber';
									$option_class = 'small-text';
									$label_after = __( '%', 'ms-custom-login' );

									if ( strtoupper( get_locale() ) == 'JA' ) {
										ms_custom_login_select( $options, $option_array, $option_name );
									} else {
										echo '<div class="form-y-pos">';
										ms_custom_login_textfield( $options, $option_name_pos, $option_label, $option_type, $option_class, $label_after );
										echo '</div>';
									}
									echo '<div class="form-y-pos">' . __( 'From', 'ms-custom-login' ) . '</div>';
									if ( strtoupper( get_locale() ) == 'JA' ) {
										echo '<div class="form-y-pos">';
										ms_custom_login_textfield( $options, $option_name_pos, $option_label, $option_type, $option_class, $label_after );
										echo '</div>';
									} else {
										ms_custom_login_select( $options, $option_array, $option_name );
									} ?></td>
								</tr>
							</table></td>
						</tr>
					</table>
				</div>
			</div><!-- /#form-setting -->

			<div id="button-setting" class="option-box option-check"><?php /* Login Button Setting */ ?>
				<h3><?php _e( 'Login Button Setting', 'ms-custom-login' ); ?></h3>
				<div class="inside">
					<table class="form-table">
						<tr><?php /* Button Text Color */ ?>
							<th scope="row"><?php printf( __( '%s Text Color', 'ms-custom-login' ), __( 'Button', 'ms-custom-login' ) ); ?></th>
							<td><?php
								$option_name = 'mcl_btn_text_color';
								$default_color = $default_option['mcl_btn_text_color'];
								ms_custom_login_color_picker( $options, $option_name, $default_color );
							?></td>
						</tr>

						<tr><?php /* Button Border Color */ ?>
							<th scope="row"><?php printf( __( '%s Border Color', 'ms-custom-login' ), __( 'Button', 'ms-custom-login' ) ); ?></th>
							<td><?php
								$option_name = 'mcl_btn_border_color';
								$default_color = $default_option['mcl_btn_border_color'];
								ms_custom_login_color_picker( $options, $option_name, $default_color );
							?></td>
						</tr>

						<tr><?php /* Button Color */ ?>
							<th scope="row"><?php printf( __( '%s Color', 'ms-custom-login' ), __( 'Button', 'ms-custom-login' ) ); ?></th>
							<td><table class="nest"><tr>
								<td><p><?php _e( 'Background color', 'ms-custom-login' ); ?></p><?php
									$option_name = 'mcl_btn_bg_color';
									$default_color = $default_option['mcl_btn_bg_color'];
									ms_custom_login_color_picker( $options, $option_name, $default_color );
								?></td>
								<td><p><?php _e( 'Hover color', 'ms-custom-login' ); ?></p><?php
									$option_name = 'mcl_btn_bg_hover';
									$default_color = $default_option['mcl_btn_bg_hover'];
									ms_custom_login_color_picker( $options, $option_name, $default_color );
								?></td>
							</tr></table></td>
						</tr>
					</table>
				</div>
			</div><!-- /#button-setting -->

			<div id="links-setting" class="option-box option-check"><?php /* Links Setting */ ?>
				<h3><?php _e( 'Links Setting', 'ms-custom-login' ); ?></h3>
				<div class="inside">
					<table class="form-table">
						<tr><?php /* Password Link */ ?>
							<th scope="row"><?php _e( 'Password Link', 'ms-custom-login' ); ?></th>
							<td><fieldset><?php
								$option_name = 'mcl_hide_nav';
								$option_text = __( 'Hide the "Register" and "Lost password" links.', 'ms-custom-login' );
								ms_custom_login_checkbox( $options, $option_name, $option_text );
							?></fieldset></td>
						</tr>

						<tr><?php /* Back Link */ ?>
							<th scope="row"><?php _e( 'Back Link', 'ms-custom-login' ); ?></th>
							<td><fieldset><?php
								$option_name = 'mcl_hide_backlink';
								$option_text = __( 'Hide the "Back to blog" link.', 'ms-custom-login' );
								ms_custom_login_checkbox( $options, $option_name, $option_text );
							?></fieldset></td>
						</tr>
					</table>
				</div>
			</div><!-- /#links-setting -->
		</div><!-- /.panel -->

		<div id="<?php echo $tab_title['css']['id']; ?>" class="panel"><?php /* panel */ ?>
			<div id="custom-css-setting"><?php /* Custom CSS Setting */ ?>
				<h3><?php _e( 'Custom CSS', 'ms-custom-login' ); ?></h3>
				<table class="form-table">
					<tr>
						<td><?php
							$option_name ='mcl_custom_css';
							$option_cols = '50';
							$option_rows = '3';
							$content = isset( $options['mcl_custom_css'] ) && ! empty( $options['mcl_custom_css'] ) ? $options['mcl_custom_css'] : '/* ' . __( 'Enter Your Custom CSS Here', 'ms-custom-login' ) . ' */';
							ms_custom_login_textarea( $options, $option_name, $option_cols, $option_rows, $content );
						?></td>
					</tr>
				</table>
			</div><!-- /#custom-css-setting -->
		</div><!-- /.panel -->

		<?php if ( strcmp( get_template(), 'chocolat' ) == 0 ) : ?>
		<div id="<?php echo $tab_title['option']['id']; ?>" class="panel"><?php /* panel */ ?>
			<div id="login-option"><?php /* Options */ ?>
				<h3><?php _e( 'Options', 'ms-custom-login' ); ?></h3>
				<table class="form-table">
					<tr>
						<td class="center"><img src="<?php echo esc_url( plugins_url( 'inc/mcl-chocolat/img/login-chocolat.png', __FILE__ ) ); ?>" alt="<?php _e( 'Chocolat', 'ms-custom-login' ); ?>" class="w-150"><br /><?php _e( 'Chocolat', 'ms-custom-login' ); ?></td>
						<td><fieldset><?php
							$option_name = 'mcl_option_chocolat';
							$option_text = __( 'Use the theme "Chocolat" in the login page.', 'ms-custom-login' );
							ms_custom_login_checkbox( $options, $option_name, $option_text );
						?></fieldset></td>
					</tr>
				</table>
			</div><!-- /#login-option -->
		</div><!-- /.panel -->
		<?php endif; ?>

	</div><!-- /#tabset -->

	<div id="submit-button" class="clearfix">
		<?php submit_button( __( 'Save Changes', 'ms-custom-login' ), 'primary', 'save' );
			if ( ! is_multisite() && is_user_logged_in() ) : ?>
				<p id="preview"><a class="thickbox button" href="<?php echo wp_login_url(); ?>" ><?php _e( 'Preview', 'ms-custom-login' ); ?></a></p>
			<?php endif;
			submit_button( __( 'Reset Defaults', 'ms-custom-login' ), 'secondary', 'reset' ); ?>
	</div>

	<!-- Notice Option -->
	<div id="notice-option" class="update-nag clearfix">
		<p class="notice-title"><?php _e( 'If you can not "Save changes" or "reset":', 'ms-custom-login' ); ?><span class="notice-open"><?php _e( 'More detailed', 'ms-custom-login' ); ?></span><span class="notice-close"><?php _e( 'Dismiss this notice', 'ms-custom-login' ); ?></span></p>
		<p class="notice-desc"><?php echo __( 'When WAF setting of the server is enabled, you can not "Save changes" or "reset".', 'ms-custom-login' ) . ' ' . __( 'Please save the settings after you disable the WAF configuration.', 'ms-custom-login' ); ?><br /><?php _e( 'Once you have save the settings, for security, please reenable the WAF setting.', 'ms-custom-login' ); ?><br /><?php _e( 'Please contact the server administrator for more information.', 'ms-custom-login' ); ?></p>
	</div>
	</form>

	<?php /* login page preview */
	if ( ! is_multisite() && is_user_logged_in() ) : ?>
	<div id="preview-popup">
		<h3 class="title"><?php _e( 'Preview', 'ms-custom-login' ); ?></h3>
		<div class="preview-inline">
			<div id="preview-container">
				<iframe src="<?php echo wp_login_url( get_permalink() ); ?>" sandbox=""></iframe>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>

<?php
}

/**
 * ------------------------------------------------------------
 * 7.1.1 - Login Page Logo ( link URL )
 * ------------------------------------------------------------
 */
function ms_custom_login_headerurl( $login_header_url ) {
	$options = ms_custom_login_get_option();

	if ( ! empty( $options['mcl_logo_link_attr'] ) ) {
		$login_header_url = esc_url( home_url( '/' ) );
	}

	if ( ! empty( $options['mcl_logo_link_url'] ) ) {
		$login_header_url = esc_url( $options['mcl_logo_link_url'] );
	}

	return $login_header_url;
}
add_filter( 'login_headerurl', 'ms_custom_login_headerurl' );

/**
 * ------------------------------------------------------------
 * 7.1.2 - Login Page Logo ( title )
 * ------------------------------------------------------------
 */
function ms_custom_login_headertitle( $login_header_title ) {
	$options = ms_custom_login_get_option();

	if ( ! empty( $options['mcl_logo_link_attr'] ) ) {
		if ( is_multisite() ) {
			$login_header_title = get_current_site()->site_name;
		} else {
			$login_header_title = get_option( 'blogname' );
		}
	}

	if ( ! empty( $options['mcl_logo_link_title'] ) ) {
		$login_header_title = esc_attr( $options['mcl_logo_link_title'] );
	}

	return $login_header_title;
}
add_filter( 'login_headertitle', 'ms_custom_login_headertitle' );

/**
 * ------------------------------------------------------------
 * 7.2 - HEX -> RGB
 * ------------------------------------------------------------
 */
function ms_custom_login_rgb16c( $color ) {
	$color = trim( $color, '#' );
	$c16 = '';

	for( $i = 0; $i < strlen( $color ); $i+=2 ) {
		$rgb16 = substr( $color, $i, 2 );
		$value = intval( base_convert( $rgb16, 16, 10 ) );
		$c16 .= $value.', ';
	}
	$rgb = trim( $c16, ', ' );

	return $rgb;
}

/**
 * ------------------------------------------------------------
 * 7.2.2 - Media UpLoader children
 * ------------------------------------------------------------
 */
function ms_custom_login_upload_children( $upload_option ) {
	$upload_children_class = 'media-children ';
	$upload_children_class .= ! empty( $upload_option ) ? 'children-show' : 'children-hide';
	return $upload_children_class;
}

/**
 * ------------------------------------------------------------
 * 7.3 - Login Page Style
 * ------------------------------------------------------------
 */
function ms_custom_login_style() {
	$options = ms_custom_login_get_option();
	$default = ms_custom_login_default_options();

	if ( empty( $options['mcl_default'] ) ) {
		return;
	}

	if ( strcmp( get_template(), 'chocolat' ) == 0 && ! empty( $options['mcl_option_chocolat'] ) ) {
		wp_enqueue_style( 'ms-custom-login-chocolat', MS_CUSTOM_LOGIN_PLUGIN_URL . 'inc/mcl-chocolat/mcl-chocolat.css', array(), null );
		wp_print_styles();
	}

	echo '<style type="text/css">' . "\n";

	// Web font
	if ( ! empty( $options['mcl_show_logo'] ) && ! empty( $options['mcl_show_logo_text'] ) && ! empty( $options['mcl_text_webfont'] ) ) {
		echo wp_kses_stripslashes( $options['mcl_text_webfont'] ) . "\n\n";
	}

	// html
	if ( $options['mcl_page_bg_color'] != $default['mcl_page_bg_color'] ) : ?>
	html {
		background: <?php echo esc_attr( $options['mcl_page_bg_color'] ); ?> !important;
	}
	<?php echo "\n"; endif;

	// body
	if ( ( $options['mcl_page_bg_color'] != $default['mcl_page_bg_color'] ) || ! empty( $options['mcl_page_bg_url'] ) ) {
		echo 'body {' . "\n";

		if ( $options['mcl_page_bg_color'] != $default['mcl_page_bg_color'] ) {
			echo "\t" . 'background: ' . esc_attr( $options['mcl_page_bg_color'] ) . ' !important;' . "\n";
		}

		if ( ! empty( $options['mcl_page_bg_url'] ) ) {
			echo "\t" . 'background-image: url(' . esc_url( $options['mcl_page_bg_url'] ) . ') !important;' . "\n";
			echo "\t" . 'background-repeat: ' . esc_attr( $options['mcl_bg_repeat_select'] ) . ' !important;' . "\n";
			echo "\t" . 'background-position: ' . esc_attr( $options['mcl_bg_x_select'] ).' '.esc_attr( $options['mcl_bg_y_select'] ) . ' !important;' . "\n";
			echo "\t" . 'background-attachment: ' . esc_attr( $options['mcl_bg_attach_select'] ) . ' !important;' . "\n";

			$mcl_bg_size = $options['mcl_bg_size_select'];
			if ( ! empty( $options['mcl_bg_size_value'] ) ) {
				$mcl_bg_size = $options['mcl_bg_size_value'];
			}
			echo "\t" . 'background-size: ' . esc_attr( $mcl_bg_size ) . ' !important;' . "\n";
		}
		echo '}' . "\n\n";
	}

	// .login label
	if ( $options['mcl_text_color'] != $default['mcl_text_color'] ) : ?>
	.login label {
		color: <?php echo esc_attr( $options['mcl_text_color'] ); ?>;
	}
	<?php echo "\n"; endif;

	// a
	if ( $options['mcl_link_color'] != $default['mcl_link_color'] ) : ?>
	a,
	.login #nav a,
	.login #backtoblog a {
		color: <?php echo esc_attr( $options['mcl_link_color'] ); ?>;
	}
	<?php echo "\n"; endif;

	// a:hover
	if ( $options['mcl_link_color_hover'] != $default['mcl_link_color_hover'] ) : ?>
	a:hover,
	a:active,
	a:focus,
	.login #nav a:hover,
	.login #backtoblog a:hover {
		color: <?php echo esc_attr( $options['mcl_link_color_hover'] ); ?>;
	}
	<?php echo "\n"; endif;

	// .login form
	$login_form_css = '';

	if ( $options['mcl_form_bg_color'] != $default['mcl_form_bg_color'] ) {
		$login_form_css .= "\t" . 'background-color: ' . esc_attr( $options['mcl_form_bg_color'] ) . ';' . "\n";
	}

	if ( $options['mcl_form_bg_alpha'] != 1 ) {
		$color = '';
		$rgb = ms_custom_login_rgb16c( $options['mcl_form_bg_color'] );
		$color = 'rgba('.$rgb.', '.$options['mcl_form_bg_alpha'].')';

		$login_form_css .= "\t" . 'background-color: ' . esc_attr( $color ) . ';' . "\n";
	}

	if ( ! empty( $options['mcl_form_bg_url'] ) ) {
		$login_form_css .= "\t" . 'background-image: url(' . esc_url( $options['mcl_form_bg_url'] ) . ');' . "\n";
		$login_form_css .= "\t" . 'background-repeat: ' . esc_attr( $options['mcl_form_bg_repeat_select'] ) . ';' . "\n";
		$login_form_css .= "\t" . 'background-position: ' . esc_attr( $options['mcl_form_bg_x_select'] ).' '.esc_attr( $options['mcl_form_bg_y_select'] ) . ';' . "\n";
	}

	if ( $options['mcl_form_radius'] != $default['mcl_form_radius'] ) {
		$login_form_css .= "\t" . 'border-radius: ' . absint( $options['mcl_form_radius'] ) . 'px;' . "\n";
	}

	if ( $options['mcl_form_boxshadow_radio'] != $default['mcl_form_boxshadow_radio'] ) {
		$login_form_css .= "\t" . '-webkit-box-shadow: none;' . "\n";
		$login_form_css .= "\t" . 'box-shadow: none;' . "\n";
	}

	if ( ! empty( $login_form_css ) ) {
		echo '.login form {' . "\n" . $login_form_css . '}' . "\n\n";
	}

	// .login h1 a
	$login_h1_css = '';
	$login_h1_a_css = '';
	$logo_height = '';
	$logo_width = '';
	$line_height = '';
	$text_x = '';
	$text_indent = '';
	$bg_position = '';
	$logo_hover = false;

	if ( empty( $options['mcl_show_logo'] ) ) {
		$login_h1_a_css .= "\t" . 'display: none;' . "\n";
	} else {
		if ( ! empty( $options['mcl_show_logo_text'] ) ) {
			$logo_width = 'auto';
			$line_height = $options['mcl_text_size'] * 2;
			$text_x = 'center';
			$text_indent = '0px';

			if ( $options['mcl_logo_text_color'] != $default['mcl_logo_text_color'] ) {
				$login_h1_a_css .= "\t" . 'color: ' . esc_attr( $options['mcl_logo_text_color'] ) . ';' . "\n";
			}

			if ( ! empty( $options['mcl_text_family'] ) ) $login_h1_a_css .= "\t" . 'font-family: ' . wp_kses_stripslashes( $options['mcl_text_family'] ) . ';' . "\n";

			if ( $options['mcl_text_size'] != $default['mcl_text_size'] ) {
				$login_h1_a_css .= "\t" . 'font-size: ' . absint( $options['mcl_text_size'] ) . 'px;' . "\n";
			}

			$login_h1_a_css .= "\t" . 'overflow: visible;' . "\n";
		}

		if ( empty( $options['mcl_show_logo_img'] ) ) {
			$login_h1_a_css .= "\t" . 'background: none;' . "\n";

			if ( ! empty( $options['mcl_show_logo_text'] ) ) {
				$logo_height = 'auto';
			}
		} else {
			if ( ! empty( $options['mcl_logo_url'] ) ) {
				$abs_path = rtrim( ABSPATH, '/' );
				$path = str_replace( site_url(), $abs_path, $options['mcl_logo_url'] );
				list( $width, $height ) = getimagesize( $path );

				if ( $width >= 320 ) {
					$logo_size = 'cover';
					$ratio = $width / 320;
					$height = $height / $ratio;
					if ( empty( $logo_width ) ) $logo_width = 'auto';
				} else {
					$logo_size = 'auto';
					if ( empty( $logo_width ) ) $logo_width = absint( $width ) . 'px';
				}

				$login_h1_css .= "\t" . 'margin: 0 auto 25px;' . "\n";
				$logo_height = absint ( $height ) . 'px';
				$bg_position = 'center bottom';

				$login_h1_a_css .= "\t" . 'background-image: url(' . esc_url( $options['mcl_logo_url'] ) . ') !important;' . "\n";
				$login_h1_a_css .= "\t" . '-webkit-background-size: ' . esc_attr( $logo_size ) . ';' . "\n";
				$login_h1_a_css .= "\t" . 'background-size: ' . esc_attr( $logo_size ) . ';' . "\n";
				$login_h1_a_css .= "\t" . 'margin: 0 auto;' . "\n";
			} else {
				$width = 84;
				$height = 84;
			}
			if ( ! empty( $options['mcl_show_logo_text'] ) ) {
				$text_x = 'left';
				$text_indent = ( $width + 10 ) . 'px';
				$bg_position = 'left center';
				$login_h1_css .= "\t" . 'display: table;' . "\n";
				$login_h1_css .= "\t" . 'margin: 0 auto 25px;' . "\n";
				$login_h1_a_css .= "\t" . 'margin: 0;' . "\n";
				$logo_hover = true;

				if ( $height < $options['mcl_text_size'] ) {
					$logo_height = absint ( $options['mcl_text_size'] ) . 'px';
				} else {
					$line_height = $height;
				}
			}
		}

		if ( ! empty( $bg_position ) ) $login_h1_a_css .= "\t" . 'background-position: ' . esc_attr( $bg_position ) . ';' . "\n";
		if ( ! empty( $logo_height ) ) $login_h1_a_css .= "\t" . 'height: ' . esc_attr( $logo_height ) . ';' . "\n";
		if ( ! empty( $line_height ) ) $login_h1_a_css .= "\t" . 'line-height: ' . absint( $line_height ) . 'px;' . "\n";
		if ( ! empty( $text_x ) ) $login_h1_a_css .= "\t" . 'text-align: ' . esc_attr( $text_x ) . ';' . "\n";
		if ( ! empty( $text_indent ) ) $login_h1_a_css .= "\t" . 'text-indent: ' . esc_attr( $text_indent ) . ';' . "\n";
		if ( ! empty( $logo_width ) ) $login_h1_a_css .= "\t" . 'width: ' . esc_attr( $logo_width ) . ';' . "\n";
	}

	if ( ! empty( $login_h1_css ) ) {
		echo '.login h1 {' . "\n" . $login_h1_css . '}' . "\n\n";
	}

	if ( ! empty( $login_h1_a_css ) ) {
		echo '.login h1 a {' . "\n" . $login_h1_a_css . '}' . "\n\n";
	}

	// .login h1 a:hover.
	if ( $logo_hover && ( $options['mcl_logo_text_hover'] != $default['mcl_logo_text_hover'] ) ) : ?>
	.login h1 a:hover,
	.login h1 a:active,
	.login h1 a:focus {
		color: <?php echo esc_attr( $options['mcl_logo_text_hover'] ); ?>;
	}
	<?php echo "\n"; endif;

	// .login .button-primary.
	if ( $options['mcl_btn_bg_color'] != $default['mcl_btn_bg_color'] ) : ?>
	.login .button-primary {
		background: <?php echo esc_attr( $options['mcl_btn_bg_color'] ); ?>;
	}
	<?php echo "\n"; endif;

	// .login .button-primary:hover.
	if ( $options['mcl_btn_bg_hover'] != $default['mcl_btn_bg_hover'] ) : ?>
	.login .button-primary:hover,
	.login .button-primary:focus,
	.login .button-primary:active {
		background: <?php echo esc_attr( $options['mcl_btn_bg_hover'] ); ?>;
	}
	<?php echo "\n"; endif;

	// .login .button-primary -border-.
	if ( ( $options['mcl_btn_border_color'] != $default['mcl_btn_border_color'] ) || ( $options['mcl_btn_text_color'] != $default['mcl_btn_text_color'] ) ) : ?>
	.login .button-primary,
	.login .button-primary:hover,
	.login .button-primary:focus,
	.login .button-primary:active {
	<?php if ( $options['mcl_btn_border_color'] != $default['mcl_btn_border_color'] ) : ?>
		border-color: <?php echo esc_attr( $options['mcl_btn_border_color'] ); ?>;
		-webkit-box-shadow: inset 0 1px 0 rgba( 255, 255, 255, 0.25 ), 0 1px 0 rgba( 0, 0, 0, 0.15 );
		box-shadow: inset 0 1px 0 rgba( 255, 255, 255, 0.25 ), 0 1px 0 rgba( 0, 0, 0, 0.15 );
	<?php endif;

	if ( $options['mcl_btn_text_color'] != $default['mcl_btn_text_color'] ) : ?>
		color: <?php echo esc_attr( $options['mcl_btn_text_color'] ); ?>;
	<?php endif; ?>
	}
	<?php echo "\n"; endif;

	// #login.
	$login_css = '';

	if ( $options['mcl_form_x_select'] != $default['mcl_form_x_select'] ) {
		if ( ! empty( $options['mcl_form_x_select'] ) ) $login_css .= "\t" . 'margin-' . esc_attr( $options['mcl_form_x_select'] ) . ': ' . absint( $options['mcl_form_x_pos'] ) . '%;'. "\n";
	}

	if ( $options['mcl_form_y_select'] != 'top' || $options['mcl_form_y_pos'] > 0 ) {
		if ( $options['mcl_form_y_select'] == 'center' ) {
			$form_y_pos = 50;
		} else if ( $options['mcl_form_y_select'] == 'bottom' ) {
			$form_y_pos = ( $options['mcl_form_y_pos'] > 0 ) ? 100 - absint( $options['mcl_form_y_pos'] ) : 100 ;
		} else {
			$form_y_pos = $options['mcl_form_y_pos'];
		}

		$login_css .= "\t" . 'padding-top: 0;'. "\n";
		$login_css .= "\t" . 'position: relative;'. "\n";
		$login_css .= "\t" . 'top: ' . absint( $form_y_pos ) . '%;'. "\n";
		$login_css .= "\t" . '-webkit-transform: translateY(-' . absint( $form_y_pos ) . '%);'. "\n";
		$login_css .= "\t" . '-ms-transform: translateY(-' . absint( $form_y_pos ) . '%);'. "\n";
		$login_css .= "\t" . 'transform: translateY(-' . absint( $form_y_pos ) . '%);'. "\n";
	}

	if ( ! empty( $login_css ) ) {
		echo '#login {' . "\n" . $login_css . '}' . "\n\n";
	}

	// #nav.
	if ( ! empty( $options['mcl_hide_nav'] ) ) : ?>
	#nav {
		display: none;
	}
	<?php echo "\n";
		endif;

	// #backtoblog.
	if ( ! empty( $options['mcl_hide_backlink'] ) ) : ?>
	#backtoblog {
		display: none;
	}
	<?php echo "\n";
	endif;

	// custom css.
	if ( ! empty( $options['mcl_custom_css'] ) ) {
		echo "\n" . wp_kses_stripslashes( $options['mcl_custom_css'] ) . "\n";
	} ?>
</style>
<?php
}
add_action( 'login_enqueue_scripts', 'ms_custom_login_style' );
