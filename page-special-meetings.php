<?php get_header(); the_post();?>
<div class="container">
	<div class="row page-content" id="<?php echo $post->post_name; ?>">
		<div class="col-md-9">
			<?php the_content(); ?>
			<?php 
				$none_term = get_term_by( 'name', 'None', 'people_group' );
				
				if ( $none_term ) {
					$special_meetings = ucf_bot_get_special_meetings_by_year_committee( $none_term );
					$show_videos = ucf_bot_get_theme_mod_or_default( 'show_special_meeting_videos' );
					echo ucf_bot_display_meetings_by_year( $special_meetings, $show_videos );
				}
			?>
		</div>
		<div class="col-md-3">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer();?>