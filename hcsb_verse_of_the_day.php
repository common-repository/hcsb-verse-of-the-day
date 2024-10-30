<?php
/*
Plugin Name: HCSB Verse of the Day (Plus)
Plugin URI: http://techblog.djs-consulting.com/category/programming/wordpress/plug-ins
Description: Provides a Verse (or Passage) of the Day, based on the reference from BibleGateway.com, in several translations.  Originally written to support the Holman Christian Standard Bible, it also supports the English Standard Version, the New International Version, the New King James Version, and the King James Version.
Version: 3.0.1
Author: Daniel J. Summers
Author URI: http://techblog.djs-consulting.com

This plug-in provides a widget and several different template tags, as well as
the ability for you to create your own.  

  - votd_hcsb() - This displays the heading, the Scripture text, the reference
with a link to BibleGateway.com, the HCSB copyright information, and credit to
BibleGateway.com for the reference.  It takes 4 parameters...
    - How to identify the heading (defaults to <h2>).
    - Text to put before the verse (defaults to blank).
    - Text to put after the verse (defaults to blank).
    - Text to use as the separator between the verse and the reference (defaults
      to a new line and an emphasized dash).

If you want to use that tag, but want to change how it works, modify the file
named "votd_hcsb_custom.php" with your HTML and other template tags.  To
future-proof this, do not use get and set methods of the VOTD_HCSB object.

Following are descriptions of the other template tags available.

  - votd_hcsb_heading() - This displays "Verse/Verses/Passage of the Day".
  - votd_hcsb_the_rest() - This displays the text of the verse(s), the
reference with a link to the verse on BibleGateway.com and HCSB copyright
information, and a credit line and link to BibleGateway.com for the reference.
  - votd_hcsb_text() - This displays the text of the verse(s) only.
  - votd_hcsb_reference() - This displays the reference in plain text.
  - votd_hcsb_linked_reference() - This displays the reference with a link to
the verse at BibleGateway.com and HCSB copyright information.
  - votd_hcsb_footer() - This displays the link back to BibleGateway.com and
credits them with the reference.

NOTE:  See "readme.txt" for copyright information about the HCSB.
*/

// We need SimplePie RSS for this plug-in.
require_once( ABSPATH . WPINC . '/feed.php' );

// Assemble the pieces to make one coherent plug-in.
$dir = dirname( __FILE__ );

require_once( $dir . '/votd_hcsb_template_tags.php' );
require_once( $dir . '/votd_hcsb_widget.php'        );
require_once( $dir . '/votd_hcsb_options.php'       );
require_once( $dir . '/votd_hcsb_main_logic.php'    ); 

/** @var VOTD_HCSB The Verse of the Day object instance. */
$votd = new VOTD_HCSB();
