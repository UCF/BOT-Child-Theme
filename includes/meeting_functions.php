<?php

function ucf_bot_format_meeting_metadata( $metadata ) {
	if ( isset( $metadata['ucf_meeting_date'] ) ) {
		$date = new DateTime( $metadata['ucf_meeting_date'] );
		$metadata['ucf_meeting_date'] = $date;
	}
	if ( ! empty( $metadata['ucf_meeting_start_time'] ) ) {
		$date = new DateTime( $metadata['ucf_meeting_start_time'] );
		$metadata['ucf_meeting_start_time'] = $date->format( 'g:i a' );
	} else {
		$metadata['ucf_meeting_start_time'] = 'TBD';
	}
	if ( ! empty ( $metadata['ucf_meeting_end_time'] ) ) {
		$date = new DateTime( $metadata['ucf_meeting_end_time'] );
		$metadata['ucf_meeting_end_time'] = $date->format( 'g:i a' );
	} else {
		$metadata['ucf_meeting_end_time'] = 'TBD';
	}

	// Add logic to get the correct link for the Agenda document
	if ( isset( $metadata['ucf_meeting_agenda_url'] ) && ! empty( $metadata['ucf_meeting_agenda_url'] ) ) {
		$metadata['ucf_meeting_agenda'] = $metadata['ucf_meeting_agenda_url'];
	} else {
		$metadata['ucf_meeting_agenda'] = wp_get_attachment_url( $metadata['ucf_meeting_agenda'] );
	}

	// Add logic to get the correct link for the minutes document
	if ( isset( $metadata['ucf_meeting_minutes_url'] ) && ! empty( $metadata['ucf_meeting_minutes_url'] ) ) {
		$metadata['ucf_meeting_minutes'] = $metadata['ucf_meeting_minutes_url'];
	} else {
		$metadata['ucf_meeting_minutes'] = wp_get_attachment_url( $metadata['ucf_meeting_minutes'] );
	}

	return $metadata;
}
add_filter( 'ucf_meeting_format_metadata', 'ucf_bot_format_meeting_metadata', 10, 1 );

function ucf_bot_get_comments_form_markup() {
	$form_url  = ucf_bot_get_theme_mod_or_default( 'board_comment_form_url' );
	$form_text = ucf_bot_get_theme_mod_or_default( 'board_comment_form_link_text' );

	if ( ! $form_url || ! $form_text ) return '';

	ob_start();
?>
	<p class="my-1 font-80-percent">
		<a class="document comment-request-form" href="<?php echo $form_url; ?>" rel="nofollow">
			<?php echo $form_text; ?>
		</a>
	</p>
<?php
	return ob_get_clean();
}

/**
 * Displays next board meeting
 * @author RJ Bruneel
 **/

function ucf_bot_get_next_meeting_markup() {
	ob_start();
?>
	<div class="bg-faded p-3 mb-4">
		<h3 class="text-uppercase h6 underline-gold mb-3">Next Board Meeting</h3>
		<?php $next_meeting = ucf_bot_get_next_meeting(); if ( $next_meeting ) : ?>
		<div class="row">
			<div class="col-md-1">
				<span class="fa fa-calendar"></span>
			</div>
			<div class="col-md-10">
				<h4 class="h6 mt-1"><?php echo $next_meeting->metadata['ucf_meeting_date']->format( 'F j, Y' ); ?></h4>
				<time class="font-80-percent"><?php echo $next_meeting->metadata['ucf_meeting_start_time']; ?> - <?php echo $next_meeting->metadata['ucf_meeting_end_time']; ?></time>
				<p class="my-1 font-80-percent"><?php echo $next_meeting->metadata['ucf_meeting_location']; ?></p>
				<?php if ( $next_meeting->metadata['ucf_meeting_video'] ) : ?>
				<p class="my-1 font-80-percent"><a class="document" href="<?php echo $next_meeting->metadata['ucf_meeting_video']; ?>" target="_blank">View Livestream</a></p>
				<?php endif; ?>
				<?php if ( $next_meeting->metadata['ucf_meeting_agenda'] ) : ?>
				<p class="mb-0 font-80-percent"><a class="document" href="<?php echo $next_meeting->metadata['ucf_meeting_agenda']; ?>" target="_blank">View Agenda</a></p>
				<?php endif ; ?>
				<?php if ( $next_meeting->metadata['ucf_meeting_additional_document'] && $next_meeting->metadata['ucf_meeting_additional_document_text'] ) :
					$file_url = wp_get_attachment_url( $next_meeting->metadata['ucf_meeting_additional_document'] );
				?>
				<p class="my-1 font-80-percent">
					<a class="document" href="<?php echo $file_url; ?>">
					<?php echo $next_meeting->metadata['ucf_meeting_additional_document_text']; ?>
					</a>
				</p>
				<?php endif; ?>
				<?php echo ucf_bot_get_comments_form_markup(); ?>
			</div>
		</div>
		<?php else: ?>
		<p class="font-80-percent mb-0">No Upcoming Meetings Scheduled</p>
		<?php endif; ?>
	</div>
<?php
	return ob_get_clean();
}

