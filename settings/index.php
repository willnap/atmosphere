<?php
  include '../elements/functions.php';

  🍪("units", "us");
  🍪("time", "12");
  🍪("lang", "en");
  include 'locale/' . $lang . '.php';
  if ($locale['locale'] != null) {
    setlocale(LC_TIME, $locale['locale'] . ".UTF-8");
  } else {
    setlocale(LC_TIME, $lang . "_" . strtoupper($lang) . ".UTF-8");
  }
  include '../locale/' . $lang . '.php';

  function 🔘($category, $option, $checked) {
    if ($category == $option) {
      if ($checked) {
        return "checked";
      } else {
        return "selected";
      }
    }
  }

  $main_icon = "default";
  $favicon = "favicon";
  $back_color = "#5DA8CC";
  $back_gradient = "#5DA8CC, #4A9BC3";
?>
<!doctype html>
<html lang="<?php echo $lang; ?>"<?php if ($localeRTL) { echo ' dir="rtl"'; } ?>>

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
  <meta name="description" content="Atmosphere Settings">
  <meta property="og:image" content="<?php echo "https://" . $_SERVER[HTTP_HOST] . "ig/fvicns/" . $favicon . ".png"; ?>">
  <link rel="icon" href="<?php echo "/ig/fvicns/" . $favicon . ".png"; ?>" type="image/png">
  <title><?php echo __("Settings") . " | Atmosphere"; ?></title>
  <style>
    html,body{margin:0;padding:0;overflow-x:hidden;background-color:<?php echo $back_color ?>}body{font-family:-apple-system,"Segoe UI","Roboto","Ubuntu","Cantarell","Fira Sans","Droid Sans","Helvetica Neue",Helvetica,Arial,sans-serif;color:#fff;text-align:center;width:100vw;max-width:100vw;min-height:100vh;padding:10px 0;background-image:linear-gradient(<?php echo $back_gradient; ?>)}h1,h2,h3{font-weight:400;margin:0;line-height:.9}h1{font-size:8.125em}h2{font-size:3em}.h{visibility:hidden}#ftr{display:table;margin:0 auto}#ftr a{display:inline-block;vertical-align:middle}#ftr img{padding:25px 10px 10px}@media (max-width: 670px){h1{font-size:7em}}h3{font-size:x-large;padding:5px 0 20px}form{margin:auto;padding:10px;border:2px solid rgba(255, 255, 255, 0.50);border-radius:7px;max-width:200px;width:90vw}form div{text-align:left;width:90%;margin:auto}<?php if ($localeRTL) { echo "\nform div{direction:ltr;}\n"; } ?>
  </style>
</head>

<body>
  <h2><?php echo __("Settings"); ?></h2>
  <br>
  <form method="post" action="/index.php">
    <h3><?php echo __("Units"); ?></h3>
    <div>
      <input type="radio" name="units" value="us" <?php echo 🔘($units, "us", true); ?> ><label for="us"><?php echo "US (℉, mph)"; ?></label><br>
      <input type="radio" name="units" value="ca" <?php echo 🔘($units, "ca", true); ?> ><label for="ca"><?php echo "CA (℃, km/h)"; ?></label><br>
      <input type="radio" name="units" value="uk" <?php echo 🔘($units, "uk", true); ?> ><label for="uk"><?php echo "UK (℃, mph)"; ?></label><br>
      <input type="radio" name="units" value="si" <?php echo 🔘($units, "si", true); ?> ><label for="si"><?php echo "SI (℃, m/s)"; ?></label>
    </div>
    <br>
    <h3><?php echo __("Hour Format"); ?></h3>
    <div>
      <input type="radio" name="time" value="12" <?php echo 🔘($time, "12", true); ?> ><label for="12"><?php echo __("12 Hour (AM, PM)"); ?></label><br>
      <input type="radio" name="time" value="24" <?php echo 🔘($time, "24", true); ?> ><label for="24"><?php echo __("24 Hour"); ?></label>
    </div>
    <br>
    <h3><?php echo __("Language (beta)"); ?></h3>
    <select name="lang">
      <option value="zh" <?php echo 🔘($lang, "zh", false); ?>>中文</option>
      <option value="es" <?php echo 🔘($lang, "es", false); ?>>Español</option>
      <option value="en" <?php echo 🔘($lang, "en", false); ?>>English</option>
      <option value="ar" <?php echo 🔘($lang, "ar", false); ?>>عَرَبِيّ</option>
      <option value="pt" <?php echo 🔘($lang, "pt", false); ?>>Português</option>
      <!--option value="ru" <?php echo 🔘($lang, "ru", false); ?>>русский язык</option>
      <option value="de" <?php echo 🔘($lang, "de", false); ?>>Deutsch</option-->
      <option value="fr" <?php echo 🔘($lang, "fr", false); ?>>Français</option>
      <!--option value="tr" <?php echo 🔘($lang, "tr", false); ?>>Türkçe</option>
      <option value="it" <?php echo 🔘($lang, "it", false); ?>>Italiano</option>
      <option value="pl" <?php echo 🔘($lang, "pl", false); ?>>Język Polski</option>
      <option value="uk" <?php echo 🔘($lang, "uk", false); ?>>українська мова</option>
      <option value="az" <?php echo 🔘($lang, "az", false); ?>>Azərbaycan dili</option>
      <option value="nl" <?php echo 🔘($lang, "nl", false); ?>>Nederlands</option>
      <option value="hu" <?php echo 🔘($lang, "hu", false); ?>>Magyar</option>
      <option value="el" <?php echo 🔘($lang, "el", false); ?>>Ελληνικά</option>
      <option value="cs" <?php echo 🔘($lang, "cs", false); ?>>čeština</option>
      <option value="sv" <?php echo 🔘($lang, "sv", false); ?>>Svenska</option>
      <option value="x-pig-latin" <?php echo 🔘($lang, "x-pig-latin", false); ?>>Igpay Atinlay</option-->
    </select>
    <br>
    <br>
    <button type="submit"><?php echo __("Save"); ?></button>
  </form>
  <br>
  <?php $show_settings = false; include '../elements/footer.php'; ?>
</body>

</html>
