<?php
/*
** ==============================================
** ### Template functions for VR-frases admin ###
** ###            Version: 3.0.1              ###
** ==============================================
*/


// -- Paginate results page list -- //
function vr_frases_form_paginar( $pagina, $paginas, $pos = "" ) {
	if($paginas > 1) { 
?>
		<div class="form-paginar">
			<form id="paginar<?php echo $pos; ?>" action="" method="post">
					<label><?php _e( 'Viewing page ', 'vr-frases' ); ?>
					<select style="min-width: 30px;" name="pagina" id="pagina" onchange="this.form.submit()">
<?php
						for ($i=1; $i<=$paginas; $i++) {
							if ( $pagina != $i ) { 
								echo "<option value='" . $i . "'>" . $i . "</option>";
							} else { 
								echo "<option selected='selected' value=''>" . $pagina . "</option>"; }
	    						} 
?>
					</select><?php _e( ' of ', 'vr-frases' ); echo $paginas; ?></label>
			</form>
		</div>
<?php
	}
}

// Define msg and type of view for display results
function vr_frases_define_titles() {
	if ( !$_GET ) {
		$msg = __( 'You are viewing ALL quotes.', 'vr-frases' );		// display all results (default display)
		$title = __( 'ALL quotes', 'vr-frases' );
	} elseif( isset($_GET['aut']) ) {
		$lista = "frases";							// display results for single author
		$msg =  __( 'You are viewing all Quotes from: ', 'vr-frases' ).'<span class="search-item">'.$_GET['aut'].'</span>';
		$title = __( 'Quotes', 'vr-frases' );

	} elseif ( isset($_GET['autor']) && !isset($_GET['frase']) && !isset($_GET['categoria']) ) {
		$lista = "autores";							// display results for multiple authors without quotes
		$msg = __( 'You are viewing all the Authors with a name similar to: ', 'vr-frases' ).'<span class="search-item">'.$_GET['autor'].'</span>';
		$groupby = " GROUP BY autor";			
		$title = __( 'Authors', 'vr-frases' );
	} else {
		$msg = __( 'You are viewing Search Results for the next criteria.<br />', 'vr-frases' )
		.__( '[Quote: ', 'vr-frases' ).'<span class="search-item">'.$_GET['frase'].'</span>'
		.__( ']  |  [Author: ', 'vr-frases' ).'<span class="search-item">'.$_GET['autor'].'</span>'
		.__( ']  |  [Category: ', 'vr-frases' ).'<span class="search-item">'.$_GET['categoria'].'</span>]';
		$title = __( 'Search results', 'vr-frases' );
	}
	$msglist = array( 'lista' => $lista, 'titulo' => $title, 'msg' => $msg, 'groupby' => $groupby);

	return $msglist;
}

// Calculate total items for searh criteria
function vr_frases_calcular_items ( $groupby, $searchquery ) {
	global $wpdb;
	if( !$groupby ) {
		$query = "SELECT COUNT(frase) FROM ".$wpdb->frases." NATURAL JOIN (".$wpdb->clases.", ".$wpdb->temas.") ".$searchquery;
		$registros = $wpdb->get_var( $query );
	} else {
		$query = "SELECT COUNT(autor) FROM ".$wpdb->frases." NATURAL JOIN (".$wpdb->clases.", ".$wpdb->temas.") ".$searchquery.$groupby;
		$autores =  mysql_query( $query );
		$registros = mysql_num_rows( $autores );
	}
return $registros;
}

