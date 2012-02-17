<?php

class ApplicationInformationModule extends Module
{

 public function RenderHTML($nm)
  {
   ?>
   <a href="javascript://" class="ui-item-button <?=$nm?>_button">
    <span class="ui-caption">Application Information</span>
    <span class="ui-arrow"></span>
   </a>

   <script type="text/html" id="<?=$nm?>_screen">

     <div class="ui-box-margin">
      <div class="ui-title-blue-indented">Application information</div>
      <div class="ui-list-box">
       <div class="ui-item-profile">
        <div class="ui-column-min">
         <div class="ui-image"></div>
        </div>
        <div class="ui-column-max">
         <div class="ui-caption"><?= TITLE ?></div>
         <div class="ui-summary"><?= DESCRIPTION ?></div>
        </div>
       </div>
       <span class="ui-item">
        <span class="ui-caption">Version</span>
        <span class="ui-value"><?= VERSION ?></span>
       </span>
       <span class="ui-item">
        <span class="ui-caption">Game ID</span>
        <span class="ui-value"><?= SDK_GAME_ID ?></span>
       </span>
       <span class="ui-item">
        <span class="ui-caption">Server</span>
        <?php
         $domain = (preg_match('@^(?:http://)?([^/]+)@i', SDK_HOST, $match) && $match[1]) ? $match[1] : '';
        ?>
        <span class="ui-value"><?= $domain ?></span>
       </span>
      </div>
     </div>

     <div class="ui-box-margin">
      <div class="ui-title-blue-indented">Device information</div>
      <div class="ui-list-box">
       <div class="ui-item">
        <span class="ui-caption">Standalone mode</span>
        <span class="ui-value browserStandaloneValue"></span>
       </div>
       <div class="ui-item">
        <span class="ui-caption">Connection mode</span>
        <span class="ui-value browserConnectionModeValue"></span>
       </div>
       <div class="ui-item">
        <span class="ui-caption">Connection type</span>
        <span class="ui-value browserConnectionTypeValue"></span>
       </div>
      </div>
      <div class="ui-description-blue-indented browserUserAgentValue"></div>
     </div>

   </script>
   <?
  }

 public function RenderJS($nm)
  {
   ?>
   <?if(false){?><script><?}?>

   var SCREEN = new ui.screen({
    title: 'System Information',
    content: Q.trim( Q('#'+__getName() + '_screen').html() )
   });

   var __construct = function()
    {
     Q('.' + __getName() + '_button').bind('click', function(){
      SCREEN.show();
      fillDeviceInformation();
     });
    };

   var fillDeviceInformation = function()
    {
     Q('.browserUserAgentValue', SCREEN.NODE).text(navigator.userAgent);
     Q('.browserStandaloneValue', SCREEN.NODE).text((navigator.standalone) ? 'true' : 'false');

     var connectionMode = 'UNKNOWN';
     if( typeof navigator.onLine != 'undefined' ) {
      connectionMode = (navigator.onLine) ? 'ONLINE' : 'OFFLINE';
     }
     Q('.browserConnectionModeValue', SCREEN.NODE).text(connectionMode);

     var connectionType = 'UNKNOWN';
     if( typeof navigator.connection != 'undefined' && typeof navigator.connection.type != 'undefined' ) {
      if(navigator.connection.type == navigator.connection.ETHERNET) connectionType = 'ETHERNET';
      else if(navigator.connection.type == navigator.connection.WIFI) connectionType = 'WIFI';
      else if(navigator.connection.type == navigator.connection.CELL_2G) connectionType = '2G';
      else if(navigator.connection.type == navigator.connection.CELL_3G) connectionType = '3G';
     }
     Q('.browserConnectionTypeValue', SCREEN.NODE).text(connectionType);
    };

   __construct();

   <?if(false){?></script><?}?>
   <?
  }

}