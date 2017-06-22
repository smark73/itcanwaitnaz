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
        <div class="icw-hdr full-width-content">

            <div class="one-half first">
                <div class="main-splash-left-wrap">
                    <?php

                    //PURGE
                    //purge('msplash_left');

                    //check for transient first
                    if ( false === ( $msplash_left = get_transient( 'msplash_left' ) ) ) {
                        $msplash_left_args = array(
                            'post_type' => 'page',
                            'pagename' => 'main-splash-left',
                        );
                        $msplash_left_query = new WP_Query( $msplash_left_args );
                    
                        //the loop
                        //init array to hold content in transient
                        $msplash_left = array();

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

                                $msplash_left['the_img'] = $the_img[0];
                                //ob_start();
                                //the_content();
                                //$msplash_left['the_content'] = ob_get_clean();
                                $msplash_left['the_content'] = get_the_content();

      
                                //echo '<div class="main-splash-left" style="background:url(' . $the_img[0] . ');background-size:contain;background-repeat:no-repeat;background-position:top center;">';
                                //the_content();
                                //echo '</div>';
                            }
                        }

                        //set transient for 1hr
                        set_transient( 'msplash_left', $msplash_left, 60*60 );

                    }

                    echo '<div class="main-splash-left" style="background:url(' . $msplash_left['the_img'] . ');background-size:contain;background-repeat:no-repeat;background-position:top center;">';
                    $msplash_left_the_content = apply_filters( 'the_content', $msplash_left['the_content'] );
                    echo $msplash_left_the_content;
                    echo '</div>';
                    
                    ?>

                    <div class="main-splash-left-btm">
                        <p class="win-this-car">You can win this car!</p>
                        <p>Just take the pledge!<br/>
                        Distracted Driving is Never OK!<br/></p>
                    </div>
                </div>
            </div>

            <div class="one-half">
                <div class="main-splash-right-wrap">
                    <div class="main-splash-right">
                        <p style="text-align: center;">
                            Keep your eyes on the road,<br>
                            not on your phone.<br>
                            #ItCanWaitNAZ
                        </p>
                    </div>
                    <div class="main-splash-right-btm">
                        <a class="btn-pledge" href="/take-the-pledge">Take The Pledge</a>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

        </div>

    <?php
}



// Content Area
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'page_loop' );

