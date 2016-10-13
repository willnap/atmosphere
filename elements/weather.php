<section id="now">
    <div class="lft">
      <h1><?php echo __(round($forecast->currently->temperature)); ?></h1>
      <span><?php echo __("Feels like ") . __(round($forecast->currently->apparentTemperature) . "°"); ?></span>
    </div>
    <?php echo image("m", $main_icon, $main_icon, null, null, true) . "\n"; ?>
    <div class="rgt">
      <h2><?php echo str_replace("and ", "&amp;&nbsp;", $forecast->currently->summary); ?></h2>
      <span><?php echo image(null, null, "t-p-" . $forecast->currently->precipType, null, null, false) . " " . __($forecast->currently->precipProbability * 100 . "%"); ?></span>
    </div>
  </section>
  <br>
<?php if (($forecast->alerts[0] != null) and ($forecast->alerts[0]->expires >= $forecast_time)) { include 'elements/alert.php'; } ?>
  <p class="dly"><?php echo '<span class="lft"><b>' . __("Today") . '</b><span class="ld"> ' . __($forecast->daily->data[0]->summary) . ' </span></span><span class="rgt">' . rain($forecast->daily->data[0]->precipProbability, false) . '<span class="t"> ' . __(round($forecast->daily->data[0]->temperatureMax)) . ' <span class="l"> ' . __(round($forecast->daily->data[0]->temperatureMin)) . "</span></span></span>"; ?></p>
<?php
  echo '  <ul class="hrly">';
  for ($i = 0; $i < 18; $i++) {
    echo "\n    <li>";
    if ($i == 0) {
      echo '<time id="ct">' . formatTime($forecast_time, '%-l:%M %p') . '</time> ';
    } else {
      echo '<time>' . formatTime($forecast->hourly->data[$i]->time, '%-l%p') . '</time> ';
    }
    echo rain($forecast->hourly->data[$i]->precipProbability, true);
    if ($i == 0) {
      echo image(null, $forecast->hourly->data[$i]->summary, $main_icon, null, null, false);
    } else {
      echo image(null, $forecast->hourly->data[$i]->summary, $forecast->hourly->data[$i]->icon, $forecast_sunrise, $forecast_sunset, false);
    }
    if ($i == 0) {
      echo '<span>' . __(round($forecast->currently->temperature)) . '°</span>';
    } else {
      echo '<span>' . __(round($forecast->hourly->data[$i]->temperature)) . '°</span>';
    }
    echo "</li>";
    addLimbTangencies($i);
  }
  echo '</ul>';
  for ($i = 1; $i < 6; $i++) {
    echo '  <p class="dly"><span class="lft"><b>' . formatTime($forecast->daily->data[$i]->time, '%a') . '</b><span class="ld"> ' . __($forecast->daily->data[$i]->summary) . ' </span></span><span class="rgt">' . rain($forecast->daily->data[$i]->precipProbability, false) . '<span class="t"> ' . __(round($forecast->daily->data[$i]->temperatureMax)) . ' <span class="l"> ' . __(round($forecast->daily->data[$i]->temperatureMin)) . "</span></span></span></p>\n";
  }
?>
  <br>
