<?php
/**
 * Includes functions that handle registration/enqueuing of meta tags, styles,
 * and scripts in the document head and footer.
 **/

/**
 * Enqueue front-end css and js.
 **/
function bot_child_theme_enqueue_frontend_assets() {
	$theme = wp_get_theme();
	$theme_version = $theme->get( 'Version' );

	wp_enqueue_style( 'style-child', BOT_CHILD_THEME_CSS_URL . '/style.min.css', array( 'style' ), $theme_version );

	wp_enqueue_script( 'script-child', BOT_CHILD_THEME_JS_URL . '/script.min.js', array( 'jquery', 'script' ), $theme_version, true );
}

add_action( 'wp_enqueue_scripts', 'bot_child_theme_enqueue_frontend_assets', 11 );