/**
 * Displays latest board meeting minutes
 * @author RJ Bruneel
 **/

function ucf_bot_get_latest_meeting_markup() {
	ob_start();
?>
	<div class="bg-faded p-3 mb-4">
		<h3 class="text-uppercase h6 underline-gold mb-3">Latest Board Minutes</h3>
		<?php $minutes = ucf_bot_get_latest_meeting_minutes(); if ( $minutes && ! empty( $minutes['file'] ) ) : ?>
			<a href="<?php echo $minutes['file']; ?>" class="document latest-board-minutes"><?php echo $minutes['name']; ?></a>
		<?php else : ?>
			<p class="mb-0 font-80-percent">No Minutes Available for Latest Meeting</p>
		<?php endif; ?>
	</div>
<?php
	return ob_get_clean();
}

/**
 * Displays next Board Retreat or Workshop
 * @author RJ Bruneel
 **/

function ucf_bot_get_special_meeting_markup() {
	ob_start();
?>
	<div class="bg-faded p-3 mb-4">
	<h3 class="text-uppercase h6 underline-gold mb-3">Additional Notice</h3>
	<?php $special_meeting = ucf_bot_get_next_special_meeting(); if ( $special_meeting ) : ?>
	<div class="row">
		<div class="col-md-1">
			<span class="fa fa-calendar"></span>
		</div>
		<div class="col-md-10">
			<h4 class="h6 mt-1"><?php echo $special_meeting->metadata['ucf_meeting_date']->format( 'F j, Y' ); ?></h4>
			<time class="font-80-percent"><?php echo $special_meeting->metadata['ucf_meeting_start_time']; ?> - <?php echo $special_meeting->metadata['ucf_meeting_end_time']; ?></time>
			<p class="my-1 font-80-percent"><?php echo $special_meeting->metadata['ucf_meeting_location']; ?></p>
			<?php if ( isset( $special_meeting->metadata['ucf_meeting_special_name'] ) && ! empty( $special_meeting->metadata['ucf_meeting_special_name'] ) ) : ?>
				<p class="my-1 font-80-percent"><em><?php echo $special_meeting->metadata['ucf_meeting_special_name']; ?></em></p>
			<?php endif; ?>
			<?php if ( isset( $special_meeting->metadata['ucf_meeting_agenda'] ) && ! empty( $special_meeting->metadata['ucf_meeting_agenda'] ) ) :
				$special_meeting_agenda = $special_meeting->metadata['ucf_meeting_agenda'];
			?>
				<p class="mb-0 font-80-percent"><a class="document" href="<?php echo $special_meeting_agenda; ?>" target="_blank">View Agenda</a></p>
			<?php endif; ?>
			<?php if ( isset( $special_meeting->metadata['ucf_meeting_video'] ) && ! empty( $special_meeting->metadata['ucf_meeting_video'] ) ) : ?>
				<p class="mt-1 mb-0 font-80-percent"><a class="document" href="<?php echo $special_meeting->metadata['ucf_meeting_video']; ?>" target="_blank">View Livestream</a></p>
			<?php endif; ?>
			<?php if ( $special_meeting->metadata['ucf_meeting_additional_document'] && $special_meeting->metadata['ucf_meeting_additional_document_text'] ) :
					$file_url = wp_get_attachment_url( $special_meeting->metadata['ucf_meeting_additional_document'] );
				?>
				<p class="my-1 font-80-percent">
					<a class="document" href="<?php echo $file_url; ?>">
					<?php echo $special_meeting->metadata['ucf_meeting_additional_document_text']; ?>
					</a>
				</p>
			<?php endif; ?>
		</div>
	</div>
	<?php else: ?>
	<p class="font-80-percent mb-0">No Upcoming Board Retreats or Workshops</p>
	<?php endif; ?>
	</div>
<?php
	return ob_get_clean();
}

