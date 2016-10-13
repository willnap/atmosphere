<?php
  function debug($msg) {
    global $DEBUG;
    if ($DEBUG) {
      echo "<script>console.log('{$msg}');</script>\n";
    }
  }

  function rain($probability, $return) {
    $probability = $probability * 100;
    if ($probability > 5) {
      return "<span class='r'>" . __($probability . "%") . "</span> ";
    } else if ($return) {
      return "<span class='r h'>&nbsp;</span>";
    }
  }

  function formatTime($unix, $format) {
    global $forecast_timezone;
    global $time;
    global $lang;
    global $locale24;
    if ($locale24 or ($time == "24")) {
      $format = str_replace("%I", "%-k", $format);
      $format = str_replace("%l", "%-k", $format);
      $format = str_replace("%-l", "%-k", $format);
      $format = str_replace("%p", "", $format);
      $format = str_replace("%P", "", $format);
      $time = "24";
    }
    date_default_timezone_set($forecast_timezone);
    $formatted_time = strftime($format, $unix);
    return __($formatted_time);
  }

  function image($class, $alt, $icon, $sunrise, $sunset, $large) {
    if (strpos($icon, "t-p-") !== false) {
      switch ($icon) {
        case "t-p-rain":
          return '<img class="txt" alt="' . __("Chance of Rain") . '" src="ig/wr/r-t.svg?" title="' . __("Chance of Rain") . '">';
        case "t-p-snow":
          return '<img class="txt" alt="' . __("Chance of Snow") . '" src="ig/wr/sw-t.svg?" title="' . __("Chance of Snow") . '">';
        case 't-p-sleet':
          return '<img class="txt" alt="' . __("Chance of Sleet") . '" src="ig/wr/sw-t.svg?" title="' . __("Chance of Sleet") . '">';
        default:
          return '<img class="txt" alt="' . __("Chance of Precipitation") . '" src="ig/wr/r-t.svg?" title="' . __("Chance of Precipitation") . '">';
      }
    }
    if ($class == "") { $class = null; }
    if ($alt == "") { $alt = null; }
    global $forecast_time;
    if (($sunrise != null) and ($sunset != null)) {
      if ( !((strpos($main_icon, "day") !== false) or (strpos($main_icon, "night") !== false)) ) {
        if (!( (($forecast_time - $sunrise) > 0) and (($forecast_time - $sunset) <= 0) )) {
          if ($class != null) { $class .= " dk"; } else { $class = "dk"; }
        } // else: day
      }
    }
    switch ($icon) {
      case "clear-day":
        $icon = "cr-d";
        break;
      case "clear-night":
        $icon = "cr-n";
        break;
      case "partly-cloudy-day":
        $icon = "p-cd-d";
        break;
      case "partly-cloudy-night":
        $icon = "p-cd-n";
        break;
      case "cloudy": case "cloudy-day": case "cloudy-night":
        if ($icon == "cloudy-night") { if ($class != null) { $class .= " dk"; } else { $class = "dk"; } }
        $icon = "cd";
        break;
      case "rain": case "rain-day": case "rain-night":
        if ($icon == "rain-night") { if ($class != null) { $class .= " dk"; } else { $class = "dk"; } }
        $icon = "r";
        break;
      case "fog": case "fog-day": case "fog-night":
        if ($icon == "fog-night") { if ($class != null) { $class .= " dk"; } else { $class = "dk"; } }
        $icon = "f";
        break;
      case "sleet": case "sleet-day": case "sleet-night":
        if ($icon == "sleet-night") { if ($class != null) { $class .= " dk"; } else { $class = "dk"; } }
        $icon = "st";
        break;
      case "wind": case "wind-day": case "wind-night":
        $icon = "w";
        break;
      case "tornado": case "tornado-day": case "tornado-night":
        $icon = "t";
        break;
      case "snow": case "snow-day": case "snow-night":
        $icon = "sw";
        break;
      case "default":
        $icon = "d";
        break;
      default:
        debug($icon);
        $icon = "d";
        break;
    }
    $out = "<img ";
    if ($class != null) { $out .= 'class="' . $class . '" '; }
    if ($alt != null) { $out .= 'alt="' . $alt . '" ';  }
    // $out .= 'src="ig/wr/' . $icon . '.svg?"';
    if ($large) {
      $out .= 'src="ig/wr/' . $icon . '.svg?" srcset="ig/wr/sm/' . $icon . '.png? 35w, ig/wr/md/' . $icon . '.png? 70w, ig/wr/' . $icon . '.svg? 80w"';
    } else {
      $out .= 'src="ig/wr/sm/' . $icon . '.png?" srcset="ig/wr/sm/' . $icon . '.png? 35w, ig/wr/md/' . $icon . '.png? 70w, ig/wr/' . $icon . '.svg? 80w"';
    }
    $out .= ' width="35px">';
    return $out;
  }

  function addLimbTangencies($i) {
    global $forecast;
    global $forecast_time;
    $time_1 = $forecast->hourly->data[$i]->time;
    $time_2 = $forecast->hourly->data[$i+1]->time;
    $sunrise_1 = $forecast->daily->data[0]->sunriseTime;
    $sunrise_2 = $forecast->daily->data[1]->sunriseTime;
    $sunset_1 = $forecast->daily->data[0]->sunsetTime;
    $sunset_2 = $forecast->daily->data[1]->sunsetTime;
    if ( ((($sunrise_1 - $time_1) > 0) and (($sunrise_1 - $time_2) < 0)) or ((($sunrise_2 - $time_1) > 0) and (($sunrise_2 - $time_2) < 0)) ) {
      // SUNRISE
      $valid = true;
      $type = __("Sunrise");
      $abbr_type = "sr";
      if ((($sunrise_1 - $time_1) > 0) and (($sunrise_1 - $time_2) < 0)) { $time = $forecast->daily->data[0]->sunriseTime; } else { $time = $forecast->daily->data[1]->sunriseTime; }
    } else if ( ((($sunset_1 - $time_1) > 0) and (($sunset_1 - $time_2) < 0)) or ((($sunset_2 - $time_1) > 0) and (($sunset_2 - $time_2) < 0)) ) {
      // SUNSET
      $valid = true;
      $type = __("Sunset");
      $abbr_type = "ss";
      if ((($sunset_1 - $time_1) > 0) and (($sunset_1 - $time_2) < 0)) { $time = $forecast->daily->data[0]->sunsetTime; } else { $time = $forecast->daily->data[1]->sunsetTime; }
    } else {
      $valid = false;
    }
    if ($forecast_time >= $time) {
      $valid = false;
    }
    if ($valid) {
      echo "<li>";
      echo '  <time>' . formatTime($time, '%I:%M %p') . '</time> ';
      echo '  ' . rain($forecast->hourly->data[$i]->precipProbability, true);
      echo '  <img alt="' . $type . '" src="ig/wr/sm/' . $abbr_type . '.png" srcset="ig/wr/sm/' . $abbr_type . '.png? 35w, ig/wr/md/' . $abbr_type . '.png? 70w, ig/wr/' . $abbr_type . '.svg? 80w">';
      echo '  <span>' . $type . '</span>';
      echo "</li>";
    }
  }

  function 🍪($name, $default) {
    global $$name;
    if (isset($_POST[$name])) {
      $$name = $_POST[$name];
      setcookie($name, $$name, time()+31556926, "/", "." . $_SERVER[HTTP_HOST]);
    } else if ($_COOKIE[$name] != null) {
      $$name = $_COOKIE[$name];
      setcookie($name, $$name, time()+31556926, "/", "." . $_SERVER[HTTP_HOST]);
    } else {
      $$name = $default;
    }
  }

  function __($message) {
    global $locale;
    global $lang;
    if ($locale[$message] != null) {
      return $locale[$message];
    } else {
      if ($lang == "ar") {
        $west = array("0","1","2","3","4","5","6","7","8","9");
        $east = array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩");
        $message = str_replace($west, $east, $message);
        $message = str_replace("%", "٪", $message);
      }
      return $message;
    }
  }

?>
