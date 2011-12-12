<?php

class API_DashboardControlModule extends Module
{

 public function RenderHTML($nm)
  {
   ?>
   <a href="javascript://" class="ui-item-button <?=$nm?>_button">
    <span class="ui-caption">Dashboard Control</span>
    <span class="ui-arrow"></span>
   </a>

   <script type="text/html" id="<?=$nm?>_screen">

     <div class="ui-box-margin">
      <div class="ui-list-box jumpButtonsList">

       <a href="javascript://" class="ui-item-button showDashboard" onclick="MNDirect.execAppCommand('jumpToLeaderboard')">
        <span class="ui-caption">
         Leaderboards
         <span class="ui-summary">jumpToLeaderboard</span>
        </span>
        <span class="ui-arrow"></span>
       </a>

       <a href="javascript://" class="ui-item-button showDashboard" onclick="MNDirect.execAppCommand('jumpToBuddyList')">
        <span class="ui-caption">
         Friends List
         <span class="ui-summary">jumpToBuddyList</span>
        </span>
        <span class="ui-arrow"></span>
       </a>

       <a href="javascript://" class="ui-item-button showDashboard" onclick="MNDirect.execAppCommand('jumpToUserProfile')">
        <span class="ui-caption">
         User Profile
         <span class="ui-summary">jumpToUserProfile</span>
        </span>
        <span class="ui-arrow"></span>
       </a>

       <a href="javascript://" class="ui-item-button showDashboard" onclick="MNDirect.execAppCommand('jumpToUserHome')">
        <span class="ui-caption">
         User Home
         <span class="ui-summary">jumpToUserHome</span>
        </span>
        <span class="ui-arrow"></span>
       </a>

       <a href="javascript://" class="ui-item-button showDashboard" onclick="MNDirect.execAppCommand('jumpToAddFriends')">
        <span class="ui-caption">
         Add Friends
         <span class="ui-summary">jumpToAddFriends</span>
        </span>
        <span class="ui-arrow"></span>
       </a>

       <a href="javascript://" class="ui-item-button showDashboard" onclick="MNDirect.execAppCommand('jumpToAchievements')">
        <span class="ui-caption">
         Achievements
         <span class="ui-summary">jumpToAchievements</span>
        </span>
        <span class="ui-arrow"></span>
       </a>

       <a href="javascript://" class="ui-item-button showDashboard" onclick="MNDirect.execAppCommand('jumpToGameInfo')">
        <span class="ui-caption">
         Game Info
         <span class="ui-summary">jumpToGameInfo</span>
        </span>
        <span class="ui-arrow"></span>
       </a>

       <a href="javascript://" class="ui-item-button showDashboard" onclick="MNDirect.execAppCommand('jumpToGameShop')">
        <span class="ui-caption">
         Game Shop
         <span class="ui-summary">jumpToGameShop</span>
        </span>
        <span class="ui-arrow"></span>
       </a>

       <a href="javascript://" class="ui-item-button showDashboard" onclick="MNDirect.execAppCommand('jumpToBuyCredits')">
        <span class="ui-caption">
         Buy Credits
         <span class="ui-summary">jumpToBuyCredits</span>
        </span>
        <span class="ui-arrow"></span>
       </a>

       <a href="javascript://" class="ui-item-button showDashboard" onclick="MNDirect.execAppCommand('jumpToBuySubscription')">
        <span class="ui-caption">
         Buy Subscription
         <span class="ui-summary">jumpToBuySubscription</span>
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
    title: 'Dashboard Control',
    content: Q.trim( Q('#'+__getName() + '_screen').html() )
   });

   var __construct = function()
    {
     Q('.' + __getName() + '_button').bind('click', SCREEN.show);
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