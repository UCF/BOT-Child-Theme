<?php

/***************************************************************************
 * SETTINGS RETRIEVAL FUNCTIONS
 *
 ***************************************************************************/
/**
 * Returns the default value for a setting in ThemeConfig::$setting_defaults,
 * or $fallback if one is not available.
 **/

function get_setting_default( $setting, $fallback=null ) {
	return isset( $setting_defaults[$setting] ) ? ThemeConfig::$setting_defaults[$setting] : $fallback;
}

/**
 * Returns a theme mod, the theme mod's default defined in
 * ThemeConfig::$setting_defaults, or $fallback.
 **/

function get_theme_mod_or_default( $mod, $fallback='' ) {
	return get_theme_mod( $mod, get_setting_default( $mod, $fallback ) );
}

function get_board_members() {
	$args = array(
		'post_type'      => 'person',
		'posts_per_page' => -1,
		'category_name'  => 'trustee'
	);
	return get_posts( $args );
}

function get_board_members_as_options() {
	$members = get_board_members();
	$retval = array();
	// Add an empty value as an option
	$retval[''] = '';
	foreach( $members as $member ) {
		$retval[$member->ID] = $member->post_title;
	}
	return $retval;
}
?>