<?php


add_action( 'genesis_before_entry', 'featured_post_image', 8 );
function featured_post_image() {
	// don't show image on following
	if ( ! is_singular( 'post' ) || is_single('Zaadii Tso'))  {
  		return;
  	}
  	// show images
	the_post_thumbnail('post-image');
}

genesis();
