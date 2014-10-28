<?php
/**
 * login-register.php
 * option page register file
 */

/**
 * ------------------------------------------------------------
 * 10.0 - Checkbox
 * ------------------------------------------------------------
 */

function ms_custom_login_checkbox( $options, $option_name, $option_text ) {
	$options = $options;
	$option_name = $option_name;
	$option_text = $option_text;
?>
	<p><label><input id="ms_custom_login_options[<?php esc_attr_e( $option_name ); ?>]" name="ms_custom_login_options[<?php esc_attr_e( $option_name ); ?>]" type="checkbox" value="1" <?php checked( $options[$option_name], 1 ); ?> /><?php esc_attr_e( $option_text ); ?></label></p>
<?php
}

/**
 * ------------------------------------------------------------
 * 10.1 - Radio Button
 * ------------------------------------------------------------
 */

function ms_custom_login_radio( $options, $option_array, $option_id, $option_name ) {
	$options = $options;
	$option_array = $option_array;
	$option_id = $option_id;
	$option_name = $option_name;

	if ( is_array( $option_array ) ) {
?>
	<div id="<?php esc_attr_e( $option_id ); ?>" class="radio-button">
	<?php foreach ( $option_array as $option ) : ?>
		<label><input type="radio" name="ms_custom_login_options[<?php esc_attr_e( $option_name ); ?>]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php checked( $options[$option_name], $option['value'] ); ?> /><?php esc_attr_e( $option['label'] ); ?>
		<?php if ( isset( $option['img'] ) ) : ?>
			<img src="<?php echo esc_url( plugins_url( 'img/' . $option['img'] , __FILE__ ) ) ?>" alt="<?php esc_attr_e( $option['label'] ); ?>">
		<?php endif; ?></label>
	<?php endforeach; ?>
	</div>
<?php
	}
}

/**
 * ------------------------------------------------------------
 * 10.2 - Select Box
 * ------------------------------------------------------------
 */

function ms_custom_login_select( $options, $option_array, $option_name ) {
	$options = $options;
	$option_array = $option_array;
	$option_name = $option_name;
?>
	<select id="ms_custom_login_options[<?php esc_attr_e( $option_name ); ?>]" name="ms_custom_login_options[<?php esc_attr_e( $option_name ); ?>]" >
	<?php if ( is_array( $option_array ) ) :
		foreach ( $option_array as $option ) : ?>
			<option value="<?php esc_attr_e( $option['value'] ); ?>" <?php selected( $options[$option_name], $option['value'] ); ?>><?php esc_attr_e( $option['label'] ); ?></option>
	<?php endforeach; endif; ?>
	</select>
<?php
}

/**
 * ------------------------------------------------------------
 * 10.3 - Color Picker
 * ------------------------------------------------------------
 */

function ms_custom_login_color_picker( $options, $option_name, $default_color ) {
	$options = $options;
	$option_name = $option_name;
	$default_color = $default_color;
?>
	<div class="color-picker">
		<input id="ms_custom_login_options[<?php esc_attr_e( $option_name ); ?>]" name="ms_custom_login_options[<?php esc_attr_e( $option_name ); ?>]" value="<?php esc_attr_e( $options[$option_name] ); ?>" type="text" data-default-color="<?php esc_attr_e( $default_color ); ?>" class="color-picker-field" />
	</div>
<?php
}

/**
 * ------------------------------------------------------------
 * 10.4 - Media UpLoader
 * ------------------------------------------------------------
 */

function ms_custom_login_media_uploader( $options, $text_domain, $option_id, $option_name, $option_desc ) {
	$options = $options;
	$text_domain = $text_domain;
	$option_id = $option_id;
	$option_name = $option_name;
	$option_desc = $option_desc;
	$upload_remove_class = ! empty( $options[$option_name] ) ? 'remove-open' : 'upload-open';

	if ( function_exists( 'wp_enqueue_media' ) ) : ?>
		<div id="option-<?php esc_attr_e( $option_id ); ?>" class="media-upload">
			<p><?php esc_attr_e( $option_desc ); ?></p>
			<div class="upload-remove <?php esc_attr_e( $upload_remove_class ); ?>">
				<input id="ms_custom_login_options[<?php esc_attr_e( $option_name ); ?>]" name="ms_custom_login_options[<?php esc_attr_e( $option_name ); ?>]" value="<?php echo esc_url( $options[$option_name] ); ?>" type="hidden" class="regular-text" />
				<table><tr>
					<td class="upload-button"><input id="option-upload-<?php esc_attr_e( $option_id ); ?>" class="button option-upload-button" value="<?php _e( 'Select Image', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?>" type="button"></td>
					<?php if ( ! empty( $options[$option_name] ) ) {
						$image_src = esc_url( $options[$option_name] );
						if( preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $image_src ) ) {
							echo '<td class="upload-preview"><img src="'.$image_src.'" alt="" /></td>';
						}
					} ?>
					<td class="remove-button"><input id="option-remove-<?php esc_attr_e( $option_id ); ?>" class="button option-remove-button" value="<?php _e( 'Delete Image', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?>" type="button"></td>
				</tr></table>
			</div>
		</div>
	<?php else : ?>
		<p><?php _e( 'Sorry, WordPress you are using is not supported. Upgrade your version of WordPress.', MS_CUSTOM_LOGIN_TEXTDOMAIN ); ?></p>
