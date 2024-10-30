<?php
/**
 * Plugin Name: LH Login Page
 * Plugin URI: https://lhero.org/portfolio/lh-login-page/
 * Description: HTML5 custom login page via shortcode
 * Author: Peter Shaw
 * Version: 2.14
 * Author URI: https://shawfactor.com/
 * Text Domain: lh_login_page
 * Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('LH_login_page_plugin')) {


class LH_login_page_plugin {

var $filename;
var $options;
var $opt_name = 'lh_login_page-options';
var $page_id_field = 'lh_login_page-page_id';
var $documentation_link = 'https://lhero.org/plugins/lh-login-page/';
var $use_email_field_name = 'lh_login_page-use_email';
var $allow_redirects_field_name = 'lh_login_page-allow_redirects';
var $appmode_prompt_logon_field_name = 'lh_login_page-appmode_prompt_logon';
var $namespace = 'lh_login_page';

private static $instance;

static function uninstall(){

delete_option('lh_profile_page-options');

}

static function login_header( $title = 'Log In', $message = '', $wp_error = '' ) {
    
    $return_string .= 
    	$message = apply_filters( 'login_message', $message );
	if ( !empty( $message ) ){
		return '<p>'.$message.'</p>
		';
	} else {
	    
	    return '';
	    
	}
    
}

private function curpageurl() {
	$pageURL = 'http';

	if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")){
		$pageURL .= "s";
}

	$pageURL .= "://";

	if (($_SERVER["SERVER_PORT"] != "80") and ($_SERVER["SERVER_PORT"] != "443")){
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

}

	return $pageURL;
}

private function isValidURL($url){ 

return (bool)parse_url($url);
}


private function uri_to_array($uri){

$uri = str_replace('&amp;', '&', $uri);

$url = parse_url($uri);
$query = array();
  
  if (isset($url['query'])){
 
parse_str($url['query'], $query);

return $query;
	
  } else {

return false;	
	
  }
}



private function return_rest_login_url() {

return rest_url( 'lh-login-page/v1/login-user/');

}



private function simple_login_user($username, $password){


if (isset($this->options[$this->use_email_field_name]) and ($this->options[$this->use_email_field_name] == 1)){ 

$user = get_user_by( 'email', $username );

} else {

$user = get_user_by( 'login', $username );

}

if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status ){
			 $login_data['user_login'] = $user->user_login;
}

 $login_data['user_password'] = $password;
 $login_data['remember'] = true;

if (is_ssl()) {

$user_verify = wp_signon( $login_data, true ); 

} else {

$user_verify = wp_signon( $login_data, false ); 


}

$user_verify = apply_filters( 'authenticate', $user_verify,  $username, $password);

return $user_verify;


}



 /**
     * Helper function for registering and enqueueing scripts and styles.
     *
     * @name    The    ID to register with WordPress
     * @file_path        The path to the actual file
     * @is_script        Optional argument for if the incoming file_path is a JavaScript source file.
     */
    private function load_file( $name, $file_path, $is_script = false, $deps = array(), $in_footer = true, $atts = array() ) {
        $url  = plugins_url( $file_path, __FILE__ );
        $file = plugin_dir_path( __FILE__ ) . $file_path;
        if ( file_exists( $file ) ) {
            if ( $is_script ) {
                wp_register_script( $name, $url, $deps, filemtime($file), $in_footer ); 
                wp_enqueue_script( $name );
            }
            else {
                wp_register_style( $name, $url, $deps, filemtime($file) );
                wp_enqueue_style( $name );
            } // end if
        } // end if
	  
	  if (isset($atts) and is_array($atts) and isset($is_script)){
		
		
  $atts = array_filter($atts);

if (!empty($atts)) {

  $this->script_atts[$name] = $atts; 
  
}

		  
	 add_filter( 'script_loader_tag', function ( $tag, $handle ) {
	   

	   
if (isset($this->script_atts[$handle][0]) and !empty($this->script_atts[$handle][0])){
  
$atts = $this->script_atts[$handle];

$implode = implode(" ", $atts);
  
unset($this->script_atts[$handle]);

return str_replace( ' src', ' '.$implode.' src', $tag );

unset($atts);
usent($implode);

		 

	 } else {
	   
 return $tag;	   
	   
	   
	 }
	

}, 10, 2 );
 

	
	  
	}
		
    } // end load_file

