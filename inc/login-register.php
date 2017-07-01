<?php
/**
 * login-register.php
 * option page register file
 */

/**
 * ------------------------------------------------------------
 * 10.0 - Text
 * ------------------------------------------------------------
 */
function ms_custom_login_textfield( $options, $option_name, $option_label = '', $option_type = 'text', $option_class = 'regular-text', $label_after = '', $placeholder = '' ) {
?>
	<p><?php
	if ( ! empty( $option_label ) ) {
		echo '<label for="ms_custom_login_options[' . esc_attr( $option_name ) . ']">' . esc_attr( $option_label ) . '</label>';
	} ?>
	<input id="ms_custom_login_options[<?php echo esc_attr( $option_name ); ?>]" class="<?php echo esc_attr( $option_class ); ?>" type="<?php echo esc_attr( $option_type ); ?>" name="ms_custom_login_options[<?php echo esc_attr( $option_name ); ?>]"<?php echo ( ! empty( $placeholder ) ) ? ' placeholder="' . esc_attr( $placeholder ) . '"' : ''; ?> value="<?php
	switch ( $option_type ) {
		case 'url':
			echo esc_url( $options[ $option_name ] );
			break;
		case 'email':
			echo antispambot( $options[ $option_name ] );
			break;
		case 'number':
			echo absint( $options[ $option_name ] );
			break;
		case 'hidden':
			echo esc_attr( $options[ $option_name ] );
			break;
		default:
			echo esc_attr( $options[ $option_name ] );
	}
?>" /><?php
		echo ( ! empty( $label_after ) ) ? '&nbsp;' . esc_attr( $label_after ) : '';
	?></p>
<?php
}

/**
 * ------------------------------------------------------------
 * 10.1 - Textarea
 * ------------------------------------------------------------
 */
function ms_custom_login_textarea( $options, $option_name, $option_cols = '60', $option_rows = '3', $content = '' ) {
	$content =  ( ! empty( $content ) ) ? $content : $options[ $option_name ];
?>
	<p><textarea id="ms_custom_login_options[<?php echo esc_attr( $option_name ); ?>]" cols="<?php echo absint( $option_cols ); ?>" rows="<?php echo absint( $option_rows ); ?>" name="ms_custom_login_options[<?php echo esc_attr( $option_name ); ?>]"><?php echo esc_textarea( $content ); ?></textarea></p>
<?php
}

/**
 * ------------------------------------------------------------
 * 10.2 - Checkbox
 * ------------------------------------------------------------
 */
function ms_custom_login_checkbox( $options, $option_name, $option_text = '', $option_img = '' ) {
?>
	<p class="checkbox"><label><input id="ms_custom_login_options[<?php echo esc_attr( $option_name ); ?>]" name="ms_custom_login_options[<?php echo esc_attr( $option_name ); ?>]" type="checkbox" value="1" <?php checked( $options[ $option_name ], 1 ); ?> /><?php
	if ( ! empty( $option_img ) ) {
		echo '<img src="' . esc_url( plugins_url( $option_img, __FILE__ ) ) . '" alt="' . esc_attr( $option_text ) . '">';
	}
	echo esc_attr( $option_text ); ?></label></p>
<?php
}

/**
 * ------------------------------------------------------------
 * 10.3 - Radio Button
 * ------------------------------------------------------------
 */
function ms_custom_login_radio( $options, $option_array, $option_id, $option_name ) {
	if ( is_array( $option_array ) ) {
?>
	<div id="<?php echo esc_attr( $option_id ); ?>" class="radio-button">
	<?php foreach ( $option_array as $option ) : ?>
		<label><input type="radio" name="ms_custom_login_options[<?php echo esc_attr( $option_name ); ?>]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $options[ $option_name ], $option['value'] ); ?> /><?php echo esc_attr( $option['label'] ); ?>
		<?php if ( isset( $option['img'] ) ) : ?>
			<img src="<?php echo esc_url( plugins_url( 'img/' . $option['img'] , __FILE__ ) ) ?>" alt="<?php echo esc_attr( $option['label'] ); ?>">
		<?php endif; ?></label>
	<?php endforeach; ?>
	</div>
<?php
	}
}

/**
 * ------------------------------------------------------------
 * 10.4 - Select Box
 * ------------------------------------------------------------
 */