// --- Uses $_GET to define search query from user inputs -- 77
function vr_frases_search_query() {
	$numtags = count( $_GET );
	$nomtags = array_keys( $_GET ); 	// obtiene los nombres de las varibles
	$valtags = array_values( $_GET );	// obtiene los valores de las varibles
	
	// crea las variables y les asigna el valor
	for( $i=0; $i<$numtags; $i++ ){ 
		$$nomtags[$i]=$valtags[$i]; 
	}

	if( $_GET['aut'] ) {
		$autor = $_GET['aut'];
	}

	// if quote or author 
	if( $frase <> "" && $autor <> "" ) {
		$qwhere = " WHERE frase LIKE '%".$frase."%' AND autor LIKE '%".$autor."%'";
	} elseif ( $frase <> "" && $autor == "" ) {
		$qwhere = " WHERE frase LIKE '%".$frase."%'";
	} elseif ( $frase == "" && $autor <> "" ) {
		$qwhere = " WHERE autor LIKE '%".$autor."%'";
	} elseif ( $frase == "" && $autor == "" ) {
		$qwhere = "";
	}

	// if category
	if( $categoria == "porfrase" ) {
		$qcat = " ORDER BY frase ASC";	
	} elseif ( $categoria == "porautor" ) {
		$qcat = " ORDER BY autor ASC";	
	} elseif ( $categoria == "portema" ) {
		$qcat = " ORDER BY tema ASC";	
	} elseif ( $categoria == "porclase" ) {
		$qcat = " ORDER BY clase ASC";
	} elseif ( $categoria <> "" && $qwhere <> "" ) {
		$qcat = " AND (clase = '".$categoria."' OR tema = '".$categoria."')";
	} elseif ( $categoria <> "" && $qwhere == "" ) {
		$qcat = " WHERE (clase = '".$categoria."' OR tema = '".$categoria."')";
	} elseif ( $categoria == "") {
		$qcat ="";
	}
	$searchquery = $qwhere.$qcat;				// search criteria
	
	return $searchquery;
}

// --- Generates HTML for search form --- //
function vr_frases_search_form() {
	$vr_frases_searchform = '
	<div class="frases-queryform">
		<form id="frases-searchform" method="get" action="">
			<table style="vertical-align: middle; width: 100%;">
				<thead>
					<tr>
						<td><label>'. __( 'Quote text: ', 'vr-frases' ) .'</label></td>
						<td><label>'. __( 'Author Name: ', 'vr-frases' ) .'</label></td>
						<td><label>'. __( 'Category: ', 'vr-frases' ) .'</label></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><input type="text" name="frase" size="18" /></td>
						<td><input type="text" name="autor" size="18" /></td>
						<td><select name="categoria">
								<option selected="selected" value="">'. __( 'Select an option...', 'vr-frases' ) .'</option>
							<optgroup label="'. __( 'Select Class', 'vr-frases' ) .'">
								'. vr_frases_new_list_clases() .'
							</optgoup>
							<optgroup label="'. __( 'Select Theme', 'vr-frases' ) .'">
								'. vr_frases_new_list_temas() .'
							</optgoup>
							<optgroup label="'. __( 'Select Order', 'vr-frases' ) .'">
								<option style="background:#" value="porfrase">'. __( 'Order by Quotes', 'vr-frases' ) .'</option>
								<option style="background:#" value="porautor">'. __( 'Order by Author', 'vr-frases' ) .'</option>
								<option style="background:#" value="portema">'. __( 'Order by Theme', 'vr-frases' ) .'</option>
								<option style="background:#" value="porclase">'. __( 'Order by Class', 'vr-frases' ) .'</option>
							</optgoup>
						</select></td>
						<input name="accion" type="hidden" value="buscar" />
						<input name="page" type="hidden" value="vrfr_managefrases" />
						<td style="text-align: center"><input class="button" id="xmtsearch" type="submit" value="'. __( 'Search', 'vr-frases' ) .'" name="" /></td>
					</tr>
				</tbody>
			</table>
		</form>
<div class="frases-paginar">'. vr_frases_form_paginar( $pagina, $paginas, 'bottom' ) .'</div>
	</div>';

	return $vr_frases_searchform;
}

