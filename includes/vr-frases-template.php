<?php
/*
** ==============================================
** ### Manage main display page for VR-frases ###
** ###               Version: 3.0             ###
** ==============================================
*/


// --- Main display --- //
function vr_frases_show_main() {
	global $wpdb;
	$options = get_option( 'vr_frases_options' );
		$numInputs = $options['num_inputs']; 				// limit quotes per page

	$searchquery = vr_frases_search_query();				// retrieve user search inputs

	$msglist = vr_frases_define_titles( $_GET );

	$registros = vr_frases_calcular_items ($msglist['groupby'], $searchquery);

	// Prepare data to init paginate function if necessary
	$paginas = ceil( $registros / $numInputs );				// total pages
	$pagina = $_POST["pagina"]; 
		if( !$pagina ) { 
		    $inicio = 0; $pagina=1; 		
		} else { 
		    $inicio = ( $pagina - 1 ) * $numInputs; 
		}

	$prev = $pagina - 1;
	$next = $pagina + 1;
	
	// Display msg for results and page selector
	?>
	<div class="frases-message">
		<div class="message-data"><?php echo $msglist['msg']; ?></div> 
		<div class="frases-num-items"><?php echo number_format_i18n( $registros ); _e( ' items found', 'vr-frases' ); ?></div> 
		<div class="frases-paginar"><?php vr_frases_form_paginar( $pagina, $paginas, 'bottom' ); ?></div> 
	</div>
	
	<?php	
	// Display list of quotes
	$frases = $wpdb->get_results( "SELECT * FROM ".$wpdb->frases." NATURAL JOIN (".$wpdb->clases.", " .$wpdb->temas.") ".$searchquery.$msglist['groupby']." LIMIT " . $inicio . "," . $numInputs ); 
	if ($frases) {
		echo '<div class="frases-display-main">
		<h5>'.$msglist['titulo'].'</h5><ul>';
		foreach( $frases as $frase ) {
			if ($msglist['lista'] == "frases") {
				echo "<li>" . $frase->frase . "</li>";
			} elseif ($msglist['lista'] == "autores") {
				echo '<li><a title="'.__( 'View more quotes from this Author...', 'vr-frases' ).'" href="?aut='.$frase->autor.'">'.$frase->autor.'</a></li>';
			} else {
				echo '<li><b><a title="'.__( 'View more quotes from this Author...', 'vr-frases' ).'" href="?aut='.$frase->autor.'">'.$frase->autor.'</a></b><br />'.$frase->frase.'</li>';
			}
		} 
		echo '</ul></div>';
	} else {
		echo '<div class="frases-display-main">
		<h4>'.__( 'Search results', 'vr-frases' ).'</h4><p>'
		.__( 'No items found to match your search criteria.<br />Please, try another words.', 'vr-frases' ).'</p></div>';
	}

	// Insert search form
	?>
		<div class="frases-paginar"><?php vr_frases_form_paginar( $pagina, $paginas, 'bottom' ); ?></div>
	<?php 
		echo vr_frases_search_form(); 
}
?>