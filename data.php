<?php

$complaint = array();
$price = array();
$rental = array();
$subway = array();
$restaurant = array();
$landmark = array();
$bucket = array();
$data = array();

neighborhood_data_build();
//$neighborhoods = get_neighborhood(100, 80, 18, 28, 73);
//print_r($neighborhoods);

//get_recommendation("Carnegie Hill");

// Get data
function neighborhood_data_build() {
  global $complaint;
  global $price;
  global $rental;
  global $subway;
  global $restaurant;
  global $bucket;
  global $landmark;
  global $data;

  $file_path = "/var/www/data/data.csv";

  if (file_exists($file_path) && ($handle = fopen($file_path, "r")) !== FALSE) {
    // Get header
    $header = fgetcsv($handle, 0, ",");

    while (($line = fgetcsv($handle, 0, ",")) !== FALSE) {
      $data[$line[0]] = array(
        'neighborhood' => $line[0],
        'complaint' => $line[1],
        'complaint_rank' => $line[3],
        'subway' => $line[4],
        'subway_rank' => $line[6],
        'restaurant' => $line[7],
        'restaurant_rank' => $line[9],
        'landmark' => $line[10], 
        'landmark_rank' => $line[12], 
        'price' => $line[13],
        'price_rank' => $line[15],
        'rental' => $line[16],
        'rental_rank' => $line[18],
        'bucket' => $line[19],
      );
      $landmark[$line[12]] = array(
        'neighborhood' => $line[0],
	'value' => $line[10],
	'bucket' => $line[19],
      );
      $complaint[$line[3]] = array(
        'neighborhood' => $line[0],
	'value' => $line[1],
	'bucket' => $line[19],
      );
      $price[$line[15]] = array(
        'neighborhood' => $line[0],
	'value' => $line[13],
	'bucket' => $line[19],
      );
      $rental[$line[18]] = array(
        'neighborhood' => $line[0],
	'value' => $line[16],
	'bucket' => $line[19],
      );
      $subway[$line[6]] = array(
        'neighborhood' => $line[0],
	'value' => $line[4],
	'bucket' => $line[19],
      );
      $restaurant[$line[9]] = array(
        'neighborhood' => $line[0],
	'value' => $line[7],
	'bucket' => $line[19],
      );
      $bucket[$line[19]][] = $line[0];
    }
  } // end of if
  fclose($handle);
}

/**
 * Get an array of recommended neighborhoods based on the current neighborhood.
 */
function get_recommendation($name) {
  global $data;
  global $bucket;

  $choices = array();
  $b = isset($data[$name]) ? $data[$name]['bucket'] : '';

  if ($b != '') {
    foreach ($bucket[$b] as $neighborhood) {
      if ($neighborhood != $name) {
        $choices[] = $neighborhood;
      }
    }
    shuffle($choices);
    //print_r($choices);
    return array($data[$choices[0]], $data[$choices[1]], $data[$choices[2]], $data[$choices[3]], $data[$choices[4]]);
  }

  return array();
}

/**
 * Get an array of recommended neighborhoods based on the 4sq checkin data.
 */
function get_neighborhood($total, $professional, $night_life, $transportation, $food) {
  global $complaint;
  global $price;
  global $rental;
  global $subway;
  global $restaurant;
  global $landmark;
  global $bucket;
  global $data;

  $array = array('p' => $professional, 'n' => $night_life, 't' => $transportation, 'f' => $food);
  asort($array); // sort the checkins
  $array = array_keys($array);

  // Get top 2
  $neighborhoods = array();
  foreach (array($array[3], $array[2]) as $key) {
    switch ($key) {
      case 'p': // professional
        $neighborhoods[] = $price[1]['neighborhood'];
        $neighborhoods[] = $complaint[233]['neighborhood'];
	$b = $price[1]['bucket'];
        break;
      case 'n': // nightlife
        $neighborhoods[] = $rental[1]['neighborhood']; // 233 is least expensive
        $neighborhoods[] = $subway[233]['neighborhood'];
	$b = $rental[1]['bucket'];
        break;
      case 't': // transportation
        $neighborhoods[] = $subway[233]['neighborhood'];
        $neighborhoods[] = $landmark[233]['neighborhood'];
	$b = $subway[1]['bucket'];
        break;
      case 'f': //  food
        $neighborhoods[] = $restaurant[233]['neighborhood'];
        $neighborhoods[] = $landmark[233]['neighborhood'];
	$b = $restaurant[1]['bucket'];
        break;
      default:
        $neighborhoods[] = $complaint[233]['neighborhood'];
	$b = $complaint[1]['bucket'];
    }

    foreach ($bucket[$b] as $element) {
      $neighborhoods[] = $element;
    }

  }

  $choices =  array_unique($neighborhoods);
  //shuffle($choices);

  // Re-index the array but keep the ordering
  $array = array();
  foreach ($choices as $choice) {
    $array[] = $choice;
  }

  // Getting the top 5 results
  return array($data[$array[0]], $data[$array[1]], $data[$array[2]], $data[$array[3]], $data[$array[4]]);
}
?>
