<?php get_header(); the_post();?>

<?php if ((is_home() || is_front_page())) : ?>
	<div class="bg-faded">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<?php echo ucf_bot_get_next_meeting_markup(); ?>
				</div>
				<div class="col-md-4">
					<?php echo ucf_bot_get_special_meeting_markup(); ?>
				</div>
				<div class="col-md-4">
					<?php echo ucf_bot_get_latest_meeting_markup(); ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="container my-5">
	<div class="row page-content" id="<?php echo $post->post_name; ?>">
		<div class="col-md-9">
			<?php the_content();?>
		</div>
		<div class="col-md-3">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer();?>
