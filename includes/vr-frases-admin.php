<?php
/*
** ================================================
** ### Management functions for VR-frases admin ###
** ###               Version: 3.0               ###
** ================================================
*/



/*
** ------------------------------------------
** --- Capture action to select procedure ---
** ------------------------------------------
*/
// --- Select action to page vrfr_managefrases ---
function vr_frases_manage_frases() {
	switch ( $_REQUEST['accion'] ) {
		case 'editar':
			vr_frases_editar_frase( $_GET['idfrase'] );
			break;
		case 'borrar':
			vr_frases_borrar_frase( $_GET['idfrase'] );
			break;
		case 'delfrases':
			vr_frases_mass_delete();
			break;
		default:
			vr_frases_listar_frases();
			break;
	}
}
// --- Select action to page vrfr_manageclases ---
function vr_frases_manage_clases() {
	switch ( $_REQUEST['accion'] ) {
		case 'editar':
			vr_frases_editar_clase( $_GET['idclase'] );
			break;
		case 'borrar':
			vr_frases_borrar_clase( $_GET['idclase'] );
			break;
		case 'delclases':
			vr_frases_mass_delete();
			break;
		default:
			vr_frases_listar_clases();
			break;
	}
}
// --- Select action to page vrfr_managetemas ---
function vr_frases_manage_temas() {
	switch ( $_REQUEST['accion'] ) {
		case 'editar':
			vr_frases_editar_tema( $_GET['idtema'] );
			break;
		case 'borrar':
			vr_frases_borrar_tema( $_GET['idtema'] );
			break;
		case 'deltemas':
			vr_frases_mass_delete();
			break;
		default:
			vr_frases_listar_temas();
			break;
	}
}



