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
                    //check for transient first
                    if ( false === ( $msplash_left_query = get_transient( 'msplash_left_query' ) ) ) {
                        $msplash_left_args = array(
                            'post_type' => 'page',
                            'pagename' => 'main-splash-left',
                        );
                        $msplash_left_query = new WP_Query( $msplash_left_args );
                        //set transient for 1hr
                        set_transient( 'msplash_left_query', $msplash_left_query, 60*60 );
                    }
                    //the loop
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
                        <p>We're giving away this car and more SOON!<br/>All you need to do is take the pledge!</p>
                    </div>
                </div>
            </div>

            <div class="one-half">
                <div class="main-splash-right-wrap">
                    <?php
                    //check for transient first
                    if ( false === ( $msplash_right_query = get_transient( 'msplash_right_query' ) ) ) {

                        $msplash_right_args = array(
                            'post_type' => 'page',
                            'pagename' => 'main-splash-right',
                        );
                        $msplash_right_query = new WP_Query( $msplash_right_args );
                        //set transient for 1hr
                        set_transient( 'msplash_right_query', $msplash_right_query, 60*60 );
                    }
                    //the loop
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
                    // SHOW TOTAL PLEDGES TO DATE
                    //check for transient first
                    if ( false === ( $total_pledge_count = get_transient( 'total_pledge_count' ) ) ) {
                        $total_pledge_count = new WP_Query(array(
                            'category_name' => 'take-the-pledge-naz',
                        ));
                        //set transient for 10mins
                        set_transient( 'total_pledge_count', $total_pledge_count, 600 );
                    }
                    echo "<h2 style='font-style:italic;font-weight:600;color:#757575;'>" . $total_pledge_count->found_posts . " pledges to date!</h2>";
                    
                    // SPOTLIGHT POSTS
                    //check for transient first
                    if ( false === ( $spotlight = get_transient( 'spotlight' ) ) ) {
                        $spotlight = new WP_Query(array(
                            'category_name' => 'spotlight',
                            'orderby' => 'rand',
                        ));
                        //set transient for 1hr
                        set_transient( 'spotlight', $spotlight, 60*60 );
                    }

                    //the loop
                    //DEBUG $spotlightcount = 0;
                    while ( $spotlight->have_posts() ) {

                        $spotlight->the_post();
                        global $post;

                            //DEBUG $spotlightcount += 1;
                            
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

                ?>

                <?php
                    //SPECIAL (NOT SPOTLIGHT) POSTS
                    //special posts - we dont want the content, just the featured image and title
                    //check for transient first
                    if ( false === ( $special = get_transient( 'special' ) ) ) {
                        $special = new WP_Query(array(
                            'category_name' => 'special',
                            'orderby' => 'rand',
                        ));
                        //set transient for 1hr
                        set_transient( 'special', $special, 60*60 );
                    }
                    
                    //the loop
                    //DEBUG $specialcount = 0;
                    while ( $special->have_posts() ) {

                        $special->the_post();
                        global $post;

                        //DEBUG $specialcount += 1;
                            
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
                    //RADIO SPOTS
                    //check for transient first
                    if ( false === ( $radiospots = get_transient( 'radiospots' ) ) ) {
                        $radiospots = new WP_Query(array(
                            'category_name' => 'radio-spots',
                            'orderby' => 'DESC'
                        ));
                        //set transient for 1hr
                        set_transient( 'radiospots', $radiospots, 60*60 );
                    }

                    //the loop
                    while ( $radiospots->have_posts() ) {

                        $radiospots->the_post();
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

                ?>

                <?php
                    //NOT SPOTLIGHT POSTS
                    // all pledges minus "spotlight" and "special" ones
                    //check for transient first
                    if ( false === ( $pledges = get_transient( 'pledges' ) ) ) {
                        $pledges = new WP_Query(array(
                            'category_name' => 'celebrities,take-the-pledge-naz',
                            'orderby' => 'rand',
                            'posts_per_page' => -1,
                            'nopaging' => true,
                        ));
                        //set transient for 10min
                        set_transient( 'pledges', $pledges, 600 );
                    }
                    
                    //the loop
                    //DEBUG $pledgecount = 0;
                    while ( $pledges->have_posts() ) {

                        $pledges->the_post();
                        global $post;

                        if( ! in_category( 'spotlight' ) && ! in_category( 'special' ) ){

                            //DEBUG $pledgecount += 1;
                            
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

                            //generate random background color for each grid-item
                            $rand_bg = rand(5,60)/100;


                            //wrap the pledge div with link
                            echo '
                                <a href="' . $pledge_slug . '">
                                    <div class="one-fourth pledges ' . $this_cats . '">
                                        <div class="pledge-wrap" style="background-color:rgba(214,243,255,' . $rand_bg . ');">

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

                <?php
                    //DEBUG echo "<div style='clear:both;visibility:hidden;display:none;height:0;'>";
                    //DEBUG echo "spotlight:" . $spotlightcount . "<br/>";
                    //DEBUG echo "special: " . $specialcount . "<br/>";
                    //DEBUG echo "pledges: " . $pledgecount . "<br/>";
                    //DEBUG echo "</div>";
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