/**
 * Displays meetings
 * Note: REQUIRES meetings to be pulled using the UCF_Meeting class
 * @author Jim Barnes
 * @param $meetings Array<WP_Post>
 **/

function ucf_bot_display_meetings( $meetings, $show_videos = true ) {
	ob_start();
?>
	<div class="table-responsive">
		<table class="table table-collapse table-striped font-80-percent w-100 table-meetings">
			<thead>
				<tr>
					<th>Date</th>
					<th>Time</th>
					<th>Location</th>
					<th>Agenda</th>
					<th>Minutes</th>
				<?php if( $show_videos ) { ?>
					<th>Video</th>
				<?php } ?>
				</tr>
			</thead>
			<tbody>
		<?php foreach( $meetings as $post ) : ?>
		<?php
			$date = isset( $post->metadata['ucf_meeting_date'] ) ? $post->metadata['ucf_meeting_date']->format( 'M j, Y' ) : 'TBD';
			$start = isset( $post->metadata['ucf_meeting_start_time'] ) ? $post->metadata['ucf_meeting_start_time'] : null;
			$end = isset( $post->metadata['ucf_meeting_end_time'] ) ? $post->metadata['ucf_meeting_end_time'] : null;
			$location = isset( $post->metadata['ucf_meeting_location'] ) ? $post->metadata['ucf_meeting_location'] : 'TBD';
		?>
				<tr class="mb-3">
					<td data-title="Date"><?php echo $date; ?></td>
					<td data-title="Time">
					<?php if ( ( $start && ! $end ) || ( $start == $end ) ) : ?>
						<time><?php echo $start; ?></time>
					<?php elseif ( $start && $end ) : ?>
						<time><?php echo $start; ?> - <?php echo $end; ?></time>
					<?php else : ?>
						TBD
					<?php endif; ?>
					</td>
					<td data-title="Location"><?php echo ! empty( $location ) ? $location : '-'; ?>
					</td>
					<td data-title="Agenda">
						<?php if ( isset( $post->metadata['ucf_meeting_agenda'] ) && ! empty( $post->metadata['ucf_meeting_agenda'] ) ) : ?>
						<a class="document" href="<?php echo $post->metadata['ucf_meeting_agenda']; ?>" target="_blank">Download</a>
						<?php else: ?>
						-
						<?php endif; ?>
					</td>
					<td data-title="Minutes">
						<?php if ( isset( $post->metadata['ucf_meeting_minutes'] ) && ! empty( $post->metadata['ucf_meeting_minutes'] ) ) : ?>
						<a class="document" href="<?php echo $post->metadata['ucf_meeting_minutes']; ?>" target="_blank">Download</a>
						<?php else: ?>
						-
						<?php endif; ?>
					</td>
				<?php if( $show_videos ) : ?>
					<td data-title="Video">
						<?php if ( isset( $post->metadata['ucf_meeting_video'] ) && ! empty( $post->metadata['ucf_meeting_video'] ) ) : ?>
						<a class="document" href="<?php echo $post->metadata['ucf_meeting_video']; ?>" target="_blank">Watch</a>
						<?php else : ?>
						-
						<?php endif; ?>
					</td>
				<?php endif; ?>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php
	return ob_get_clean();
}

