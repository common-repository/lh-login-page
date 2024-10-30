<?php



if ( is_user_logged_in() ) {

$user = wp_get_current_user();


$return_string = '<p>'.$user->display_name.' you are already logged in</p>';

$return_string .= '<p>If this is not you, please <a href="'.wp_logout_url(get_permalink()).'" title="Logout">logout</a></p>';

} else {



$return_string .= $this->lh_login_page_form_output($return_string, $redirect_to);

}

?>