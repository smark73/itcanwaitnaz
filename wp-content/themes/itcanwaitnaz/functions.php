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



	// ---------- ENQUE STYLES and SCRIPTS -------------------
    //add_action( 'genesis_meta', 'add_reqs_to_head' );
    function add_reqs_to_head() {
        //wp_register_script('isotope', '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.0/isotope.pkgd.min.js');
        //wp_enqueue_script('isotope', '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.0/isotope.pkgd.min.js', true);
    }
	// --------- END ENQUE STYLES and SCRIPTS -------------------



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
        		<li><a href="http://www.itcanwait.com/apps-and-tools" target="_blank">Apps & Tools</a></li>
				<li><a href="">Get Involved</a></li>
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
    //-------- END GLOBAL FUNCTIONS --------------

}