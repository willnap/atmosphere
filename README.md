# Atmosphere
A progressive weather website built with emphasis on speed, accessibility, and compatibility.

## Requirements
Atmosphere requires a server running PHP 5.6, a [Dark Sky API key](https://darksky.net/dev/), and a [Google Maps Geolocation API key](https://developers.google.com/maps/documentation/geolocation/get-api-key).

## Contents
*In order to keep each page under 10kB, all processing is done server-side using PHP, and JavaScript is only used for noncritical things.*
* **index.php** main page
* **elements/** grabbed by index.php when needed
  * weather.php: weather forecast template
  * error.php: error/404 template
  * alert.php: urgent weather alert template
  * functions.php: debug(), formatTime()...
* **style.css** below-the-fold stylings (most above inlined)
* **ig/** (images)
  * attr/: attribution icons (used in footer)
  * fvicns/: dynamic favicons (mirrors location's current weather)
  * wthr/: forecast icons (icon set created by me, inspired by iOS)
    * sm/ & md/: downscaled versions for lower resolution displays

## Usage
Insert your Dark Sky and Geolocation API keys at the top of index.php
**OR**
Add "DARKSKY_KEY" and "GEOCODE_KEY" to your site's App Settings. "ERROR_CONTACT" may also be added, but is optional.

(Size problem has been resolved: all pages are now under 10kB.)
