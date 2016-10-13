<?php
  $ip_address = $_SERVER['REMOTE_ADDR'];
  if ($ip_address != null) {
    $ip_info = json_decode(file_get_contents("http://freegeoip.net/json/{$ip_address}"));
    if (($ip_info != null) and ($ip_info->city != null)) {
      $location = $ip_info->city . ", " . $ip_info->region_code;
      $location_coords = $ip_info->latitude . "," . $ip_info->longitude;
    } else {
      $location = "San Francisco, CA";
    }
  } else {
    $location = "San Francisco, CA";
  }

  if ($geo_info->status == "ZERO_RESULTS") {
    echo "<h2>" . __("Location Not Found") . "</h2>\n";
    echo "  <p>" . __("Hmm, the location you’re looking for can’t be found.") . "<br>" . __("Are you sure you didn’t mistype it?") . "</p>\n";
    $okay = True;
  } else {
    $okay = False;
    $message = "An error occurred on your Atmosphere Weather Site “" . $_SERVER['HTTP_HOST'] . "”. Errors are usually caused by going over query limits for either Dark Sky or Google Geocode.\n\nGeocode Status: “" . $geo_info->status . "”\nDark Sky Status: “" . $forecast->flags . "”\n\nTo disable further emails, remove this address from “ERROR_CONTACT” in “index.php”.\n\nhttp://atmosphere.li\nhttps://github.com/willnap/atmosphere";
    mail($ERROR_CONTACT, 'Site Error', $message, 'From: Atmosphere <will@atmosphere.li>');
    echo "<h2>" . __("It's Not You, It's Us.") . "</h2>\n";
    echo "  <p>" . __("We’re experiencing an internal error.") . "<br>" . __("The site administrator has been contacted.") . "</p>\n";
    if (!($geo_info->status == "OK")) {
      echo "  <span>(Technical: Geocode Error)</span>\n";
      echo "  <script>console.log('" . $geo_info->status . "');</script>\n";
    } else {
      echo "  <span>(Technical: Dark Sky Error)</span>\n";
      echo "  <script>console.log('" . $forecast->flags . "');</script>\n";
    }
  }

  if ($okay) {
    echo '  <p><a href="/?loc=' . $location . '">' . __('Weather for ') . $location . '</a></p>' . "\n";
    echo '  <p id="alS"></p>' . "\n";
  }
?>
  <br><br>
  <script>
    var alS = document.getElementById("alS");
    alS.classList.add("h");
    if (navigator.geolocation) {
      alS.innerHTML = '<button onclick="loc()"><?php echo __("Weather for Current Location"); ?></button>';
      alS.classList.remove("h");
    }
    function loc() {
      if (!navigator.geolocation) {
        alS.innerHTML = "<?php echo __("Not Supported"); ?>";
        return;
      }
      function suc(position) { location = "https:" + "//" + window.location.hostname + window.location.pathname + "?loc=" + position.coords.latitude + "," + position.coords.longitude; }
      function err() { alS.innerHTML = "<?php echo __("Search Error"); ?>"; }
      alS.innerHTML = "<?php echo __("Locating..."); ?>";
      navigator.geolocation.getCurrentPosition(suc, err);
    }
  </script>