private function register_scripts_and_styles() {

if (!is_user_logged_in()){
    
    $array = array();
    $array[] = 'id="'.$this->namespace.'-auto_login-script"';
    $array[] = 'defer="defer"';
    $array[] = 'data-lh_login_page-rest_login_user-url="'.$this->return_rest_login_url().'"';
    $array[] =  'data-lh_login_page-rest_nonce="'.wp_create_nonce( 'wp_rest' ).'"';
    
    if (isset($this->options[$this->appmode_prompt_logon_field_name]) and ($this->options[$this->appmode_prompt_logon_field_name] == 1)){
      
        $array[] =  'data-lh_login_page-appmode_prompt_logon="yes"';
        $array[] =  'data-lh_login_page-html_login_user-url="'.wp_login_url().'"';
        
        
    }


// include the autologin library
$this->load_file( $this->namespace.'-auto-login-js', '/assets/auto-login.js', true, array(), true, $array );

}


}


public function plugin_menu() {
add_options_page(__('LH Login Page Options', $this->namespace ), __('Login Page', $this->namespace ), 'manage_options', $this->filename, array($this,"plugin_options"));

}

function plugin_options() {

if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

   
 // See if the user has posted us some information
    // If they did, the nonce will be set

	if( isset($_POST[ $this->namespace."-backend_nonce" ]) && wp_verify_nonce($_POST[ $this->namespace."-backend_nonce" ], $this->namespace."-backend_nonce" )) {



if (($_POST[ $this->page_id_field ] != "") and ($page = get_page(sanitize_text_field($_POST[ $this->page_id_field ])))){

if ( has_shortcode( $page->post_content, 'lh_login_page_form' ) ) {

$options[ $this->page_id_field ] = sanitize_text_field($_POST[ $this->page_id_field ]);

} else {
    
_e("shortcode not found", $this->namespace );




}

}

if (isset($_POST[$this->use_email_field_name]) and (($_POST[$this->use_email_field_name] == "0") || ($_POST[$this->use_email_field_name] == "1"))){
$options[$this->use_email_field_name] = $_POST[ $this->use_email_field_name ];
}

if (isset($_POST[$this->allow_redirects_field_name]) and (($_POST[$this->allow_redirects_field_name] == "0") || ($_POST[$this->allow_redirects_field_name] == "1"))){
$options[$this->allow_redirects_field_name] = $_POST[ $this->allow_redirects_field_name ];
}

if (isset($_POST[$this->appmode_prompt_logon_field_name]) and (($_POST[$this->appmode_prompt_logon_field_name] == "0") || ($_POST[$this->appmode_prompt_logon_field_name] == "1"))){
$options[$this->appmode_prompt_logon_field_name] = $_POST[ $this->appmode_prompt_logon_field_name ];
}

if (update_option( $this->opt_name, $options )){

$this->options = get_option($this->opt_name);


?>
<div class="updated"><p><strong><?php _e('Settings saved', $this->namespace ); ?></strong></p></div>
<?php

} 

}

    // Now display the settings editing screen

include ('partials/option-settings.php');
    


}


public function filter_login_url( $login_url ){

if ($this->options[$this->page_id_field] and ( !is_user_logged_in() )){

$bits = $this->uri_to_array($login_url);

if ($bits['redirect_to']){

$login_url = add_query_arg( "redirect_to", urlencode($bits['redirect_to']), get_permalink($this->options[$this->page_id_field]) );

} else {

$login_url = get_permalink($this->options[$this->page_id_field]);

}


}

return $login_url;

}



