<?php

class API_UserAchievementsModule extends Module
{

 public function RenderCSS($nm)
  {
   ?>
   .<?=$nm?>_item .ui-summary .ui-label {display:inline-block; width:90px; white-space:nowrap; font-size:12px;}
   .<?=$nm?>_item .ui-summary .ui-value {color:#395587; font-weight:bold; font-size:12px;}
   <?
  }

 public function RenderHTML($nm)
  {
   ?>
   <script type="text/html" id="<?=$nm?>_list">

     <div class="ui-box-margin">
      <div class="ui-title-blue-indented">Unlocked Achievements</div>
     </div>
     <div class="list">
      <!-- Items will be pushed here -->
     </div>
   </script>

   <script type="text/html" id="<?=$nm?>_item">
     <div class="ui-box-margin <?=$nm?>_item">
      <div class="ui-list-box">
       <div class="ui-item-profile">
        <div class="ui-column-min">
         <div class="ui-image thumbnail"></div>
        </div>
        <div class="ui-column-max">
         <div class="ui-caption name"></div>
         <div class="ui-summary">
          <div class="ui-row">
           <span class="ui-label">Achievement ID:</span>
           <span class="ui-value id"></span>
          </div>
          <div class="ui-row">
           <span class="ui-label">Game Points:</span>
           <span class="ui-value gamepoints"></span>
          </div>
         </div>
        </div>
       </div>
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
    title: 'User Achievements',
    content: Q.trim( Q('#'+__getName() + '_list').html() )
   });

   var ITEM_HTML = Q.trim( Q('#'+__getName() + '_item').html() );

   var __construct = function()
    {
     doList();
     initSessionHandler();
    };

   var initSessionHandler = function()
    {
     // Init "onSessionStatusChanged" handler
     MNDirect.addEventHandler
      (
       MNDirect.EventName.onSessionStatusChanged,
       doList
      );
    };

   var doList = function(e)
    {
     // Executes current user achievements list request
     MNDirect.serviceProvider.Achievements.reqCurrUserAchievementsList
      (
       // Callback function that will process ResponseItem
       renderList
      );
    };

   var renderForNotLoggedInUser = function()
    {
     Q('.list', SCREEN.NODE).html('<div class="ui-box-margin"><div class="ui-description-blue-indented">The user need to be logged in!</div></div>');
     return false;
    };

   var renderForResponseError = function(responseItem)
    {
     // Request errors processing
     if(responseItem.hadError())
      {
       var msg  = '';
           msg += 'Error';
           msg += ' ' + responseItem.getErrorCode() +  ': '; // Request error code
           msg += responseItem.getErrorMessage(); // Request error message

       Q('.list', SCREEN.NODE).html('<div class="ui-box-margin"><div class="ui-description-blue-indented">' + msg + '</div></div>');
       return true;
      }
     return false;
    };

   var renderList = function(responseItem)
    {
     // Virtual Shop available only for authorized users!
     if(!MNDirect.getSession().isUserLoggedIn()) return renderForNotLoggedInUser();

     if(renderForResponseError(responseItem)) return;

     var holder = Q('.list', SCREEN.NODE);
     holder.empty();

     // Request data extraction
     var data = responseItem.getData().entry;

     var node = Q(ITEM_HTML);
     Q().each(data, function(item, index){
      var n = node.clone();
      Q('.id', n).text(item.id);
      Q('.name', n).text(item.achievementName);
      Q('.gamepoints', n).text(item.gamePoints);
      Q('.thumbnail', n).each(function(thumb){
       thumb.style.backgroundImage = 'url("'+item.thumbnailUrl+'")';
      });
      holder.append(n);
     });
    };

   __construct();

   that.SCREEN = SCREEN;
   that.refresh = doList;

   <?if(false){?></script><?}?>
   <?
  }

}