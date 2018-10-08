<html>
<head>
<title>Game App</title>
    
    <link rel="stylesheet" type="text/css" href="style.css">
    
    <script>var hidden = false;
      var count = 1;
      setInterval(function(){ // This function is here for the blink effect of the button
      document.getElementById("link").style.visibility= hidden ? "visible" : "hidden"; // setInterval will execute this infinite time
    																				// within interval of 300 seconds
      hidden = !hidden;
      },300);
      </script>

</head>


<body>
	
<div id="wrapper">
    <h1>WHO WILL BE YOUR PARTNER IN A ZOMBIE APOCALYPSE??</h1>
    <div class="whitebox">
      
    </div>
</div>
<div class="charone">
  <img src="char1" align="left" height="60%"  />

</div>
<div class="chartwo">
  <img src="Agent" align="left"  height="60%" />

</div>
	
 
    </body>


<?php
// new 
session_start();
require_once __DIR__ . '/Facebook/autoload.php';



$fb = new Facebook\Facebook([
  'app_id' => '1848815498710864',
  'app_secret' => '30760bb1d941249ff538c476db62b5f8',
  'default_graph_version' => 'v2.9',
  
  ]);



$helper = $fb->getRedirectLoginHelper();
//$permissions = ['email']; // optional
//$permissions = ['friendlist'];
$permissions =  array("email","user_friends");	

try {
	if (isset($_SESSION['facebook_access_token'])) {
		$accessToken = $_SESSION['facebook_access_token'];
	} else {
  		$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
 	// When Graph returns an error
 	echo 'Graph returned an error: ' . $e->getMessage();
  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
 	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
  	exit;
 }
if (isset($accessToken)) {
	if (isset($_SESSION['facebook_access_token'])) {
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		header('Location: http://localhost/fb_S/fb/i.php');
	} else {
		// getting short-lived access token
		$_SESSION['facebook_access_token'] = (string) $accessToken;
	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();
		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
		// setting default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}
	
  // redirect the user back to the same page if it has "code" GET variable
	if (isset($_GET['code'])) {
		header('Location: ./');
	}
	
  //header('Location: http://localhost/fb/i.php');

  } else {
	// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
	$loginUrl = $helper->getLoginUrl('http://localhost/fb_S/fb/', $permissions);
	
	echo '<center><a class="link" href="' . $loginUrl . '"></a></center>';


}



?>

</html>