function filter_logout_url( $logout_url ){

$bits = $this->uri_to_array($logout_url);



if ($this->options[$this->page_id_field]){



$logout_url = get_permalink($this->options[$this->page_id_field]);

$logout_url = add_query_arg( 'lh-login-page-action', 'logout', $logout_url );

$logout_url = add_query_arg( 'lh_login_page_nonce', wp_create_nonce("lh_login_page_nonce"), $logout_url );




if (isset($bits['redirect_to'])){

$logout_url = add_query_arg( 'redirect_to', urlencode($bits['redirect_to']), $logout_url );

}



}

return $logout_url;

}




function lh_login_page_form_output($return_string, $redirect_to){
    
    
    


if(isset($_GET['login']) && $_GET['login'] == 'failed'){
    
$return_string .=  LH_login_page_plugin::login_header( 'Login failed', __( 'Login failed: You have entered an incorrect email or password, please try again.', $this->namespace ));

}

$return_string .= '
<form name="lh_login_page-login_form" id="lh_login_page-login_form" action="" method="post" accept-charset="utf-8"
data-lh_login_page-nonce="'.wp_create_nonce("lh_login_page-nonce").'" data-lh_login_page-rest_nonce="'.wp_create_nonce( 'wp_rest' ).'" data-lh_login_page-login-user-url="'.$this->return_rest_login_url().'">

<noscript>'.__( 'Please switch on Javascript to enable this registration', $this->namespace ).'</noscript>


';



if (isset($this->options[$this->use_email_field_name]) and ($this->options[$this->use_email_field_name] == 1)){ 

$return_string .= '<p>
<!--[if lt IE 10]><br/><label for="lh_login_page-user_login">'.__( 'Email', $this->namespace ).'</label><br/><![endif]--><input type="email" id="lh_login_page-user_login" name="lh_login_page-user_login" placeholder="yourname@email.com" required="required"  ';

} else {


$return_string .= '<p>
<!--[if lt IE 10]><br/><label for="lh_login_page-user_login">'.__( 'User name', $this->namespace ).'</label><br/><![endif]-->
<input type="text" id="lh_login_page-user_login" name="lh_login_page-user_login" placeholder="your username" required="required"  ';

}

if (isset($_POST['lh_login_page-user_login'])){

$return_string .= ' value="'.$_POST['lh_login_page-user_login'].'"';

} elseif (isset($_GET['lh_login_page-user_login'])){
    
$return_string .= ' value="'.$_GET['lh_login_page-user_login'].'"';   
    
}



$return_string .= '></p>';


$return_string .= '<p><!--[if lt IE 10]><label for="lh-login-page-password">'.__( 'Password', $this->namespace ).'</label><br/><![endif]--><input type="password" id="lh-login-page-password" name="lh-login-page-password" placeholder="password" required="required"  /></p>
'; 



$return_string .= '<input type="hidden" id="lh_login_page-nonce" name="lh_login_page-nonce" value="" />
';

if ($redirect_to){

$return_string .= '<input type="hidden" id="lh_login_page-redirect_to" name="redirect_to" value="'.$redirect_to.'" />
';

}

ob_start();

apply_filters('login_form', '') ;


$return_string .= ob_get_contents();
ob_end_clean();


$return_string .= '<p>
<input type="submit" id="lh_login_page-login-submit" name="lh_login_page-login-submit" value="'.__( 'Login', $this->namespace ).'"/>
</p>
';


$return_string .= '
</form>
';

ob_start();

apply_filters('lh_login_form_after', '') ;


$return_string .= ob_get_contents();
ob_end_clean();


$return_string .= '<p>
<a href="'.wp_lostpassword_url().'" title="'.__( 'Lost Password', $this->namespace ).'">'.__( 'Lost Password', $this->namespace ).'</a>
</p>
';

//dequeue the web credentials script on the login page
wp_dequeue_script($this->namespace.'-web_credentials-js');

//enqueue the login functionality
$this->load_file( 'lh_login_page_script', '/assets/lh-login-page.js', true, array(), true, array('defer="defer"'));


return $return_string;


}


