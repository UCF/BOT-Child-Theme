<?php

function format_meeting_metadata( $metadata ) {
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
	return $metadata;
}
add_filter( 'ucf_meeting_format_metadata', 'format_meeting_metadata', 10, 1 );

/**
 * Displays meetings
 * Note: REQUIRES meetings to be pulled using the UCF_Meeting class
 * @author Jim Barnes
 * @param $meetings Array<WP_Post>
 **/

function display_meetings( $meetings, $show_videos = true ) {
	ob_start();
?>
	<table class="table table-collapse table-striped small">
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
					<a class="document" href="<?php echo wp_get_attachment_url( $post->metadata['ucf_meeting_agenda'] ); ?>" target="_blank">Download</a>
					<?php else: ?>
					-
					<?php endif; ?>
				</td>
				<td data-title="Minutes">
					<?php if ( isset( $post->metadata['ucf_meeting_minutes'] ) && ! empty( $post->metadata['ucf_meeting_minutes'] ) ) : ?>
					<a class="document" href="<?php echo wp_get_attachment_url( $post->metadata['ucf_meeting_minutes'] ); ?>" target="_blank">Download</a>
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
<?php
	return ob_get_clean();
}

function display_meetings_by_year( $years, $show_videos = true ) {
	ob_start();
	if ( ! $years ) :
?>
	<p>No meetings are scheduled at this time.</p>
<?php
	return ob_get_clean();
	endif;
	reset( $years );
	$first_year = ( is_array( $years ) ) ? (int)key( $years ) : null;
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
			<?php echo display_meetings( $meetings, $show_videos ); ?>
		</div>
	<?php endforeach; ?>
	</div>
<?php
	return ob_get_clean();
}

function get_meetings_committee( $committee, $args=array() ) {
	$args['meta_query'] = array(
		array(
			'key'      => 'ucf_meeting_committee',
			'value'    => $committee->term_id
		)
	);
	return UCF_Meeting::all( $args );
}

function get_meetings_by_year_committee( $committee, $args=array() ) {
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
			)
		)
	);
	return UCF_Meeting::group_by_year( $args );
}

function get_special_meetings_by_year_committee( $committee, $args=array() ) {
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
			array(
				'key'      => 'ucf_meeting_special_meeting',
				'value'    => 1,
				'compare'  => '='
			)
		)
	);
	return UCF_Meeting::group_by_year( $args );
}

function get_latest_meeting_minutes( $committee='None', $args=array() ) {
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
			)
		)
	);
	$meetings = UCF_Meeting::all( $args );
	$meeting = ( count( $meetings ) ) ? $meetings[0] : null;
	if ( $meeting ) {
		$retval = array(
			'name'  => $meeting->metadata['ucf_meeting_date']->format( 'F j, Y' ),
			'file'  => wp_get_attachment_url( $meeting->metadata['ucf_meeting_minutes'] ),
			'video' => isset( $meeting->metadata['ucf_meeting_video'] ) ? $meeting->metadata['ucf_meeting_video'] : null
		);
	}
	return $retval;
}

function get_next_meeting( $committee='None', $args=array() ) {
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
			)
		)
	);
	$meetings = UCF_Meeting::all( $args );
	
	return ( count( $meetings ) ) ? $meetings[0] : null;
}

function get_next_special_meeting( $committee='None', $args=array() ) {
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
?>
