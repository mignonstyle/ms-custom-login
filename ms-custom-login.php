<?php
/**
 * Plugin Name: MS Custom Login
 * Plugin URI: https://wordpress.org/plugins/ms-custom-login/
 * Description: MS Custom Login is you can easily customize the login page of your WordPress.
 * Text Domain: ms-custom-login
 * Domain Path: /languages
 * Version: 0.2
 * Author: Mignon Style
 * Author URI: http://mignonstyle.com
 * License: GNU General Public License v2.0
 * 
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
	$plugin_description = __( 'MS Custom Login is you can easily customize the login page of your WordPress.', MS_CUSTOM_LOGIN_TEXTDOMAIN );
	return $plugin_description;
}

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

function ms_custom_login_admin_enqueue_style() {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_style( 'ms_custom_login_style', MS_CUSTOM_LOGIN_PLUGIN_URL . 'css/ms-custom-login.css', array(), null );
}
add_action( 'admin_enqueue_scripts', 'ms_custom_login_admin_enqueue_style' );

/**
 * ------------------------------------------------------------
 * 0.3 - Read javascript file
 * ------------------------------------------------------------
 */

function ms_custom_login_admin_print_scripts() {
	wp_enqueue_script( 'ms_custom_login_js', MS_CUSTOM_LOGIN_PLUGIN_URL . 'js/ms-custom-login.js', array( 'jquery' ), false, true );

	// color picker
	wp_enqueue_script( 'ms_custom_login_colorpicker_js', MS_CUSTOM_LOGIN_PLUGIN_URL . 'js/color-picer.js', array( 'wp-color-picker' ), false, true );

	// media uploader
	if ( function_exists( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
		wp_register_script( 'ms_custom_login_media_uploader_js', MS_CUSTOM_LOGIN_PLUGIN_URL . 'js/media-uploader.js', array( 'jquery' ), false, true );
		$translation_array = array(
			'title'  => __( 'Select Image', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
			'button' => __( 'Set up Image', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
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

	// Read the script only to options page
	add_action( 'admin_print_scripts-'.$page_hook, 'ms_custom_login_admin_print_scripts' );
}
add_action( 'admin_menu', 'ms_custom_login_add_page' );

/**
 * ------------------------------------------------------------
 * 2.0 - To register the setting options
 * ------------------------------------------------------------
 */

function ms_custom_login_options_init(){
	register_setting( 'ms_custom_login_options', 'ms_custom_login_options', 'ms_custom_login_validate' );
	include_once( MS_CUSTOM_LOGIN_PLUGIN_PATH . 'inc/login-register.php' );
}
add_action( 'admin_init', 'ms_custom_login_options_init' );

/**
 * ------------------------------------------------------------
 * 3.0 - Create an array of default options
 * ------------------------------------------------------------
 */

function ms_custom_login_default_options() {
	$default_options = array(
		// Default
		'mcl_default' => '',

		// Page Setting
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

		// Logo Setting
		'mcl_show_logo'      => 1,
		'mcl_logo_link_attr' => 0,
		'mcl_logo_url'       => '',

		// Form Setting
		'mcl_form_bg_color'         => '#ffffff',
		'mcl_form_bg_alpha'         => 1,
		'mcl_form_bg_url'           => '',
		'mcl_form_bg_x_select'      => 'left',
		'mcl_form_bg_y_select'      => 'top',
		'mcl_form_bg_repeat_select' => 'repeat',
		'mcl_form_radius'           => 0,
		'mcl_form_boxshadow_radio'  => 'boxshadow_true',

		// Button Setting
		'mcl_btn_text_color'   => '#ffffff',
		'mcl_btn_border_color' => '#0074a2',
		'mcl_btn_bg_color'     => '#2ea2cc',
		'mcl_btn_bg_hover'     => '#1e8cbe',

		//Custom CSS
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
			'label'  => __( 'Add drop shadow', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'boxshadow_false' => array(
			'value'  => 'boxshadow_false',
			'id'     => 'no-boxshadow',
			'label'  => __( 'Remove drop shadow', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
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
			'label' => __( 'left', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'center' => array(
			'value' => 'center',
			'label' => __( 'center', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'right' => array(
			'value' => 'right',
			'label' => __( 'right', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
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
			'label' => __( 'top', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'center' => array(
			'value' => 'center',
			'label' => __( 'center', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'bottom' => array(
			'value' => 'bottom',
			'label' => __( 'bottom', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
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
			'label' => __( 'repeat', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'repeat-x' => array(
			'value' => 'repeat-x',
			'label' => __( 'repeat-x', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'repeat-y' => array(
			'value' => 'repeat-y',
			'label' => __( 'repeat-y', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'no-repeat' => array(
			'value' => 'no-repeat',
			'label' => __( 'no-repeat', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
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
			'label' => __( 'scroll', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'fixed' => array(
			'value' => 'fixed',
			'label' => __( 'fixed', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
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
			'label' => __( 'auto', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'contain' => array(
			'value' => 'contain',
			'label' => __( 'contain', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'cover' => array(
			'value' => 'cover',
			'label' => __( 'cover', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
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
			'label' => __( '1', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'0.9' => array(
			'value' => '0.9',
			'label' => __( '0.9', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'0.8' => array(
			'value' => '0.8',
			'label' => __( '0.8', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'0.7' => array(
			'value' => '0.7',
			'label' => __( '0.7', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'0.6' => array(
			'value' => '0.6',
			'label' => __( '0.6', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'0.5' => array(
			'value' => '0.5',
			'label' => __( '0.5', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'0.4' => array(
			'value' => '0.4',
			'label' => __( '0.4', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'0.3' => array(
			'value' => '0.3',
			'label' => __( '0.3', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'0.2' => array(
			'value' => '0.2',
			'label' => __( '0.2', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'0.1' => array(
			'value' => '0.1',
			'label' => __( '0.1', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
		'0' => array(
			'value' => '0',
			'label' => __( '0', MS_CUSTOM_LOGIN_TEXTDOMAIN ),
		),
	);
	return $bg_alpha;
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

	if ( !current_user_can( 'manage_options' ) ) 
		wp_die( _e( 'You do not have sufficient permissions to access this page.', MS_CUSTOM_LOGIN_TEXTDOMAIN ) );
	?>
	<div id="ms-custom-login" class="wrap">
		<h2><?php _e( MS_CUSTOM_LOGIN_TITLE ); ?></h2>

		<form method="post" action="options.php" enctype="multipart/form-data">
		<?php settings_fields( 'ms_custom_login_options' ); ?>
			<input id="ms_custom_login_options[mcl_default]" class="regular-text" type="hidden" name="ms_custom_login_options[mcl_default]" value="<?php echo esc_attr_e( $options['mcl_default'] ); ?>" />

			<div id="page-setting" class="option-box option-check"><?php /* Login Page Setting */ ?>
				<h3><?php _e( 'Login Page Setting', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></h3>
				<div class="inside">
					<table class="form-table">
						<tr><?php /* Page Background Color */ ?>
							<th scope="row"><?php printf( __( '%s Background Color', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Page', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></th>
							<td><?php
								$option_name = 'mcl_page_bg_color';
								$default_color = $default_option['mcl_page_bg_color'];
								ms_custom_login_color_picker( $options, $option_name, $default_color );
							?></td>
						</tr>

						<tr><?php /* Page Background Image */ ?>
							<th scope="row"><?php printf( __( '%s Background Image', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Page', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></th>
							<td><fieldset><?php
								$option_id = 'page-bg';
								$option_name = 'mcl_page_bg_url';
								$option_desc = __( 'The use for the backgrounds of the login page. Recommend a png, jpg or gif file.', MS_CUSTOM_LOGIN_TEXTDOMAIN );
								ms_custom_login_media_uploader( $options, MS_CUSTOM_LOGIN_TEXTDOMAIN, $option_id, $option_name, $option_desc );
							?></fieldset></td>
						</tr>

						<tr><?php /* Page Background Position */ ?>
							<th scope="row"><?php printf( __( '%s Background Position', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Page', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></th>
							<td><table class="nest"><tr>
								<td><p><?php _e( 'Horizontal direction', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p><?php
									$option_array = ms_custom_login_bg_position_x();
									$option_name = 'mcl_bg_x_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
								<td><p><?php _e( 'Vertical direction', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p><?php
									$option_array = ms_custom_login_bg_position_y();
									$option_name = 'mcl_bg_y_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
								<td><p><?php _e( 'Repeat', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p><?php
									$option_array = ms_custom_login_bg_repeat();
									$option_name = 'mcl_bg_repeat_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
								<td><p><?php _e( 'Attachment', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p><?php
									$option_array = ms_custom_login_bg_attach();
									$option_name = 'mcl_bg_attach_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
							</tr></table></td>
						</tr>

						<tr><?php /* Page Background Size */ ?>
							<th scope="row"><?php printf( __( '%s Background Size', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Page', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></th>
							<td><table class="nest"><tr>
								<td colspan="2"><p><?php printf( __( 'Please Select the %s background size or enter the value.', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Page', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></p></td>
							</tr><tr>
								<td><?php
									$option_array = ms_custom_login_bg_size();
									$option_name = 'mcl_bg_size_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
							<td><input id="ms_custom_login_options[mcl_bg_size_value]" name="ms_custom_login_options[mcl_bg_size_value]" value="<?php esc_attr_e( $options['mcl_bg_size_value'] ); ?>" type="text" class="regular-text" placeholder="<?php _e( 'Enter the value', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?>" /></td>
					</tr></table></td>
						</tr>

						<tr><?php /* Page Text Color */ ?>
							<th scope="row"><?php printf( __( '%s Text Color', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Page', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></th>
							<td><table class="nest"><tr>
								<td><p><?php _e( 'Text color', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p><?php
									$option_name = 'mcl_text_color';
									$default_color = $default_option['mcl_text_color'];
									ms_custom_login_color_picker( $options, $option_name, $default_color );
								?></td>
								<td><p><?php _e( 'Link color', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p><?php
									$option_name = 'mcl_link_color';
									$default_color = $default_option['mcl_link_color'];
									ms_custom_login_color_picker( $options, $option_name, $default_color );
								?></td>
								<td><p><?php _e( 'Hover color', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p><?php
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
				<h3><?php _e( 'Login Page Logo Setting', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></h3>
				<div class="inside">
					<table class="form-table">
						<tr class="target"><?php /* Logo Display */ ?>
							<th scope="row"><?php _e( 'Logo Display', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></th>
							<td><fieldset><?php
								$option_name = 'mcl_show_logo';
								$option_text = __( 'Display a logo.', MS_CUSTOM_LOGIN_TEXTDOMAIN );
								ms_custom_login_checkbox( $options, $option_name, $option_text );
							?></fieldset></td>
						</tr>

						<tr class="hidebox"><?php /* Logo Link Attribute */ ?>
							<th scope="row"><?php _e( 'Link Attribute', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></th>
							<td><fieldset><?php
								$option_name = 'mcl_logo_link_attr';
								$option_text = __( 'URL of a site name and a site is used for a logo.', MS_CUSTOM_LOGIN_TEXTDOMAIN );
								ms_custom_login_checkbox( $options, $option_name, $option_text );
							?></fieldset></td>
						</tr>

						<tr class="hidebox"><?php /* Logo Image */ ?>
							<th scope="row"><?php _e( 'Logo Image', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></th>
							<td><fieldset><?php
								$option_id = 'mcl-logo-img';
								$option_name = 'mcl_logo_url';
								$option_desc = __( 'Image you have set will be used for the logo on the login page. Recommend a png, jpg or gif file of width 320px.', MS_CUSTOM_LOGIN_TEXTDOMAIN );
								ms_custom_login_media_uploader( $options, MS_CUSTOM_LOGIN_TEXTDOMAIN, $option_id, $option_name, $option_desc );
							?></fieldset></td>
						</tr>
					</table>
				</div>
			</div><!-- /#logo-setting -->

			<div id="form-setting" class="option-box option-check"><?php /* Login Form Setting */ ?>
				<h3><?php _e( 'Login Form Setting', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></h3>
				<div class="inside">
					<table class="form-table">
						<tr><?php /* Form Background Color */ ?>
							<th scope="row"><?php printf( __( '%s Background Color', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Form', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></th>
							<td><table class="nest"><tr>
								<td colspan="2"><p><?php _e( 'Select the transparency if you want to make transparent the color of the background. The default is Opacity.', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p></td>
								</tr><tr>
								<td><p><?php _e( 'Background color', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p><?php
									$option_name = 'mcl_form_bg_color';
									$default_color = $default_option['mcl_form_bg_color'];
									ms_custom_login_color_picker( $options, $option_name, $default_color );
								?></td>
								<td><p><?php echo __( 'Background Transparency', MS_CUSTOM_LOGIN_TEXTDOMAIN ) . ' ' . __( '( Transparency : 0 - Opacity : 1 )', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ?></p><?php
									$option_array = ms_custom_login_bg_alpha();
									$option_name = 'mcl_form_bg_alpha';
									ms_custom_login_select( $options, $option_array, $option_name );
									?></td>
							</tr></table></td>
						</tr>

						<tr><?php /* Form Background Image */ ?>
							<th scope="row"><?php printf( __( '%s Background Image', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Form', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></th>
							<td><fieldset><?php
								$option_id = 'mcl-form-bg';
								$option_name = 'mcl_form_bg_url';
								$option_desc = __( 'The use for the backgrounds of the form of the login page. Recommend a png, jpg or gif file of width 320px, height 275px.', MS_CUSTOM_LOGIN_TEXTDOMAIN );
								ms_custom_login_media_uploader( $options, MS_CUSTOM_LOGIN_TEXTDOMAIN, $option_id, $option_name, $option_desc );
							?></fieldset></td>
						</tr>

						<tr><?php /* Form Background Position */ ?>
							<th scope="row"><?php printf( __( '%s Background Position', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Form', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></th>
							<td><table class="nest"><tr>
								<td><p><?php _e( 'Horizontal direction', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p><?php
									$option_array = ms_custom_login_bg_position_x();
									$option_name = 'mcl_form_bg_x_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
								<td><p><?php _e( 'Vertical direction', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p><?php
									$option_array = ms_custom_login_bg_position_y();
									$option_name = 'mcl_form_bg_y_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
								<td><p><?php _e( 'Repeat', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p><?php
									$option_array = ms_custom_login_bg_repeat();
									$option_name = 'mcl_form_bg_repeat_select';
									ms_custom_login_select( $options, $option_array, $option_name );
								?></td>
							</tr></table></td>
						</tr>

						<tr><?php /* Form Rounded Rectangle Size */ ?>
							<th scope="row"><?php printf( __( '%s Rounded Rectangle Size', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Form', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></th>
							<td><fieldset>
								<p><?php _e( 'Set in px the size of the rounded corners. The default is 0px.', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p>
								<input id="ms_custom_login_options[mcl_form_radius]" name="ms_custom_login_options[mcl_form_radius]" value="<?php echo absint( $options['mcl_form_radius'] ); ?>" type="number" class="small-text" />&nbsp;<?php _e( 'px', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?>
							</fieldset></td>
						</tr>

						<tr><?php /* Form Box Shadow */ ?>
							<th scope="row"><?php printf( __( '%s Box Shadow', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Form', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></th>
							<td><fieldset><?php
								$option_array = ms_custom_login_form_boxshadow();
								$option_id = 'form-boxshadow';
								$option_name = 'mcl_form_boxshadow_radio';
								ms_custom_login_radio( $options, $option_array, $option_id, $option_name );
							?></fieldset></td>
						</tr>
					</table>
				</div>
			</div><!-- /#form-setting -->

			<div id="button-setting" class="option-box option-check"><?php /* Login Button Setting */ ?>
				<h3><?php _e( 'Login Button Setting', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></h3>
				<div class="inside">
					<table class="form-table">
						<tr><?php /* Button Text Color */ ?>
							<th scope="row"><?php printf( __( '%s Text Color', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Button', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></th>
							<td><?php
								$option_name = 'mcl_btn_text_color';
								$default_color = $default_option['mcl_btn_text_color'];
								ms_custom_login_color_picker( $options, $option_name, $default_color );
							?></td>
						</tr>

						<tr><?php /* Button Border Color */ ?>
							<th scope="row"><?php printf( __( '%s Border Color', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Button', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></th>
							<td><?php
								$option_name = 'mcl_btn_border_color';
								$default_color = $default_option['mcl_btn_border_color'];
								ms_custom_login_color_picker( $options, $option_name, $default_color );
							?></td>
						</tr>

						<tr><?php /* Button Color */ ?>
							<th scope="row"><?php printf( __( '%s Color', MS_CUSTOM_LOGIN_TEXTDOMAIN ), __( 'Button', MS_CUSTOM_LOGIN_TEXTDOMAIN ) ); ?></th>
							<td><table class="nest"><tr>
								<td><p><?php _e( 'Background color', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p><?php
									$option_name = 'mcl_btn_bg_color';
									$default_color = $default_option['mcl_btn_bg_color'];
									ms_custom_login_color_picker( $options, $option_name, $default_color );
								?></td>
								<td><p><?php _e( 'Hover color', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p><?php
									$option_name = 'mcl_btn_bg_hover';
									$default_color = $default_option['mcl_btn_bg_hover'];
									ms_custom_login_color_picker( $options, $option_name, $default_color );
								?></td>
							</tr></table></td>
						</tr>
					</table>
				</div>
			</div><!-- /#button-setting -->

			<div id="custom-css-setting" class="option-box option-check"><?php /* Custom CSS Setting */ ?>
				<h3><?php _e( 'Custom CSS Setting', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></h3>
				<div class="inside">
					<table class="form-table">
						<tr><?php /* Custom CSS */ ?>
							<th scope="row"><?php _e( 'Custom CSS', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></th>
							<td><p><textarea id="ms_custom_login_options[mcl_custom_css]" cols="60" rows="3" name="ms_custom_login_options[mcl_custom_css]" placeholder="/* CSS */"><?php echo esc_textarea( $options['mcl_custom_css'] ); ?></textarea></p></td>
						</tr>
					</table>
				</div>
			</div><!-- /#custom-css-setting -->

			<div id="submit-button">
				<?php submit_button( __( 'Save Changes', MS_CUSTOM_LOGIN_TEXTDOMAIN ), 'primary', 'save' ); ?>
				<?php submit_button( __( 'Reset Defaults', MS_CUSTOM_LOGIN_TEXTDOMAIN ), 'secondary', 'reset' ); ?>
			</div>
		</form>
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
		$login_header_url = site_url();
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
		$login_header_title = get_option( 'blogname' );
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
 * 7.2 - Login Page Style
 * ------------------------------------------------------------
 */

function ms_custom_login_style() {
	$options = ms_custom_login_get_option();

	if ( empty( $options['mcl_default'] ) ) {
		return;
	}
?>
<style type="text/css">
html {
	background: <?php esc_attr_e( $options['mcl_page_bg_color'] ); ?>;
}

body {
	background: <?php esc_attr_e( $options['mcl_page_bg_color'] ); ?>;
<?php if ( ! empty( $options['mcl_page_bg_url'] ) ) : ?>
	background-image: url(<?php echo esc_url( $options['mcl_page_bg_url'] ); ?>);
	background-repeat: <?php esc_attr_e( $options['mcl_bg_repeat_select'] ); ?>;
	background-position: <?php echo esc_attr( $options['mcl_bg_x_select'] ).' '.esc_attr( $options['mcl_bg_y_select'] ); ?>;
	background-attachment: <?php esc_attr_e( $options['mcl_bg_attach_select'] ); ?>;
	background-size: <?php
		$mcl_bg_size = $options['mcl_bg_size_select'];

		if ( ! empty( $options['mcl_bg_size_value'] ) ) {
			$mcl_bg_size = $options['mcl_bg_size_value'];
		}
		esc_attr_e( $mcl_bg_size );
?>;
<?php endif; ?>
}

.login label {
	color: <?php esc_attr_e( $options['mcl_text_color'] ); ?>;
}

a,
.login #nav a,
.login #backtoblog a {
	color: <?php esc_attr_e( $options['mcl_link_color'] ); ?>;
}

a:hover,
a:active,
a:focus,
.login #nav a:hover,
.login #backtoblog a:hover {
	color: <?php esc_attr_e( $options['mcl_link_color_hover'] ); ?>;
}

.login form {
<?php
	$color = $options['mcl_form_bg_color'];

	if ( $options['mcl_form_bg_alpha'] != 1 ) {
		$rgb = ms_custom_login_rgb16c( $options['mcl_form_bg_color'] );
		$color = 'rgba('.$rgb.', '.$options['mcl_form_bg_alpha'].')';
	}
?>
	background-color: <?php esc_attr_e( $color ); ?>;
<?php if ( ! empty( $options['mcl_form_bg_url'] ) ) : ?>
	background-image: url(<?php echo esc_url( $options['mcl_form_bg_url'] ); ?>);
	background-repeat: <?php esc_attr_e( $options['mcl_form_bg_repeat_select'] ); ?>;
	background-position: <?php echo esc_attr( $options['mcl_form_bg_x_select'] ).' '.esc_attr( $options['mcl_form_bg_y_select'] ); ?>;
<?php endif; ?>
	border-radius: <?php echo absint( $options['mcl_form_radius'] ); ?>px;
<?php if ( $options['mcl_form_boxshadow_radio'] == 'boxshadow_false' ) : ?>
	-webkit-box-shadow: none;
	box-shadow: none;
<?php endif; ?>
}

.login h1 a {
<?php if ( empty( $options['mcl_show_logo'] ) ) : ?>
	display: none;
<?php else :
	if ( ! empty( $options['mcl_logo_url'] ) ) :
		list( $width, $height ) = getimagesize( $options['mcl_logo_url'] );
		$logo_width = $width;
		$logo_height = $height;

		if ( $logo_width >= 320 ) {
			$logo_size ='cover';
			$ratio = $logo_width / 320;
			$logo_height = $logo_height / $ratio;
			$logo_width = 'auto';
		} else {
			$logo_size ='auto';
			$logo_width = absint( $logo_width ).'px';
		}
?>
	background: url(<?php echo esc_url( $options['mcl_logo_url'] ); ?>) no-repeat center bottom;
	-webkit-background-size: <?php esc_attr_e( $logo_size ); ?>;
	background-size: <?php esc_attr_e( $logo_size ); ?>;
	height: <?php echo absint( $logo_height ); ?>px;
	min-height: 84px;
	width: <?php esc_attr_e( $logo_width ); ?>;
<?php endif; endif; ?>
}

.login .button-primary {
	background: <?php esc_attr_e( $options['mcl_btn_bg_color'] ); ?>;
}

.login .button-primary:hover,
.login .button-primary:focus,
.login .button-primary:active {
	background: <?php esc_attr_e( $options['mcl_btn_bg_hover'] ); ?>;
}

.login .button-primary,
.login .button-primary:hover,
.login .button-primary:focus,
.login .button-primary:active {
	border-color: <?php esc_attr_e( $options['mcl_btn_border_color'] ); ?>;
	-webkit-box-shadow: inset 0 1px 0 rgba( 255, 255, 255, 0.25 ), 0 1px 0 rgba( 0, 0, 0, 0.15 );
	box-shadow: inset 0 1px 0 rgba( 255, 255, 255, 0.25 ), 0 1px 0 rgba( 0, 0, 0, 0.15 );
	color: <?php esc_attr_e( $options['mcl_btn_text_color'] ); ?>;
}
<?php if ( ! empty( $options['mcl_custom_css'] ) ) {
	echo "\n" . esc_attr( $options['mcl_custom_css'] ) . "\n";
} ?>
</style>
<?php
}
add_action( 'login_enqueue_scripts', 'ms_custom_login_style' );