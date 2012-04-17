<?php

class API_InitModule extends Module
{

 public function RenderJS()
  {
   ?>
   <?if(false){?><script><?}?>

    // Init application reload handler
    MNDirect.AppReloadClient.setAppReloadHandler (
     // Sets predefined reload handler that allows to reload application
     MNDirect.AppReloadClient.APP_RELOAD_ALLOWED
    );

    // Init application after reload handler
    MNDirect.AppReloadClient.setAppAfterReloadHandler (
     // Sets predefined reload handler that allows to show dashboard after application reloading
     MNDirect.AppReloadClient.APP_AFTER_RELOAD_AUTO
    );

    // Init welcome notification for logged in users
    MNDirectPopup.init (MNDirectPopup.MNDIRECTPOPUP_WELCOME);

    // Required for init SDK parameters definition
    var GAME_ID = <?= SDK_GAME_ID ?>;
    var GAME_SECRET = <?= SDK_GAME_SECRET ?>;

    // Init SDK
    MNDirect.init(GAME_ID, GAME_SECRET);

    // Init SDK button location and show its
    MNDirectButton.initWithLocation ( MNDirectButton.MNDIRECTBUTTON_TOPRIGHT );
    MNDirectButton.show();

   <?if(false){?></script><?}?>
   <?
  }

}