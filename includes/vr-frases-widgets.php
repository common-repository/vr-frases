<?php
/*
** ========================================
** ### FrasesWidget Class for VR-frases ###
** ###            Version: 3.0          ###
** ========================================
*/

// --- Class to manage widget on several instances --- //

class VR_Frases_Widget extends WP_Widget {

	/** constructor */

	function VR_Frases_Widget() {

		//parent::WP_Widget( 'fraseswidget', $name = 'VR-Frases' );

	       //parent::WP_Widget( false, $name = 'VR_Frases_Widget' );

       	$widget_ops = array( 'classname' => 'vr-frases_widget', 'description' => __( 'This widget display a random phrase on every page reload.', 'vr-frases' ) );

	       $this->WP_Widget( 'VR_Frases_Widget', __( 'VR-frases', 'vr-frases' ), $widget_ops);

	}



	/** @see WP_Widget::widget */

	function widget( $args, $instance ) {

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;

		if ( $title )

		echo $before_title . $title . $after_title; 

		echo vr_frases_random_frase();

		echo $after_widget;

	}



	/** @see WP_Widget::update */

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;

	}



	/** @see WP_Widget::form */

	function form( $instance ) {

		if ( $instance ) {

			$title = esc_attr( $instance[ 'title' ] );

		}

		else {

			$title = __( 'New title', 'vr-frases' );

		}

		?>

		<p>

			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'vr-frases'); ?></label> 

			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />

		</p>

		<?php 

	}



} // class VR_Frases_Widget







/*

** --- ### Dashboard Widget ### ---

*/



// --- Add a widget to dashboard --- //

function vr_frases_dash_widget() {

	$frases = vr_frases_total_frases();

	$autores = vr_frases_total_autores();

	$temas = vr_frases_total_temas();

	$clases = vr_frases_total_clases();

	$random = vr_frases_random_frase();

	$version = get_option('vr_frases_version');



	echo '<p><b>'.__( 'Registered data totals:', 'vr-frases' ).'</u></b>';

	echo '<ul><li>'.__( 'At this momment your database contents ', 'vr-frases' ).'<a href="admin.php?page=vrfr_managefrases">'.$frases.'&nbsp;'.__( 'Quotes', 'vr-frases' ).'</a>'.__( ' from ', 'vr-frases' ).$autores.'&nbsp;'.__( 'Authors', 'vr-frases' ).'.</li></ul>';

	echo '<ul><li>'.__( 'Your classification handlers: ', 'vr-frases' ).'<a href="admin.php?page=vrfr_manageclases">'.$clases.'&nbsp;'.__( 'Classes', 'vr-frases' ).'</a>'.__( ' and ', 'vr-frases' ).'<a href="admin.php?page=vrfr_managetemas">'.$temas.'&nbsp;'.__( 'Themes', 'vr-frases' ).'</a>.</li></ul>';

	echo '<fieldset id="box" style="height: auto; border: solid 1px; padding: 10px;">';

		echo '<legend style="padding: 5px;"><em>'.__( 'Sample of output style for [randomfrase] and sidebar widgets:', 'vr-frases' ).'</em></legend>';

		echo '<ul><li>'.$random.'</li></ul>';

	echo '</fieldset>';

	echo '<p>'.__( 'You are using <b>VR-frases</b> version: ', 'vr-frases' ).'<b>'.$version.'</b></p>';

}



function vr_frases_add_dashboard_widget() {

	wp_add_dashboard_widget( 'vr_frases_dashboard',  __( 'Take a look for VR-frases', 'vr-frases' ), 'vr_frases_dash_widget' );

}



// --- Register widget --- //

add_action( 'widgets_init', create_function( '', 'return register_widget( "VR_Frases_Widget" );' ) );



// --- Insert dashboard widget only for admin pages --- //

if (is_admin()) {

	add_action( 'wp_dashboard_setup', 'vr_frases_add_dashboard_widget' ); 

}

?>