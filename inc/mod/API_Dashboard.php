<?php

class API_DashboardModule extends Module
{

 public function RenderHTML($nm)
  {
   ?>
   <a href="javascript://" class="ui-item-button <?=$nm?>_button">
    <span class="ui-caption">Dashboard</span>
    <span class="ui-arrow"></span>
   </a>

   <script type="text/html" id="<?=$nm?>_screen">

     <div class="ui-box-margin">
      <div class="ui-title-blue-indented">Basic controls</div>
      <div class="ui-list-box">
       <a href="javascript://" class="ui-item-button showLauncherIcon">
        <span class="ui-caption">Show Launcher Icon</span>
        <span class="ui-arrow"></span>
       </a>
       <a href="javascript://" class="ui-item-button hideLauncherIcon">
        <span class="ui-caption">Hide Launcher Icon</span>
        <span class="ui-arrow"></span>
       </a>
       <a href="javascript://" class="ui-item-button showDashboard">
        <span class="ui-caption">Show Dashboard</span>
        <span class="ui-arrow"></span>
       </a>
      </div>
      <div class="ui-description-blue-indented">
       The launcher icon located on the right-top corner.<br />
       Tap on the launcher icon to show the dashboard.
      </div>
     </div>

   </script>
   <?
  }

 public function RenderJS($nm)
  {
   ?>
   <?if(false){?><script><?}?>

   var SCREEN = new ui.screen({
    title: 'Dashboard',
    content: Q.trim( Q('#'+__getName() + '_screen').html() )
   });

   var __construct = function()
    {
     Q('.' + __getName() + '_button').bind('click', SCREEN.show);
     initShowLauncherIconButton();
     initHideLauncherIconButton();
     initShowDashboardButton();
    };

   var initShowLauncherIconButton = function()
    {
     Q('.showLauncherIcon', SCREEN.NODE).bind('click', function(){
      // Shows Launcher Icon
      MNDirectButton.show();
     });
    };

   var initHideLauncherIconButton = function()
    {
     Q('.hideLauncherIcon', SCREEN.NODE).bind('click', function(){
      // Hide Launcher Icon
      MNDirectButton.hide();
     });
    };

   var initShowDashboardButton = function()
    {
     Q('.showDashboard', SCREEN.NODE).bind('click', function(){
      // Shows the dashboard
      MNDirectUIHelper.showDashboard();
     });
    };

   __construct();

   <?if(false){?></script><?}?>
   <?
  }

}