<?php
// Remove Page/Post Title
//remove_action( 'genesis_post_title', 'genesis_do_post_title' );
//remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

//* Remove the post meta function
//remove_action( 'genesis_after_post_content', 'genesis_post_meta' );



// Content Area
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'page_loop' );

function page_loop(){
    ?>
            <div class="pledge-grid-wrap">

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
                                <form name="sortpledges" id="sortpledges" class="sortpledges" method="post" action="' . site_url() . '/wp-admin/admin-ajax.php">
                                <!-- div id="sortpledges" class="sortpledges" -->
                                    <span class="sortby">Sort by | </span>
                                    <select class="pledge-sort-filter" name="pledge_sort">
                                        <option value="pledge_date">Date (new first)</option>
                                        <option value="pledge_date_rev">Date (old first)</option>
                                        <option value="pledge_title">Title (A-Z)</option>
                                        <option value="pledge_title_rev">Title (Z-A)</option>
                                        <option value="pledge_rand">Random Order</option>
                                    </select>
                                    <input type="submit" name="sortbtn" id="sortbtn" value="Go">
                                </form><!-- /div -->
                            </div>

                        </div>
                        <div class="clearfix"></div>
                        <div class="post-listing submitted-pledges">
                        ';


                    //---- SUBMITTED PLEDGES/POSTS

                    //purge('pledge_posts');


                    //init for wp_query ($pledges)
                    $args_cats = array();
                    $pledge_sort = '';
                    $args_orderby = '';
                    $args_order = '';
                    // add query vars for sorting
                    $pledge_sort = isset( $_GET['pledge_sort'] ) ? esc_attr( $_GET['pledge_sort'] ) : 'date';
                    switch( $pledge_sort ) {
                        case 'rand':
                            $args_orderby = 'date';
                            $args_order   = 'rand';
                            break;
                            
                        case 'pledge_date':
                            $args_orderby = 'date';
                            $args_order   = 'DESC';
                            break;
                            
                        case 'pledge_date_rev':
                            $args_orderby = 'date';
                            $args_order   = 'ASC';
                            break;
                            
                        case 'pledge_title':
                            $args_orderby = 'title';
                            $args_order   = 'DESC';
                            break;
                            
                        case 'pledge_title_rev':
                            $args_orderby = 'title';
                            $args_order   = 'ASC';
                            break;

                        default:
                            $args_orderby = 'date';
                            $args_order   = 'rand';
                            break;
                    }
                    // get the cats want to show/hide for pledger section
                    $args_cats = filter_pledger_categories();

                    $args = array(
                        'post_type' => 'post',
                        'cat' => $args_cats,
                        'posts_per_page' => 50,
                        'orderby' => $args_orderby,
                        'order' => $args_order,
                        //'nopaging' => true,
                    );


                    // all pledges minus "spotlight" and "special" ones
                    //check for transient first
                    //if ( false === ( $pledge_posts = get_transient( 'pledge_posts' ) ) ) {

                        $pledges = new WP_Query( $args );

                        //the loop
                        //DEBUG $pledgecount = 0;
                        while ( $pledges->have_posts() ) {

                            $pledges->the_post();
                            global $post;
                            display_pledges();

                        }

                        //set transient for 10min
                        //set_transient( 'pledge_posts', $pledges, 600 );

                    //}

                ?>

                <?php
                    //DEBUG echo "<div style='clear:both;visibility:hidden;display:none;height:0;'>";
                    //DEBUG echo "spotlight:" . $spotlightcount . "<br/>";
                    //DEBUG echo "special: " . $specialcount . "<br/>";
                    //DEBUG echo "pledges: " . $pledgecount . "<br/>";
                    //DEBUG echo "</div>";
                ?>
                <span class="load-more"></span>
                </div>
            </div>


    <?php
        wp_reset_query();
    
}



// genesis child theme
genesis();