function page_loop(){
    ?>
            <div class="pledge-grid-wrap">

                <div class="spot-spec-radio">

                <?php
                    //---- SPOTLIGHT & SPECIAL POSTS (top row)
                    //     We have 1 row of 3 columns
                    //      [spotlight(random)] [spotlight(random)] [special(Zaadii)]

                    //purge('spotlight_posts');

                    //check for transient first
                    if ( false === ( $spotlight_posts = get_transient( 'spotlight_posts' ) ) ) {
                        $spotlight = new WP_Query(array(
                            'category_name' => 'spotlight',
                            'orderby' => 'rand',
                            'posts_per_page' => 2,
                        ));

                        //the loop
                        //init array to hold content in transient
                        $spotlight_posts = array();

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

                            $post_id = $post->ID;
                            $spotlight_posts[$post_id]['slug'] = $post->post_name;
                            $spotlight_posts[$post_id]['cats'] = $this_cats;
                            $spotlight_posts[$post_id]['thumb'] = get_the_post_thumbnail( "thumbnail" );
                            $spotlight_posts[$post_id]['title'] = get_the_title();
                            $spotlight_posts[$post_id]['content'] = get_the_content();

                        }

                        //set transient for 1hr
                        set_transient( 'spotlight_posts', $spotlight_posts, 60*60 );

                    }

                    //display them

                    // count - only showing 2, set first one with class "first"
                    $spotlight_post_count = 0;
                    $spotlight_post_first_class = '';

                    foreach( $spotlight_posts as $key => $spotlight_post ){

                        $pledge_slug = $spotlight_post['slug'];
                        $this_cats = $spotlight_post['cats'];
                        $this_thumb = $spotlight_post['thumb'];
                        $this_title = $spotlight_post['title'];
                        $this_content = $spotlight_post['content'];

                        //count
                        $spotlight_post_count += 1;
                        if($spotlight_post_count === 1){
                            $spotlight_post_first_class = " first "; 
                        } else {
                            $spotlight_post_first_class = ""; 
                        }

                        //wrap the pledge div with link
                        echo '<a href="' . $pledge_slug . '"><div class="one-third pledges-sticky ' . $this_cats . $spotlight_post_first_class . '">';
                        echo $this_thumb;
                        //echo '<figure class="pledge-title">' . $this_title . '</figure>';
                        echo apply_filters( 'the_content', $this_content );
                        echo '</div></a>';

                    }

                ?>

                <?php
                    //---- SPECIAL POSTS (only Zaadii)

                    //purge('special_posts');

                    //special posts - we dont want the content, just the featured image and title
                    //check for transient first
                    if ( false === ( $special_posts = get_transient( 'special_posts' ) ) ) {
                        $special = new WP_Query(array(
                            'category_name' => 'special',
                            'orderby' => 'rand',
                        ));
                    
                        //the loop
                        //init array to hold content in transient
                        $special_posts = array();

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

                            $post_id = $post->ID;
                            $special_posts[$post_id]['slug'] = $post->post_name;
                            $special_posts[$post_id]['cats'] = $this_cats;
                            $special_posts[$post_id]['thumb'] = get_the_post_thumbnail( $post->ID, "thumbnail" );
                            $special_posts[$post_id]['title'] = get_the_title();

                        }

                        //set transient for 1hr
                        set_transient( 'special_posts', $special_posts, 60*60 );

                    }

                    //display them
                    foreach ($special_posts as $key => $special_post) {
                        
                        $pledge_slug = $special_post['slug'];
                        $this_cats = $special_post['cats'];
                        $this_thumb = $special_post['thumb'];
                        $this_title = $special_post['title'];

                        //wrap the pledge div with link
                        echo '
                            <a href="' . $pledge_slug . '">
                                <div class="one-third zaadii-home pledges-sticky ' . $this_cats . '">
                                    <div class="pledge-wrap">
                            ';
                                        echo $this_thumb;
                                        echo '<figure class="pledge-title">' . $this_title . '</figure>';
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
                    //---- RADIO SPOTS

                    //purge('radiospots_posts');

                    //check for transient first
                    if ( false === ( $radiospots_posts = get_transient( 'radiospots_posts' ) ) ) {
                        $radiospots = new WP_Query(array(
                            'category_name' => 'radio-spots',
                            'orderby' => 'DESC'
                        ));

                        //the loop
                        //init array to hold content in transient
                        $radiospots_posts = array();

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

                            $post_id = $post->ID;
                            $radiospots_posts[$post_id]['slug'] = $post->post_name;
                            $radiospots_posts[$post_id]['cats'] = $this_cats;
                            $radiospots_posts[$post_id]['thumb'] = get_the_post_thumbnail( $post_id, "thumbnail" );
                            $radiospots_posts[$post_id]['title'] = get_the_title();
                            $radiospots_posts[$post_id]['content'] = get_the_content();

                        }

                        //set transient for 1hr
                        set_transient( 'radiospots', $radiospots_posts, 60*60 );

                    }

                    // display them
                    foreach ($radiospots_posts as $key => $radiospots_post) {

                        $pledge_slug = $radiospots_post['slug'];
                        $this_cats = $radiospots_post['cats'];
                        $this_thumb = $radiospots_post['thumb'];
                        $this_title = $radiospots_post['title'];
                        $this_content = $radiospots_post['content'];

                        //wrap the pledge div with link
                        echo '
                            <a href="' . $pledge_slug . '">
                                <div class="one-fourth pledges ' . $this_cats . '">
                                    <div class="pledge-wrap">
                            ';
                                        echo $this_thumb;
                                        echo '<figure class="pledge-title">' . $this_title . '</figure>';
                                        echo apply_filters( 'the_content', $this_content );
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

                <div class="clearfix"></div>
                </div><!-- spot-spec-radio -->

                <?php

                    // **** SUBMITTED PLEDGES *****

                    //---- SHOW TOTAL PLEDGES TO DATE & SORTING OPTIONS
                    $total_pledge_count = new WP_Query(array(
                        'category_name' => 'take-the-pledge-naz',
                    ));
                    $pledge_count = $total_pledge_count->found_posts;

                    echo '
                        <div class="clearfix"></div>
                        <div class="pledge-count-sort">

                            <div class="pledge-count-wrap">
                                <p class="pledge-count">' . $pledge_count . ' pledges to date!</p>
                            </div>
                            <div class="pledge-sort-wrap">
                                <form name="sortpledgesform" id="sortpledgesform" class="sortpledges" method="POST" action="' . site_url() . '/wp-admin/admin-ajax.php">
                                    <span class="sortby">Sort by | </span>
                                    <select class="pledge-sort-filter" name="pledge_sort">
                                        <option value="pledge_date">Date (new first)</option>
                                        <option value="pledge_date_rev">Date (old first)</option>
                                        <option value="pledge_title">Title (A-Z)</option>
                                        <option value="pledge_title_rev">Title (Z-A)</option>
                                        <option value="pledge_rand">Random Order</option>
                                    </select>
                                    <input type="submit" name="sortbtn" value="Go">
                                    <input type="hidden" name="action" value="sortFilter">
                                </form>
                            </div>

                        </div>
                        <div class="clearfix"></div>
                        ';

                    echo '<div id="displaypledges">';


                    //---- SUBMITTED PLEDGES/POSTS

                    //purge('pledge_posts');

                    $args_cats = filter_pledger_categories();
                    //init args for wp_query ($pledges), then add the query vars for sorting
                    $args = array(
                        'post_type' => 'post',
                        'cat' => $args_cats,
                        'posts_per_page' => -1,
                        'nopaging' => true,
                        'orderby' => 'rand',
                    );

                    // all pledges minus "spotlight" and "special" ones
                    //check for transient first
                    if( false === ( $pledge_posts = get_transient( 'pledge_posts' ) ) ) {
                        $pledge_posts = new WP_Query( $args );
                        //set transient for 10min
                        set_transient( 'pledge_posts', $pledge_posts, 600 );
                    }

                    //display them
                    if( $pledge_posts->have_posts() ) {
                        //DEBUG $pledgecount = 0;
                        while ( $pledge_posts->have_posts() ) {
                            $pledge_posts->the_post();
                            global $post;
                            display_pledges();
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

                </div><!-- display-pledges -->
            </div><!-- pledge-grid-wrap -->


    <?php
        wp_reset_query();
    
}



// genesis child theme
genesis();