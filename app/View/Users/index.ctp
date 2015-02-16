 <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
  <script src="https://apis.google.com/js/client:platform.js?parsetags=explicit" async defer></script>
  <script>
  function render() {
    gapi.auth.signIn({
      'callback': 'googleCallBack',
      'accesstype': 'online',
      'redirecturi':'postmessage',
      'clientid': '<?php echo GOOGLE_CLIENT_ID?>',
      'cookiepolicy': 'single_host_origin',
      'requestvisibleactions': 'http://schema.org/AddAction',
      'scope': 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/userinfo.email',
      //'approvalprompt': 'force'
    });
  }
  </script>
  <style type="text/css">
    #customBtn {
      display: inline-block;
      background: #dd4b39;
      color: white;
      width: 165px;
      border-radius: 5px;
      white-space: nowrap;
    }
    #customBtn:hover {
      background: #e74b37;
      cursor: hand;
    }
    span.label {
      font-weight: bold;
    }
    span.icon {
      background: url('/+/images/branding/btn_red_32.png') transparent 5px 50% no-repeat;
      display: inline-block;
      vertical-align: middle;
      width: 35px;
      height: 35px;
      border-right: #bb3f30 1px solid;
    }
    span.buttonText {
      display: inline-block;
      vertical-align: middle;
      padding-left: 35px;
      padding-right: 35px;
      font-size: 14px;
      font-weight: bold;
      /* Use the Roboto font that is loaded in the <head> */
      font-family: 'Roboto',arial,sans-serif;
    }
  </style>
  <!-- In the callback, you would hide the gSignInWrapper element on a
  successful sign in -->
  <div id="gSignInWrapper" onclick="render()">
    <span class="label">Sign in with:</span>
    <div id="customBtn" class="customGPlusSignIn">
      <span class="icon"></span>
      <span class="buttonText">Google</span>
    </div>
  </div>



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  


<script>
    function googleCallBack(authResult) {
        console.log(authResult);
         if (authResult['code']) {
        
            // Hide the sign-in button now that the user is authorized, for example:
            $('#signinButton').attr('style', 'display: none');

            // Send the code to the server
            $.ajax({
              type: 'POST',
              dataType:'json',
              url: '<?php echo Router::url(array('controller' => 'users', 'action'=>'storeToken'))?>',
              success: function(result) {
                  console.log(result['error'])
                if (result['error'] == true) {
                    window.location.href = '<?php echo Router::url(array('controller' => 'users', 'action'=>'login'))?>';
                }
              },
             // processData: false,
              data: authResult
            });
        } else if (authResult['error']) {
          // There was an error.
          // Possible error codes:
          //   "access_denied" - User denied access to your app
          //   "immediate_failed" - Could not automatially log in the user
          // console.log('There was an error: ' + authResult['error']);
        }

    }
</script>


