<?php get_header(); the_post();?>
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