<?php




// Content Area
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'page_loop' );

function page_loop(){
    ?>
            <div class="pledge-grid-wrap">

                <div class="post-listing">
                <?php

                    while ( have_posts() ) {

                        the_post();

                        global $post;

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
                        
                    


                ?>

                </div>
            </div>


    <?php
        //wp_reset_query();
    
}


// AJAX LOAD MORE ON SCROLL STUFF
function be_ajax_load_more() {
    
    //check_ajax_referer( 'be-load-more-nonce', 'nonce' );

    $args = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();
    $args['post_type'] = isset( $args['post_type'] ) ? esc_attr( $args['post_type'] ) : 'post';
    $args['paged'] = esc_attr( $_POST['page'] );
    $args['post_status'] = 'publish';
    //$args['category_name'] = 'take-the-pledge-naz';
    $args['posts_per_page'] = 10;
    $args['offset'] = 10 + 10 * ( $_POST['page'] - 10 );

    ob_start();
    $loop = new WP_Query( $args );
    if( $loop->have_posts() ): while( $loop->have_posts() ): $loop->the_post();
        //be_post_summary();
        display_the_post();
    endwhile; endif;
    wp_reset_postdata();
    $data = ob_get_clean();
    wp_send_json_success( $data );
    wp_die();
}
add_action( 'wp_ajax_be_ajax_load_more', 'be_ajax_load_more' );
add_action( 'wp_ajax_nopriv_be_ajax_load_more', 'be_ajax_load_more' );



function display_the_post(){

    global $post;

    //if( ! in_category( 'spotlight' ) && ! in_category( 'special' ) ){

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

    //}
}


/**
 * Javascript for Load More
 *
 */
function be_load_more_js() {
    global $wp_query;
    $args = array(
        'url'   => admin_url( 'admin-ajax.php' ),
        'query' => $wp_query->query,
    );
            
    wp_enqueue_script( 'be-load-more', get_stylesheet_directory_uri() . '/js/load-more.js', array( 'jquery' ), '1.0', true );
    wp_localize_script( 'be-load-more', 'beloadmore', $args );
    
}
add_action( 'wp_enqueue_scripts', 'be_load_more_js' );





function add_scripts_to_btm() {
    ?>
        <script type="text/javascript" src="/wp-content/themes/itcanwaitnaz/js/scripts.js"></script>
    <?php
}
add_action('genesis_after_footer', 'add_scripts_to_btm');



// genesis child theme
genesis();