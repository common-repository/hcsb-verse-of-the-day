<?php
/**
 * HCSB Verse of the Day (Plus) - Settings Administration.
 * 
 * This file contains the logic necessary to allow the user to select their
 * version from a list of known versions.
 * 
 * @author Daniel J. Summers <daniel@djs-consulting.com>
 */

/**
 * Register the HCSB Verse of the Day options page.
 */
function votd_hcsb_plugin_menu() {
	add_options_page( 'HCSB Verse of the Day Options', 'HCSB Verse of the Day', 
			'manage_options', 'votd_hcsb',
			'votd_hcsb_plugin_options' );
}

/**
 * Register the options with the settings API.
 */
function votd_hcsb_register_settings() {
	register_setting( 'votd_hcsb', 'votd_hcsb', 'votd_hcsb_validate_options' );
	add_settings_section( 'votd_hcsb_main', 'Settings', 'votd_hcsb_main_text',
			'votd_hcsb' );
	add_settings_field( 'votd_hcsb_translation', 'Translation',
			'votd_hcsb_option_translation', 'votd_hcsb',
			'votd_hcsb_main' );
}

add_action( 'admin_menu', 'votd_hcsb_plugin_menu' );
add_action( 'admin_init', 'votd_hcsb_register_settings' );

/**
 * Handle the option administration for this plugin.
 */
function votd_hcsb_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
<div class="wrap">
	<h2>HCSB Verse of the Day</h2>
	<form method="post" action="options.php"><?php
		settings_fields     ( 'votd_hcsb' );
		do_settings_sections( 'votd_hcsb' );
		do_settings_fields  ( 'votd_hcsb' ); ?>
		<input type="submit" name="Submit" value="<?php esc_attr_e( 'Save Changes' );?>" />
		<p><em>NOTE:</em> To save bandwidth, the plug-in caches the result of
		the verse of the day, and does not obtain it again until the date
		changes.&nbsp; If you want to force it to reload, just save this page
		with no changes.</p>
	</form>
</div>
<?php
}

/**
 * Text to display before the setting group.
 */
function votd_hcsb_main_text() {
	// Silence is golden 
}

function votd_hcsb_option_translation() {
	$options = get_option ( VOTD_HCSB::VOTD_OPTIONS );
?><select size="1" id="votd_hcsb_option_translation" name="votd_hcsb[<?php echo VOTD_HCSB::TRANSLATION; ?>]">
	<option value="HCSB" <?php if ( 'HCSB' == $options[ VOTD_HCSB::TRANSLATION ] ) echo 'selected="selected"'; ?>>Holman Christian Standard Bible (HCSB)</option>
	<option value="ESV"  <?php if ( 'ESV'  == $options[ VOTD_HCSB::TRANSLATION ] ) echo 'selected="selected"'; ?>>English Standard Version (ESV)</option>
	<option value="NKJV" <?php if ( 'NKJV' == $options[ VOTD_HCSB::TRANSLATION ] ) echo 'selected="selected"'; ?>>New King James Version (NKJV)</option>
	<option value="NIV"  <?php if ( 'NIV'  == $options[ VOTD_HCSB::TRANSLATION ] ) echo 'selected="selected"'; ?>>New International Version (NIV)</option>
	<option value="KJV"  <?php if ( 'KJV'  == $options[ VOTD_HCSB::TRANSLATION ] ) echo 'selected="selected"'; ?>>King James Version (KJV)</option>
</select><?php 
}

/**
 * Validate the options from the user.
 * 
 * @param mixed[] $input The options from the page.
 */
function votd_hcsb_validate_options($input) {
	
	$options = get_option( VOTD_HCSB::VOTD_OPTIONS );
	
	$options[ VOTD_HCSB::TRANSLATION ] = $input[ VOTD_HCSB::TRANSLATION ];
	$options[ VOTD_HCSB::DATE        ] = '';
	
	return $options;
}
