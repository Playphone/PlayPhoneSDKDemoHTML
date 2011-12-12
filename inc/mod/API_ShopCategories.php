<?php

class API_ShopCategoriesModule extends Module
{

 public function RenderHTML($nm)
  {
   ?>
   <script type="text/html" id="<?=$nm?>_list">

     <div class="ui-box-margin">
      <div class="ui-title-blue-indented">Virtual Shop Categories List</div>
      <div class="categoriesList">
       <!-- Items will be pushed here -->
      </div>
     </div>

   </script>

   <script type="text/html" id="<?=$nm?>_item">
     <div class="ui-item">
      <span class="ui-caption name"></span>
      <span class="ui-value id"></span>
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
    title: 'Shop Categories',
    content: Q.trim( Q('#'+__getName() + '_list').html() )
   });

   var ITEM_HTML = Q.trim( Q('#'+__getName() + '_item').html() );

   var __construct = function()
    {
     doVirtualShopCategoriesList();
     initSessionHandler();
    };

   var initSessionHandler = function()
    {
     // Init "onSessionStatusChanged" handler
     MNDirect.addEventHandler
      (
       MNDirect.EventName.onSessionStatusChanged,
       doVirtualShopCategoriesList
      );
    };

   var doVirtualShopCategoriesList = function(e)
    {
     // Executes current game shop (packs) categories list request
     MNDirect.serviceProvider.VEconomy.reqThisGameGetShopCategoryList
      (
       // Callback function that will process ResponseItem
       renderVirtualShopCategoriesList
      );
    };

   var renderForNotLoggedInUser = function()
    {
     Q('.categoriesList', SCREEN.NODE)
     .removeClass('ui-list-box')
     .html('<div class="ui-description-blue-indented">The user need to be logged in!</div>');
     return false;
    };

   var renderForResponseError = function(responseItem)
    {
     // Request errors processing
     if(responseItem.hadError())
      {
       var msg  = '';
           msg += 'Error ';
           msg += '(' + responseItem.getErrorCode() +  '): '; // Request error code
           msg += responseItem.getErrorMessage(); // Request error message

       Q('.categoriesList', SCREEN.NODE)
       .removeClass('ui-list-box')
       .html('<div class="ui-description-blue-indented">' + msg + '</div>');

       return true;
      }
     return false;
    };

   var renderVirtualShopCategoriesList = function(responseItem)
    {
     // Virtual Shop available only for authorized users!
     if(!MNDirect.getSession().isUserLoggedIn()) return renderForNotLoggedInUser();

     if(renderForResponseError(responseItem)) return;

     var holder = Q('.categoriesList', SCREEN.NODE);
     holder.addClass('ui-list-box').empty();

     // Request data extraction
     var data = responseItem.getData().entry;

     var node = Q(ITEM_HTML);
     Q().each(data, function(item, index){
      var n = node.clone();
      Q('.name', n).text(item.categoryName);
      Q('.id', n).text(item.id);
      holder.append(n);
     });
    };

   __construct();

   that.SCREEN = SCREEN;

   <?if(false){?></script><?}?>
   <?
  }

}