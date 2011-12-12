<?php

class API_GameAchievementsModule extends Module
{

 public function RenderCSS($nm)
  {
   ?>
   .<?=$nm?>_item .ui-buttons {
    position:absolute;
    right:20px;
    margin-top:-23px;
    text-align:right;
    white-space:nowrap;
   }
   .<?=$nm?>_item .ui-summary {min-height:68px;}
   .<?=$nm?>_item .ui-summary .ui-label {display:inline-block; width:90px; white-space:nowrap; font-size:12px;}
   .<?=$nm?>_item .ui-summary .ui-value {color:#395587; font-weight:bold; font-size:12px;}
   <?
  }

 public function RenderHTML($nm)
  {
   ?>
   <script type="text/html" id="<?=$nm?>_list">

     <div class="ui-box-margin">
      <div class="ui-title-blue-indented">Achievements</div>
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
         <div class="ui-buttons">
          <a href="javascript://" class="ui-button-green unlock"></a>
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
    title: 'Game Achievements',
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
       filterUnlockButtons
      );
    };

   var initUnlockButtons = function()
    {
     Q('.unlock', SCREEN.NODE).bind('click', function(e){
      if(!MNDirect.getSession().isUserLoggedIn())
       {
        alert('The user need to be logged in!');
        return false;
       }
      var el = this;
      var id = Number(Q(el).attr('item-id'));
      if(!id) return false;
      var prevHTML = Q(el).html();
      Q(el).text('Wait...');
      var callback = function(responseItem)
       {
        Q(el).html(prevHTML);
        var msg = '';
        if(responseItem.hadError())
         {
          msg = 'Error ' + responseItem.getErrorCode() + ':\n\n'
              + responseItem.getErrorMessage();
         }
        else
         {
          msg = 'The achievement successfully unlocked!';
          Q(el).addClass('hidden');
         }
        alert(msg);
       };
      // Executes unlock achievement request
      MNDirect.serviceProvider.Achievements.reqCurrUserUnlockAchievement(callback,id);
     });
    };

   var filterUnlockButtons = function()
    {
     if(!MNDirect.getSession().isUserLoggedIn())
      {
       Q('.unlock.hidden', SCREEN.NODE).removeClass('hidden');
      }
     Q('.unlock', SCREEN.NODE).text('Wait...');
     var callback = function(responseItem)
      {
       Q('.unlock', SCREEN.NODE).text('Unlock');
       if(responseItem.hadError()) return;
       Q.each(responseItem.getData().entry, function(v){
        Q('.unlock[item-id="'+v.id+'"]', SCREEN.NODE).addClass('hidden');
       });
      };
     // Executes current user achievements list request
     MNDirect.serviceProvider.Achievements.reqCurrUserAchievementsList(callback);
    };

   var doList = function(e)
    {
     // Executes current user achievements list request
     MNDirect.serviceProvider.Achievements.reqThisGameAchievementsList
      (
       // Callback function that will process ResponseItem
       renderList
      );
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
     //if(!MNDirect.getSession().isUserLoggedIn()) return renderForNotLoggedInUser();

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
      Q('.unlock', n).attr('item-id', item.id);
      holder.append(n);
     });

     initUnlockButtons();
     filterUnlockButtons();
    };

   __construct();

   that.SCREEN = SCREEN;
   that.refresh = filterUnlockButtons;

   <?if(false){?></script><?}?>
   <?
  }

}