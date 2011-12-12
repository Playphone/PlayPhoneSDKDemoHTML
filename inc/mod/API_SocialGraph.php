<?php

class API_SocialGraphModule extends Module
{

 public function RenderHTML($nm)
  {
   ?>
   <a href="javascript://" class="ui-item-button <?=$nm?>_button">
    <span class="ui-caption">Social Graph</span>
    <span class="ui-arrow"></span>
   </a>

   <script type="text/html" id="<?=$nm?>_list">

     <div class="ui-box-margin">
      <div class="ui-title-blue-indented">User Buddies</div>
      <div class="ui-list-box buddiesList">
       <!-- Items will be pushed here -->
      </div>
     </div>

   </script>

   <script type="text/html" id="<?=$nm?>_item">
     <a href="javascript://" class="ui-item-button">
      <span class="ui-caption nickname"></span>
      <span class="ui-arrow"></span>
     </a>
   </script>
   <?
  }

 public function RenderJS($nm)
  {
   ?>
   <?if(false){?><script><?}?>

   var SCREEN = new ui.screen({
    title: 'Social Graph',
    content: Q.trim( Q('#'+__getName() + '_list').html() )
   });

   var ITEM_HTML = Q.trim( Q('#'+__getName() + '_item').html() );

   var __construct = function()
    {
     Q('.' + __getName() + '_button').bind('click', doBuddiesList);
    };

   var doBuddiesList = function(e)
    {
      var element = this;
      var prevHTML = Q(element).html();
      Q(element).html('<span class="ui-caption">Please wait...</span>');
      var callback = function (responseItem) {
       renderBuddiesList(responseItem);
       SCREEN.show();
       Q(element).html(prevHTML);
      };

      MNDirect
      .serviceProvider
      .SocialGraph
      .reqViewerFriends(callback);
    };

   var renderBuddiesList = function(responseItem)
    {
     var holder = Q('.buddiesList', SCREEN.NODE);
     holder.empty();

     // Request errors processing
     if(responseItem.hadError())
      {
       alert
        (
         'Error:\n'
         // Request error message
         + responseItem.getErrorMessage()
         // Request error code
         + '\n\nCode: ' + responseItem.getErrorCode()
        );
       ui.goBack();
       return;
      }

     // Request data extraction
     var data = responseItem.getData().entry;

     var node = Q(ITEM_HTML);
     Q().each(data, function(item, index){
      var n = node.clone();
      Q('.nickname', n).text(item.nickname);
      holder.append(n);
     });

    };

   __construct();

   <?if(false){?></script><?}?>
   <?
  }

}