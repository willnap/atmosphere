<?php
  function debug($msg) {
    global $DEBUG;
    if ($DEBUG) {
      echo "<script>console.log('{$msg}');</script>\n";
    }
  }

  function rain($probability, $return) {
    $probability = $probability * 100;
    if ($probability > 0) {
      return "<span class='r'>{$probability}%</span> ";
    } else if ($return) {
      return "<span class='r h'>&nbsp;</span>";
    }
  }

  function formatTime($unix, $format) {
    global $forecast_timezone;
    $time = new DateTime("@" . $unix);
    $time->setTimeZone(new DateTimeZone($forecast_timezone));
    return strval($time->format($format));
  }

  function image($class, $alt, $icon, $sunrise, $sunset) {
    if (strpos($icon, "t-p-") !== false) {
      switch ($icon) {
        case "t-p-rain":
          return '<img class="txt" alt="Chance of Rain" src="ig/wr/r-t.svg" title="Chance of Rain">';
        case "t-p-snow":
          return '<img class="txt" alt="Chance of Snow" src="ig/wr/sw-t.svg" title="Chance of Snow">';
        case 't-p-sleet':
          return '<img class="txt" alt="Chance of Sleet" src="ig/wr/sw-t.svg" title="Chance of Sleet">';
        default:
          return '<img class="txt" alt="Chance of Precipitation" src="ig/wr/r-t.svg" title="Chance of Precipitation">';
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
    $out .= 'src="ig/wr/' . $icon . '.svg">';
    return $out;
  }

  function addLimbTangencies($i) {
    global $forecast;
    $time_1 = $forecast->hourly->data[$i]->time;
    $time_2 = $forecast->hourly->data[$i+1]->time;
    $sunrise_1 = $forecast->daily->data[0]->sunriseTime;
    $sunrise_2 = $forecast->daily->data[1]->sunriseTime;
    $sunset_1 = $forecast->daily->data[0]->sunsetTime;
    $sunset_2 = $forecast->daily->data[1]->sunsetTime;
    if ( ((($sunrise_1 - $time_1) > 0) and (($sunrise_1 - $time_2) < 0)) or ((($sunrise_2 - $time_1) > 0) and (($sunrise_2 - $time_2) < 0)) ) {
      // SUNRISE
      $valid = true;
      $type = "Sunrise";
      $abbr_type = "sr";
      if ((($sunrise_1 - $time_1) > 0) and (($sunrise_1 - $time_2) < 0)) { $time = $forecast->daily->data[0]->sunriseTime; } else { $time = $forecast->daily->data[1]->sunriseTime; }
    } else if ( ((($sunset_1 - $time_1) > 0) and (($sunset_1 - $time_2) < 0)) or ((($sunset_2 - $time_1) > 0) and (($sunset_2 - $time_2) < 0)) ) {
      // SUNSET
      $valid = true;
      $type = "Sunset";
      $abbr_type = "ss";
      if ((($sunset_1 - $time_1) > 0) and (($sunset_1 - $time_2) < 0)) { $time = $forecast->daily->data[0]->sunsetTime; } else { $time = $forecast->daily->data[1]->sunsetTime; }
    } else {
      $valid = false;
    }
    if ($valid) {
      echo "<li>";
      echo '  <time>' . formatTime($time, 'g:i A') . '</time> ';
      echo '  ' . rain($forecast->hourly->data[$i]->precipProbability, true);
      echo '  <img alt="' . $type . '" src="ig/wr/' . $abbr_type . '.svg">';
      echo '  <span>' . $type . '</span>';
      echo "</li>";
    }
  }

?>
