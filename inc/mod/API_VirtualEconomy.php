<?php

class API_VirtualEconomyModule extends Module
{

 public function RenderHTML($nm)
  {
   ?>
   <a href="javascript://" class="ui-item-button <?=$nm?>_button">
    <span class="ui-caption">Virtual Economy</span>
    <span class="ui-arrow"></span>
   </a>

   <script type="text/html" id="<?=$nm?>_screen">

     <div class="ui-box-margin">
      <div class="ui-list-box">
       <a href="javascript://" class="ui-item-button vItemsScreenButton">
        <span class="ui-caption">
         Virtual Items
         <span class="ui-summary">These are the items used in game</span>
        </span>
        <span class="ui-arrow"></span>
       </a>
       <a href="javascript://" class="ui-item-button storeScreenButton">
        <span class="ui-caption">
         PlayPhone Store
         <span class="ui-summary">User can purchase these items on the PlayPhone store</span>
        </span>
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
    title: 'Virtual Economy',
    content: Q.trim( Q('#'+__getName() + '_screen').html() )
   });

   var __construct = function()
    {
     Q('.' + __getName() + '_button').bind('click', SCREEN.show);

     Q('.vItemsScreenButton', SCREEN.NODE).bind('click', function(){
      SCREEN.switchTo(Modules.API_VirtualItemsScreenModule.SCREEN);
     });
     Q('.storeScreenButton', SCREEN.NODE).bind('click', function(){
      SCREEN.switchTo(Modules.API_StoreModule.SCREEN);
     });
    };

   __construct();

   <?if(false){?></script><?}?>
   <?
  }

}