function ms_custom_login_select( $options, $option_array, $option_name ) {
?>
	<select id="ms_custom_login_options[<?php echo esc_attr( $option_name ); ?>]" name="ms_custom_login_options[<?php echo esc_attr( $option_name ); ?>]" >
	<?php if ( is_array( $option_array ) ) :
		foreach ( $option_array as $option ) : ?>
			<option value="<?php echo esc_attr( $option['value'] ); ?>" <?php selected( $options[ $option_name ], $option['value'] ); ?>><?php echo esc_attr( $option['label'] ); ?></option>
	<?php endforeach; endif; ?>
	</select>
<?php
}

/**
 * ------------------------------------------------------------
 * 10.5 - Color Picker
 * ------------------------------------------------------------
 */
function ms_custom_login_color_picker( $options, $option_name, $default_color ) {
	$default_color = ms_custom_login_sanitize_hex_color( $default_color );

?>
	<div class="color-picker">
		<input id="ms_custom_login_options[<?php echo esc_attr( $option_name ); ?>]" name="ms_custom_login_options[<?php echo esc_attr( $option_name ); ?>]" value="<?php
	$color = ms_custom_login_sanitize_hex_color( $options[ $option_name ] );
	$color = ! empty( $color ) ? $color : $default_color;
	echo esc_attr( $color ); ?>" type="text" data-default-color="<?php echo esc_attr( $default_color ); ?>" class="color-picker-field" />
	</div>
<?php
}

/**
 * ------------------------------------------------------------
 * 10.5.1 - Color Sanitize
 * ------------------------------------------------------------
 */
function ms_custom_login_sanitize_hex_color( $color ) {
	if ( '' === $color ) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}

	return null;
}

/**
 * ------------------------------------------------------------
 * 10.6 - Media UpLoader
 * ------------------------------------------------------------
 */
