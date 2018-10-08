<?php
  use Facebook\Facebook;
  use Facebook\Exceptions\FacebookResponseException;
  use Facebook\Exceptions\FacebookSDKException;

  session_start();
  require_once __DIR__ . '/Facebook/autoload.php';
  $fb = new Facebook([
    'app_id' => '468433546988272',
    'app_secret' => 'bd5108b5cd645abe07a388d3eb2f9bb3',
    'default_graph_version' => 'v2.9',
  ]);
  $helper = $fb->getRedirectLoginHelper();
//$permissions = ['email']; // optional





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



    // Getting user facebook profile info
    try {

        $profileRequest = $fb->get('/me?fields=name,first_name,last_name,birthday,email,link,gender,locale,picture,games',$_SESSION['facebook_access_token']);
        $profileRequest1 = $fb->get('/me?fields=name');
        $requestPicture = $fb->get('/me/picture?redirect=false&height=310&width=300'); //getting user picture
        $profileRequest3 = $fb->get('/me?fields=gender');
        $requestFriends = $fb->get('/me/taggable_friends?fields=name,picture.width(300).height=310&limit=20');
        $profileRequest4 = $fb->get('/me?fields=games');


		    $fbUserProfile = $profileRequest->getGraphNode()->asArray();

        $friends = $requestFriends->getGraphEdge();

        //$birthday= $fb->get('/me?fields=age_range,timezone');

        $a = $fb->get('/me/friends?fields=name,gender'); //////////////////////////////////////////////////////////

        $requestFriendsPicture = $fb->get('/me/friends/picture?redirect=false&height=310&width=300');

        $b = $a ->getGraphEdge();

        $fbUserProfile1 = $profileRequest1->getGraphNode();

        $picture = $requestPicture->getGraphNode();

        //$Friendspicture = $requestFriendsPicture->getGraphNode();

        //$bday = $birthday->getGraphNode();

        $fbUserProfile3 = $profileRequest3->getGraphNode();

        $fbUserProfile4 = $profileRequest4->getGraphNode();





		// If button is clicked a photo with a caption will be uploaded to facebook
		if(isset($_POST['insert'])){
     	$data = ['source' => $fb->fileToUpload(__DIR__.'/photo.jpeg'), 'message' => 'Check out this app! It is awesome http://localhost/fb/i.php '];
		$request = $fb->post('/me/photos', $data);
		$response = $request->getGraphNode()->asArray();
		header("Location: http://facebook.com");

    }



    } catch(FacebookResponseException $e) {


        echo 'Graph returned an errrrrrror: ' . $e->getMessage();
        session_destroy();
        header("Location: ./");
        exit;
    } catch(FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }






   // assigning a country according to the timezone
  $randomInteger = rand(0,19);
  $name= $friends[$randomInteger]['name'];
  $FriendsPic= $friends[$randomInteger]['picture'];

  // getting gender
  if ($fbUserProfile['gender']=='male'){
  	$gender = 'female';
  }
  else{
  	$gender = 'male';
  }

  // Reasons

  $reasons = array(
  "Tommy Vercetti-Grand Theft Auto Vice City",
  "Agent 47-Hitman",
  "Captain Price-Call of Duty Modern Warfare",
  "Barbarian-Clash of Clans",
  "Ezio Auditore da Firenze-Assassin's Creed 2",
  " Master Chief-Halo: Combat Evolved"

  );
  $selected_reason=$reasons[array_rand($reasons)];

  if ($selected_reason=='Tommy Vercetti-Grand Theft Auto Vice City'){

  }
  elseif ($selected_reason=='Agent 47-Hitman') {

  }
  else{
    $gender = 'Agent';

  }


}else{

}
?>


<html>
  <head>
    <title>Game FB App</title>
    <script src="html2canvas.js"></script>
    <style type="text/css">

      body {
          background-image: url("backgroundNext.jpeg");
          height: 100%;
  	      background-repeat: no-repeat;
          text-align: center;
          background-attachment: fixed;
          background-size: cover;

      }
          .warning{font-family:Arial, Helvetica, sans-serif;color:#FFF; top:0px;position:relative;}
          .you { position: relative; top: 25% ;}
          .me { position: relative; top: 25% ; margin-left: 10%;}
          .content{font-family: Papyrus,fantasy;top:-450px;left:830px;position:relative;font-size:20px; }

      .button{
          background-image: url("share.png");
          background-size: 400px 50px;
          width: 400px;
          height:50px;
          margin-top: 5%;
      }



    </style>

</head>

<body>

  <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9&appId=1848815498710864";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>



	<h1 class="warning">
    <b>

      <?php
        echo $name." Will be your partner in a in a zombie apocalypse. !";
      ?>

    </b>
  </h1>

    <section>

      <div class="images" style="position:relative;left:0;">

        <?php
          echo "<img src='".$picture['url']."' class='you' id='you' />";
          echo "<img src='".$FriendsPic['url']."' class='me' id='you'/>";
        ?>

      </div>
    </section>

    <div class="fb-share-button" data-href="http://localhost/fb_S/fb/i.php" data-layout="button_count" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="http://localhost/fb_S/fb/&amp;src=sdkpreparse">Share </a></div>
    </body>
</html>
