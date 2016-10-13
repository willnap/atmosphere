<?php
  $DARKSKY_KEY = getenv('DARKSKY_KEY');
  if ($DARKSKY_KEY == null) {
    $DARKSKY_KEY = "";
  }
  $GEOCODE_KEY = getenv('GEOCODE_KEY');
  if ($GEOCODE_KEY == null) {
    $GEOCODE_KEY = "";
  }
  $ERROR_CONTACT = getenv('ERROR_CONTACT');
  if ($ERROR_CONTACT == null) {
    $ERROR_CONTACT = "error@atmosphere.li";
  }
  $DEBUG = getenv('DEBUG');
  if ($DEBUG == null) {
    $DEBUG = False;
  }

  $location = htmlspecialchars($_GET["loc"]);
  $ip_address = $_SERVER['REMOTE_ADDR'];
  include 'elements/functions.php';
  🍪("units", "us");
  🍪("lang", "en");
  include 'locale/' . $lang . '.php';
  if ($locale_php != null) {
    setlocale(LC_TIME, $locale_php . ".UTF-8");
  }
  if ($locale24) {
    setcookie("time", "24", time()+31556926, "/", "." . $_SERVER[HTTP_HOST]);
  } else {
    🍪("time", "12");
  }
  if ($location != null) {
    debug("Path: User Input");
  } else if (preg_match('/spider|bot/', $_SERVER[HTTP_USER_AGENT])) {
    debug("Path: Bot");
    $location = "San Francisco, CA";
  } else if ($ip_address != null) {
    $ip_info = json_decode(file_get_contents("http://freegeoip.net/json/{$ip_address}"));
    if (($ip_info != null) and ($ip_info->city != null)) {
      debug("Path: IP Address");
      $location = $ip_info->city . ", " . $ip_info->region_code;
      $location_coords = $ip_info->latitude . "," . $ip_info->longitude;
    } else {
      debug("Path: IP Error");
      $location = "San Francisco, CA";
    }
  } else {
    debug("Path: Default");
    $location = "San Francisco, CA";
  }

  $location = str_replace(" ", "_", htmlspecialchars($location));
  $geo_info = json_decode(file_get_contents("https://maps.google.com/maps/api/geocode/json?address={$location}&key={$GEOCODE_KEY}&language={$locale_geo}"));
  if (($geo_info != null) and ($geo_info->status == "OK")) {
    $location_coords = $geo_info->results[0]->geometry->location->lat . "," . $geo_info->results[0]->geometry->location->lng;
    $forecast = json_decode(file_get_contents("https://api.darksky.net/forecast/{$DARKSKY_KEY}/{$location_coords}?lang={$lang}&units={$units}"));
    $location = str_replace(", USA", "", $geo_info->results[0]->formatted_address);
    $location = str_replace(", EE. UU.", "", $geo_info->results[0]->formatted_address);
  } else if ($location_coords != null) {
    $location = str_replace("_", " ", $location);
    $forecast = json_decode(file_get_contents("https://api.darksky.net/forecast/{$DARKSKY_KEY}/{$location_coords}?lang={$lang}&units={$units}"));
  } else {
    $location = str_replace("_", " ", $location);
    $valid = False;
  }
  if (($forecast != null) and ($forecast->currently->icon != "")) {
    $valid = True;
  } else {
    $forecast = json_decode(file_get_contents("https://api.darksky.net/forecast/{$DARKSKY_KEY}/{$location_coords}?lang={$lang}&units={$units}"));
    if (($forecast != null) and ($forecast->currently->icon != "")) {
      debug("Second Try");
      $valid = True;
    } else {
      debug("Second Try Failed");
      $valid = False;
    }
  }

  if ($valid) {
    $main_icon = $forecast->currently->icon;
    $favicon = $main_icon;
    $forecast_time = $forecast->currently->time;
    $forecast_timezone = $forecast->timezone;
    $forecast_sunrise = $forecast->daily->data[0]->sunriseTime;
    $forecast_sunset = $forecast->daily->data[0]->sunsetTime;
    if ( !((strpos($main_icon, "day") !== false) or (strpos($main_icon, "night") !== false)) ) { /* If $main_icon dosen't include "-day" or "-night", add it. */
      if ( (($forecast_time - $forecast_sunrise) > 0) and (($forecast_time - $forecast_sunset) <= 0) ) {
        $main_icon = $main_icon . "-day";
      } else {
        $main_icon = $main_icon . "-night";
      }
    }
  } else {
    $main_icon = "default";
    $favicon = "favicon";
  }

  switch ($main_icon) {
    case "clear-day": case "cloudy-day": case "partly-cloudy-day": case "wind-day":
      $back_color = "#5DA8CC";
      $back_gradient = "#5DA8CC, #4A9BC3";
      debug("Back: Bright Day");
      break;
    case "clear-night": case "cloudy-night": case "partly-cloudy-night": case "wind-night":
      $back_color = "#1C2139";
      $back_gradient = "#1C2139, #2F3956";
      debug("Back: Bright Night");
      break;
    case "rain-day": case "fog-day": case "tornado-day":
      $back_color = "#98AFC7";
      $back_gradient = "#98AFC7, #6A788A";
      debug("Back: Dim Day");
      break;
    case "rain-night": case "fog-night": case "tornado-night":
      $back_color = "#7F90AA";
      $back_gradient = "#7F90AA, #485568";
      debug("Back: Dim Night");
      break;
    case "snow-day": case "sleet-day":
      $back_color = "#939BA6";
      $back_gradient = "#939BA6 0%, #87919D 45%, #6F7C8C 100%";
      debug("Back: Muted Day");
      break;
    case "snow-night": case "sleet-night":
      $back_color = "#6B6A70";
      $back_gradient = "#6B6A70 0%, #727179 45%, #404751 100%";
      debug("Back: Muted Night");
      break;
    default:
      $back_color = "#5DA8CC";
      $back_gradient = "#5DA8CC, #4A9BC3";
      debug("Back: Default ({$main_icon})");
      break;
  }

