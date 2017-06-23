<?php
/**
 * Functions
 *
 * @package      itcanwaitnaz.com
 * @author       Great Circle Media - Stacy Mark <stacy.mark@kaff.com>
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

/**
 * Theme Setup
 *
 * This setup function attaches all of the site-wide functions 
 * to the correct actions and filters. All the functions themselves
 * are defined below this setup function.
 *
 */
add_action('genesis_setup', 'child_theme_setup');
function child_theme_setup(){

    //-------- SETUP --------------
	//* Start the engine
	include_once( get_template_directory() . '/lib/init.php' );

	//* Child theme (do not remove)
	define( 'CHILD_THEME_NAME', 'It Can Wait NAZ Theme (Genesis Child)' );
	define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
	define( 'CHILD_THEME_VERSION', '2.1.2' );

	//* Enqueue Google Fonts
	add_action( 'wp_enqueue_scripts', 'genesis_sample_google_fonts' );
	function genesis_sample_google_fonts() {

		wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700', array(), CHILD_THEME_VERSION );

	}
	//* Add HTML5 markup structure
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
	//* Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );
	//* Add support for custom background
	add_theme_support( 'custom-background' );
	//* Add support for 3-column footer widgets
	add_theme_support( 'genesis-footer-widgets', 3 );

    //-------- END SETUP --------------



	//---------- ADD TO HEAD------------
	add_action( 'genesis_meta', 'add_to_head' );
	function add_to_head() {
		//add our typekit script to load fonts
		?>
			<script src="https://use.typekit.net/tts8hxr.js"></script>
			<script>try{Typekit.load({ async: true });}catch(e){}</script>
		<?php
	}
	//---------- END ADD TO HEAD------------



	// ---------- ENQUEUE STYLES and SCRIPTS -------------------
    function icw_enqueue() {
        //wp_register_script('isotope', '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.0/isotope.pkgd.min.js');
        //wp_enqueue_script('isotope', '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.0/isotope.pkgd.min.js', true);
        wp_register_script( 'icwnaz-scripts', get_stylesheet_directory_uri() . '/js/scripts.min.js' );
        wp_enqueue_script( 'icwnaz-scripts', get_stylesheet_directory_uri() . '/js/scripts.min.js', array('jquery'), CHILD_THEME_VERSION, true );
        wp_register_style( 'icwnaz-styles', get_stylesheet_directory_uri() . '/styles/style.min.css', array(), CHILD_THEME_VERSION );
        wp_enqueue_style( 'icwnaz-styles' );
    }
    add_action( 'wp_enqueue_scripts', 'icw_enqueue' );
	// --------- END -------------------



	//---------- CUSTOMIZE OUR HEADER------------
    remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
    remove_action( 'genesis_site_description', 'genesis_seo_site_description');
    add_action( 'genesis_header', 'custom_hdr_title' );
    function custom_hdr_title() {
        ?>
        <div class="icw-title-left one-third first">
			<a href="/">
				<img src="<?php echo get_stylesheet_directory_uri() . '/images/logo.png'?>" class="icw-hdr-logo">
			</a>
        </div>
        <div class="icw-title-right two-thirds">
        	<ul class="icw-menu">
        		<li><a href="/take-the-pledge/">Take The Pledge</a></li>
        		<li><a href="/learn">Learn</a></li>
				<li><a href="/our-sponsors">Our Sponsors</a></li>
        	</ul>
        </div>
        <?php
        wp_reset_query();
    }
	//---------- END CUSTOMIZE OUR HEADER------------



    //-------- FOOTER --------------
    remove_action( 'genesis_footer', 'genesis_do_footer' );
    add_action( 'genesis_footer', 'child_custom_footer' );
    function child_custom_footer() {
        ?>
            <p class="copyright" data-enhance="false" data-role="none">
            	<a href="/" data-enhance="false" data-role="none">It Can Wait NAZ</a> &nbsp; | &nbsp; <a href="http://www.itcanwait.com" target="_blank">It Can Wait</a> &nbsp; | &nbsp; <a href="/contact-us">Contact Us</a>
            </p>
        <?php
    }
    //-------- END FOOTER --------------




    // -------- POSTS ----------------

	//* Customize the post info function
	add_filter( 'genesis_post_info', 'sp_post_info_filter' );
	function sp_post_info_filter($post_info) {
	if ( !is_page() ) {
		if ( in_category('take-the-pledge-naz' ) ){
			$post_info = 'Pledged on [post_date]';
		} else {
			$post_info = '';
		}
		return $post_info;
	}}

	//* Customize the post meta function
	add_filter( 'genesis_post_meta', 'sp_post_meta_filter' );
	function sp_post_meta_filter($post_meta) {
	if ( !is_page() ) {
		//$post_meta = '[post_categories before="See All Pledges: "] [post_tags before="Tagged: "]';
		$post_meta = '';
		return $post_meta;
	}}

	// -------- END POSTS ----------------




    //-------- ADMIN CUSTOMIZATIONS --------------
	// add more buttons to editor
	function add_more_buttons($buttons) {
		 $buttons[] = 'hr';
		 $buttons[] = 'del';
		 $buttons[] = 'sub';
		 $buttons[] = 'sup';
		 $buttons[] = 'fontselect';
		 $buttons[] = 'fontsizeselect';
		 $buttons[] = 'cleanup';
		 $buttons[] = 'styleselect';
		 return $buttons;
	}
	add_filter("mce_buttons_3", "add_more_buttons");
    //-------- END ADMIN CUSTOMIZATIONS --------------



    //-------- GLOBAL FUNCTIONS --------------
	//check if  on DEV or LIVE site
    function live_or_local(){
        if( strpos( $_SERVER['HTTP_HOST'], '.vag') !== false ){
            //on vagrant dev site
            $liveOrLocal = 'local';
        } else {
            $liveOrLocal = 'live';
        }
        return $liveOrLocal;
    }

    function filter_pledger_categories(){
        // Category ID's for reference:
        //   local dev: uncat=1, celeb=3, take-the-pledge=4, special=6, spotlight=8, radio=9,
        //   live prod: uncat=1, celeb=2, take-the-pledge=3, special=7, spotlight=9, radio=10,
        // we don't want the following cats in the pledger section:  special, spotlight, radio
        if( live_or_local() === 'local' ){
            //$cat_ids_to_hide = array(6,8,9);
            $cat_ids = array( 3,4,-6,-8,-9 );
        } else {
            //$cat_ids_to_hide = array(7,9,10);
            $cat_ids = array( 2,3,-7,-9,-10 );
        }
        return $cat_ids;
    }
    //-------- END GLOBAL FUNCTIONS --------------





	// --------  ADD CUSTOM QUERY VARS
	function add_query_vars_filter($vars){
	    $vars[] = 'rsvpe';
	    $vars[] = 'rsvpn';
	    $vars[] = 'pledge_sort';
	    return $vars;
	}
	add_filter('query_vars', 'add_query_vars_filter');
	// ------ END -----------




	// ------ RSVP CUSTOMIZATION
	// customize RSVP page
	function customize_rsvp_page() {
		global $post;
		if( $post->post_name === 'rsvp' ) {

			//change post title to username
			remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
			add_action( 'genesis_entry_header', 'rsvp_user_info_title' );
			function rsvp_user_info_title(){
				//load the user name or use empty default
				$rsvp_email = (get_query_var('rsvpe')) ? get_query_var('rsvpe') : "unknown@itcanwaitnaz.com";
				$rsvp_name = (get_query_var('rsvpn')) ? get_query_var('rsvpn') : "Pledger!";

				// check for MailChimp merge tag not working correctly
				if ( preg_match('/[\W]+/', $rsvp_name ) ) {
				    $rsvp_name = "Pledger!";
				}

				echo '<h1 class="entry-title">Hello ' . $rsvp_name . '</h1>';
			}
		}
	}
	add_action( 'wp', 'customize_rsvp_page' );

	// ----- END -------------


	// Purge all the transients associated with our plugin.
	function purge( $trans_name ) {

	  global $wpdb;

	  //$prefix = esc_sql( $this -> get_transient_prefix() );
	  $prefix = $trans_name;

	  $options = $wpdb -> options;

	  $t  = esc_sql( "_transient_timeout_$prefix%" );

	  $sql = $wpdb -> prepare (
	    "
	      SELECT option_name
	      FROM $options
	      WHERE option_name LIKE '%s'
	    ",
	    $t
	  );

	  $transients = $wpdb -> get_col( $sql );

	  // For each transient...
	  foreach( $transients as $transient ) {

	    // Strip away the WordPress prefix in order to arrive at the transient key.
	    $key = str_replace( '_transient_timeout_', '', $transient );

	    // Now that we have the key, use WordPress core to the delete the transient.
	    delete_transient( $key );

	  }
	  
	  // But guess what?  Sometimes transients are not in the DB, so we have to do this too:
	  wp_cache_flush();
	  
	}



	// ------ LOAD MORE ON SCROLL
    // Notes for me:
    // This line doesn't work for us (not sure why):  $args = isset($_POST['query']...
    // this adds a new WP Query to the bottom of an existing group of posts in a page where you want more to show 
    // upon scrolling down.  The new query is a separate query and needs to have same params as original.
    // It doesn't work with random sorting, as each new query is a "new" query, not a continuation of the first.
    // It works as a new query getting the next page of posts in a specific order.

    /**
     * AJAX Load More 
     * @link http://www.billerickson.net/infinite-scroll-in-wordpress
     */
    function be_ajax_load_more() {
        global $wp_query;
        global $post;
        //doesnt work??
        $args = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();

        //var_dump($_POST);

        //if( $args['scrollload'] == 1 ) {

            // get the cats wanted to show/hide for pledger section
            $cats = filter_pledger_categories();

            //$args['post_type'] = isset( $args['post_type'] ) ? esc_attr( $args['post_type'] ) : 'post';
            $args['post_type'] = 'post';
            $args['cat'] = $cats;
            $args['paged'] = esc_attr( $_POST['page'] );
            $args['post_status'] = 'publish';
            //$args['orderby'] = 'rand';
            $args['posts_per_page'] = 50;

            ob_start();
            $pledges = new WP_Query( $args );
            //echo $pledges->request;
            if( $pledges->have_posts() ): while( $pledges->have_posts() ): $pledges->the_post();
                display_pledges();
            endwhile; endif; wp_reset_postdata();
            $data = ob_get_clean();

            wp_send_json_success( $data );
            wp_die();

        //}
    }
    //add_action( 'wp_ajax_be_ajax_load_more', 'be_ajax_load_more' );
    //add_action( 'wp_ajax_nopriv_be_ajax_load_more', 'be_ajax_load_more' );

    /**
     * Javascript for Load More
     */
    function be_load_more_js() {
        global $post;
        global $wp_query;

        //doesnt work??
        //$args = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();

        if( is_home() || is_front_page() ) {

            $args = array(
                'url'   => admin_url( 'admin-ajax.php' ),
                'query' => $wp_query->query,
                'scrollload' => 1,
            );

            //$args = isset( $args['query'] ) ? array_map( 'esc_attr', $args['query'] ) : array();
            //$args['post_type'] = isset( $args['post_type'] ) ? esc_attr( $args['post_type'] ) : 'post';
            //var_dump( $args );

            wp_enqueue_script( 'be-load-more', get_stylesheet_directory_uri() . '/js/load-more.js', array( 'jquery' ), '1.0', true );
            wp_localize_script( 'be-load-more', 'beloadmore', $args );
        }
    }
    //add_action( 'wp_enqueue_scripts', 'be_load_more_js' );

    // ------ END LOAD MORE ON SCROLL



    // ------ SORT
    function icw_ajax_sort() {
        global $wp_query;
        global $post;
        //doesnt work??
        $args = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();

        //var_dump($_POST);

        //if( $args['scrollload'] == 1 ) {

            // get the cats wanted to show/hide for pledger section
            $cats = filter_pledger_categories();

            //$args['post_type'] = isset( $args['post_type'] ) ? esc_attr( $args['post_type'] ) : 'post';
            $args['post_type'] = 'post';
            $args['cat'] = $cats;
            $args['paged'] = esc_attr( $_POST['page'] );
            $args['post_status'] = 'publish';
            //$args['orderby'] = 'rand';
            $args['posts_per_page'] = 50;

            ob_start();
            $pledges = new WP_Query( $args );
            //echo $pledges->request;
            if( $pledges->have_posts() ): while( $pledges->have_posts() ): $pledges->the_post();
                display_pledges();
            endwhile; endif; wp_reset_postdata();
            $data = ob_get_clean();

            wp_send_json_success( $data );
            wp_die();

        //}
    }
    //add_action( 'wp_ajax_icw_ajax_sort', 'icw_ajax_sort' );
    //add_action( 'wp_ajax_nopriv_icw_ajax_sort', 'icw_ajax_sort' );

    /**
     * Javascript for Sort
     */
    function icw_load_on_sort() {
        global $post;
        global $wp_query;

        //doesnt work??
        $args = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();

        if( is_home() || is_front_page() ) {

            $args = array(
                'url'   => admin_url( 'admin-ajax.php' ),
                'query' => $wp_query->query,
                'scrollload' => 1,
            );

            //$args = isset( $args['query'] ) ? array_map( 'esc_attr', $args['query'] ) : array();
            //$args['post_type'] = isset( $args['post_type'] ) ? esc_attr( $args['post_type'] ) : 'post';
            //var_dump( $args );

            wp_enqueue_script( 'icw-load-on-sort', get_stylesheet_directory_uri() . '/js/pledge-sort.js', array( 'jquery' ), '1.0', true );
            wp_localize_script( 'icw-load-on-sort', 'icwloadonsort', $args );
        }
    }
    //add_action( 'wp_enqueue_scripts', 'icw_load_on_sort' );
    // ------ END SORT


    // --- DISPLAY USED IN SCROLL & SORT
    function display_pledges() {
        global $post;

        //generate random background color for each grid-item
        $rand_bg = rand(5,60)/100;

        $post_id = $post->ID;

        echo '
            <a href="' . $post->post_name . '">
                <div class="one-fourth pledges take-the-pledge-naz">
                    <div class="pledge-wrap" style="background-color:rgba(214,243,255,' . $rand_bg . ');">
            ';
                        //echo get_the_post_thumbnail( $post_id, "thumbnail" );
                        the_post_thumbnail( "thumbnail" );
                        //echo $this_thumb;
                        //echo '<figure class="pledge-title">' . $this_title . '</figure>';
                        the_title('<figure class="pledge-title">', '</figure>');
                        //echo apply_filters( 'the_content', $this_content );
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
    // ------

    function pledge_filter_function(){

        $args = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();

        $args['posts_per_page'] = -1;
        $args['nopaging'] = true;
        $args['cat'] = filter_pledger_categories();
        $args['post_type'] = 'post';

        // add query vars for sorting
        $pledge_sort = '';
        $pledge_sort = isset( $_POST['pledge_sort'] ) ? esc_attr( $_POST['pledge_sort'] ) : 'rand';

        switch( $pledge_sort ) {
            case 'rand':
                $args['orderby'] = 'date';
                $args['order']   = 'rand';
                break;
                
            case 'pledge_date':
                $args['orderby'] = 'date';
                $args['order']   = 'DESC';
                break;
                
            case 'pledge_date_rev':
                $args['orderby'] = 'date';
                $args['order']   = 'ASC';
                break;
                
            case 'pledge_title':
                $args['orderby'] = 'title';
                $args['order']   = 'ASC';
                break;
                
            case 'pledge_title_rev':
                $args['orderby'] = 'title';
                $args['order']   = 'DESC';
                break;

            default:
                $args['orderby'] = 'rand';
                break;
        }

        //print_r($args);
     
        $query = new WP_Query( $args );
     
        if( $query->have_posts() ) :
            while( $query->have_posts() ): $query->the_post();
                display_pledges();
            endwhile;
            wp_reset_postdata();
        else :
            echo 'No posts found';
        endif;
     
        die();
    }
    add_action('wp_ajax_sortFilter', 'pledge_filter_function'); 
    add_action('wp_ajax_nopriv_sortFilter', 'pledge_filter_function');

    /**
     * Javascript for Sort
     */
    function icw_load_on_sort_script() {
        global $post;
        global $wp_query;

        if( is_home() || is_front_page() ) {

            $args = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();

            wp_enqueue_script( 'icw-load-on-sort-script', get_stylesheet_directory_uri() . '/js/pledge-sort-filter.js', array( 'jquery' ), '1.0', true );
            wp_localize_script( 'icw-load-on-sort-script', 'icwloadonsortscript', $args );
        }
    }
    add_action( 'wp_enqueue_scripts', 'icw_load_on_sort_script' );

}