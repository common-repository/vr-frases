<?php
/*
Plugin Name: VR-frases
Plugin URI:  http://www.vruiz.net/portfolio/vr-frases/
Description: Creates an manage a list of quotes and authors. Display options for your template or sidebar (widget).
Version:     3.0.1
Author:      Vicente Ruiz
Author URI:  http://www.vruiz.net
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: vr-frases
Domain Path: /languages
*/


/* 
Copyright 2006-2017  Vicente Ruiz (vr-frases : webmaster@vruiz.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



/*
** --------------------------------
** --- Declare global variables ---
** --------------------------------
*/
global $wpdb;
	// Set table names with WP prefix
	$wpdb->frases = $wpdb->prefix . 'vr_fr_frases';
	$wpdb->clases = $wpdb->prefix . 'vr_fr_clases';
	$wpdb->temas  = $wpdb->prefix . 'vr_fr_temas';


/*
** -------------------------
** --- Plugin activation ---
** -------------------------
*/
function vr_frases_activar() {
	global $wpdb;

	$version = get_option( 'vr_frases_version' );				// get installed version
	$options = get_option( 'vr_frases_options' );				// get options (if exists)

	if ( !$version || $version < '2.0' ) {
		vr_frases_upgrade();								// update database & options
	} elseif ( $version < '3.0.0' ) { 
		update_option( 'vr_frases_version', '3.0.0' );			// no need upgrade database
	}
}
register_activation_hook( __FILE__, 'vr_frases_activar' );
		
// Upgrade database and options			
function vr_frases_upgrade() {
	global $wpdb;
	$wpdb->oldest = $wpdb->prefix . 'fr_frases';

	// look for previous install.
	$oldest = $wpdb->get_var("SHOW TABLES LIKE '$wpdb->oldest'");		// verify if oldest tables exists
	$testdb = $wpdb->get_var("SHOW TABLES LIKE '$wpdb->frases'");		// verify if actual tables exists

	if ( $oldest ) {
		$msg = '<div id="message" class="updated fade"><p><strong>'
		.__( 'Warning: Updating form oldest version! You need upgrade manually to 1.5.x. version previous to install 2.0.x or your data wlll be lost.', 'vr-frases' ).
		'</strong></p></div>';								
		exit ($msg);							// oldest data found, then NON update, NON install
	} elseif ( !$testdb ) {
		vr_frases_new_first();						// no data found, then NEW install
	} elseif ( $testdb ) {
		update_option( 'vr_frases_version', '3.0.0' );			// found data from versions 1.5.x or later no need upgrade
	}
}


function vr_frases_new_first() {
	global $wpdb;
	require_once(ABSPATH.'/wp-admin/includes/upgrade.php');

	// --- Create or update tables and add first entry in every table. This entry will be deleted later. --- //
	$create_frases = "CREATE TABLE ". $wpdb->frases . " (
							idfrase int(11) NOT NULL auto_increment,
							autor text NOT NULL default '',
							frase text NOT NULL default '',
							idclase int(11) NOT NULL default '1',
							idtema int(11) NOT NULL default '1',
							PRIMARY KEY (idfrase)
							);";
	dbDelta($create_frases);
		$frase = "Primera frase - First Quote";
		$autor = "autor - Author";
		$idclase = "1";
		$idtema = "1";
		$frases = $wpdb->get_results( "select * from $wpdb->frases" );
		if (!$frases) {
			$wpdb->query( "INSERT INTO $wpdb->frases ( autor , frase , idclase , idtema ) VALUES ( '$autor' , '$frase' , '$idclase' , '$idtema' )" );
		}

	$create_clases = "CREATE TABLE ". $wpdb->clases . " (
							idclase int(11) NOT NULL auto_increment,
							clase tinytext NOT NULL default '',
							PRIMARY KEY (idclase)
							);";
	dbDelta($create_clases);
		$clase = "-n/a-";
		$clases = $wpdb->get_results( "select * from $wpdb->clases" );
		if (!$clases) {
			$wpdb->query( "INSERT INTO $wpdb->clases ( clase ) VALUES ( '$clase' )" );
		}

	$create_temas = "CREATE TABLE ". $wpdb->temas . " (
							idtema int(11) NOT NULL auto_increment,
							tema tinytext NOT NULL default '',
							PRIMARY KEY (idtema)
							);";
	dbDelta($create_temas);
		$tema = "-n/a-";
		$temas = $wpdb->get_results( "select * from $wpdb->temas" );
		if (!$temas) {
			$wpdb->query( "INSERT INTO $wpdb->temas ( tema ) VALUES ( '$tema' )" );
		}

	// --- Add init options --- //
	$new_options = array(
			'num_inputs' => 25, 
			'page_slug' => 'frases',
			'hide_autor' => 0,
			'link_autor' => 0,
			'side_autor' => 0,
			'sep_lines' => 0
			);
	add_option( 'vr_frases_options', $new_options );
	update_option( 'vr_frases_version', '3.0.0' );
}



