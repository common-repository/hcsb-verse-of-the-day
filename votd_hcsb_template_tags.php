<?php
/**
 * HCSB Verse of the Day (Plus) - Template Tags.
 * 
 * This file contains the template tags for the HCSB Verse of the Day (Plus)
 * plug-in.
 * 
 * @author Daniel J. Summers <daniel@djs-consulting.com>
 */

/**
 * WordPress Template Tag for the default implementation.
 *
 * Usage: <?php votd_hcsb(); ?>
 *
 * @param string $heading The tag to use for the heading (defaults to "<h2>").
 * @param string $before_text Text to include before the verse (defaults to blank).
 * @param string $after_text Text to include after the verse (defaults to blank).
 * @param string $separator The text to use as a separator between the verse and the
 *                           reference (defaults to a new line and emphasized dash).
 */
function votd_hcsb( $heading = '<h2>', $before_text = '', $after_text = '',
		$separator = '<br /> &mdash; ') {
	
	// If a custom function has been developed, use it.
	if ( function_exists( 'votd_hcsb_custom' ) ) {
		votd_hcsb_custom( $heading, $before_text, $after_text, $separator );
	} else {
		// Display heading
		echo $heading;
		votd_hcsb_heading();
		echo str_replace( '<', '</', $heading );
		
		// Display verse with text before and after.
		echo $before_text;
		votd_hcsb_the_rest( $separator );
		echo $after_text;
	}
}

/**
 * WordPress Template Tag for inserting the heading.
 * 
 * Usage: <?php votd_hcsb_heading(); ?>
 */
function votd_hcsb_heading() {
	global $votd;
		
	echo $votd->Heading();
}

/**
 * WordPress Template Tag for inserting the text, reference, copyright
 * information, and credits.
 * 
 * Usage: <?php votd_hcsb_the_rest(); ?>
 *    or  <?php votd_hcsb_the_rest(' '); ?> to put the reference on the same
 *                                          line as the text
 * 
 * @param string $separator The text to use between the verse and the reference.
 */
function votd_hcsb_the_rest( $separator = '<br /> &mdash; ' ) {
	
	votd_hcsb_text();
	echo $separator;
	votd_hcsb_linked_reference();
	echo '<br /><br />';
	votd_hcsb_footer();
}

/**
 * WordPress Template Tag for inserting the just the text of the verse(s).
 * 
 * Usage: <?php votd_hcsb_text(); ?>
 */
function votd_hcsb_text() {
	global $votd;
		
	echo $votd->Text();
}

/**
 * WordPress Template Tag for inserting the reference in plain text.
 * 
 * Usage: <?php votd_hcsb_reference(); ?>
 */
function votd_hcsb_reference() {
	global $votd;
		
	echo $votd->Reference();
}

/**
 * WordPress Template Tag for inserting the reference with a link to it on
 * BibleGateway.com.
 * 
 * Usage: <?php votd_hcsb_linked_reference(); ?>
 */
function votd_hcsb_linked_reference ( ) {
	global $votd;
		
	echo $votd->LinkedReference();
}

/**
 * WordPress Template Tag for inserting the footer with a link and credits
 * for BibleGateway.com.
 * 
 * Usage: <?php votd_hcsb_footer(); ?>
 */
function votd_hcsb_footer() {
	global $votd;
		
	echo $votd->Footer();
}
