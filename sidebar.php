<aside class="sidebar mb-5">
	<?php if ( ( ! is_home() && ! is_front_page() ) ) : ?>
		<?php
			echo ucf_bot_get_next_meeting_markup();
			echo ucf_bot_get_special_meeting_markup();
			echo ucf_bot_get_latest_meeting_markup();
		?>
	<?php endif; ?>

	<?php $committees = get_terms( array( 'people_group' ) ); ?>

	<h2 class="text-uppercase h5 mt-5 mb-3">Committees</h2>
	<ul class="list-gold-arrow">
	<?php
	foreach( $committees as $committee ) :
		$archived = get_field( 'people_group_archive_toggle', $committee );
		if( ! $archived ) :
	?>
			<li>
				<a href="<?php echo get_site_url( null, '/committees/' . $committee->slug ); ?>">
					<?php echo $committee->name; ?>
				</a>
			</li>
	<?php
		endif;
	endforeach;
	?>
		<li><a href="<?php echo get_site_url(); ?>/committee-archive/">Past Committee Archive</a></li>
	</ul>
</aside>