function ms_custom_login_media_uploader( $options, $text_domain, $option_id, $option_name, $option_desc = '', $option_desc2 = '' ) {
	$upload_remove_class = ! empty( $options[ $option_name ] ) ? 'remove-open' : 'upload-open';

	if ( function_exists( 'wp_enqueue_media' ) ) : ?>
		<div id="option-<?php echo esc_attr( $option_id ); ?>" class="media-upload">
			<?php
				if ( ! empty( $option_desc ) ) {
				echo '<p>' . esc_attr( $option_desc ) ;
				echo ( ! empty( $option_desc2 ) ) ? '<br />' . esc_attr( $option_desc2 ) : '';
				echo '</p>';
				}
			?>
			<div class="upload-remove <?php echo esc_attr( $upload_remove_class ); ?>">
				<input id="ms_custom_login_options[<?php echo esc_attr( $option_name ); ?>]" name="ms_custom_login_options[<?php echo esc_attr( $option_name ); ?>]" value="<?php echo esc_url( $options[ $option_name ] ); ?>" type="hidden" class="regular-text" />
				<table><tr>
					<td class="upload-button"><input id="option-upload-<?php echo esc_attr( $option_id ); ?>" class="button option-upload-button" value="<?php _e( 'Select Image', 'ms-custom-login' ); ?>" type="button"></td>
					<?php if ( ! empty( $options[ $option_name ] ) ) {
						$image_src = esc_url( $options[ $option_name ] );
						if ( preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $image_src ) ) {
							echo '<td class="upload-preview"><img src="'.$image_src.'" alt="" /></td>';
						}
					} ?>
					<td class="remove-button"><input id="option-remove-<?php echo esc_attr( $option_id ); ?>" class="button option-remove-button" value="<?php _e( 'Delete Image', 'ms-custom-login' ); ?>" type="button"></td>
				</tr></table>
			</div>
		</div>
	<?php else : ?>
		<p><?php _e( 'Sorry, WordPress you are using is not supported. Upgrade your WordPress.', 'ms-custom-login' ); ?></p>
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
		// Default.
		$input['mcl_default'] = 'true';

		// Options.
		if ( ! isset( $input['mcl_option_chocolat'] ) ) {
			$input['mcl_option_chocolat'] = null;
		}

		if ( 1 == $input['mcl_option_chocolat'] ) {
			$input['mcl_option_chocolat'] = 1;
		} else {
			$input['mcl_option_chocolat'] = 0;
		}

		// Page Setting.
		$input['mcl_page_bg_color'] = esc_attr( $input['mcl_page_bg_color'] );

		$input['mcl_page_bg_url'] = esc_url_raw( $input['mcl_page_bg_url'] );

		if ( ! isset( $input['mcl_bg_x_select'] ) ) {
			$input['mcl_bg_x_select'] = null;
		}

		if ( ! array_key_exists( $input['mcl_bg_x_select'], ms_custom_login_bg_position_x() ) ) {
			$input['mcl_bg_x_select'] = null;
		}

		if ( ! isset( $input['mcl_bg_y_select'] ) ) {
			$input['mcl_bg_y_select'] = null;
		}

		if ( ! array_key_exists( $input['mcl_bg_y_select'], ms_custom_login_bg_position_y() ) ) {
			$input['mcl_bg_y_select'] = null;
		}

		if ( ! isset( $input['mcl_bg_repeat_select'] ) ) {
			$input['mcl_bg_repeat_select'] = null;
		}

		if ( ! array_key_exists( $input['mcl_bg_repeat_select'], ms_custom_login_bg_repeat() ) ) {
			$input['mcl_bg_repeat_select'] = null;
		}

		if ( ! isset( $input['mcl_bg_attach_select'] ) ) {
			$input['mcl_bg_attach_select'] = null;
		}

		if ( ! array_key_exists( $input['mcl_bg_attach_select'], ms_custom_login_bg_attach() ) ) {
			$input['mcl_bg_attach_select'] = null;
		}

		if ( ! isset( $input['mcl_bg_size_select'] ) ) {
			$input['mcl_bg_size_select'] = null;
		}

		if ( ! array_key_exists( $input['mcl_bg_size_select'], ms_custom_login_bg_size() ) ) {
			$input['mcl_bg_size_select'] = null;
		}

		$input['mcl_bg_size_value'] = sanitize_text_field( $input['mcl_bg_size_value'] );

		$input['mcl_text_color'] = esc_attr( $input['mcl_text_color'] );

		$input['mcl_link_color'] = esc_attr( $input['mcl_link_color'] );

		$input['mcl_link_color_hover'] = esc_attr( $input['mcl_link_color_hover'] );

		// Logo Setting.
		if ( ! isset( $input['mcl_show_logo'] ) ) {
			$input['mcl_show_logo'] = null;
		}

		if ( 1 == $input['mcl_show_logo'] ) {
			$input['mcl_show_logo'] = 1;
		} else {
			$input['mcl_show_logo'] = 0;
		}

		if ( ! isset( $input['mcl_logo_link_attr'] ) ) {
			$input['mcl_logo_link_attr'] = null;
		}

		if ( 1 == $input['mcl_logo_link_attr'] ) {
			$input['mcl_logo_link_attr'] = 1;
		} else {
			$input['mcl_logo_link_attr'] = 0;
		}

		$input['mcl_logo_link_url'] = esc_url_raw( $input['mcl_logo_link_url'] );

		$input['mcl_logo_link_title'] = esc_attr( $input['mcl_logo_link_title'] );

		if ( ! isset( $input['mcl_show_logo_img'] ) ) {
			$input['mcl_show_logo_img'] = null;
		}

		if ( 1 == $input['mcl_show_logo_img'] ) {
			$input['mcl_show_logo_img'] = 1;
		} else {
			$input['mcl_show_logo_img'] = 0;
		}

		$input['mcl_logo_url'] = esc_url_raw( $input['mcl_logo_url'] );

		if ( ! isset( $input['mcl_show_logo_text'] ) ) {
			$input['mcl_show_logo_text'] = null;
		}

		if ( 1 == $input['mcl_show_logo_text'] ) {
			$input['mcl_show_logo_text'] = 1;
		} else {
			$input['mcl_show_logo_text'] = 0;
		}

		$input['mcl_text_size'] = absint( $input['mcl_text_size'] );

		$input['mcl_logo_text_color'] = esc_attr( $input['mcl_logo_text_color'] );

		$input['mcl_logo_text_hover'] = esc_attr( $input['mcl_logo_text_hover'] );

		$input['mcl_text_family'] = wp_kses_stripslashes( $input['mcl_text_family'] );

		$input['mcl_text_webfont'] = wp_kses_stripslashes( $input['mcl_text_webfont'] );

		// Form Setting.
		$input['mcl_form_bg_color'] = esc_attr( $input['mcl_form_bg_color'] );

		if ( ! isset( $input['mcl_form_bg_alpha'] ) ) {
			$input['mcl_form_bg_alpha'] = 1;
		}

		if ( ! array_key_exists( $input['mcl_form_bg_alpha'], ms_custom_login_bg_alpha() ) ) {
			$input['mcl_form_bg_alpha'] = 1;
		}

		$input['mcl_form_bg_url'] = esc_url_raw( $input['mcl_form_bg_url'] );

		if ( ! isset( $input['mcl_form_bg_x_select'] ) ) {
			$input['mcl_form_bg_x_select'] = null;
		}

		if ( ! array_key_exists( $input['mcl_form_bg_x_select'], ms_custom_login_bg_position_x() ) ) {
			$input['mcl_form_bg_x_select'] = null;
		}

		if ( ! isset( $input['mcl_form_bg_y_select'] ) ) {
			$input['mcl_form_bg_y_select'] = null;
		}

		if ( ! array_key_exists( $input['mcl_form_bg_y_select'], ms_custom_login_bg_position_y() ) ) {
			$input['mcl_form_bg_y_select'] = null;
		}

		if ( ! isset( $input['mcl_form_bg_repeat_select'] ) ) {
			$input['mcl_form_bg_repeat_select'] = null;
		}

		if ( ! array_key_exists( $input['mcl_form_bg_repeat_select'], ms_custom_login_bg_repeat() ) ) {
			$input['mcl_form_bg_repeat_select'] = null;
		}

		$input['mcl_form_radius'] = absint( $input['mcl_form_radius'] );

		if ( ! isset( $input['mcl_form_boxshadow_radio'] ) ) {
			$input['mcl_form_boxshadow_radio'] = null;
		}

		if ( ! array_key_exists( $input['mcl_form_boxshadow_radio'], ms_custom_login_form_boxshadow() ) ) {
			$input['mcl_form_boxshadow_radio'] = null;
		}

		if ( ! isset( $input['mcl_form_x_select'] ) ) {
			$input['mcl_form_x_select'] = null;
		}

		if ( ! array_key_exists( $input['mcl_form_x_select'], ms_custom_login_bg_position_x() ) ) {
			$input['mcl_form_x_select'] = null;
		}

		if ( ! isset( $input['mcl_form_y_select'] ) ) {
			$input['mcl_form_y_select'] = null;
		}

		if ( ! array_key_exists( $input['mcl_form_y_select'], ms_custom_login_bg_position_y() ) ) {
			$input['mcl_form_y_select'] = null;
		}

		$input['mcl_form_x_pos'] = absint( $input['mcl_form_x_pos'] );

		$input['mcl_form_y_pos'] = absint( $input['mcl_form_y_pos'] );

		// Button Setting.
		$input['mcl_btn_text_color'] = esc_attr( $input['mcl_btn_text_color'] );

		$input['mcl_btn_border_color'] = esc_attr( $input['mcl_btn_border_color'] );

		$input['mcl_btn_bg_color'] = esc_attr( $input['mcl_btn_bg_color'] );

		$input['mcl_btn_bg_hover'] = esc_attr( $input['mcl_btn_bg_hover'] );

		// Links Setting.
		if ( ! isset( $input['mcl_hide_nav'] ) ) {
			$input['mcl_hide_nav'] = null;
		}

		if ( 1 == $input['mcl_hide_nav'] ) {
			$input['mcl_hide_nav'] = 1;
		} else {
			$input['mcl_hide_nav'] = 0;
		}

		if ( ! isset( $input['mcl_hide_backlink'] ) ) {
			$input['mcl_hide_backlink'] = null;
		}

		if ( 1 == $input['mcl_hide_backlink'] ) {
			$input['mcl_hide_backlink'] = 1;
		} else {
			$input['mcl_hide_backlink'] = 0;
		}

		// Custom CSS Setting.
		$input['mcl_custom_css'] = wp_kses_stripslashes( $input['mcl_custom_css'] );
	}
	return $input;
}
