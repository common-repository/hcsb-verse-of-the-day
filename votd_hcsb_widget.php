<?php
/**
 * HCSB Verse of the Day (Plus) - Widget.
 * 
 * This contains the widget code for the HCSB Verse of the Day plug-in.
 * 
 * @author Daniel J. Summers <daniel@djs-consulting.com>
 */

/**
 * Initialize the VOTD widget.
 */
function votd_hcsb_init_widget() {
	
	if ( !function_exists( 'register_sidebar_widget' ) ) return;
	
	/**
	 * Display the HCSB Verse of the Day widget.
	 * 
	 * (NOTE: The body of this widget usese the "textwidget" class, so its
	 * styling should match whatever CSS your theme has for text widgets.)
	 */
	function votd_hcsb_widget ( $args ) {
		global $votd;
		
		extract ( $args );
		echo $before_widget;
		
?>		<h3 class="widget_title"><?php echo $votd->Heading(); ?></h3>
		<div class="textwidget">
		<p><?php echo $votd->Text(); ?><br/>
		<small>&nbsp;&nbsp;&mdash; <?php echo $votd->LinkedReference(); ?><br/><br/>
		<small><?php echo $votd->Footer(); ?></small></small></p></div><?php  
		echo $after_widget;
	}
	
	wp_register_sidebar_widget( 'votd_hcsb_widget', 'HCSB Verse of the Day',
			'votd_hcsb_widget', array( description => 'HCSB Verse of the Day Widget' ) );
}

add_action( 'widgets_init', 'votd_hcsb_init_widget' );
