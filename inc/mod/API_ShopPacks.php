<?php

class API_ShopPacksModule extends Module
{

 public function RenderCSS($nm)
  {
   ?>
   .<?=$nm?>_item .ui-buttons {text-align:right; white-space:nowrap; padding:.8em 0 0 .8em}
   .<?=$nm?>_item .ui-summary .description {display:block; color:black; padding:.2em 0;}
   .<?=$nm?>_item .ui-summary .ui-label {display:inline-block; width:60px; white-space:nowrap; font-size:12px;}
   .<?=$nm?>_item .ui-summary .ui-value {color:#395587; font-weight:bold; font-size:12px;}
   .<?=$nm?>_item .ui-summary .price {color:#009a0c;}
   <?
  }

 public function RenderHTML($nm)
  {
   ?>
   <script type="text/html" id="<?=$nm?>_list">
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
         <div class="ui-row description"></div>
         <div class="ui-row">
          <span class="ui-label">Category:</span>
          <span class="ui-value category"></span>
         </div>
         <div class="ui-row">
          <span class="ui-label">Amount:</span>
          <span class="ui-value amount"></span>
         </div>
         <div class="ui-row">
          <span class="ui-label">Price:</span>
          <span class="ui-value price"></span>
         </div>
        </div>
        <div class="ui-buttons">
         <a href="javascript://" class="ui-button-green buy">Buy Now</a>
        </div>
       </div>
      </div>
      <span class="ui-item">
       <span class="ui-caption">Pack ID</span>
       <span class="ui-value id"></span>
      </span>
      <span class="ui-item">
       <span class="ui-caption">Category ID</span>
       <span class="ui-value category-id"></span>
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
    title: 'Shop Packs',
    content: Q.trim( Q('#'+__getName() + '_list').html() )
   });

   that.SCREEN = SCREEN;

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
       function(e)
        {
         doList();
        }
      );
    };

   var initBuyButtons = function()
    {
     Q('.list .buy').bind('click', function(e){
      var id = Number(Q(this).attr('item-id'));
      var sign = Number(Q(this).attr('item-sign'));
      if(sign) realBuyItem(id);
      else virtualBuyItem(id);
     });
    };

   var realBuyItem = function(id)
    {
     var callback = function(responseItem)
      {
       var msg = '';
       if(responseItem.hadError())
        {
         msg  = 'Error ' + responseItem.getErrorCode() + ':\n\n';
         msg += responseItem.getErrorMessage();
        }
       else
        {
         msg = 'Done.';
        }
       alert(msg);
      };

     var param = Q.param({ 'packId': id, 'amount': 1 });

     MNDirect
     .serviceProvider
     .VEconomy
     .UI
     .showDashboardDialogCheckoutShopPackList( [id], [1], callback, param, 0);
    };

   var virtualBuyItem = function(id)
    {
     var callback = function(responseItem)
      {
       var msg = '';
       if(responseItem.hadError())
        {
         msg  = 'Error ' + responseItem.getErrorCode() + ':\n\n';
         msg += responseItem.getErrorMessage();
        }
       else
        {
         msg = 'Done.';
        }
       alert(msg);
      };
     MNDirect
     .serviceProvider
     .VEconomy
     .reqThisGameBuyShopPackList( [id], [1], callback);
    };

   var renderForNotLoggedInUser = function()
    {
     Q('.list', SCREEN.NODE).html('<div class="ui-description-blue-indented">The user need to be logged in!</div>');
     ui.refreshScroller();
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

       Q('.list', SCREEN.NODE).html('<div class="ui-description-blue-indented">' + msg + '</div>');
       ui.refreshScroller();
       return true;
      }
     return false;
    };

   var doList = function()
    {
     // Virtual Items list available only for authorized users!
     if(!MNDirect.getSession().isUserLoggedIn()) return renderForNotLoggedInUser();

     // Executes current game virtual items list request.
     MNDirect.serviceProvider.VEconomy.reqThisGameGetShopPackList
      (
       // Callback function that renders response
       function(responseItem)
        {
         renderList(responseItem);
        }
      );
    };

   var renderList = function(responseItem)
    {
     var holder = Q('.list', SCREEN.NODE);
     holder.empty();

     // Request errors processing
     if(renderForResponseError(responseItem)) return;

     // Request data extraction
     var data = responseItem.getData().entry;

     var node = Q(ITEM_HTML);
     Q().each(data, function(item, index){
      var n = node.clone();
      var itemRaw = item.getValueRawData();

      Q('.thumbnail', n).each(function(thumb){
       thumb.style.backgroundImage = 'url("'+item.thumbnailUrl+'")';
      });
      Q('.id', n).text(item.id);
      Q('.category-id', n).text(item.categoryId);
      Q('.name', n).text(item.packName);
      Q('.category', n).text(itemRaw.category_name);
      Q('.description', n).text(item.packDesc);
      Q('.amount', n).text(itemRaw.commodity_amount);
      var priceText = 'FREE';
      if(item.price)
       {
        priceText = item.price;
        if(item.priceCurrSign) priceText += ' ' + item.priceCurrSign;
       }
      Q('.price', n).text(priceText);

      Q('.buy', n)
      .attr('item-id', item.id)
      // FIXME: check by "priceItemId" property.
      //        Use showDashboardDialogCheckoutShopPackList if priceItemId = null
      //
      // .attr('item-sign', (!item.priceItemId) ? 1 : 0);
      .attr('item-sign', (item.price) ? 1 : 0);

      holder.append(n);
     });

     ui.refreshScroller();
     initBuyButtons();
    };

   __construct();

   <?if(false){?></script><?}?>
   <?
  }

}