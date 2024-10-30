(function() {

lhloginpagemediate = sessionStorage.getItem("lh_login_page-mediate");
isInWebAppiOS = (window.navigator.standalone === true);
isInWebAppChrome = (window.matchMedia('(display-mode: standalone)').matches);

if ('credentials' in navigator) {



if (lhloginpagemediate != 'yes'){

navigator.credentials.preventSilentAccess();

sessionStorage.setItem("lh_login_page-mediate", "yes");


navigator.credentials.get({
  password: true, // Obtain password credentials or not
  mediation: 'required' // `unmediated: true` lets the user automatically sign in
}).then(function(cred) {




if ((cred.type) && (cred.type !== null) && (cred.type == 'password')) {

//console.log(cred.password);

//console.log(cred.id);

url = document.getElementById("lh_login_page-auto_login-script").getAttribute("data-lh_login_page-rest_login_user-url");

nonce = document.getElementById("lh_login_page-auto_login-script").getAttribute("data-lh_login_page-rest_nonce");



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
   	'Accept': 'application/json, text/plain, */*',
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


} else if (isInWebAppiOS || isInWebAppChrome) {
    
    //alert('foo');
    
    
}

})();