/*
** -----------------------
** --- Admin functions ---
** -----------------------
*/
if (!is_admin()) {
	add_action( 'init', 'vr_frases_enqueue_style' );
}
	add_action( 'init', 'vr_frases_textdomain' );
	add_action( 'admin_init', 'vr_frases_enqueue_script' );
	add_action( 'admin_init', 'vr_frases_register_settings' );
	add_action( 'admin_menu', 'vr_frases_add_menu' );

// --- Create Text Domain For Translations
function vr_frases_textdomain() {
	load_plugin_textdomain( 'vr-frases', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

// --- Register option setings ---
function vr_frases_register_settings() {
	register_setting( 'vr_frases_options_group', 'vr_frases_options', 'vr_frases_options_validate' );
}

// --- Enqueue stylesheet --
function vr_frases_enqueue_style() {
	wp_register_style( 'vr-frases', WP_PLUGIN_URL . '/vr-frases/css/vr-frases.css' );
	wp_enqueue_style( 'vr-frases', WP_PLUGIN_URL . '/vr-frases/css/vr-frases.css' );
}

// --- Enqueue script --
function vr_frases_enqueue_script() {
	wp_register_script( 'vr-frases', WP_PLUGIN_URL . '/vr-frases/scripts/functions.js' );
	wp_enqueue_script( 'vr-frases', WP_PLUGIN_URL . '/vr-frases/scripts/functions.js' );
}


// --- Create menu tabs to Admin panel ---
function vr_frases_add_menu() {
	add_menu_page( __( 'VR-frases', 'vr-frases' ), __('VR-frases', 'vr-frases' ), 'manage_options', 'vrfr_managefrases', '', plugins_url( 'vr-frases/images/menu.png' ) );
	add_submenu_page( 'vrfr_managefrases', __( 'VR-frases Frases', 'vr-frases' ), __( 'Manage Quotes', 'vr-frases' ), 'manage_options', 'vrfr_managefrases', 'vr_frases_manage_frases' );
	add_submenu_page( 'vrfr_managefrases', __( 'VR-frases Frases', 'vr-frases' ), __( 'Add new Quote', 'vr-frases' ), 'manage_options', 'vrfr_addnewitem', 'vr_frases_addnew_item' );
	add_submenu_page( 'vrfr_managefrases', __( 'VR-frases Classes', 'vr-frases' ), __( 'Manage Classes', 'vr-frases' ), 'manage_options', 'vrfr_manageclases', 'vr_frases_manage_clases' );
	add_submenu_page( 'vrfr_managefrases', __( 'VR-frases Themes', 'vr-frases' ), __( 'Manage Themes', 'vr-frases' ), 'manage_options', 'vrfr_managetemas', 'vr_frases_manage_temas' );
	add_submenu_page( 'vrfr_managefrases', __( 'VR-frases Settings', 'vr-frases' ), __( 'Settings', 'vr-frases' ), 'manage_options', 'vrfr_managesettings', 'vr_frases_manage_options' );
}

// --- Add Settings link that display in plugin spage
function vr_frases_action_links( $links, $file ) {
    static $vr_frases;
 
    if (!$vr_frases) {
        $vr_frases = plugin_basename(__FILE__);
    }
    if ( $file == $vr_frases ) {
        // The "page" query string value must be equal to the slug of the Settings admin page we defined earlier, which in this case equals "myplugin-settings".
        $settings_link = '<a href="admin.php?page=vrfr_managesettings">'. __( 'Settings', 'vr-frases' ) .'</a>';
        array_unshift( $links, $settings_link) ;
    }
 
    return $links;
}
add_filter('plugin_action_links', 'vr_frases_action_links', 10, 2);





/*
** ------------------------------
** --- Include manage modules ---
** ------------------------------
*/
if (is_admin()) {
	include_once dirname( __FILE__ ) . '/includes/vr-frases-admin.php';
	include_once dirname( __FILE__ ) . '/includes/vr-frases-options.php';
	include_once dirname( __FILE__ ) . '/includes/vr-frases-functions.php';
	include_once dirname( __FILE__ ) . '/includes/vr-frases-widgets.php';
}

if (!is_admin()) { 
	include_once dirname( __FILE__ ) . '/includes/vr-frases-template.php';
	include_once dirname( __FILE__ ) . '/includes/vr-frases-functions.php';
	include_once dirname( __FILE__ ) . '/includes/vr-frases-shortcodes.php';
	include_once dirname( __FILE__ ) . '/includes/vr-frases-widgets.php';
}

/*
** -----------
** --- END ---
** -----------
*/
?>