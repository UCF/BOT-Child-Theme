<?php
get_header();
$term = $wp_query->get_queried_object();
$today = new DateTime( 'now' );
$archived = get_field( 'people_group_archive_toggle', $term );
?>

<div class="container mt-4 mb-5">
	<div class="row">
		<div class="col-md-9">

		<?php if( ! $archived ) : ?>
			<p class="lead font-weight-light"><?php echo $term->description; ?></p>
		<?php else :
			$archive_message = get_field( 'people_group_archive_message', $term );
			if ( ! $archive_message ) {
				$archived_date = get_field( 'people_group_archive_date', $term );
				$archived_date = ( $archived_date ) ? ' on ' . $archived_date : '';
				$archive_message = 'This committee was archived' . $archived_date . ' and is no longer active.';
			}
		?>
			<div class="lead font-weight-light">
				<?php echo $archive_message; ?>
			</div>
		<?php endif; ?>

		<?php
			$meetings = ucf_bot_get_meetings_by_year_committee( $term );
			$show_videos = get_field( 'people_group_video_toggle', $term );

			echo ucf_bot_display_meetings_by_year( $meetings, $show_videos );

			if( ! $archived ) :
				echo ucf_bot_display_committee_members( $term );
				echo ucf_bot_display_committee_staff( $term );
			endif;

			$charter = get_field( 'people_group_charter', 'people_group_' . $term->term_id );

			if( $charter ) :
		?>
			<h2 class="h5 text-uppercase mb-4 mt-5">Committee Charter</h2>
			<a class="document" href="<?php echo $charter; ?>"><?php echo $term->name; ?> Committee Charter</a>
		<?php
			endif;
		?>
		</div>
		<div class="col-md-3">
		<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
