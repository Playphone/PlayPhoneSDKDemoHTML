<?php

class API_AchievementsModule extends Module
{

 public function RenderHTML($nm)
  {
   ?>
   <a href="javascript://" class="ui-item-button <?=$nm?>_button">
    <span class="ui-caption">Achievements</span>
    <span class="ui-arrow"></span>
   </a>

   <script type="text/html" id="<?=$nm?>_screen">

     <div class="ui-box-margin">
      <div class="ui-list-box">
       <a href="javascript://" class="ui-item-button gameAchievementsScreenButton">
        <span class="ui-caption">Achievements List</span>
        <span class="ui-arrow"></span>
       </a>
       <a href="javascript://" class="ui-item-button userAchievementsScreenButton">
        <span class="ui-caption">User Achievements</span>
        <span class="ui-arrow"></span>
       </a>
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
    title: 'Achievements',
    content: Q.trim( Q('#'+__getName() + '_screen').html() )
   });

   var __construct = function()
    {
     Q('.' + __getName() + '_button').bind('click', SCREEN.show);

     Q('.gameAchievementsScreenButton', SCREEN.NODE).bind('click', function(){
      Modules.API_GameAchievementsModule.refresh();
      SCREEN.switchTo(Modules.API_GameAchievementsModule.SCREEN);
     });
     Q('.userAchievementsScreenButton', SCREEN.NODE).bind('click', function(){
      Modules.API_UserAchievementsModule.refresh();
      SCREEN.switchTo(Modules.API_UserAchievementsModule.SCREEN);
     });
    };

   __construct();

   <?if(false){?></script><?}?>
   <?
  }

}