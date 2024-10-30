function lh_wp_fb_autoconnect_connect() {

FB.login(function(resp){ if (resp.authResponse)jfb_js_login_callback(); }, {scope:'email', auth_type:'rerequest'});


}

if (document.getElementById("lh_login_page-facebook_connect")){


document.getElementById("lh_login_page-facebook_connect").addEventListener("click", lh_wp_fb_autoconnect_connect, false);
document.getElementById("lh_login_page-facebook_connect").style.width = "100%";
document.getElementById("lh_login_page-facebook_connect").style.backgroundColor = "#3b5998";



}