/*
** -----------------------------------
** --- Main pages to manage tables ---
** -----------------------------------
*/
// --- Main frases page list --- //
function vr_frases_listar_frases( $pagina = "" ) {
	global $wpdb;

	if ( $_POST['accion'] == 'validar' ) { vr_frases_validar_frase(); }

	$options = get_option( 'vr_frases_options' );
		$numInputs = $options['num_inputs']; 
	$pagina = $_POST['pagina'];
		
	$searchquery = vr_frases_search_query();

	if ( !$_GET['accion'] == "buscar" && $_GET['page'] == "vrfr_managefrases"  && !$_GET['aut'] ) { $_GET = ""; }
	$msglist = vr_frases_define_titles( $_GET );

	$registros = vr_frases_calcular_items( $msglist['groupby'], $searchquery) ;

	if ( !$pagina ) { $inicio = 0; $pagina = 1; } else { $inicio = ( $pagina - 1 ) * $numInputs; } 
	$paginas = ceil($registros / $numInputs); 

	?>

	<div class="wrap">		
		<div id="icon-edit-pages" class="icon32 icon32-posts-page"><br /></div><h1><?php _e( 'Manage Quotes', 'vr-frases' ); ?></h1>

		<h3><?php echo $msglist['msg']; ?></h3>	

		<div class="tablenav">
			<div class="frases-num-items alignleft">
				<h4><?php echo number_format_i18n( $registros ); _e( ' items found', 'vr-frases' ); ?></h4>
				<span class="frases-paginar alignleft"><?php vr_frases_form_paginar( $pagina, $paginas, 'top', $registros ); ?></span>
			</div>
			<div class="alignright" style="height: auto; border: solid 1px; padding: 5px;"><?php echo vr_frases_search_form(); ?></div>
		</div>

			<?php $frases = $wpdb->get_results( "SELECT * FROM ".$wpdb->frases." NATURAL JOIN (".$wpdb->clases.", " .$wpdb->temas.")".$searchquery.$msglist['groupby']." LIMIT ".$inicio.",".$numInputs );
			if ( !empty( $frases ) ) { ?>
			<form name="listform" id="listform" action="" method="post">
				<table class="wp-list-table widefat fixed tags">
					<thead>
						<tr>
							<th scope="col" id="cb" class="manage-column column-cb check-column">
								<input title="<?php _e( 'Select/Unselect all', 'vr-frases' ) ?>" type="checkbox" name="chkid" value="" onclick="SetAllCheckBoxes( 'listform', 'chkid', this.checked );" />
								<label></label>
							</th>
							<th scope="col" style="width: 5%;"><?php _e( 'ID', 'vr-frases' ) ?></th>
							<th scope="col" style="width: 45%;"><?php _e( 'Quote', 'vr-frases' ); ?></th>
							<th scope="col" style="width: 20%;"><?php _e( 'Author', 'vr-frases' ); ?></th>
							<th scope="col" style="width: 10%;"><?php _e( 'Class', 'vr-frases' ); ?></th>
							<th scope="col" style="width: 10%;"><?php _e( 'Theme', 'vr-frases' ); ?></th>
							<th scope="col" style="width: 5%;"><?php _e( 'Edit', 'vr-frases' ); ?></th>
							<th scope="col" style="width: 5%;"><?php _e( 'Delete', 'vr-frases' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach( $frases as $frase ) { $class = ( $class == 'alternate' ) ? '' : 'alternate'; ?>
						<tr class="<?php echo $class; ?>">
							<th scope="row" class="column-cb check-column" scope="row">
								<?php if ( !$msglist['groupby'] ) { ?>
									<input type="checkbox" name="<?php echo $frase->idfrase; ?>" value="<?php echo $frase->idfrase; ?>" />
								<?php } ?>
							</th>
							<td><?php if ( !$msglist['groupby'] ) { echo $frase->idfrase; } ?></td>
							<td><?php if ( !$msglist['groupby'] ) { echo $frase->frase; ?>
								<div class="row-actions">
									<span class="edit"><a href="?page=vrfr_managefrases&amp;accion=editar&amp;idfrase=<?php echo $frase->idfrase; ?>" class='edit'><?php _e( 'Edit', 'vr-frases' ); ?></a></span>
									<span class="delete"> | <a href="?page=vrfr_managefrases&amp;accion=borrar&amp;idfrase=<?php echo $frase->idfrase; ?>" onclick="return confirm( '<?php _e( 'Are you sure deleting this quote?', 'vr-frases' ); ?>' )"><?php _e( 'Delete', 'vr-frases' ); ?></a></span>
								</div>
								<?php } ?>
							</td>
							<td><?php echo '<a title="'.__( 'View more quotes from this Author...', 'vr-frases' ).'" href="?page=vrfr_managefrases&amp;aut='.$frase->autor.'">'.$frase->autor.'</a>'; ?></td>
							<td><?php if ( !$msglist['groupby'] ) { echo $frase->clase; } ?></td>
							<td><?php if ( !$msglist['groupby'] ) { echo $frase->tema; } ?></td>
							<td><?php if ( !$msglist['groupby'] ) { ?>
									<a href="?page=vrfr_managefrases&amp;accion=editar&amp;idfrase=<?php echo $frase->idfrase; ?>" class='edit'><?php _e( 'Edit', 'vr-frases' ); ?></a>
								<?php } ?>
							</td>
							<td><?php if ( !$msglist['groupby'] ) { ?>
									<a href="?page=vrfr_managefrases&amp;accion=borrar&amp;idfrase=<?php echo $frase->idfrase; ?>" onclick="return confirm( '<?php _e( 'Are you sure deleting this quote?', 'vr-frases' ); ?>' )"><?php _e( 'Delete', 'vr-frases' ); ?></a>
								<?php } ?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<th scope="col" class="manage-column column-cb check-column">
								<input title="<?php _e( 'Select/Unselect all', 'vr-frases' ) ?>" type="checkbox" name="chkid" value="" onclick="SetAllCheckBoxes( 'listform', 'chkid', this.checked );" />
								<label></label>
							</th>
							<th scope="col"><?php _e( 'ID', 'vr-frases' ) ?></th>
							<th scope="col"><?php _e( 'Quote', 'vr-frases' ); ?></th>
							<th scope="col"><?php _e( 'Author', 'vr-frases' ); ?></th>
							<th scope="col"><?php _e( 'Class', 'vr-frases' ); ?></th>
							<th scope="col"><?php _e( 'Theme', 'vr-frases' ); ?></th>
							<th scope="col"><?php _e( 'Edit', 'vr-frases' ); ?></th>
							<th scope="col"><?php _e( 'Delete', 'vr-frases' ); ?></th>
						</tr>
					</tfoot>
				</table>
				<p><input name="accion" type="hidden" value="delfrases" />
				<input id="delbutton" class="button" type="submit" value="<?php _e( 'Delete selected', 'vr-visitas' ); ?>" name="" onclick="return confirm( '<?php _e( 'Are you sure you want to delete these quotes?', 'vr-frases' ); ?>' )" name="" /></p>
			</form>

		<?php } else {?>
			<div id="message" class="error clear settings-error"><p><strong><?php _e( 'No quotes to list.', 'vr-frases' ); ?></strong></p></div>
		<?php } ?>
	</div>
<?php
}

// --- Page addnew for quotes ---
function vr_frases_addnew_item() {
	global $wpdb;
	// Procesar una nueva frase
	if ( $_POST["accion"] == "addfrase" ) {
		foreach( $_POST as $key => $value ) {
			if ( $value == "" ) { $error = "error"; } 
		}
		if ( !isset( $error ) ) {
			$frase = $wpdb->get_row( "SELECT * FROM ".$wpdb->frases." NATURAL JOIN (".$wpdb->clases.", " .$wpdb->temas.") WHERE frase = '".$_POST[frase]."' AND autor =  '".$_POST[autor]."'" );
			if ( !empty( $frase ) ) {
				$msg = "<span style='color: #FF0000'>".__( 'Error processing QUOTE.', 'vr-frases' )."</span> ";
				$msg .= __( 'Quote already exists. Data not saved.', 'vr-frases' );
			} else {
				$wpdb->query( "INSERT INTO $wpdb->frases ( autor , frase , idclase , idtema ) VALUES ( '$_POST[autor]', '$_POST[frase]', '$_POST[idclase]', '$_POST[idtema]' ) ");
				if ( !mysql_error() )	{
					$msg = __( 'Quote added successfully: ', 'vr-frases' ).$_POST[frase]." - ".$_POST[autor];
				} else	{
				$msg = "<span style='color: #FF0000'>".__( 'Error processing Quote: mySql Error ', 'vr-frases' ).mysql_error()."</span>";
				}
			}
		} else	{	
			$msg = "<div id='message' class='error settings-error'><p><strong><span style='color: #FF0000'>".__( 'Error processing Quote: Fields cannot be void.', 'vr-frases' )."</span></strong></p></div>";
		}
	}

	if ( $msg != '' ) : ?>
		<div id="message" class="updated settings-error"><p><strong><?php print $msg; ?></strong></p></div>
	<?php endif; ?>
	
	<div class="wrap">		
		<div id="icon-edit" class="icon32 icon32-posts-post"><br /></div><h2><?php _e( 'Add new quote', 'vr-frases' ); ?></h2>
		<div id ="col-left" class="form-wrap">
			<form id="addnew" name="addnew" method="post" action="?page=vrfr_addnewitem">
				<input name="accion" type="hidden" value="addfrase" />
				<div class="form-field">
					<label for="frase"><?php _e( 'Quote', 'vr-frases' ); ?></label>
					<textarea name='frase' id='frase' cols="90" rows="3"><?php echo $_POST['frase']; ?></textarea>
				</div>
				<div class="form-field">
					<label for="autor"><?php _e( 'Author', 'vr-frases' ); ?></label>
					<input type='text' name='autor' id='autor' size='90' value="<?php echo $_POST['autor']; ?>" />
				</div>
				<div class="form-field alignleft">
					<label for="clase"><?php _e( 'Class', 'vr-frases' ); ?></label>
					<select name="idclase" id="idclase">
						<option value=""><?php _e( '*CLASS*', 'vr-frases' ); ?></option>
						<?php
						$clases = $wpdb->get_results( "SELECT * FROM ".$wpdb->clases." ORDER by clase ASC" );
						foreach ( $clases as $clase ) {
							if ( $clase->clase == $_POST['idclase'] ) {
								echo '<option selected="selected" value="' . $clase->idclase . '">' . $clase->clase . '</option>';
							} else {
								echo '<option value="' . $clase->idclase . '">' . $clase->clase . '</option>';
							}
						}
						?> 
					</select>
				</div>
				<div class="form-field alignleft">
					<label for="tema"><?php _e( 'Theme', 'vr-frases' ); ?></label>
					<select name="idtema" id="idtema">
						<option value=""><?php _e( '*THEME*', 'vr-frases' ); ?></option>
						<?php
						$temas = $wpdb->get_results( "SELECT * FROM ".$wpdb->temas." ORDER by tema ASC" );
						foreach ( $temas as $tema ) {
							if ( $tema->tema == $_POST['idtema'] ) {
								echo '<option selected="selected" value="' . $tema->idtema . '">' . $tema->tema . '</option>';
							} else {
							echo '<option value="' . $tema->idtema . '">' . $tema->tema . '</option>';
							}
						}
						?> 
					</select>
				</div>
				<p class="submit clear">
					<input id="addnew" class="button" type="submit" value="<?php _e( 'Save', 'vr-frases' ); ?>" name="" />
				</p>
			</form>
		</div>
	</div>
<?php
}

// --- Main page for classes - List & addnew ---
function vr_frases_listar_clases( $pagina = "" ) {
	global $wpdb;
	$pagina = $_POST['pagina'];
	if ( $_POST['accion'] == 'validar' ) { vr_frases_validar_clase(); }
	if ( $_POST['accion'] == 'addclase' ) { vr_frases_addnew_clase(); }

	$registros = $wpdb->get_var( "SELECT COUNT(clase) as count FROM $wpdb->clases " ); 
	$options = get_option( 'vr_frases_options' );
	$numInputs = $options['num_inputs']; 

	if ( !$pagina ) { $inicio = 0; $pagina = 1; } else { $inicio = ( $pagina - 1 ) * $numInputs; } 
	$paginas = ceil( $registros / $numInputs ); 

	?>

	<div class="wrap">		
		<div id="icon-edit-pages" class="icon32 icon32-posts-page"><br /></div><h2><?php _e( 'Manage classes', 'vr-frases' ); ?></h2>
	
		<div id="col-container">
			<div id="col-right">
				<div class="col-wrap">
					<?php $clases = $wpdb->get_results( "SELECT * from $wpdb->clases ORDER by clase ASC LIMIT ".$inicio.",".$numInputs );
					if ( !empty( $clases ) ) { ?>
					<div class="tablenav top"><?php vr_frases_form_paginar( $pagina, $paginas, "top", $registros ); ?></div>
					<form name="listform" id="listform" action="" method="post">
						<table class="wp-list-table widefat fixed tags">
							<thead>
								<tr>
									<th scope="col" id="cb" class="manage-column column-cb check-column">
										<input title="<?php _e( 'Select/Unselect all', 'vr-frases' ) ?>" type="checkbox" name="chkid" value="" onclick="SetAllCheckBoxes( 'listform', 'chkid', this.checked);" />
										<label></label>
									</th>
									<th scope="col" style="width: 5%;"><?php _e( 'ID', 'vr-frases' ) ?></th>
									<th scope="col" style="width: 65%;"><?php _e( 'Class', 'vr-frases' ); ?></th>
									<th scope="col" style="width: 10%;"><?php _e( 'Quotes', 'vr-frases' ); ?></th>
									<th scope="col" style="width: 10%;"><?php _e( 'Edit', 'vr-frases' ); ?></th>
									<th scope="col" style="width: 10%;"><?php _e( 'Delete', 'vr-frases' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach( $clases as $clase ) { $class = ( $class == 'alternate' ) ? '' : 'alternate'; ?>
								<?php $contador = $wpdb->get_results( "SELECT count(frase) as frases FROM ".$wpdb->frases." NATURAL JOIN ".$wpdb->clases." WHERE idclase = ".$clase->idclase ); ?>
								<tr class="<?php echo $class; ?>">
									<th scope="row" class="check-column" scope="row">
										<?php foreach( $contador as $count ) { $numfrases = $count->frases; } ?>
										<?php if ( !$numfrases > 0 ) { ?>
										<input type="checkbox" name="<?php echo $clase->idclase; ?>" value="<?php echo $clase->clase; ?>" />
										<?php } ?>
									</th>
									<td scope="row"><?php echo $clase->idclase; ?></th>
									<td><?php echo $clase->clase; ?></td>
									<td><?php echo $numfrases; ?></td>
									<td><a href="?page=vrfr_manageclases&amp;accion=editar&amp;idclase=<?php echo $clase->idclase; ?>" class='edit'><?php _e( 'Edit', 'vr-frases' ); ?></a></td>
									<td><?php if ( !$numfrases > 0 ) { ?>
										<a href="?page=vrfr_manageclases&amp;accion=borrar&amp;idclase=<?php echo $clase->idclase; ?>" onclick="return confirm( '<?php _e( 'Are you sure deleting this theme?', 'vr-frases' ); ?>' )"><?php _e( 'Delete', 'vr-frases' ); ?></a>
									<?php } else { _e( 'Delete', 'vr-frases' ); } ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th scope="col" class="manage-column column-cb check-column">
										<input title="<?php _e( 'Select/Unselect all', 'vr-frases' ) ?>" type="checkbox" name="chkid" value="" onclick="SetAllCheckBoxes( 'listform', 'chkid', this.checked);" />
										<label></label>
									</th>
									<th scope="col"><?php _e( 'ID', 'vr-frases' ) ?></th>
									<th scope="col"><?php _e( 'Class', 'vr-frases' ); ?></th>
									<th scope="col"><?php _e( 'Quotes', 'vr-frases' ); ?></th>
									<th scope="col"><?php _e( 'Edit', 'vr-frases' ); ?></th>
									<th scope="col"><?php _e( 'Delete', 'vr-frases' ); ?></th>
								</tr>
							</tfoot>
						</table>
						<div class="tablenav bottom">
							<div class="alignleft">
								<input name="accion" type="hidden" value="delclases" />
								<input id="delbutton" class="button" type="submit" value="<?php _e( 'Delete selected', 'vr-visitas' ); ?>" name="" onclick="return confirm( '<?php _e( 'Are you sure you want to delete these themes?', 'vr-frases' ); ?>' )" name="" />	
								<small><?php _e( 'NOTICE: You only can delete items that do not have related quotes. You can modify them, or go to delete the related quotes before proceed.', 'vr-frases' ); ?></small>	
							</div>
						</div>
					</form>
					<?php } else {?>
						<div id="message" class="updated settings-error"><p><strong><?php _e( 'No classes to list.', 'vr-frases' ); ?></strong></p></div>
					<?php } ?>
				</div>
			</div>

			<div id="col-left">
				<div class="col-wrap">
					<div class="form-wrap">
					<h3><?php _e( 'Add new Class', 'vr-frases' ); ?></h3>
					<form id="posts-filter" name="addnew" method="post" action="">
						<input name="accion" type="hidden" value="addclase" />
						<div class="form-field">
							<label for="clase"><?php _e( 'Class', 'vr-frases' ); ?></label>
							<input type='text' name='clase' id='clase' size='40' />
						</div>
						<p class="submit">
							<input id="addnew" class="button" type="submit" value="<?php _e( 'Save', 'vr-frases' ); ?>" name="" />
						</p>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}

// --- Main page for themes - List & addnew ---
function vr_frases_listar_temas( $pagina = "" ) {
	global $wpdb;
	$pagina = $_POST['pagina'];
	if ( $_POST['accion'] == 'validar' ) { vr_frases_validar_tema(); }
	if ( $_POST['accion'] == 'addtema' ) { vr_frases_addnew_tema(); }

	$registros = $wpdb->get_var( "SELECT COUNT(tema) as count FROM $wpdb->temas " ); 
	$options = get_option( 'vr_frases_options' );
	$numInputs = $options['num_inputs']; 

	if ( !$pagina ) { $inicio = 0; $pagina = 1; } else { $inicio = ( $pagina - 1 ) * $numInputs; } 
	$paginas = ceil( $registros / $numInputs ); 

	?>

	<div class="wrap">		
		<div id="icon-edit-pages" class="icon32 icon32-posts-page"><br /></div><h2><?php _e( 'Manage themes', 'vr-frases' ); ?></h2>
	
		<div id="col-container">
			<div id="col-right">
				<div class="col-wrap">
					<?php $temas = $wpdb->get_results( "SELECT * from $wpdb->temas ORDER by tema ASC LIMIT ".$inicio.",".$numInputs );
					if ( !empty( $temas ) ) { ?>
					<div class="tablenav top"><?php vr_frases_form_paginar($pagina, $paginas, "top", $registros); ?></div>
					<form name="listform" id="listform" method="post" action="">
						<table class="wp-list-table widefat fixed tags">
							<thead>
								<tr>
									<th scope="col" id="cb" class="manage-column column-cb check-column">
										<input title="<?php _e( 'Select/Unselect all', 'vr-frases' ) ?>" type="checkbox" name="chkid" value="" onclick="SetAllCheckBoxes( 'listform', 'chkid', this.checked);" />
										<label></label>
									</th>
									<th scope="col" style="width: 5%;"><?php _e( 'ID', 'vr-frases' ) ?></th>
									<th scope="col" style="width: 65%;"><?php _e( 'Theme', 'vr-frases' ); ?></th>
									<th scope="col" style="width: 10%;"><?php _e( 'Quotes', 'vr-frases' ); ?></th>
									<th scope="col" style="width: 10%;"><?php _e( 'Edit', 'vr-frases' ); ?></th>
									<th scope="col" style="width: 10%;"><?php _e( 'Delete', 'vr-frases' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach( $temas as $tema ) { $class = ( $class == 'alternate' ) ? '' : 'alternate'; ?>
								<?php $contador = $wpdb->get_results( "SELECT count(frase) as frases FROM ".$wpdb->frases." NATURAL JOIN ".$wpdb->temas." WHERE idtema = ".$tema->idtema ); ?>
								<tr class="<?php echo $class; ?>">
									<th scope="row" class="check-column" scope="row">
										<?php foreach( $contador as $count ) { $numfrases = $count->frases; } ?>
										<?php if ( !$numfrases > 0 ) { ?>
										<input type="checkbox" name="<?php echo $tema->idtema; ?>" value="<?php echo $tema->tema; ?>" />
										<?php } ?>
									</th>
									<td scope="row"><?php echo $tema->idtema; ?></th>
									<td><?php echo $tema->tema; ?></td>
									<td><?php echo $numfrases; ?></td>
									<td><a href="?page=vrfr_managetemas&amp;accion=editar&amp;idtema=<?php echo $tema->idtema; ?>" class='edit'><?php _e( 'Edit', 'vr-frases' ); ?></a></td>
									<td><?php if ( !$numfrases > 0 ) { ?>
									<a href="?page=vrfr_managetemas&amp;accion=borrar&amp;idtema=<?php echo $tema->idtema; ?>" onclick="return confirm( '<?php _e( 'Are you sure deleting this theme?', 'vr-frases' ); ?>' )"><?php _e( 'Delete', 'vr-frases' ); ?></a>
									<?php } else { _e( 'Delete', 'vr-frases' ); } ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th scope="col" class="manage-column column-cb check-column">
										<input title="<?php _e( 'Select/Unselect all', 'vr-frases' ) ?>" type="checkbox" name="chkid" value="" onclick="SetAllCheckBoxes( 'listform', 'chkid', this.checked);" />
										<label></label>
									</th>
									<th scope="col"><?php _e( 'ID', 'vr-frases' ) ?></th>
									<th scope="col"><?php _e( 'Theme', 'vr-frases' ); ?></th>
									<th scope="col"><?php _e( 'Quotes', 'vr-frases' ); ?></th>
									<th scope="col"><?php _e( 'Edit', 'vr-frases' ); ?></th>
									<th scope="col"><?php _e( 'Delete', 'vr-frases' ); ?></th>
								</tr>
							</tfoot>
						</table>
						<div class="tablenav bottom">
							<div class="alignleft">
								<input name="accion" type="hidden" value="deltemas" />
								<input id="delbutton" class="button" type="submit" value="<?php _e( 'Delete selected', 'vr-visitas' ); ?>" name="" onclick="return confirm( '<?php _e( 'Are you sure you want to delete these themes?', 'vr-frases' ); ?>' )" name="" />	
								<small><?php _e( 'NOTICE: You only can delete items that do not have related quotes. You can modify them, or go to delete the related quotes before proceed.', 'vr-frases' ); ?></small>	
							</div>
						</div>
					</form>
					<?php } else {?>
						<div id="message" class="updated settings-error"><p><strong><?php _e( 'No themes to list.', 'vr-frases' ); ?></strong></p></div>
					<?php } ?>
				</div>
			</div>

			<div id="col-left">
				<div class="col-wrap">
					<div class="form-wrap">
					<h3><?php _e( 'Add new Theme', 'vr-frases' ); ?></h3>
					<form id="posts-filter" name="addnew" method="post" action="">
						<input name="accion" type="hidden" value="addtema" />
						<div class="form-field">
							<label for="tema"><?php _e( 'Theme', 'vr-frases' ); ?></label>
							<input type='text' name='tema' id='tema' size='40' />
						</div>
						<p class="submit">
							<input id="addnew" class="button" type="submit" value="<?php _e( 'Save', 'vr-frases' ); ?>" name="" />
						</p>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}



/*
** ---------------------------------------
** --- Functions to validate new items ---
** ---------------------------------------
*/
// --- Notice: validate new quote included in our main function ---
//
// --- Validate new class ---
function vr_frases_addnew_clase() {
	global $wpdb;
		// Procesar una nueva clase
	if ( $_POST["accion"] == "addclase" ) {
		if ( !empty( $_POST['clase'] ) ) {
			$clase = $wpdb->get_row( "SELECT * FROM ".$wpdb->clases." WHERE clase = '".$_POST[clase]."' " );
			if ( !empty( $clase ) ) {
				$msg = "<span style='color: #FF0000'>".__( 'Error processing CLASS.', 'vr-frases' )."</span> ";
				$msg .= __( 'Class already exists. Data not saved.', 'vr-frases' );
			} else {
				$wpdb->query("INSERT INTO $wpdb->clases ( clase ) VALUES ( '$_POST[clase]' )");
				if ( !mysql_error() )	{
					$msg = __( 'Class added successfully: ', 'vr-frases' ).$_POST[clase];
				} else	{
				$msg = "<span style='color: #FF0000'>".__( 'Error processing Class. mySql Error: ', 'vr-frases' ).mysql_error()."</span>";
				}
			}
		} else	{	
			$msg = "<div id='message' class='updated settings-error'><p><strong><span style='color: #FF0000'>".__( 'Error processing Class. Fields cannot be void.', 'vr-frases' )."</span></strong></p></div>";

		}
	}
	if ( $msg != '' ) : ?>
		<div id="message" class="updated settings-error"><p><strong><?php print $msg; ?></strong></p></div>
	<?php endif;
}

// --- Validate new theme
function vr_frases_addnew_tema() {
	global $wpdb;
	// Procesar un nuevo tema
	if ( $_POST["accion"] == "addtema" ) {
		if ( !empty( $_POST['tema'] ) )	{
			$tema = $wpdb->get_row(" SELECT * FROM ".$wpdb->temas." WHERE tema = '".$_POST[tema]."' " );
			if ( !empty( $tema ) ) {
				$msg = "<span style='color: #FF0000'>".__( 'Error processing THEME.', 'vr-frases' )."</span> ";
				$msg .= __( 'Theme already exists. Data not saved.', 'vr-frases' );
			} else {
				$wpdb->query( "INSERT INTO $wpdb->temas ( tema ) VALUES ( '$_POST[tema]' )" );
				if ( !mysql_error() )	{
					$msg = __( 'Theme added successfully: ', 'vr-frases' ).$_POST[tema];
				} else	{
				$msg = "<span style='color: #FF0000'>".__( 'Error processing Theme. mySql Error: ', 'vr-frases' ).mysql_error()."</span>";
				}
			}
		} else	{	
			$msg = "<div id='message' class='updated settings-error'><p><strong><span style='color: #FF0000'>".__( 'Error processing Theme. Fields cannot be void.', 'vr-frases' )."</span></strong></p></div>";

		}
	}
	if ( $msg != '' ) : ?>
		<div id="message" class="updated settings-error"><p><strong><?php print $msg; ?></strong></p></div>
	<?php endif; 
}


/*
** -------------------------------
** --- Functions to edit items ---
** -------------------------------
*/
// --- Edit single quote ---
function vr_frases_editar_frase( $id = "" ) {
	global $wpdb;
	$frase = $wpdb->get_row( "SELECT * FROM ".$wpdb->frases." WHERE idfrase=".$id );
	if ( !empty( $frase ) ) { ?>	
	<div class="wrap">		

		<div id="icon-edit" class="icon32 icon32-posts-post"><br /></div><h2><?php _e( 'Edit quote: ', 'vr-frases' ); ?><b><?php echo $_POST['idfrase']; ?></b></h2>
		<form method='post' action='?page=vrfr_managefrases'>
			<input name="accion" type="hidden" value="validar" />
			<input name="idfrase" type="hidden" value="<?php echo $id; ?>" />
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="frase"><?php _e( 'Quote', 'vr-frases' ); ?></label></th>
					<td style='text-align: left'><textarea name='frase' id='frase' cols="90" rows="3"><?php echo $frase->frase; ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label for="autor"><?php _e( 'Author', 'vr-frases' ); ?></label></th>
					<td style='text-align: left'><input type='text' name='autor' id='autor' size='90' value="<?php echo $frase->autor; ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="idclase"><?php _e( 'Class', 'vr-frases' ); ?></label></th>
					<td>
					<select name="idclase" id="idclase">
					<option value=""><?php _e( '*CLASS*', 'vr-frases' ); ?></option>
				<?php $clases = $wpdb->get_results( "SELECT * FROM ".$wpdb->clases." ORDER by clase ASC" );
					foreach ( $clases as $clase ) {
						if ( $clase->idclase == $frase->idclase ) {
							echo '<option selected="selected" value="' . $clase->idclase . '">' . $clase->clase . '</option>';
						} else {
							echo '<option value="' . $clase->idclase . '">' . $clase->clase . '</option>';
						}
					} ?> 
					</select></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="tema"><?php _e( 'Theme', 'vr-frases' ); ?></label></th>
					<td>
					<select name="idtema" id="idtema">
					<option value=""><?php _e( '*THEME*', 'vr-frases' ); ?></option>
					<?php $temas = $wpdb->get_results( "SELECT * FROM ".$wpdb->temas." ORDER by tema ASC" );
					foreach ( $temas as $tema ) {
						if ( $tema->idtema == $frase->idtema ) {
							echo '<option selected="selected" value="' . $tema->idtema . '">' . $tema->tema . '</option>';
						} else {
							echo '<option value="' . $tema->idtema . '">' . $tema->tema . '</option>';
						}
					} ?> 
					</select></td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" value="<?php _e( 'Save', 'vr-frases' ); ?>" name="" /></td>
			</p>
		</form>
	</div>
	<?php }
}

// --- Edit single class ---
function vr_frases_editar_clase( $id = "" ) {
	global $wpdb;
	$clase = $wpdb->get_row( "SELECT * FROM ".$wpdb->clases." WHERE idclase=".$id );
	if ( !empty( $clase ) ) { ?>	
	<div class="wrap">		
		<div id="icon-edit" class="icon32 icon32-posts-post"><br /></div><h2><?php _e( 'Edit class: ', 'vr-frases' ); ?><b><?php echo $id; ?></b></h2>
		<form method='post' action='?page=vrfr_manageclases'>
			<input name="accion" type="hidden" value="validar" />
			<input name="idclase" type="hidden" value="<?php echo $id; ?>" />
			<table class="form-table">
				<tr>
					<th scope="row"><label for="clase"><?php _e( 'Class', 'vr-frases' ); ?></label></th>
					<td style='text-align: left'><input type='text' name='clase' id='clase' size='30' value="<?php echo $clase->clase; ?>" /></td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" value="<?php _e( 'Save', 'vr-frases' ); ?>" name="" /></td>
			</p>
		</form>
	</div>
	<?php }
}

// --- Edit single theme ---
function vr_frases_editar_tema( $id = "" ) {
	global $wpdb;
	$tema = $wpdb->get_row( "SELECT * FROM ".$wpdb->temas." WHERE idtema=".$id );
	if ( !empty( $tema ) ) { ?>	
	<div class="wrap">		
		<div id="icon-edit" class="icon32 icon32-posts-post"><br /></div><h2><?php _e( 'Edit theme: ', 'vr-frases' ); ?><b><?php echo $id; ?></b></h2>
		<form method='post' action='?page=vrfr_managetemas'>
			<input name="accion" type="hidden" value="validar" />
			<input name="idtema" type="hidden" value="<?php echo $id; ?>" />
			<table class="form-table">
				<tr>
					<th scope="row"><label for="tema"><?php _e( 'Theme', 'vr-frases' ); ?></label></th>
					<td style='text-align: left'><input type='text' name='tema' id='tema' size='30' value="<?php echo $tema->tema; ?>" /></td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" value="<?php _e( 'Save', 'vr-frases' ); ?>" name="" /></td>
			</p>
		</form>
	</div>
	<?php }
}



/*
** ------------------------------------------------------------
** --- Functions to validate edited items & update database ---
** ------------------------------------------------------------
*/
// --- Validate single quote ---
function vr_frases_validar_frase() {
	global $wpdb;
	if ( $_POST['accion'] == 'validar' ) {
		foreach( $_POST as $key => $value ) {
			if ( $value == "" ) { $error = "error"; }
		}
		if ( !isset( $error ) ) {
			$wpdb->query( "UPDATE $wpdb->frases SET autor = '$_POST[autor]' , frase = '$_POST[frase]' , idclase = '$_POST[idclase]' , idtema = '$_POST[idtema]' WHERE idfrase = $_POST[idfrase]" );
			if ( !mysql_error() )	{
				$msg = __( 'Quote updated successfully: ', 'vr-frases' ).$_POST[frase]." - ".$_POST[autor];
			} else	{
				$msg = "<span style='color: #FF0000'>".__( 'Error processing Quote: mySql Error ', 'vr-frases' ).mysql_error()."</span>";
			}
		} else	{	
			$msg = "<div id='message' class='updated settings-error'><p><strong><span style='color: #FF0000'>".__( 'Error processing Quote: Fields cannot be void.', 'vr-frases' )."</span></strong></p></div>";
			vr_frases_editar_frase( $_POST['idfrase'] );
			exit ( $msg );
		}
	} ?>
	<div id="message" class="updated settings-error"><p><strong><?php print $msg; ?></strong></p></div>
<?php 
}

// --- Validate single class ---
function vr_frases_validar_clase() {
	global $wpdb;
	
	if ( $_POST['accion'] == 'validar' ) {
		if ( !empty( $_POST['clase'] ) )	{
			$wpdb->query( "UPDATE $wpdb->clases SET clase = '$_POST[clase]' WHERE idclase = $_POST[idclase]" );
			if ( !mysql_error() )	{
				$msg = __( 'Class updated successfully: ', 'vr-frases' ).$_POST[clase];
			} else	{
				$msg = "<span style='color: #FF0000'>".__( 'Error processing Class: mySql Error ', 'vr-frases' ).mysql_error()."</span>";
			}
		} else	{	
			$msg = "<div id='message' class='updated settings-error'><p><strong><span style='color: #FF0000'>".__( 'Error processing Class: Fields cannot be void.', 'vr-frases' )."</span></strong></p></div>";
			vr_frases_editar_clase( $_POST['idclase'] );
			exit ( $msg );
		}
	} ?>
	<div id="message" class="updated settings-error"><p><strong><?php print $msg; ?></strong></p></div>
<?php 
}

// --- Validate single theme ---
function vr_frases_validar_tema() {
	global $wpdb;
	
	if ( $_POST['accion'] == 'validar' ) {
		if ( !empty( $_POST['tema'] ) )	{
			$wpdb->query( "UPDATE $wpdb->temas SET tema = '$_POST[tema]' WHERE idtema = $_POST[idtema]" );
			if ( !mysql_error() )	{
				$msg = __( 'Theme updated successfully: ', 'vr-frases' ).$_POST[idtema].' '.$_POST[tema];
			} else	{
				$msg = "<span style='color: #FF0000'>".__( 'Error processing Theme: mySql Error ', 'vr-frases' ).mysql_error()."</span>";
			}
		} else	{	
			$msg = "<div id='message' class='updated settings-error'><p><strong><span style='color: #FF0000'>".__( 'Error processing Theme: Fields cannot be void.', 'vr-frases' )."</span></strong></p></div>";
			vr_frases_editar_tema( $_POST['idtema'] );
			exit ( $msg );
		}
	} ?>
	<div id="message" class="updated settings-error"><p><strong><?php print $msg; ?></strong></p></div>
<?php 
}



/*
** ---------------------------------
** --- Functions to delete items ---
** ---------------------------------
*/
// --- Delete single quote ---
function vr_frases_borrar_frase( $id = "" ) {
	global $wpdb;
	$frase = $wpdb->get_row( "SELECT * FROM ".$wpdb->frases." WHERE idfrase=".$id );
	if ( !empty( $frase ) ) {
		$msg = __( 'Quote deleted successfully: ', 'vr-frases' ).$frase->idfrase.' - '.$frase->frase.' - '.$frase->autor.'.'; 
		$wpdb->query( "DELETE FROM ".$wpdb->frases." WHERE idfrase=".$id );
		?>
		<div id="message" class="updated settings-error"><p><strong><?php print $msg; ?></strong></p></div>
		<?php
	}
	vr_frases_listar_frases();
}

// --- Delete single class ---
function vr_frases_borrar_clase( $id = "" ) {
	global $wpdb;
	$clase = $wpdb->get_row( "SELECT * FROM ".$wpdb->clases." WHERE idclase=".$id );
	if ( !empty( $clase ) ) {
		$msg = __( 'Class deleted successfully: ', 'vr-frases' ).$clase->idclase.' - '.$clase->clase; 
		$wpdb->query("DELETE FROM ".$wpdb->clases." WHERE idclase=".$id);
		?>
		<div id="message" class="updated settings-error"><p><strong><?php print $msg; ?></strong></p></div>
		<?php
	}
	vr_frases_listar_clases();
}

// --- Delete single theme ---
function vr_frases_borrar_tema( $id = "" ) {
	global $wpdb;
	$tema = $wpdb->get_row( "SELECT * FROM ".$wpdb->temas." WHERE idtema=".$id );
	if ( !empty( $tema ) ) {
		$msg = __( 'Theme deleted successfully: ', 'vr-frases' ).$tema->idtema.' - '.$tema->tema; 
		$wpdb->query( "DELETE FROM ".$wpdb->temas." WHERE idtema=".$id );
		?>
		<div id="message" class="updated settings-error"><p><strong><?php print $msg; ?></strong></p></div>
		<?php
	}
	vr_frases_listar_temas();
}

// --- Delete multiple items ---
function vr_frases_mass_delete() {
	global $wpdb;
	if ( $_POST["accion"] == 'delfrases' ) {
		$database = $wpdb->frases; 
		$iditem = "idfrase";
		$item = "idfrase";
	} elseif ( $_POST["accion"] == 'delclases' ) {
		$database = $wpdb->clases;
		$iditem = "idclase";
		$item = "clase";
	} elseif ( $_POST["accion"] == 'deltemas' ) {
		$database = $wpdb->temas;
		$iditem = "idtema";
		$item = "tema";
	}

	if ($database) {
		foreach ( array_keys($_POST) as $key ) {
			if ( $key != 'accion' && $key != 'chkid' && !empty( $key ) ) {
				$query = "SELECT ".$item." FROM ".$database." WHERE ".$iditem." = ".$key;
				$result = mysql_query( $query );
				$elemento = mysql_result( mysql_query( $query ), 0, $item );
				$wpdb->query( "DELETE FROM ".$database." WHERE ".$iditem." = ".$key );
				if ( !mysql_error( )) {
					if ( empty( $list_items ) ) {
						$list_items = $elemento;
					} else {
						$list_items .= ", " . $elemento;
					}
				} else {				
					$msg = "<span style='color: #FF0000'>".__( 'Error while processing: mysql Error ', 'vr-frases' ). mysql_error()."</span>";
				}
			}
		}
		if ( !empty( $list_items ) && $msg == '' ) { 
			$msg = __( 'Deleted items: ', 'vr-visitas' ).$list_items;
		} else {
			$msg = "<span style='color: #FF0000'>".__( 'Error while processing: Item list cannot be void.', 'vr-frases' )."</span>";
		}
		$wpdb->query("OPTIMIZE TABLE ".$database);
	}
	if ($msg != '' ) : ?>
		<div id="message" class="updated fade"><p><strong><?php print $msg; ?></strong></p></div>
	<?php endif;
	
	if ( $_POST["accion"] == 'delfrases' ) {
		vr_frases_listar_frases();
	} elseif ( $_POST["accion"] == 'delclases' ) {
		vr_frases_listar_clases();
	} elseif ( $_POST["accion"] == 'deltemas' ) {
		vr_frases_listar_temas();
	}
}
?>