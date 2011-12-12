<?php

class API_VirtualItemsScreenModule extends Module
{

 public function RenderCSS($nm)
  {
   ?>
   .<?=$nm?>_item .vItemDescription {min-height: 42px}
   .<?=$nm?>_item .ui-buttons {text-align:right; white-space:nowrap; padding-left:.8em}
   <?
  }

 public function RenderHTML($nm)
  {
   ?>
   <script type="text/html" id="<?=$nm?>_list">
    <div class="ui-box-margin">
     <div class="ui-title-blue-indented">List of Game Items</div>
    </div>
    <div class="vItemsList">
     <!-- Items will be pushed here -->
    </div>
   </script>

   <script type="text/html" id="<?=$nm?>_item">
    <div class="ui-box-margin <?=$nm?>_item">
     <div class="ui-list-box">
      <div class="ui-item-profile">
       <div class="ui-column-min">
        <div class="ui-image vItemThumbnail"></div>
       </div>
       <div class="ui-column-max">
        <div class="ui-caption vItemName"></div>
        <div class="ui-summary vItemDescription"></div>
        <div class="ui-buttons">
         <a href="javascript://" class="ui-button-green userAdd">Add</a>
         <a href="javascript://" class="ui-button-red userSubstract">Substract</a>
        </div>
       </div>
      </div>
      <span class="ui-item">
       <span class="ui-caption">Item ID</span>
       <span class="ui-value vItemId"></span>
      </span>
      <span class="ui-item">
       <span class="ui-caption">User amount</span>
       <span class="ui-value userAmount">0</span>
      </span>
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
    title: 'Virtual Items',
    content: Q.trim( Q('#'+__getName() + '_list').html() )
   });

   that.SCREEN = SCREEN;

   var ITEM_HTML = Q.trim( Q('#'+__getName() + '_item').html() );

   var __construct = function()
    {
     doVirtualItemsList();
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
         doVirtualItemsList();
        }
      );
    };

   var renderForNotLoggedInUser = function()
    {
     Q('.vItemsList', SCREEN.NODE).html('<div class="ui-description-blue-indented">The user need to be logged in!</div>');
     ui.refreshScroller();
     return false;
    };

   var doVirtualItemsList = function()
    {
     // Virtual Items list available only for authorized users!
     if(!MNDirect.getSession().isUserLoggedIn()) return renderForNotLoggedInUser();

     // Executes current game virtual items list request.
     MNDirect.serviceProvider.VEconomy.reqThisGameGetVItemList
      (
       // Callback function that renders response
       function(responseItem)
        {
         renderVirtualItemsList(responseItem);
         doUserVirtualItemsProcess();
        }
      );
    };

   var doUserVirtualItemsProcess = function()
    {
     // Virtual Items list available only for authorized users!
     if(!MNDirect.getSession().isUserLoggedIn()) return renderForNotLoggedInUser();

     // Executes current user item inventory list request.
     MNDirect.serviceProvider.VEconomy.reqCurrUserGetVItemList
      (
        // Callback function that process response
        fillUserVirtualItems
      );
    };

   var fillUserVirtualItems = function(responseItem)
    {
     if(responseItem.hadError()) return;
     var data = {};
     Q.each(responseItem.getData().entry, function(v){
      data[v.id] = v
     });
     Q('.vItemsList .<?=$nm?>_item', SCREEN.NODE).each(function(node){
      processUserVirtualItem(node, data);
     });
    };

   var processUserVirtualItem = function(node, userItems)
    {
     var itemId = Q(node).attr('item-id');
     if(!itemId.length || !Number(itemId[0])) return;
     itemId = itemId[0];
     var data = (typeof userItems[itemId] != 'undefined') ? userItems[itemId] : null;
     Q('.userAmount', node).text(String(
      (data && typeof data.count != 'undefined') ? data.count : 0
     ));
     Q('.userAdd', node).bind('click', function(e){
      Q(this).text('Wait...');
      requestOrderCurrentUserVirtualItem(itemId, 1);
     });
     Q('.userSubstract', node).bind('click', function(e){
      Q(this).text('Wait...');
      requestOrderCurrentUserVirtualItem(itemId, 0);
     });
    };

   var requestOrderCurrentUserVirtualItem = function(itemId, operationSign)
    {
     var count = Math.abs(Number(prompt(
      (
       'Please, enter amount of items, that you want to '
       + ((operationSign) ? 'add' : 'substract')
       + ':'
      ),
      '1'
     )));
     if(!count) return;
     count = (!operationSign) ? (0 - count) : count;

     // Executes add(issue)/remove(use) item to/from user inventory request.
     // This is main engine to add/grant user item in game and to remove/use/spend items in game.
     MNDirect.serviceProvider.VEconomy.reqCurrUserAddVItem
      (
       itemId,
       count,
       doVirtualItemsList
      );
    };

   var renderVirtualItemsList = function(responseItem)
    {
     var holder = Q('.vItemsList', SCREEN.NODE);
     holder.empty();

     // Request errors processing
     if(responseItem.hadError())
      {
       var msg  = '';
           msg += 'Error '
           msg += '(' + responseItem.getErrorCode() +  '): '; // Request error code
           msg += responseItem.getErrorMessage() // Request error message
       holder.html('<div class="ui-description-blue-indented">' + msg + '</div>');
       return;
      }

     // Request data extraction
     var data = responseItem.getData().entry;

     var node = Q(ITEM_HTML);
     Q().each(data, function(item, index){
      var n = node.clone();

      n.attr('item-id', String(item.id));

      Q('.vItemThumbnail', n).each(function(thumb){
       thumb.style.backgroundImage = 'url("'+item.thumbnailUrl+'")';
      });
      Q('.vItemName', n).text(item.itemName);
      Q('.vItemDescription', n).text(item.itemDesc);
      Q('.vItemId', n).text(String(item.id));
      holder.append(n);
     });
     ui.refreshScroller();
    };

   __construct();

   <?if(false){?></script><?}?>
   <?
  }

}