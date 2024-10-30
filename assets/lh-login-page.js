(function() {

if (document.getElementById("lh_login_page-login_form")){

document.getElementById("lh_login_page-nonce").value = document.getElementById("lh_login_page-login_form").getAttribute("data-lh_login_page-nonce");


document.getElementById("lh_login_page-login-submit").style.width = "100%";


document.getElementById("lh_login_page-user_login").focus();
document.getElementById("lh_login_page-user_login").select();

sessionStorage.setItem("lh_login_page-mediate", "yes");



}



document.getElementsByTagName("body")[0].style.display = "block";




})();