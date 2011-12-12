<?php

class API_LeaderboardModule extends Module
{

 public function RenderCSS($nm)
  {
   ?>
   <?if(false){?><style><?}?>
    .<?=$nm?>_screen .postScore {display: block;}
    .<?=$nm?>_screen .postScore, .<?=$nm?>_screen .period {margin-top: 6px;}
    .<?=$nm?>_screen .scope label {width: 50%;}
    .<?=$nm?>_screen .period label {width: 33.33%;}

   .<?=$nm?>_item .ui-summary .ui-label {display:inline-block; width:50px; white-space:nowrap; font-size:12px;}
   .<?=$nm?>_item .ui-summary .ui-value {color:#395587; font-weight:bold; font-size:12px;}

   <?if(false){?></style><?}?>
   <?
  }

 public function RenderHTML($nm)
  {
   ?>
   <a href="javascript://" class="ui-item-button <?=$nm?>_button">
    <span class="ui-caption">Leaderboard</span>
    <span class="ui-arrow"></span>
   </a>

   <script type="text/html" id="<?=$nm?>_screen">
    <div class="<?=$nm?>_screen">

     <div class="ui-box-margin">

      <div class="ui-switch-radio scope">
       <input type="radio" name="scope" id="leaderboardScopeGlobal" value="LEADERBOARD_SCOPE_GLOBAL" checked="checked" />
       <input type="radio" name="scope" id="leaderboardScopeLocal" value="LEADERBOARD_SCOPE_LOCAL" />
       <label for="leaderboardScopeGlobal" class="selected">Global</label><label for="leaderboardScopeLocal">Local</label>
      </div>

      <div class="ui-switch-radio period">
       <input type="radio" name="period" id="leaderboardPeriodAll" value="LEADERBOARD_PERIOD_ALL_TIME" checked="checked" />
       <input type="radio" name="period" id="leaderboardPeriodMonth" value="LEADERBOARD_PERIOD_THIS_MONTH" />
       <input type="radio" name="period" id="leaderboardPeriodWeek" value="LEADERBOARD_PERIOD_THIS_WEEK" />
       <label for="leaderboardPeriodAll" class="selected">All Time</label><label for="leaderboardPeriodMonth">Month</label><label for="leaderboardPeriodWeek">Week</label>
      </div>

      <a href="javascript://" class="ui-button-green postScore"></a>

     </div>

     <div class="list">
      <!-- Items will be pushed here -->
     </div>

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
           <span class="ui-label">Place:</span>
           <span class="ui-value place"></span>
          </div>
          <div class="ui-row">
           <span class="ui-label">Score:</span>
           <span class="ui-value score"></span>
          </div>
          <div class="ui-row">
           <span class="ui-label">User ID:</span>
           <span class="ui-value id"></span>
          </div>
         </div>
        </div>
       </div>
      </div>
     </div>
   </script>
   <?
  }

 public function RenderJS()
  {
   ?>
   <?if(false){?><script><?}?>

   var SCREEN = new ui.screen({
    title: 'Leaderboard',
    content: Q.trim( Q('#'+__getName() + '_screen').html() )
   });

   var ITEM_HTML = Q.trim( Q('#'+__getName() + '_item').html() );

   var __construct = function()
    {
     Q('.' + __getName() + '_button').bind('click', function(){
      SCREEN.show();
      doList();
     });

     initPostScoreButton();

     Q('.scope input, .period input', SCREEN.NODE).bind('change', doList);
     ui.radioLabels(Q('.scope label', SCREEN.NODE), 'selected', 'click');
     ui.radioLabels(Q('.period label', SCREEN.NODE), 'selected', 'click');

     initSessionHandler();
     doList();
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

   var initPostScoreButton = function()
    {
     var defaultText = 'Post Score!';
     Q('.postScore', SCREEN.NODE)
     .text(defaultText)
     .bind('click', function(e){
      var el = this;
      Q(el).text('Processing...');
      var score = Number(prompt('Please, enter amount of scores', '0'));
      if(!score)
       {
        Q(el).text(defaultText);
        alert('Request refused.\nAmount of scores need to be more then zero.');
        return;
       }
      // Pending score posting. Can be used for not logged in users.
      MNDirect.postGameScorePending(score);

      if(!MNDirect.getSession().isUserLoggedIn())
       {
        Q(el).text(defaultText);
        alert('You are not logged in!\nYour last score that will be posted on the leaderboard will be saved after log-in.');
        return;
       }

      Q(el).text('Please, wait...');
      setTimeout(function(){
       doList();
       Q(el).text(defaultText);
      }, 1000);

     });
    };

   var getParams = function()
    {
     var scope = Q('.scope input', SCREEN.NODE).val();
     var period = Q('.period input', SCREEN.NODE).val();
     if
      (
       typeof scope[0] == 'undefined'
       || typeof period[0] == 'undefined'

       // Using predefined SDK constants. See documentation
       || typeof MNDirect.serviceProvider.Info[scope[0]] == 'undefined'
       || typeof MNDirect.serviceProvider.Info[period[0]] == 'undefined'
      ) return false;

     return {
      'scope': MNDirect.serviceProvider.Info[scope[0]],
      'period': MNDirect.serviceProvider.Info[period[0]]
     };
    };

   var renderListMessage = function(msg)
    {
     Q('.list', SCREEN.NODE).html('<div class="ui-box-margin"><div class="ui-description-blue-indented">' + msg + '</div></div>');
    };

   var doList = function()
    {
     var param = getParams();
     if(renderForNotLoggedInUser() || !param) return false;
     renderListMessage('Please wait...');
     // Executes Leaderboard info request by defined scope and period signs.
     MNDirect.serviceProvider.Info.reqCurrUserLeaderboard( renderList, param.scope, param.period );
    };

   var renderForNotLoggedInUser = function()
    {
     if(!MNDirect.getSession().isUserLoggedIn())
      {
       renderListMessage('The user need to be logged in!');
       return true;
      }
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
       renderListMessage(msg);
       return true;
      }
     return false;
    };

   var renderList = function(responseItem)
    {
     if(renderForNotLoggedInUser() || renderForResponseError(responseItem)) return false;

     var holder = Q('.list', SCREEN.NODE);
     holder.empty();

     // Request data extraction
     var data = responseItem.getData().entry;

     if(!data.length)
      {
       renderListMessage('The list is empty');
      }

     var node = Q(ITEM_HTML);
     Q().each(data, function(item, index){
      var n = node.clone();
      Q('.id', n).text(item.id);
      Q('.name', n).text(item.nickname);
      Q('.place', n).text(item.extLB.place);
      Q('.score', n).text(item.extLB.score);
      Q('.thumbnail', n).each(function(thumb){
       thumb.style.backgroundImage = 'url("'+item.thumbnailUrl+'")';
      });
      Q('.unlock', n).attr('item-id', item.id);
      holder.append(n);
     });
     ui.refreshScroller();
    };

   __construct();

   <?if(false){?></script><?}?>
   <?
  }

}