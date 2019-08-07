<?php

function display_committee_members( $people_group ) {
	$people_group_id = $people_group->term_id;
	$chair = get_field( 'people_group_chair', 'people_group_' . $people_group_id );
	$vice_chair = get_field( 'people_group_vice_chair', 'people_group_' . $people_group_id );
	$ex_officio = get_field( 'people_group_ex_officio', 'people_group_' . $people_group_id );
    $exclude = array();
    
	if ( $chair ) {
		$exclude[] = $chair->ID;
	}
	if ( $vice_chair ) {
		$exclude[] = $vice_chair->ID;
	}
	if ( $ex_officio ) {
		$exclude[] = $ex_officio->ID;
    }
    
	// Remove the committee officers from the rest of the members.
	$args = array(
		'post_type'      => 'person',
		'posts_per_page' => -1,
		'post__not_in'   => $exclude,
		'meta_key'       => 'person_last_name',
		'order'          => 'ASC',
		'orderby'        => 'meta_value',
		'category_name'  => 'trustee',
		'tax_query'      => array(
			array(
				'taxonomy' => 'people_group',
				'field'    => 'id',
				'terms'    => $people_group_id
			)
		)
    );
    
    $people = get_posts( $args );    
    if ( count( $people ) < 1 ) return;
    
	ob_start();
?>
	<h2 class="h5 text-uppercase mb-4 mt-5">Committee Members</h2>
	<div class="row">
		<?php if ( $chair ) : $chair = UCF_People_PostType::append_metadata( $chair ); ?>
		<div class="col-6 col-md-4">
			<?php echo get_person_markup( $chair, 'Chair' ); ?>
		</div>
		<?php endif; ?>
		<?php if ( $vice_chair ) : $vice_chair = UCF_People_PostType::append_metadata( $vice_chair ); ?>
		<div class="col-6 col-md-4">
			<?php echo get_person_markup( $vice_chair, 'Vice Chair' ); ?>
		</div>
		<?php endif; ?>
		<?php if ( $ex_officio ) : $ex_officio = UCF_People_PostType::append_metadata( $ex_officio ); ?>
		<div class="col-6 col-md-4">
			<?php echo get_person_markup( $ex_officio, 'Ex Officio' ); ?>
		</div>
		<?php endif; ?>
	<?php foreach( $people as $i=>$person ) : $person = UCF_People_PostType::append_metadata( $person ); ?>
		<div class="col-md-4 col-sm-6">
			<?php echo get_person_markup( $person ); ?>
		</div>
	<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}

function display_committee_staff( $people_group ) {
    $people_group_id = $people_group->term_id;
    
	$args = array(
		'post_type'      => 'person',
		'posts_per_page' => -1,
		'meta_key'       => 'person_last_name',
		'order'          => 'ASC',
		'orderby'        => 'meta_value',
		'category_name'  => 'committee-staff',
		'tax_query'      => array(
			array(
				'taxonomy' => 'people_group',
				'field'    => 'id',
				'terms'    => $people_group_id
			)
		)
    );
    
	$people = get_posts( $args );
    if ( count( $people ) < 1 ) return;
    
	ob_start();
?>
	<h2 class="h5 text-uppercase mb-4 mt-5">Committee Staff</h2>
<?php
    foreach( $people as $i=>$person ) : $person = UCF_People_PostType::append_metadata( $person ); ?>
    
        <?php if ( $i % 3 === 0 ) : ?><div class="row"><?php endif; ?>
        <div class="col-md-4 col-sm-6">
            <?php echo get_person_markup( $person ); ?>
        </div>
        <?php if ( $i % 3 === 2  || $i == count( $people ) - 1 ) : ?></div><?php endif; ?>
<?php
    endforeach;
    
	return ob_get_clean();
}
?>
