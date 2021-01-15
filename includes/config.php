<?php
/**
 * Handle all theme configuration here
 **/

define( 'THEME_CUSTOMIZER_PREFIX', 'bot_child_' );
define( 'BOT_CHILD_THEME_URL', get_stylesheet_directory_uri() );
define( 'BOT_CHILD_THEME_STATIC_URL', BOT_CHILD_THEME_URL . '/static' );
define( 'BOT_CHILD_THEME_CSS_URL', BOT_CHILD_THEME_STATIC_URL . '/css' );
define( 'BOT_CHILD_THEME_JS_URL', BOT_CHILD_THEME_STATIC_URL . '/js' );

define( 'BOT_CHILD_THEME_CUSTOMIZER_DEFAULTS', serialize( array(
	THEME_CUSTOMIZER_PREFIX . 'board_chair' => null,
	THEME_CUSTOMIZER_PREFIX . 'board_vice_chair' => null,
	THEME_CUSTOMIZER_PREFIX . 'show_board_meeting_videos' => false,
	THEME_CUSTOMIZER_PREFIX . 'show_special_meeting_videos' => false
) ) );

/**
 * Customizer Sections
 **/
function ucf_bot_define_customizer_sections( $wp_customize ) {
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'board_positions',
		array(
			'title' => 'Board Titles'
		)
    );

	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'meeting_videos',
		array(
			'title' => 'Board Meeting Videos'
		)
	);

	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'comments_form',
		array(
			'title' => 'Board Comments Form'
		)
	);
}

add_action( 'customize_register', 'ucf_bot_define_customizer_sections' );

/**
 * Customizer Fields
 **/

function ucf_bot_define_customizer_fields( $wp_customize ) {
	# Board Titles
    $board_members = ucf_bot_get_board_members_as_options();

	$wp_customize->add_setting(
		'board_chair'
    );

	$wp_customize->add_control(
		'board_chair',
		array(
			'type'        => 'select',
			'label'       => 'Board Chair',
			'description' => 'Select the current board chair.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'board_positions',
			'choices'     => $board_members
		)
    );

	$wp_customize->add_setting(
		'board_vice_chair'
    );

	$wp_customize->add_control(
		'board_vice_chair',
		array(
			'type'        => 'select',
			'label'       => 'Board Vice Chair',
			'description' => 'Select the current board vice chair.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'board_positions',
			'choices'     => $board_members
		)
    );

	# Meeting Videos
	$wp_customize->add_setting(
		'show_board_meeting_videos',
		array(
			'default' => true
		)
    );

	$wp_customize->add_control(
		'show_board_meeting_videos',
		array(
			'type'        => 'checkbox',
			'label'       => 'Show Board Meeting Videos',
			'description' => 'Show videos column in the list of board meetings.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'meeting_videos'
		)
    );

	$wp_customize->add_setting(
		'show_special_meeting_videos',
		array(
			'default' => true
		)
    );

	$wp_customize->add_control(
		'show_special_meeting_videos',
		array(
			'type'        => 'checkbox',
			'label'       => 'Show Special Meeting Videos',
			'description' => 'Show videos column in the list of special meetings.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'meeting_videos'
		)
	);

	# Electronic Contact Form
	$wp_customize->add_setting(
		'board_comment_form_url'
	);

	$wp_customize->add_control(
		'board_comment_form_url',
		array(
			'type'        => 'url',
			'label'       => 'Board Comment Form URL',
			'description' => 'The URL of the board comment form.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'comments_form'
		)
	);

	$wp_customize->add_setting(
		'board_comment_form_link_text'
	);

	$wp_customize->add_control(
		'board_comment_form_link_text',
		array(
			'type'        => 'text',
			'label'       => 'Board Comment Form Link Text',
			'description' => 'The text of the board comments form link.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'comments_form'
		)
	);
}

add_action( 'customize_register', 'ucf_bot_define_customizer_fields' );

function ucf_bot_add_footer_menu(){
	register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );
}
add_action( 'after_setup_theme', 'ucf_bot_add_footer_menu' );
