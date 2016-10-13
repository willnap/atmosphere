<div id="ftr">
    <a href="https://darksky.net/dev/"><img alt="Dark Sky" src="/ig/attr/ds.svg" title="<?php echo __("Powered by Dark Sky"); ?>" height="38px"></a>
    <a href="https://willnapier.co"><img alt="WN" src="/ig/attr/wn.svg" title="<?php echo __("Created by Will Napier"); ?>" height="30px"></a>
    <a href="https://github.com/willnap"><img alt="Github" src="/ig/attr/gh.svg" title="<?php echo __("Source on Github"); ?>" height="38px"></a>
<?php if ($show_settings) { echo '    <form id="uts" method="post"><button type="submit" name="units" value="us" id="us" title="Imperial Units (℉, mph)">US</button><button type="submit" name="units" value="ca" id="ca" title="(℃, km/h)">CA</button><button type="submit" name="units" value="uk" id="uk" title="(℃, mph)">UK</button><button type="submit" name="units" value="si" id="si" title="SI Units (℃, m/s)">SI</button>  <a href="/settings"><img id="sg" alt="' . __("Settings") . '" src="ig/sg.svg" title="' . __("Settings") . '" width="15px"></a></form>' . "\n"; } ?>
  </div>
