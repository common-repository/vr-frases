<?php
/*
** ======================================
** ### Shortcodes for VR-frases admin ###
** ###           Version: 3.0         ###
** ======================================
*/

// --- [vrfrases] - Displays main page content and search form --- //
function vr_frases_show_shortcode( $atts, $content = null, $code="" ) {
	$content = vr_frases_show_main();
	return $content;
}
add_shortcode( 'vrfrases', 'vr_frases_show_shortcode' );

// --- [randomfrase] - Includes a random phrase into post or page content --- //
function vr_frases_randomfrase_shortcode( $atts, $content = null, $code = "" ) {
	$content = vr_frases_random_frase();
	return $content;
}
add_shortcode( 'randomfrase', 'vr_frases_randomfrase_shortcode' );

// --- [frasescount] - Return an integer with the number of phrases --- //
function vr_frases_frasescount_shortcode( $atts, $content = null, $code = "" ) {
	$content = vr_frases_total_frases();
	return $content;
}
add_shortcode( 'frasescount', 'vr_frases_frasescount_shortcode' );

// --- [autorescount] - Return an integer with the number of authors --- //
function vr_frases_autorescount_shortcode( $atts, $content = null, $code = "" ) {
	$content =  vr_frases_total_autores();
	return $content;
}
add_shortcode( 'autorescount', 'vr_frases_autorescount_shortcode' );



/*
** --- ### Deprecated functions from older versions ### ---
*/

function fr_form_autor(){
	vr_frases_form_autor();	
}
function fr_form_palabra(){
	vr_frases_form_texto();
}
function fr_form_filtro(){
	vr_frases_form_filtro();
}
function vr_show_frases(){
	vr_frases_show_main();
}
?>