function ucf_bot_display_meetings_by_year( $years, $show_videos = true ) {
	ob_start();
	if ( ! $years ) :
?>
	<p>No meetings are scheduled at this time.</p>
<?php
	return ob_get_clean();
	endif;
	reset( $years );

	$first_year = (int)date( "Y" );
	// If no meetings in the current year select the first item in the array of meetings
	if( is_array( $years ) && !in_array( $first_year, array_keys( $years ) ) ) {
		$first_year = (int)key( $years );
	}
?>
	<div class="row mt-5">
		<div class="col-md-8">
			<h2 class="h5 text-uppercase mb-4">Meetings in <span id="meeting-year"><?php echo $first_year; ?></span></h2>
		</div>
		<div class="col-md-4">
			<div class="meeting-select">
				<label class="form-label font-weight-bold" for="year_select">Select Year</label>
				<select id="year_select" class="form-control dropdown custom-select-sm">
				<?php foreach ( array_keys( $years ) as $year ) :?>
					<option value="<?php echo $year; ?>"<?php echo ( $first_year === $year ) ? ' selected' : ''; ?>><?php echo $year; ?></option>
				<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>
	<div class="tab-content">
	<?php foreach( $years as $year=>$meetings ) : ?>
		<div role="tabpanel" class="tab-pane<?php echo ($first_year === $year) ? ' active' : ''; ?>" id="panel_<?php echo $year; ?>">
			<?php echo ucf_bot_display_meetings( $meetings, $show_videos ); ?>
		</div>
	<?php endforeach; ?>
	</div>
<?php
	return ob_get_clean();
}

function ucf_bot_get_meetings_committee( $committee, $args=array() ) {
	$args['meta_query'] = array(
		array(
			'key'      => 'ucf_meeting_committee',
			'value'    => $committee->term_id
		)
	);
	return UCF_Meeting::all( $args );
}

function ucf_bot_get_meetings_by_year_committee( $committee, $args=array() ) {
	$args['meta_key'] = 'ucf_meeting_date';
	$args['orderby'] = 'meta_value';
	$args['order'] = 'ASC';
	$args['meta_query'] = array(
		array(
			'key'      => 'ucf_meeting_committee',
			'value'    => $committee->term_id,
			'compare'  => 'LIKE'
		),
		array(
			'relation' => 'OR',
			array(
				'key'      => 'ucf_meeting_special_meeting',
				'compare'  => 'NOT EXISTS'
			),
			array(
				'key'      => 'ucf_meeting_special_meeting',
				'value'    => 1,
				'compare'  => '!='
			),
		),
		array(
			'relation' => 'OR',
			array(
				'key'      => 'ucf_meeting_other_meeting',
				'compare'  => 'NOT EXISTS'
			),
			array(
				'key'      => 'ucf_meeting_other_meeting',
				'value'    => 1,
				'compare'  => '!='
			)
		)
	);
	return UCF_Meeting::group_by_year( $args );
}

function ucf_bot_get_special_meetings_by_year_committee( $committee, $args=array() ) {
	$args['meta_key'] = 'ucf_meeting_date';
	$args['orderby'] = 'meta_value';
	$args['order'] = 'ASC';
	$args['meta_query'] = array(
		array(
			'key'      => 'ucf_meeting_committee',
			'value'    => $committee->term_id,
			'compare'  => 'LIKE'
		),
		array(
			'key'      => 'ucf_meeting_special_meeting',
			'value'    => 1,
			'compare'  => '='
		)
	);
	return UCF_Meeting::group_by_year( $args );
}

function ucf_bot_get_other_meetings_by_year_committee( $committee, $args=array() ) {
	$args['meta_key'] = 'ucf_meeting_date';
	$args['orderby'] = 'meta_value';
	$args['order'] = 'ASC';
	$args['meta_query'] = array(
		array(
			'key'      => 'ucf_meeting_committee',
			'value'    => $committee->term_id,
			'compare'  => 'LIKE'
		),
		array(
			'key'      => 'ucf_meeting_other_meeting',
			'value'    => 1,
			'compare'  => '='
		)
	);
	return UCF_Meeting::group_by_year( $args );
}

