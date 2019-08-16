<?php
get_header();
the_post();
$post = UCF_People_PostType::append_metadata( $post );
?>
<div class="container mt-5">
	<div class="page-content person-profile mb-5">
		<div class="row">
			<div class="col-md-3 details">
				<?php
				$image = get_the_post_thumbnail_url( $post );
				if ( !$image ) {
					$image = BOT_CHILD_THEME_IMG_URL . '/no-photo.png';
				}
				?>
				<img class="img-fluid rounded-circle" src="<?php echo $image; ?>">
			</div>
			<div class="col-md-6">
				<?php if ( isset( $post->metadata['person_job_title'] ) ) : ?>
					<p class="lead"><?php echo $post->metadata['person_job_title']; ?></p>
				<?php endif; ?>
				<?php if ( $post->post_content ) : the_content(); ?>
				<?php else : ?>
					<p><em>No biography available.</em></p>
				<?php endif; ?>
			</div>
			<div class="col-md-3">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>