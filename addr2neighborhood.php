<?php

/**
 * Get neighborhood name from address.
 */
function address2neighborhood($address, $city = 'New York', $zip = '') {
  $address = urlencode($address);
  $city = urlencode($city);
  
  $json = file_get_contents("http://streeteasy.com/nyc/api/areas/for_address?address=$address&city=$city&zip=$zip&key=426b0d9941425f139ac042bf931b3033b6fd3817&format=json");
  $array = json_decode($json);
  if (property_exists($array, 'name') && !empty($array->name)) {
    return $array->name;
  } else {
    return '';
  }
}


function address2encoded($address, $city = 'New York', $zip = '') {
  $address = urlencode($address);
  $city = urlencode($city);
  
  $json = file_get_contents("http://streeteasy.com/nyc/api/areas/for_address?address=$address&city=$city&zip=$zip&key=426b0d9941425f139ac042bf931b3033b6fd3817&format=json");
  $array = json_decode($json);
  if (property_exists($array, 'boundary_encoded_points_string') && !empty($array->boundary_encoded_points_string)) {
    return $array->boundary_encoded_points_string;
  } else {
    return '';
  }
}

function neighborhood2encoded($area) {
  $area = urlencode($area);

  $json = file_get_contents("http://streeteasy.com/nyc/api/areas/search?q=$area&key=426b0d9941425f139ac042bf931b3033b6fd3817&format=json");
  $array = json_decode($json, true);
  //if (property_exists($array, 'areas') && !empty($array[areas][0]['boundary_encoded_points_string'])) {
    return $array[areas][0]['boundary_encoded_points_string'];
  //} else {
  //  return '';
  //}
}

?>
