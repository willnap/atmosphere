# Atmosphere
A progressive weather website built with emphasis on speed, accessibility, and compatibility. [**atmosphere.li**](http://atmosphere.li)

## Requirements
Atmosphere requires a server running PHP 5.6, a [Dark Sky API key](https://darksky.net/dev/), and a [Google Maps Geolocation API key](https://developers.google.com/maps/documentation/geolocation/get-api-key).

## Contents
*In order to keep each page under 10kB, all processing is done server-side using PHP, and JavaScript is only used for noncritical things.*
* **index.php** main page
* **elements/** grabbed by index.php when needed
  * weather.php: weather forecast template
  * error.php: error/404 template
  * alert.php: urgent weather alert template
  * debug.php: debugging function (console.log)
* **style.css** all noncritical stylings (critical items inlined)
* **ig/**
  * attr/: attribution icons (used in footer)
  * fvicns/
  * wthr/

## Usage
Insert your Dark Sky and Geolocation API keys at the top of index.php.