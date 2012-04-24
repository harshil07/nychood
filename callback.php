<?php

//Put in the key and secret for YOUR Foursquare app, callback URL and receive the _GET code.
$clientId = "KT4LJGNBECARUCN05I1M45KJUQ5CRF5IHGMOZFQDLAO5C0HD";
$clientSecret = "WJHXFJBE05Q1FCR1ZLEUM0CLN00LEAVDJ4XILO3BUAY03QJ2";
$redirectUrl= "http://nychood.com/callback.php";
$settoken=$_GET['code'];

//Includes the foursquare-async library files
require_once('EpiCurl.php');
require_once('EpiSequence.php');
require_once('EpiFoursquare.php');
include('data.php');

//Start session and make some magic…
session_start();
$foursquareObj = new EpiFoursquare($clientId, $clientSecret);
$token = $foursquareObj->getAccessToken($settoken, $redirectUrl);
$foursquareObj->setAccessToken($token["access_token"]);

// You should save the $token in your $_SESSION and in your database for further use, but we don’t need it right now. 
// You will need to setAccessToken($token["access_token"]); the first time you want to make a call in a certain page.
$_SESSION["access_token"]= $token["access_token"];
$access_token = $token["access_token"];
// $_SESSION["access_token"] = $access_token
$checkinUrl = "https://api.foursquare.com/v2/users/self/checkins?limit=500&oauth_token=" . $access_token;
$checkins = file_get_contents($checkinUrl);
// print json_decode($checkins);


$checkins_recoded = json_decode($checkins, true);
//var_dump($checkins_recoded);
 $pro = 0;
 $night = 0;
 $transport = 0;
 $food = 0;
 for($c=0;$c<=count($checkins_recoded['response']['checkins']['items']);$c++){
	if (!empty($checkins_recoded['response']['checkins']['items'][$c]['venue']['categories'][0]['parents'][0])){
//		var_dump($checkins_recoded['response']['checkins']['items'][$c]['venue']['categories'][0]['parents'][0]);
		switch ($checkins_recoded['response']['checkins']['items'][$c]['venue']['categories'][0]['parents'][0]) {
		case "Professional & Other Places":
			$pro = $pro + 1;
			break;
		case "Food":
			$food = $food + 1;
			break;
		case "Travel & Transport":
			$transport = $transport + 1;
			break;
		case "Nightlife Spots":
			$night = $night + 1;
			break;
		}
	}
 }
// print "Professional: " . $pro;
// print "<br>Night Life: " . $night;
// print "<br>Transport: " . $transport;
// print "<br>Food: " . $food;
$total = $food + $transport + $night + $pro;
// print "<br>Total: " . $total;
?>

<html>
  <head>
  <style type="text/css">
h1 {
	text-align: center;
}
body {
  background: url('/images/IMG_7467.jpg') no-repeat;
  background-size: 100%;
}
div#right{
	float: right;
	top:0;
	right:0;
	width:21%;
	padding-right: 13%;
}

div#neighborhoods{
	float: left;
	width: 65%;
	margin-left: auto;
    margin-right: auto;
	padding-left: 10px;
	}

a.button {
font-size: 20px;
line-height: 30px;
vertical-align: bottom;
border: 1px solid #B0281A;
color: white;
text-shadow: 0 1px rgba(0, 0, 0, 0.1);
background-color: #D14836;
background-image: linear-gradient(top,#dd4b39,#d14836);
display: inline-block;
min-width: 65%;
text-align: center;
font-weight: 700;
-webkit-border-radius: 2px;
border-radius: 2px;
-webkit-user-select: none;
cursor: pointer;
opacity: 0.75;
-webkit-transition: opacity 300ms ease-out 100ms;
-moz-transition: opacity 300ms ease-out 100ms;
-o-transition: opacity 300ms ease-out 100ms;
margin: 0;
padding: 15px;
-webkit-border-radius: 10px;
-moz-border-radius: 10px;
}
#logo {
  margin-bottom: 5em;
  align: center;
  margin-left: auto;
  margin-right: auto;
  width: 40%;
  }

#neighborhood .button {
  font-size: 2em;
  text-decoration: none;
}

</style>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Task');
        data.addColumn('number', 'Hours per Day');
        data.addRows(5);
        data.setValue(0, 0, 'Professional');
        data.setValue(0, 1, <?php echo $pro; ?>);
        data.setValue(1, 0, 'Night Life');
        data.setValue(1, 1, <?php echo $night; ?>);
        data.setValue(2, 0, 'Transport');
        data.setValue(2, 1, <?php echo $transport; ?>);
        data.setValue(3, 0, 'Food');
        data.setValue(3, 1, <?php echo $food; ?>);


        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 450, height: 300, title: 'What goes into how we chose your neighborhood'});
      }
    </script>
  </head>

  <body> 
  <div id="header">
    <div id="logo">
      <a href="http://nychood.com/">
        <img src="images/logo.png">
      </a>
    </div>
  </div>
  <div id="neighborhoods">
		<?php $neighborhoods = get_neighborhood($total,$pro,$night,$transport,$food); 
		$c = 1;
		?>
		
		<?php foreach ($neighborhoods as $neighborhood) { 		?>
		    <div id="neighborhood"><a href="http://nychood.com/home.php?neighborhood=<?php echo $neighborhood['neighborhood']; ?>&c=<?php 
			echo $c;  
			$c = $c + 1;
			?>" class="button"><?php echo $neighborhood['neighborhood']; ?>
			</a></div><br/>
		  <?php } ?>
		</div>
  <div id="right">
		
		<div id="chart_div">
		</div>
	</div>
  </body>
</html>