function lh_login_page_form_shortcode_output($atts) {


    // define attributes and their defaults
    extract( shortcode_atts( array (
        'redirect_to' => false
    ), $atts ) );

$return_string = '';


include ('partials/lh_login_page_shortcode_output.php');


return $return_string;

}




function lh_login_page_link_shortcode_output($atts, $content = "Login") {


    // define attributes and their defaults
    extract( shortcode_atts( array (
        'redirect' => false
    ), $atts ) );

if (isset($redirect)){

if ($redirect == 'self'){

$redirect = $this->curpageurl();

}


$return_string = '<a href="'.wp_login_url($redirect).'" title="Login">'.$content.'</a>';

} else {

$return_string = '<a href="'.wp_login_url().'" title="Login">'.$content.'</a>';

}


return $return_string;

}


public function register_shortcodes(){

add_shortcode('lh_login_page_form', array($this,"lh_login_page_form_shortcode_output"));
add_shortcode('lh_login_page_link', array($this,"lh_login_page_link_shortcode_output"));




}

function force_logout() {

if (isset($_GET['lh-login-page-action'])){

if ( wp_verify_nonce( $_GET['lh_login_page_nonce'], "lh_login_page_nonce") ) {

wp_logout();





}

}

}


function extend_cookie_expiration_to_1_year( $expirein ) {
   return 31556926; // 1 year in seconds
}



function redirect_if_logged_in() { 



   global $wp_query; 

if ( is_singular() ) { 

$post = $wp_query->get_queried_object(); 

if (isset($post) and isset($this->options[$this->page_id_field]) and ( $post->ID == $this->options[$this->page_id_field] ) and is_user_logged_in()) { 
    
if (isset($_GET['redirect_to']) and ($this->isValidURL($_GET['redirect_to']))){

$url =  $_GET['redirect_to'];

} else {


$url =  home_url();


}




wp_redirect($url);exit();
    
    



} 

} 

}


function login_user(){

if (isset($_POST['lh_login_page-login-submit'])){

if ( wp_verify_nonce( $_POST['lh_login_page-nonce'], "lh_login_page-nonce") ) {
    
$user_verify = $this->simple_login_user(sanitize_user($_POST['lh_login_page-user_login']), sanitize_text_field($_POST['lh-login-page-password']));



if ( is_wp_error($user_verify) ) {
    
do_action( 'wp_login_failed', $login_data['user_login'] );

$url =  add_query_arg( 'error', 'unknown_pasword' );

wp_redirect( $url ); exit;

} else {    

if (isset($_GET['redirect_to'])){

wp_redirect( $_GET['redirect_to'] ); exit;

} elseif (isset($_POST['redirect_to'])){


wp_redirect( $_POST['redirect_to'] ); exit;


} else {

if ($this->options[$this->allow_redirects_field_name] == 1){

$redirect_to = $this->curpageurl();

$redirect_to = apply_filters( 'login_redirect', $redirect_to, null, $user_verify);

wp_redirect( $redirect_to ); exit;

} else {

wp_redirect( $this->curpageurl() ); exit;


}

}


}


    
    
    
    
} 

}

}

// add a settings link next to deactive / edit
public function add_settings_link( $links, $file ) {

	if( $file == $this->filename ){
		$links[] = '<a href="'. admin_url( 'options-general.php?page=' ).$this->filename.'">Settings</a>';
	}
	return $links;
}


/**
	 * Display an informational section in the plugin admin ui.
	 * @param $meta
	 * @param $file
	 *
	 * @return array
	 */
	public function plugin_row_meta( $meta, $file ) {
		if( $file == $this->filename ){
			$meta[] = '<span><a href="'.$this->documentation_link.'">'.__( 'Documentation', $this->namespace ).'</a></span>';
			$meta[] = '<span><a href="https://profiles.wordpress.org/shawfactor/#content-plugins">'.__( 'More plugins', $this->namespace ).'</a></span>';
		}
		return $meta;
	}