// --- Create all themes list for use in <select> search form --- //
function vr_frases_new_list_temas() {
	global $wpdb;

	$temas = $wpdb->get_results( "SELECT * from $wpdb->temas ORDER BY tema ASC" );
	foreach($temas as $tema) { 
		$list_temas .= '<option value="' . $tema->tema . '">' . $tema->tema . '</option>';
	}
	return $list_temas;
}
// --- Create all classes list for use in <select> search form --- //
function vr_frases_new_list_clases() {
	// Crea una lista de clases para usar como filtro
	global $wpdb;

	$clases = $wpdb->get_results( "SELECT * from $wpdb->clases ORDER BY clase ASC" );
	foreach($clases as $clase) { 
		$list_clases .= '<option value="' . $clase->clase . '">' . $clase->clase . '</option>';
	}
	return $list_clases;
}




/*
** -----------------------
** --- Other Functions ---
** -----------------------
*/

// --- Select a random quote. Used in shortcode [randomfrase] and widget display --- //
function vr_frases_random_frase() {
	global $wpdb;
	$options = get_option( 'vr_frases_options' );
	$fila = $wpdb->get_row( "select frase, autor from $wpdb->frases ORDER BY rand()" );
	
	if( $options['sep_lines'] == "1" ) { 
		$sep ="<br />"; 
	} else { $sep = ""; 
	}
	
	if( $options['link_autor'] == "1" ) {
		$autorpart = "<a title='".__( 'View more quotes from this Author...', 'vr-frases' )."' href='".get_settings( 'siteurl' )."/".$options['page_slug']."/?aut=" . $fila->autor . "'><b><em>" . $fila->autor . "</em></b></a>"; 
	} else {
		$autorpart = "<b><em>" . $fila->autor . "</em></b>";
	}
	
	if( $options['side_autor'] == "1" ) {
		 $frase = $autorpart . ": " . $sep . $fila->frase;
	} else {
		$frase = $fila->frase . " " . $sep . $autorpart;
	}
	
	if( $options['hide_autor'] == "1" ) { 
		$frase = $fila->frase;
	}
	return $frase;
}

// --- Counter for total quotes. Used in shortcode [frasescount] --- //
function vr_frases_total_frases() {
	// Muestra el numero total de frases
	global $wpdb;
	$frases = $wpdb->get_var( "SELECT COUNT(frase) AS count FROM $wpdb->frases" );
	return  number_format_i18n( $frases );
}

// --- Counter for total authors. Used in shortcode [autorescount] --- //
function vr_frases_total_autores() {
	// Muestra el numero total de autores
	global $wpdb;
	$frases = $wpdb->get_results( "SELECT COUNT(autor) AS count FROM $wpdb->frases GROUP BY autor" );
	$autores = count( $frases );
	return number_format_i18n( $autores );
}

// --- Counter for total themes --- //
function vr_frases_total_temas() {
	// Muestra el numero total de temas
	global $wpdb;
	$resultado = $wpdb->get_results( "SELECT COUNT(tema) AS count FROM $wpdb->temas GROUP BY tema" );
	$temas = count( $resultado );
	return number_format_i18n( $temas );
}

// --- Counter for total classes --- //
function vr_frases_total_clases() {
	// Muestra el numero total de clases
	global $wpdb;
	$resultado = $wpdb->get_results( "SELECT COUNT(clase) AS count FROM $wpdb->clases GROUP BY clase" );
	$clases = count( $resultado );
	return number_format_i18n( $clases );
}

// --- Display a single quote. The idfrase ($idfrase) is mandatory --- //
function vr_frases_single_frase($idfrase) {
	// Muestra una frase concreta, el argumento corresponde al id de la frase
	global $wpdb;
	$options = get_option( 'vr_frases_options' );
	$fila = $wpdb->get_row( "SELECT * FROM $wpdb->frases WERE idfrase = $idfrase" );
	$frase = '<a title="'.__( 'View more quotes from this Author...', 'vr-frases' ).'" href="'.get_settings( 'siteurl' ).'/'.$options['page_slug'].'/?autor=' . $fila->autor . '&amp;caso=filtro"><b>' . $fila->autor . '</b></a>: '. $fila->frase; 
	echo $frase;
}
?>