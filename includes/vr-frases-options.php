<?php
/*
** ==========================================
** ### Options and settings for VR-frases ###
** ###            Version: 3.0            ###
** ==========================================
*/

// --- Manage options since WP 2.7 --- //
function vr_frases_manage_options() {
settings_errors($setting = '', $sanitize = FALSE, $hide_on_update = FALSE );
?>	
	<div class="wrap">		
		<div id="icon-options-general" class="icon32"><br /></div><h2><?php _e( 'VR-Frases settings', 'vr-frases' ); ?></h2>
		<form method="post" action="options.php">
     		<?php settings_fields( 'vr_frases_options_group' ); ?>
			<?php $options = get_option( 'vr_frases_options' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th colspan="2" scope="col"><h3><?php _e( 'Settings for main and manage pages', 'vr-frases' ); ?></h3></th>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="num_inputs"><?php _e( 'Quotes per page', 'vr-frases' ); ?></label></th>
					<td><input type="text" name="vr_frases_options[num_inputs]" id="num_inputs" size="6" value="<?php echo $options['num_inputs']; ?>" />
					<span class="description"><br /><?php _e( 'Limit to display results (quotes or authors) per page (must be > 0)', 'vr-frases' ); ?></span></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="num_inputs"><?php _e( 'Page slug', 'vr-frases' ); ?></label></th>
					<td><input type="text" name="vr_frases_options[page_slug]" id="page" size="6" value="<?php echo $options['page_slug']; ?>" />
					<span class="description"><br /><?php _e( 'Caption (slug) of the page wich contain the main shortcode.', 'vr-frases' ); ?></span></td>
				</tr>
				<tr valign="top">
					<th colspan="2" scope="col"><h3><?php _e( 'Settings for widget and [randomfrase] short code', 'vr-frases' ); ?></h3></th>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="hide_autor"><?php _e( 'Hide author', 'vr-frases' ); ?></label></th>
					<td><input type="checkbox" name="vr_frases_options[hide_autor]" id="hide_autor" value="1" <?php checked( '1', $options['hide_autor']) ?>" />
					<span class="description"><?php _e( 'Hide the author name.', 'vr-frases' ); ?></span></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="link_autor"><?php _e( 'Link author', 'vr-frases' ); ?></label></th>
					<td><input type="checkbox" name="vr_frases_options[link_autor]" id="link_autor" value="1" <?php checked( '1', $options['link_autor']) ?>" />
					<span class="description"><?php _e( 'Link the author name to main page in order to view more quotes from him.', 'vr-frases' ); ?></span></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="side_autor"><?php _e( 'Side author', 'vr-frases' ); ?></label></th>
					<td><input type="checkbox" name="vr_frases_options[side_autor]" id="side_autor" value="1" <?php checked( '1', $options['side_autor']) ?>" />
					<span class="description"><?php _e( 'Mark to display author name before the quote. Unmark to display after.', 'vr-frases' ); ?></span></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="sep_lines"><?php _e( 'Separate lines', 'vr-frases' ); ?></label></th>
					<td><input type="checkbox" name="vr_frases_options[sep_lines]" id="sep_lines" value="1" <?php checked( '1', $options['sep_lines']) ?>" />
					<span class="description"><?php _e( 'Mark to insert &lt;br /&gt; between author and quote. Otherwise inserts a blank space.', 'vr-frases' ); ?></span></td>
				</tr>
			</table>
			<p><?php _e( 'You are using <b>VR-frases</b>: version ', 'vr-frases' ); ?><b><?php echo get_option( 'vr_frases_version' ); ?></b></p>
			<fieldset id="box" style="height: auto; border: solid 1px; padding: 10px;">
				<legend style="padding: 5px;"><em><?php _e( 'Sample of output style for [randomfrase] and sidebar widgets:', 'vr-frases' ); ?></em></legend>
				<ul><li><?php echo vr_frases_random_frase(); ?></li></ul>
			</fieldset>
	        	<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e( 'Save changes', 'vr-frases' ); ?>" /></p>
 		</form>
	</div>
<?php
}

// --- Validate and manage errors on update options since WP 3.0 --- //
function vr_frases_options_validate($input) {
	global $options;
	$options = get_option( 'vr_frases_options' );
	// First value is either 0 or 1
	$input['hide_autor'] = ( $input['hide_autor'] == 1 ? 1 : 0 );
	// First value is either 0 or 1
	$input['link_autor'] = ( $input['link_autor'] == 1 ? 1 : 0 );
	// First value is either 0 or 1
	$input['side_autor'] = ( $input['side_autor'] == 1 ? 1 : 0 );
	// First value is either 0 or 1
	$input['sep_lines'] = ( $input['sep_lines'] == 1 ? 1 : 0 );
	// Value must be > 0
	if($input['num_inputs'] <= 0 || $input['num_inputs'] == "") {
		add_settings_error( $input['num_inputs'], 'data_error', __( 'Sorry, the number of inputs per page must be > 0, and cannot be void.', 'vr-frases' ), 'error' );
		$input['num_inputs'] = $options['num_inputs'];
	}
	// Value must not be null
	if(!$input['page_slug'] <> "") {
		add_settings_error( $input['page_slug'], 'data error', __( 'Sorry, the page slug cannot be void.', 'vr-frases' ), 'error' );
		$input['page_slug'] = $options['page_slug'];
	} else {
		$input['page_slug'] = sanitize_title($input['page_slug']);	// just plain text for slug
	}
	return $input;
}
?>