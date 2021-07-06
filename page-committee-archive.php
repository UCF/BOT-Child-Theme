<?php get_header(); the_post();?>
<div class="container">
	<div class="row page-content" id="<?php echo $post->post_name; ?>">
		<div class="col-md-9">
			<?php the_content();?>
			<ul>
			<?php 
				$committees = get_terms( array( 'people_group' ) );;

				foreach( $committees as $committee ) :
					$archived = get_field( 'people_group_archive_toggle', $committee );
					if( $archived ) :
				?>
						<li>
							<a href="<?php echo get_site_url( null, '/committees/' . $committee->slug ); ?>">
								<?php echo $committee->name; ?> &mdash; archived <?php echo get_field( 'people_group_archive_date', $committee ); ?>
							</a>
						</li>
				<?php 
					endif;
				endforeach; 
				?>
			</ul>
		</div>
		<div class="col-md-3">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer();?>