<?php

function ucf_bot_get_board_members_as_options() {
	$members = ucf_bot_get_board_members();
	$retval = array();
	$retval[''] = '';

	foreach( $members as $member ) {
		$retval[$member->ID] = $member->post_title;
	}

	return $retval;
}

function ucf_bot_get_board_members() {
	$args = array(
		'post_type'      => 'person',
		'posts_per_page' => -1,
		'category_name'  => 'trustee'
	);
	
	return get_posts( $args );
}

?>