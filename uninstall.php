<?php
/*
 * Uninstall plugin
 */
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit ();


function vr_frases_uninstall() {
	global $wpdb;
	// Set table names with WP prefix
	$wpdb->frases = $wpdb->prefix . 'vr_fr_frases';
	$wpdb->clases = $wpdb->prefix . 'vr_fr_clases';
	$wpdb->temas  = $wpdb->prefix . 'vr_fr_temas';

	// Delete options
	$options = get_option( 'vr_frases_options' );
	if ( $options ) { 
		delete_option( 'vr_frases_options' );
		delete_option( 'vr_frases_version' );
		delete_option( 'widget_vr_frases_widget' );
	
	}

	// Delete database
	if ( $wpdb->frases ) {
		$wpdb->query ( "DROP TABLE IF EXISTS ". $wpdb->frases );
		$wpdb->query ( "DROP TABLE IF EXISTS ". $wpdb->clases );
		$wpdb->query ( "DROP TABLE IF EXISTS ". $wpdb->temas );
	}
}

vr_frases_uninstall();

?>