?>
<!doctype html>
<html lang="<?php echo $lang; ?>"<?php if ($localeRTL) { echo ' dir="rtl"'; } ?>>

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
  <meta name="description" content="<?php echo $forecast->daily->summary; ?>" />
  <meta property="og:image" content="<?php echo "https://" . $_SERVER[HTTP_HOST] . "ig/fvicns/" . $favicon . ".png"; ?>" />
  <link rel="icon" href="<?php echo "ig/fvicns/" . $favicon . ".png"; ?>" type="image/png">
  <title><?php if ($valid) { echo __("Atmosphere for ") . $location; } else { echo __("Error") . " | Atmosphere"; } ?></title>
  <style>html,body{margin:0;padding:0;overflow-x:hidden;background-color:<?php echo $back_color ?>}body{font-family:-apple-system,"Segoe UI","Roboto","Ubuntu","Cantarell","Fira Sans","Droid Sans","Helvetica Neue",Helvetica,Arial,sans-serif;color:#fff;text-align:center;width:100vw;max-width:100vw;min-height:100vh;padding:10px 0;background-image:linear-gradient(<?php echo $back_gradient; ?>)}<?php if ($units != null) { echo "#" . $units . "{ font-weight: bolder; }"; } ?></style>
  <link rel="stylesheet" async type="text/css" href="stylev5.css"><?php if ($localeRTL) { echo "\n<style>#now .rgt, .dly .lft{text-align:right}.dly .rgt{text-align:left;}b{margin-right:0;margin-left:10px;min-width:4%;}.dly .r{margin-right:0;margin-left:15%}</style>\n"; } ?>
</head>

<body>
  <form>
    <span><?php echo __("select to change city"); ?></span><br>
    <input type="text" placeholder="<?php echo __("Enter a Location"); ?>" value="<?php echo $location; ?>" name="loc" onfocus="shwL()" onblur="hdL()" aria-label="<?php echo __("Change Location"); ?>">
  </form>
  <span id="al"></span>
  <?php
    if ($valid) {
      include 'elements/weather.php';
    } else {
      include 'elements/error.php';
    }
  ?>
  <?php $show_settings = true; include 'elements/footer.php'; ?>
  <script>function shwL(){al.classList.remove("h")}function hdL(){window.setTimeout(LTo,1e3)}function LTo(){al.classList.add("h")}function loc(){function a(a){location="https://"+window.location.hostname+window.location.pathname+"?loc="+a.coords.latitude+","+a.coords.longitude}function b(){al.innerHTML="<?php echo __("Search Error"); ?>"}return navigator.geolocation?(al.innerHTML="<?php echo __("Locating..."); ?>",void navigator.geolocation.getCurrentPosition(a,b)):void(al.innerHTML="<?php echo __("Not Supported"); ?>")}function updateClock(){var a=new Date,ba=a.getMinutes(),c=a.getHours();<?php if ($lang == "ar") { echo 'var eN=["٠","١","٢","٣","٤","٥","٦","٧","٨","٩"];for(var i=0;i<11;i++){ba=ba.toString().replace(i.toString(), eN[i]);ba=ba.toString().replace(i.toString(), eN[i]);}'; } ?>ba<10?ba="0"+ba:c!=prevLocHour&&document.location.reload(!0),timeStr=hour+":"+ba+" "+ampm,ba!=prevMin&&(clock.innerHTML=timeStr),prevMin=ba;setTimeout(updateClock,1e3)}var al=document.getElementById("al");if(al.classList.add("h"),navigator.geolocation&&(al.innerHTML='<button onclick="loc()"><?php echo __("Current Location"); ?></button>'),document.getElementById("ct")){var clock=document.getElementById("ct"),time=clock.innerHTML,timeItems=time.split(" "),ampm=timeItems[1];timeItems=timeItems[0].split(":");var prevMin=timeItems[1],hour=timeItems[0],timeStr=hour+":"+prevMin+" "+ampm;clock.innerHTML=timeStr;var t=new Date,prevLocHour=t.getHours();updateClock()}</script>
</body>

</html>
