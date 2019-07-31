<?php

// Theme foundation
include_once 'includes/base.php';
include_once 'includes/config.php';
include_once 'includes/meta.php';

// Add other includes to this file as needed.
include_once 'includes/shortcodes.php';
include_once 'includes/meeting_functions.php';
include_once 'includes/committee_functions.php';

/**
 * Updates the people_group custom taxonomies labels
 **/
function people_group_labels( $labels ) {
	$labels['singular'] = 'Committee';
	$labels['plural'] = 'Committees';
	$labels['slug'] = 'committees';
	return $labels;
}

add_filter( 'ucf_people_group_labels', 'people_group_labels', 10, 1 );
