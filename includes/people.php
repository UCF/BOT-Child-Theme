<?php

function get_board_members_as_options() {
	$members = get_board_members();
	$retval = array();
	$retval[''] = '';

	foreach( $members as $member ) {
		$retval[$member->ID] = $member->post_title;
	}

	return $retval;
}

?>