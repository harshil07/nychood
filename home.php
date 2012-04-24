<!DOCTYPE html "-//W3C//DTD XHTML 1.0 Strict//EN" 
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>NYCHood</title>
    <link rel="stylesheet" href="ui.progress-bar.css">

     <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAAIfmSxXo3bN3BpC14haMqlRSz58roCK_JWD4njaHFdXUQ95VS1xQ4tMEtIAY1FCK9PdPHROCESd0wJQ" type="text/javascript"></script>
    <script type="text/javascript">

var map;
var geocoder = new GClientGeocoder();

    function initialize(address, encodedstring) {
      if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("map_canvas"));
		map.addControl(new GSmallMapControl());
		map.setCenter(new GLatLng(37.4419, -122.1419), 13);
		var encodedPolyline = new GPolyline.fromEncoded({
			color: "#FF0000",
			weight: 5,
			points: encodedstring,
			levels: "BBB",
			zoomFactor: 32,
			numLevels: 4
		});
		map.addOverlay(encodedPolyline);
		showAddress(address + ", New York, NY")
      }
    }


function showAddress(address) {
  geocoder.getLatLng(
    address,
    function(point) {
      if (!point) {
        alert(address + " not found");
      } else {
        map.setCenter(point, 13);
        var marker = new GMarker(point);
        map.addOverlay(marker);

        // As this is user-generated content, we display it as
        // text rather than HTML to reduce XSS vulnerabilities.
        marker.openInfoWindow(document.createTextNode(address));
      }
    }
  );
}
	
    </script>
    <style type="text/css">
<!--
h1 {
	text-align: center;
}
body {
	background: url('/images/IMG_7467.jpg') no-repeat ;
	background-size: 100%;
	text-align: center;

}
div.container{
	background-color:#FFF;
	width:500px;
	height:300px;
	opacity:0.75;
}
#logo {
  margin-bottom: 1em;
}

    </style>
  </head>
      <?php
	  include('addr2neighborhood.php');
	  include('data.php');
	  global $data;
	 $neighborhood =  $_GET['neighborhood'];

	$encoded_str = '';
	$encoded_str = neighborhood2encoded($neighborhood);
?>

  
  <body onload="initialize('<?php echo $neighborhood . "','" . $encoded_str; ?>')" onunload="GUnload()">

  <div class="header"></a> 
    <!-- end .header --></div>
<div class="content" style="text-align:center">
   <div id="logo">
   <a href="http://nychood.com/"><img src="images/logo.png"></a> 
  </div><br/><br/>
   <table><td> <div id="map_canvas" style="width: 500px; height: 400px"></div><td>
 <td> <div class="container" style="left:200px">


	
  <font style="font-family: Verdana, Geneva, sans-serif; font-size: 18px; font-weight: bold;"><?php echo $neighborhood; ?></font>
  <table align="center">
  	<tr>
	<td><font style="color: #000; font-weight: bold; font-family: Bentham;">Restaurants, Shopping, Amenties: </font></td><td style="padding-left:20px">
  	<div id="progress_bar" class="ui-progress-bar ui-container">
      <div class="ui-progress" style="width:<?php echo $data[$neighborhood]['restaurant_rank']/233*100 . '%';?>;">
        <span class="ui-label" style="display:none;"><b class="value">79%</b></span>
      </div>
    </div></td></tr>
    <tr><td><font style="color: #000; font-weight: bold; font-family: Bentham;">Cultural Life: </font></td><td style="padding-left:20px">
  	<div id="progress_bar" class="ui-progress-bar ui-container">
      <div class="ui-progress" style="width: <?php echo $data[$neighborhood]['landmark_rank']/233*100 . '%';?>;;">
        <span class="ui-label" style="display:none;"><b class="value">79%</b></span>
      </div>
    </div></td></tr>
    <tr><td><font style="color: #000; font-weight: bold; font-family: Bentham;">Living Expense: </font></td><td style="padding-left:20px">
  	<div id="progress_bar" class="ui-progress-bar ui-container">
      <div class="ui-progress" style="width: <?php echo $data[$neighborhood]['rental_rank']/233*100 . '%';?>;">
        <span class="ui-label" style="display:none;"><b class="value">79%</b></span>
      </div>
    </div></td></tr>
    <tr><td><font style="color: #000; font-weight: bold; font-family: Bentham;">Transportation:</font> </td><td style="padding-left:20px">
  	<div id="progress_bar" class="ui-progress-bar ui-container">
      <div class="ui-progress" style="width:<?php echo $data[$neighborhood]['subway_rank']/233*100 . '%';?>;">
        <span class="ui-label" style="display:none;"><b class="value">79%</b></span>
      </div>
    </div></td></tr>
    <tr><td><font style="color: #000; font-weight: bold; font-family: Bentham;">Safety: </font></td><td style="padding-left:20px">
  	<div id="progress_bar" class="ui-progress-bar ui-container">
      <div class="ui-progress" style="width:<?php echo $data[$neighborhood]['complaint_rank']/233*100 . '%';?>;;">
        <span class="ui-label" style="display:none;"><b class="value">79%</b></span>
      </div>
    </div></td></tr>
    
</table>

</div></td>
     </body>

</html>