//this function will only create a login page if one is not already set in options

public function create_page() {

$localoptions = get_option($this->opt_name);


if (!$page = get_page($localoptions[$this->page_id_field])){


$page['post_type']    = 'page';
$page['post_content'] = '[lh_login_page_form]';
$page['post_status']  = 'publish';
$page['post_title']   = 'Login Page';

if ($pageid = wp_insert_post($page)){

$options = $localoptions;

$options[$this->page_id_field] = $pageid;

if (update_option($this->opt_name, $options )){

}

}
}
}


public function on_activate($network){

global $wpdb;

  if ( is_multisite() && $network ) {

        // store the current blog id
   
        // Get all blogs in the network and activate plugin on each one
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {

  switch_to_blog($blog_id);

$this->create_page();



            restore_current_blog();


        }





    } else {

$this->create_page();

}

}


public function hide_title($title, $id = NULL){


if (in_the_loop() && is_singular() && $id == $this->options[$this->page_id_field]){


return '';


} else {

return $title;

}




}

public function wp_logout(){

// this needs to be fixed

if (isset($_GET['redirect_to']) and ($this->isValidURL($_GET['redirect_to']))){

$url =  $_GET['redirect_to'];

} else {


$url =  get_permalink($this->options[$this->page_id_field]);


}




wp_redirect($url);exit();


}





public function redirect_to_login() {

//various conditions to force the log in from and override other plugins way of logging on, currently just woocommerce

if (!is_user_logged_in() and in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) and is_account_page()) {


$location = wp_login_url($_SERVER["REQUEST_URI"]);

     wp_safe_redirect($location);
      exit;



}


}



public function general_init() {
  
          // Load JavaScript and stylesheets
        $this->register_scripts_and_styles();
  
  

}

public function rest_api_init() {
    register_rest_route( 
        'lh-login-page/v1',
        '/login-user/',
        array(
            'methods' => 'POST',
            'callback' => array( $this, 'rest_login_user'),
            'args'     => array(
            'username'  => array( 'required' => true ), // This is where we could do the validation callback
	        'password'  => array( 'required' => true ),
       )
        )
    );
}



// Return all notices
public function rest_login_user($request_data) {

$parameters = $request_data->get_params();


if( !isset( $parameters['username'] ) || empty($parameters['username']) ){

return new WP_Error( 'awesome_no_author', 'Missing Username', array( 'status' => 404 ) );

} elseif( !isset( $parameters['password'] ) || empty($parameters['password']) ){

return new WP_Error( 'awesome_no_author', 'Missing Password', array( 'status' => 404 ) );

} else {

$user_verify = $this->simple_login_user($parameters['username'], $parameters['password']);

if ( is_wp_error($user_verify) ){


return new WP_Error( 'awesome_no_author', $user_verify->get_error_message(), array( 'status' => 404 ) );

} else {

wp_set_auth_cookie( $user_verify->ID, true);

return $user_verify->ID;

}

}

}

public function plugins_loaded(){


load_plugin_textdomain( $this->namespace, false, basename( dirname( __FILE__ ) ) . '/languages' ); 

}

  /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }


public function __construct() {

$this->filename = plugin_basename( __FILE__ );
$this->options = get_option($this->opt_name);

add_action('admin_menu', array($this,"plugin_menu"));
add_filter( 'login_url', array($this,"filter_login_url"), 1000, 1);
add_filter( 'logout_url', array($this,"filter_logout_url"));
add_action( 'init', array($this,"register_shortcodes"));
add_action( 'after_setup_theme', array($this,"force_logout"));
add_filter( 'auth_cookie_expiration', array($this,"extend_cookie_expiration_to_1_year"));
add_action('template_redirect', array($this,"redirect_if_logged_in"));
add_action( 'wp_loaded', array($this,"login_user"));
add_filter('plugin_action_links', array($this,"add_settings_link"), 10, 2);
add_action( 'plugin_row_meta', array( $this, 'plugin_row_meta' ),10 ,2 );
add_filter('the_title', array( $this, 'hide_title' ),10,2);
add_action('wp_logout',array( $this, 'wp_logout' ));


//register required styles and scripts
add_action('init', array($this,"general_init"));


//create a custom rest api endpoint to house our responses
add_action( 'rest_api_init', array( $this, 'rest_api_init') ); 

//protect certain pages
add_action('template_redirect', array($this,"redirect_to_login"), 9);

//run whatever on plugins loaded (currently just translations)
add_action( 'plugins_loaded', array($this,"plugins_loaded"));

}


}



