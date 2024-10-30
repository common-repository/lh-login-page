function lh_login_page_readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function lh_login_page_eraseCookie(name) {
    createCookie(name,"",-1);
}

if ('credentials' in navigator) {

lhloginpagemediate = lh_login_page_readCookie('lh_login_page-mediate');

if (lhloginpagemediate != 'yes'){

navigator.credentials.preventSilentAccess();

document.cookie = "lh_login_page-mediate=yes";


navigator.credentials.get({
  password: true, // Obtain password credentials or not
  mediation: 'required' // `unmediated: true` lets the user automatically sign in
}).then(function(cred) {




if (cred.type == 'password') {

//console.log(cred.password);

//console.log(cred.id);

url = document.getElementById("lh_login_page-web_credentials").getAttribute("data-lh_login_page-login-user-url");

nonce = document.getElementById("lh_login_page-web_credentials").getAttribute("data-lh_login_page-rest_nonce");



      // Construct FormData object
        let form = new FormData();
    form.append('username', cred.id);
    form.append('password', cred.password);


      // `POST` the credential object as `credentials`.
      // id, password and the additional data will be encoded and
      // sent to the url as the HTTP body.
      fetch(url, {           // Make sure the URL is HTTPS
        method: 'POST',      // Use POST
   	headers: {
	'X-WP-Nonce': nonce
    },
      credentials: 'include',
      body: form
      }).then(function(response) {
  if(response.ok) {
     
return response.text();
  }



        // continuation
      }).then(function(text) { 

  	console.log(text); 
  	window.location.reload(true); 

  	
  	
  });
    }





});



}


}