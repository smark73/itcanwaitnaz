<?php
// Remove Page/Post Title
//remove_action( 'genesis_post_title', 'genesis_do_post_title' );
//remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

//* Remove the post meta function
//remove_action( 'genesis_after_post_content', 'genesis_post_meta' );




// custom page header
add_action( 'genesis_after_header', 'cust_pg_hdr' );
function cust_pg_hdr() {
    ?>
        <div class="icw-hdr">

            <div class="one-half first">
                    <?php
                    $msplash_left_args = array(
                        'post_type' => 'page',
                        'pagename' => 'main-splash-left',
                    );
                    $msplash_left_query = new WP_Query( $msplash_left_args );
                    if( $msplash_left_query->have_posts() ){
                        while ( $msplash_left_query->have_posts() ) {
                            $msplash_left_query->the_post();
                            global $post;

                            //wp_get_attachment_image_src($attachment_id) returns an array with
                            //[0] => url
                            //[1] => width
                            //[2] => height
                            //[3] => boolean: true if $url is a resized image, false if it is the original or if no image is available.
                            //$full_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
                            $the_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
  
                            echo '<div class="main-splash-left">';
                            the_content();
                            echo '</div>';
                        }
                    }
                    ?>
            </div>

            <div class="one-half">
                    <?php
                    $msplash_right_args = array(
                        'post_type' => 'page',
                        'pagename' => 'main-splash-right',
                    );
                    $msplash_right_query = new WP_Query( $msplash_right_args );
                    if( $msplash_right_query->have_posts() ){
                        while ( $msplash_right_query->have_posts() ) {
                            $msplash_right_query->the_post();
                            global $post;

                            //wp_get_attachment_image_src($attachment_id) returns an array with
                            //[0] => url
                            //[1] => width
                            //[2] => height
                            //[3] => boolean: true if $url is a resized image, false if it is the original or if no image is available.
                            //$full_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
                            $the_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
  
                            echo '<div class="main-splash-right" style="background:url(' . $the_img[0] . ');background-size:cover;background-repeat:no-repeat;background-position:top center;">';
                            the_content();
                            echo '</div>';
                        }
                    }
                    ?>
            </div>

            <div class="one-half first">
                <div class="main-splash-left-btm">
                    &nbsp;
                </div>
            </div>
            <div class="one-half">
                <div class="main-splash-right-btm">
                    <a class="btn-pledge" href="/take-the-pledge">Take The Pledge</a>
                </div>
            </div>

        </div>

    <?php
}



// Content Area
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'page_loop' );

function page_loop(){
    ?>
            <div class="post-grid grid">

                    <?php
                    $thumbs = new WP_Query(array(
                        'category_name' => 'celebrities,take-the-pledge-naz',
                        'orderby' => 'rand',
                        ));

                    // init vars to create 1 larger image
                    $el='';
                    
                    while ( $thumbs->have_posts() ) {
                        $thumbs->the_post();
                        global $post;
                        
                        // get categories to add as classes for sorting with isotope
                        $post_cats = wp_get_post_categories( $post->ID );
                        
                        $this_cats = '';
                        
                        foreach( $post_cats as $c ){
                            $cat = get_category( $c );
                            $this_cats .= $cat->slug;
                            $this_cats .= " ";
                        }
                        
                        //wp_get_attachment_image_src($attachment_id) returns an array with
                        //[0] => url
                        //[1] => width
                        //[2] => height
                        //[3] => boolean: true if $url is a resized image, false if it is the original or if no image is available.
                        $full_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
                        $lg_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
                        
                        //populate the data-index attr with the array of indexes
                        $this_index = $el - 1;

                        $pledge_slug = $post->post_name;

                        echo '<div class="grid-item normal-size ' . $this_cats . '">';

                        echo '<div class="pledges">';
                        //check if has featured image, if so, use it, else use title
                        echo '<a href="' . $pledge_slug . '">' . get_the_post_thumbnail( $post->ID, "thumbnail" ) . '</a>';
                        the_title('<figure class="pledge-title">', '</figure>', true);
                        
                        //echo apply_filters( 'the_content', get_the_content() );
                        the_content();
                        echo '</div>';


                        echo '</div>';



                    }
                    ?>
                </div>
            </div>


    <?php
        wp_reset_query();
    
}


add_action('genesis_after_footer', 'add_scripts_to_btm');
function add_scripts_to_btm() {
    ?>
         <script type="text/javascript" src="/wp-content/themes/itcanwaitnaz/js/scripts.js"></script>
    <?php
}


// genesis child theme
genesis();