$lh_login_page_instance = LH_login_page_plugin::get_instance();
register_activation_hook(__FILE__, array($lh_login_page_instance,'on_activate'), 10, 1);
register_uninstall_hook( __FILE__, array('LH_login_page_plugin','uninstall'));


}

if (!class_exists('LH_WP_FB_AutoConnect_plugin')) {

class LH_WP_FB_AutoConnect_plugin {
    

 /**
     * Helper function for registering and enqueueing scripts and styles.
     *
     * @name    The    ID to register with WordPress
     * @file_path        The path to the actual file
     * @is_script        Optional argument for if the incoming file_path is a JavaScript source file.
     */
    private function load_file( $name, $file_path, $is_script = false, $deps = array(), $in_footer = true, $atts = array() ) {
        $url  = plugins_url( $file_path, __FILE__ );
        $file = plugin_dir_path( __FILE__ ) . $file_path;
        if ( file_exists( $file ) ) {
            if ( $is_script ) {
                wp_register_script( $name, $url, $deps, filemtime($file), $in_footer ); 
                wp_enqueue_script( $name );
            }
            else {
                wp_register_style( $name, $url, $deps, filemtime($file) );
                wp_enqueue_style( $name );
            } // end if
        } // end if
	  
	  if (isset($atts) and is_array($atts) and isset($is_script)){
		
		
  $atts = array_filter($atts);

if (!empty($atts)) {

  $this->script_atts[$name] = $atts; 
  
}

		  
	 add_filter( 'script_loader_tag', function ( $tag, $handle ) {
	   

	   
if (isset($this->script_atts[$handle][0]) and !empty($this->script_atts[$handle][0])){
  
$atts = $this->script_atts[$handle];

$implode = implode(" ", $atts);
  
unset($this->script_atts[$handle]);

return str_replace( ' src', ' '.$implode.' src', $tag );

unset($atts);
usent($implode);

		 

	 } else {
	   
 return $tag;	   
	   
	   
	 }
	

}, 10, 2 );
 

	
	  
	}
		
    } // end load_file

    
public function the_action_remover() {
    
    if (function_exists('jfb_output_facebook_init')) {
remove_action( 'wp_footer', 'jfb_output_facebook_init');
}

if (function_exists('jfb_output_facebook_callback')) {
remove_action('wp_footer', 'jfb_output_facebook_callback');
}
if (function_exists('jfb_enqueue_styles')) {
remove_action('wp_enqueue_scripts', 'jfb_enqueue_styles');
}


}

public function add_login_button() {
    
if (function_exists('jfb_output_facebook_init')){

?>
<p>or</p>
<p>
<input type="button" id="lh_login_page-facebook_connect" name="lh_login_page-facebook_connect" value="Connect with facebook" />
</p>

<?php



add_action( 'wp_footer', 'jfb_output_facebook_init');
add_action('wp_footer', 'jfb_output_facebook_callback');


//enqueue the login functionality
$this->load_file( 'lh_wp_fb_autoconnect-script', '/assets/wp-fb-autoconnect.js', true, array(), true, array('defer="defer"','async="async"'));


}


}



public function __construct() {



add_action( 'init', array($this,"the_action_remover"),1);
add_filter( 'lh_login_form_after', array($this,"add_login_button"));



}


}


$lh_wp_fb_autoconnect_instance = new LH_WP_FB_AutoConnect_plugin();


}


?>