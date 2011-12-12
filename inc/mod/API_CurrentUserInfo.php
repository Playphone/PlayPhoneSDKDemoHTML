<?php

class API_CurrentUserInfoModule extends Module
{

 public function RenderHTML($nm)
  {
   ?>
   <a href="javascript://" class="ui-item-button <?=$nm?>_button">
    <span class="ui-caption">Current User Information</span>
    <span class="ui-arrow"></span>
   </a>

   <script type="text/html" id="<?=$nm?>_screen">

     <div class="ui-box-margin">
      <div class="ui-title-blue-indented">Current user profile</div>
      <div class="ui-list-box">
       <div class="ui-item-profile">
        <div class="ui-column-min">
         <div class="ui-image currentUserAvatarBackgroundImage"></div>
        </div>
        <div class="ui-column-max">
         <div class="ui-caption currentUserNameValue"></div>
         <div class="ui-summary currentUserStatusTextValue"></div>
        </div>
       </div>
       <span class="ui-item">
        <span class="ui-caption">User ID</span>
        <span class="ui-value currentUserIdValue"></span>
       </span>
      </div>
      <div class="ui-description-blue-indented">
       The information may not be available during initialization API
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
    title: 'User Information',
    content: Q.trim( Q('#'+__getName() + '_screen').html() )
   });

   var __construct = function()
    {
     Q('.' + __getName() + '_button').bind('click', function(){
      SCREEN.show();
      refreshUserInfo();
     });
     refreshUserInfo();
     initSessionHandler();
    };

   var initSessionHandler = function()
    {
     // Init "onSessionStatusChanged" handler
     MNDirect.addEventHandler
      (
       MNDirect.EventName.onSessionStatusChanged,
       refreshUserInfo
      );
    };

   var refreshUserInfo = function()
    {
     // Getting Session
     var session = MNDirect.getSession();

     // Current User ID extraction
     var userId = session.getMyUserId();

     // Current User Name extraction
     var userName = session.getMyUserName();

     // Is user logged in checking
     var isLoggedIn = session.isUserLoggedIn();

     // Current User Avatar URL
     var avatarURL = 'inc/ui/ui-image-empty.jpg'; // Empty picture
     var userInfo = MNDirect.getSession().getMyUserInfo();
     if(userInfo && typeof userInfo.getAvatarUrl == 'function')
      {
       avatarURL = userInfo.getAvatarUrl();
      }

     var statusTextValue = (isLoggedIn) ? 'The user is logged in' : 'The user is not logged in';

     Q('.currentUserIdValue', SCREEN.NODE).text( String(userId) );
     Q('.currentUserNameValue', SCREEN.NODE).text( userName?String(userName):'' );
     Q('.currentUserStatusTextValue', SCREEN.NODE).text( String(statusTextValue) );
     Q('.currentUserAvatarBackgroundImage', SCREEN.NODE).each(function(node){
      node.style.backgroundImage = 'url("'+avatarURL+'")';
     });
    };

   __construct();

   <?if(false){?></script><?}?>
   <?
  }

}