function ucf_bot_get_latest_meeting_minutes( $committee='None', $args=array() ) {
	$retval = null;
	$today = date('Y-m-d H:i:s');
	$committee = term_exists( $committee, 'people_group' );
	$args = array(
		'posts_per_page' => 1,
		'meta_key'       => 'ucf_meeting_date',
		'meta_type'      => 'DATETIME',
		'orderby'        => 'meta_value',
		'order'          => 'DESC',
		'meta_query'     => array(
			array(
				'key'     => 'ucf_meeting_date',
				'value'   => $today,
				'compare' => '<=',
				'type'    => 'DATETIME'
			),
			array(
				'key'     => 'ucf_meeting_committee',
				'value'   => $committee['term_id'],
				'compare' => 'LIKE'
			),
			array(
				'key'     => 'ucf_meeting_minutes',
				'compare' => 'EXISTS'
			),
			array(
				'key'     => 'ucf_meeting_minutes',
				'compare' => 'NOT IN',
				'value'   => array(
					'',
					null
				)
			)
		)
	);
	$meetings = UCF_Meeting::all( $args );
	$meeting = ( count( $meetings ) ) ? $meetings[0] : null;
	if ( $meeting ) {
		$retval = array(
			'name'  => $meeting->metadata['ucf_meeting_date']->format( 'F j, Y' ),
			'file'  => $meeting->metadata['ucf_meeting_minutes'],
			'video' => isset( $meeting->metadata['ucf_meeting_video'] ) ? $meeting->metadata['ucf_meeting_video'] : null
		);
	}
	return $retval;
}

function ucf_bot_get_next_meeting( $committee='None', $args=array() ) {
	$today = date('Y-m-d');
	$committee = term_exists( $committee, 'people_group' );
	$args = array(
		'posts_per_page' => 1,
		'meta_key'       => 'ucf_meeting_date',
		'meta_type'      => 'DATE',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => 'ucf_meeting_date',
				'value'   => $today,
				'compare' => '>=',
				'type'    => 'DATE'
			),
			array(
				'key'     => 'ucf_meeting_committee',
				'value'   => $committee['term_id'],
				'compare' => '='
			),
			array(
				'relation' => 'OR',
				array(
					'key'      => 'ucf_meeting_special_meeting',
					'compare'  => 'NOT EXISTS'
				),
				array(
					'key'      => 'ucf_meeting_special_meeting',
					'value'    => '1',
					'compare'  => '!='
				)
			),
			array(
				'relation' => 'OR',
				array(
					'key'      => 'ucf_meeting_other_meeting',
					'compare'  => 'NOT EXISTS'
				),
				array(
					'key'      => 'ucf_meeting_other_meeting',
					'value'    => '1',
					'compare'  => '!='
				)
			)
		)
	);
	$meetings = UCF_Meeting::all( $args );

	return ( count( $meetings ) ) ? $meetings[0] : null;
}

function ucf_bot_get_next_special_meeting( $committee='None', $args=array() ) {
	$today = date('Y-m-d');
	$committee = term_exists( $committee, 'people_group' );
	$args = array(
		'posts_per_page' => 1,
		'meta_key'       => 'ucf_meeting_date',
		'meta_type'      => 'DATE',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query' => array(
			array(
				'key'     => 'ucf_meeting_date',
				'value'   => $today,
				'compare' => '>=',
				'type'    => 'DATE'
			),
			array(
				'key'     => 'ucf_meeting_committee',
				'value'   => $committee['term_id'],
				'compare' => '='
			),
			array(
				'key'     => 'ucf_meeting_special_meeting',
				'value'   => '1',
				'compare' => '='
			)
		)
	);
	$meetings = UCF_Meeting::all( $args );
	$meeting = ( count( $meetings ) ) ? $meetings[0] : null;
	return $meeting;
}

function ucf_bot_get_next_other_meeting( $committee='None', $args=array() ) {
	$today = date('Y-m-d');
	$committee = term_exists( $committee, 'people_group' );
	$args = array(
		'posts_per_page' => 1,
		'meta_key'       => 'ucf_meeting_date',
		'meta_type'      => 'DATE',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query' => array(
			array(
				'key'     => 'ucf_meeting_date',
				'value'   => $today,
				'compare' => '>=',
				'type'    => 'DATE'
			),
			array(
				'key'     => 'ucf_meeting_committee',
				'value'   => $committee['term_id'],
				'compare' => '='
			),
			array(
				'key'     => 'ucf_meeting_other_meeting',
				'value'   => '1',
				'compare' => '='
			)
		)
	);
	$meetings = UCF_Meeting::all( $args );
	$meeting = ( count( $meetings ) ) ? $meetings[0] : null;
	return $meeting;
}

?>