<?php endif;
}

/**
 * ------------------------------------------------------------
 * 11.0 - sanitize and validate
 * ------------------------------------------------------------
 */

function ms_custom_login_validate( $input ) {
	if ( isset( $_POST['reset'] ) ) {
		$input = ms_custom_login_default_options();
	} else {
		// Default
		$input['mcl_default'] = 'true';

		// Page Setting
		$input['mcl_page_bg_color'] = esc_attr( $input['mcl_page_bg_color'] );

		$input['mcl_page_bg_url'] = esc_url_raw( $input['mcl_page_bg_url'] );

		if ( ! array_key_exists( $input['mcl_bg_x_select'], ms_custom_login_bg_position_x() ) )
			$input['mcl_bg_x_select'] = null;

		if ( ! array_key_exists( $input['mcl_bg_y_select'], ms_custom_login_bg_position_y() ) )
			$input['mcl_bg_y_select'] = null;

		if ( ! array_key_exists( $input['mcl_bg_repeat_select'], ms_custom_login_bg_repeat() ) )
			$input['mcl_bg_repeat_select'] = null;

		if ( ! array_key_exists( $input['mcl_bg_attach_select'], ms_custom_login_bg_attach() ) )
			$input['mcl_bg_attach_select'] = null;

		if ( ! array_key_exists( $input['mcl_bg_size_select'], ms_custom_login_bg_size() ) )
			$input['mcl_bg_size_select'] = null;

		$input['mcl_bg_size_value'] = sanitize_text_field( $input['mcl_bg_size_value'] );

		$input['mcl_text_color'] = esc_attr( $input['mcl_text_color'] );
		$input['mcl_link_color'] = esc_attr( $input['mcl_link_color'] );
		$input['mcl_link_color_hover'] = esc_attr( $input['mcl_link_color_hover'] );

		// Logo Setting
		if ( ! isset( $input['mcl_show_logo'] ) )
			$input['mcl_show_logo'] = null;
		$input['mcl_show_logo'] = ( $input['mcl_show_logo'] == 1 ? 1 : 0 );

		if ( ! isset( $input['mcl_logo_link_attr'] ) )
			$input['mcl_logo_link_attr'] = null;
		$input['mcl_logo_link_attr'] = ( $input['mcl_logo_link_attr'] == 1 ? 1 : 0 );

		$input['mcl_logo_url'] = esc_url_raw( $input['mcl_logo_url'] );

		//　Form Setting
		$input['mcl_form_bg_color'] = esc_attr( $input['mcl_form_bg_color'] );

		if ( ! ( $input['mcl_form_bg_alpha'] <= 1 && $input['mcl_form_bg_alpha'] >= 0 ) ) {
			$input['mcl_form_bg_alpha'] = 1;
		} else {
			$input['mcl_form_bg_alpha'] = round( $input['mcl_form_bg_alpha'], 1 );
		}
		$input['mcl_form_bg_alpha'] = sanitize_text_field( $input['mcl_form_bg_alpha'] );

		$input['mcl_form_bg_url'] = esc_url_raw( $input['mcl_form_bg_url'] );

		if ( ! array_key_exists( $input['mcl_form_bg_x_select'], ms_custom_login_bg_position_x() ) )
			$input['mcl_form_bg_x_select'] = null;

		if ( ! array_key_exists( $input['mcl_form_bg_y_select'], ms_custom_login_bg_position_y() ) )
			$input['mcl_form_bg_y_select'] = null;

		if ( ! array_key_exists( $input['mcl_form_bg_repeat_select'], ms_custom_login_bg_repeat() ) )
			$input['mcl_form_bg_repeat_select'] = null;

		$input['mcl_form_radius'] = absint( $input['mcl_form_radius'] );

		if ( ! isset( $input['mcl_form_boxshadow_radio'] ) )
			$input['mcl_form_boxshadow_radio'] = null;
		if ( ! array_key_exists( $input['mcl_form_boxshadow_radio'], ms_custom_login_form_boxshadow() ) )
			$input['mcl_form_boxshadow_radio'] = null;

		// Button Setting
		$input['mcl_btn_text_color'] = esc_attr( $input['mcl_btn_text_color'] );
		$input['mcl_btn_border_color'] = esc_attr( $input['mcl_btn_border_color'] );
		$input['mcl_btn_bg_color'] = esc_attr( $input['mcl_btn_bg_color'] );
		$input['mcl_btn_bg_hover'] = esc_attr( $input['mcl_btn_bg_hover'] );

		// Custom CSS Setting
		$input['mcl_custom_css'] = esc_textarea( $input['mcl_custom_css'] );
	}
	return $input;
}