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
                <div class="main-splash-left-wrap">
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
  
                            echo '<div class="main-splash-left" style="background:url(' . $the_img[0] . ');background-size:contain;background-repeat:no-repeat;background-position:top center;">';
                            the_content();
                            echo '</div>';
                        }
                    }
                    ?>

                    <div class="main-splash-left-btm">
                        <p class="win-this-car">Take The Pledge and Win This Car!</p>
                        <p>We're giving away this car and more!<br/>All you need to do is take the pledge!</p>
                    </div>
                </div>
            </div>

            <div class="one-half">
                <div class="main-splash-right-wrap">
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
                    <div class="main-splash-right-btm">
                        <a class="btn-pledge" href="/take-the-pledge">Take The Pledge</a>
                    </div>
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
            <div class="pledge-grid-wrap">

                <?php
                    // STICKY POSTS
                    $sticky = new WP_Query(array(
                        'category_name' => 'celebrities,take-the-pledge-naz',
                        'orderby' => 'rand',
                        ));
                    
                    while ( $sticky->have_posts() ) {

                        $sticky->the_post();
                        global $post;

                        if(is_sticky($post->ID)){
                            
                            // get categories to add as classes for sorting with isotope
                            $post_cats = wp_get_post_categories( $post->ID );
                            
                            $this_cats = '';
                            
                            foreach( $post_cats as $c ){
                                $cat = get_category( $c );
                                $this_cats .= $cat->slug;
                                $this_cats .= " ";
                            }
                            
                            //slug for the link
                            $pledge_slug = $post->post_name;

                            //wrap the pledge div with link
                            echo '<a href="' . $pledge_slug . '"><div class="one-third pledges-sticky ' . $this_cats . '">';

                            echo get_the_post_thumbnail( $post->ID, "thumbnail" );
                            the_title('<figure class="pledge-title">', '</figure>', true);
                            //echo '<a href="' . $pledge_slug . '">' . get_the_post_thumbnail( $post->ID, "thumbnail" ) . '</a>';
                            //the_title('<a href="' . $pledge_slug .'"><figure class="pledge-title">', '</figure></a>', true);

                            //echo apply_filters( 'the_content', get_the_content() );
                            the_content();

                            echo '</div></a>';

                        }
                    }
                ?>

                <?php
                    //SPECIAL (NOT STICKY) POSTS
                    //special posts - we dont want the content, just the featured image and title
                    $special = new WP_Query(array(
                        'category_name' => 'special',
                        'orderby' => 'rand',
                        ));
                    
                    while ( $special->have_posts() ) {

                        $special->the_post();
                        global $post;

                            
                        // get categories to add as classes for sorting with isotope
                        $post_cats = wp_get_post_categories( $post->ID );
                        
                        $this_cats = '';
                        
                        foreach( $post_cats as $c ){
                            $cat = get_category( $c );
                            $this_cats .= $cat->slug;
                            $this_cats .= " ";
                        }
                        
                        //slug for the link
                        $pledge_slug = $post->post_name;

                        //wrap the pledge div with link
                        echo '
                            <a href="' . $pledge_slug . '">
                                <div class="one-fourth pledges ' . $this_cats . '">
                                    <div class="pledge-wrap">

                            ';

                                        echo get_the_post_thumbnail( $post->ID, "thumbnail" );
                                        the_title('<figure class="pledge-title">', '</figure>', true);


                        echo '
                                        <div class="view-share vsHide">
                                            <img src="/wp-content/themes/itcanwaitnaz/images/view-share.png">
                                        </div>
                                    </div>
                                </div>
                            </a>
                            ';


                    }
                ?>

                <?php
                    //NOT STICKY POSTS
                    $pledges = new WP_Query(array(
                        'category_name' => 'celebrities,take-the-pledge-naz',
                        'orderby' => 'rand',
                        ));
                    
                    while ( $pledges->have_posts() ) {

                        $pledges->the_post();
                        global $post;

                        if(! is_sticky($post->ID)){
                            
                            // get categories to add as classes for sorting with isotope
                            $post_cats = wp_get_post_categories( $post->ID );
                            
                            $this_cats = '';
                            
                            foreach( $post_cats as $c ){
                                $cat = get_category( $c );
                                $this_cats .= $cat->slug;
                                $this_cats .= " ";
                            }
                            
                            //slug for the link
                            $pledge_slug = $post->post_name;

                            //wrap the pledge div with link
                            echo '
                                <a href="' . $pledge_slug . '">
                                    <div class="one-fourth pledges ' . $this_cats . '">
                                        <div class="pledge-wrap">

                                ';

                                            echo get_the_post_thumbnail( $post->ID, "thumbnail" );
                                            the_title('<figure class="pledge-title">', '</figure>', true);
                                            //echo '<a href="' . $pledge_slug . '">' . get_the_post_thumbnail( $post->ID, "thumbnail" ) . '</a>';
                                            //the_title('<a href="' . $pledge_slug .'"><figure class="pledge-title">', '</figure></a>', true);

                                            //echo apply_filters( 'the_content', get_the_content() );
                                            the_content();

                            echo '
                                            <div class="view-share vsHide">
                                                <img src="/wp-content/themes/itcanwaitnaz/images/view-share.png">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                ';

                        }
                    }
                ?>

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