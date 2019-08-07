<?php

/**
 * Returns a theme mod's default value from a constant.
 *
 * @since 1.0.0
 * @param string $theme_mod The name of the theme mod
 * @param string $defaults Serialized array of theme mod names + default values
 * @return mixed Theme mod default value, or false if a default is not set
 **/
function ucfbot_get_theme_mod_default( $theme_mod, $defaults=BOT_CHILD_THEME_CUSTOMIZER_DEFAULTS ) {
	$defaults = unserialize( $defaults );
	if ( $defaults && isset( $defaults[$theme_mod] ) ) {
		return $defaults[$theme_mod];
	}
	return false;
}

/**
 * Returns a theme mod value or the default set in
 * $defaults if the theme mod value hasn't been set yet.
 *
 * @since 1.0.0
 * @param string $theme_mod The name of the theme mod
 * @param string $defaults Serialized array of theme mod names + default values
 * @return mixed Theme mod value or its default
 **/
function ucfbot_get_theme_mod_or_default( $theme_mod, $defaults=BOT_CHILD_THEME_CUSTOMIZER_DEFAULTS ) {
	$default = ucfwp_get_theme_mod_default( $theme_mod, $defaults );
	return get_theme_mod( $theme_mod, $default );
}

?>