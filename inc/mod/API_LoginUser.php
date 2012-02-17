<?php

class API_LoginUserModule extends Module
{

 public function RenderHTML($nm)
  {
   ?>
   <a href="javascript://" class="ui-item-button <?=$nm?>_button">
    <span class="ui-caption">Login User</span>
    <span class="ui-arrow"></span>
   </a>

   <script type="text/html" id="<?=$nm?>_screen">

     <!-- Information box -->
     <div class="ui-box-margin">
      <div class="ui-title-blue-indented">User Information</div>

      <div class="ui-list-box">
       <span class="ui-item">
        <span class="ui-caption">User ID</span>
        <span class="ui-summary">Current user ID</span>
        <span class="ui-value currentUserIdValue"></span>
       </span>
       <span class="ui-item">
        <span class="ui-caption">User Name</span>
        <span class="ui-value currentUserNameValue"></span>
       </span>
       <span class="ui-item">
        <span class="ui-caption">User is logged in</span>
        <span class="ui-value currentUserStatusValue"></span>
       </span>
      </div>

      <div class="ui-description-blue-indented">
       The information may not be available during initialization API
      </div>
     </div>

     <!-- Controls -->
     <div class="ui-box-margin">

      <div class="ui-list-box">
       <a href="javascript://" class="ui-item-button loginButton">
        <span class="ui-caption">Login</span>
        <span class="ui-summary">User login dialog will shows</span>
        <span class="ui-arrow"></span>
       </a>
       <a href="javascript://" class="ui-item-button loginAsGuestButton">
        <span class="ui-caption">Login as Guest</span>
        <span class="ui-arrow"></span>
       </a>
       <a href="javascript://" class="ui-item-button logoutButton">
        <span class="ui-caption">Logout</span>
        <span class="ui-summary">User logout dialog will shows</span>
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
    title: 'Login User',
    content: Q.trim( Q('#'+__getName() + '_screen').html() )
   });

   var __construct = function()
    {
     Q('.' + __getName() + '_button').bind('click', SCREEN.show);
     initLoginButton();
     initLoginAsGuestButton();
     initLogoutButton();
     refreshUserInfo();
     initSessionHandler();
    };

   var initSessionHandler = function()
    {
     // Init "onSessionStatusChanged" handler
     MNDirect.addEventHandler
      (
       MNDirect.EventName.onSessionStatusChanged,
       function(e)
        {
         MNDirectUIHelper.hideDashboard();
         refreshUserInfo();
        }
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

     Q('.currentUserIdValue', SCREEN.NODE).text( String(userId) );
     Q('.currentUserNameValue', SCREEN.NODE).text( String(userName) );
     Q('.currentUserStatusValue', SCREEN.NODE).text( String(isLoggedIn) );
    };

   var initLoginAsGuestButton = function()
    {
     //MNDirect.addEventHandler(MNDirect.EventName.onExecUICommandReceived, function(e){
     //  if(e.cmdName == 'OnLoginScreenWait') {
     //   MNDirect.execAppCommand('execLoginGuest', null);
     //  }
     //});

     Q('.loginAsGuestButton', SCREEN.NODE).bind('click', function(){
      if(!MNDirect.isUserLoggedIn())
       {
        // Login as Guest
        MNDirect.execAppCommand('execLoginGuest', null);
       }
      else
       {
        alert('The user is logged in!');
       }
     });

    };

   var initLoginButton = function()
    {
     Q('.loginButton', SCREEN.NODE).bind('click', function(){
      if(!MNDirect.isUserLoggedIn())
       {
        // Shows User Login dialog
        MNDirect.serviceProvider.ViewerProfile.UI.showDashboardDialogUserLogin();
       }
      else
       {
        alert('The user is logged in!');
       }
     });
    };

   var initLogoutButton = function()
    {
     Q('.logoutButton', SCREEN.NODE).bind('click', function(){
      if(MNDirect.isUserLoggedIn())
       {
        MNDirect.execAppCommand('jumpToUserProfile',null);
        MNDirectUIHelper.showDashboard();
       }
      else
       {
        alert('The user is not logged in!');
       }
     });
    };

   __construct();

   <?if(false){?></script><?}?>
   <?
  }

}