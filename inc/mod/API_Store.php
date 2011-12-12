<?php

class API_StoreModule extends Module
{

 public function RenderHTML($nm)
  {
   ?>
   <script type="text/html" id="<?=$nm?>_screen">

     <div class="ui-box-margin">
      <div class="ui-list-box">
       <a href="javascript://" class="ui-item-button vShopCategoriesScreenButton">
        <span class="ui-caption">Shop Categories</span>
        <span class="ui-arrow"></span>
       </a>
       <a href="javascript://" class="ui-item-button vShopPacksScreenButton">
        <span class="ui-caption">Shop Packs</span>
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

   var that = this;

   var SCREEN = new ui.screen({
    title: 'PlayPhone Store',
    content: Q.trim( Q('#'+__getName() + '_screen').html() )
   });

   var __construct = function()
    {
     Q('.vShopCategoriesScreenButton', SCREEN.NODE).bind('click', function(){
      SCREEN.switchTo(Modules.API_ShopCategoriesModule.SCREEN);
     });
     Q('.vShopPacksScreenButton', SCREEN.NODE).bind('click', function(){
      SCREEN.switchTo(Modules.API_ShopPacksModule.SCREEN);
     });
    };

   __construct();

   that.SCREEN = SCREEN;

   <?if(false){?></script><?}?>
   <?
  }

}