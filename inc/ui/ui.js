var ui = (new function(){
 var that = this;

 var TITLE_DEFAULT_TEXT = 'PlayPhone SDK Demo';

 var SCROLLER;

 var __construct = function()
  {
   Q(function(){
    initScroller();
    initBackButton();
   });
  };

 var setTitle = function(title)
  {
   var title = (typeof title == 'string' && Q.trim(title).length > 0) ? Q.trim(title) : TITLE_DEFAULT_TEXT;
   Q('#title').html(title);
   return that;
  };

 var goBack = function()
  {
   setTitle();
   Q('.screen').addClass('hidden');
   Q('#home').removeClass('hidden');
   Q('#back').addClass('hidden');
   refreshScroller();
  };

 var switchToScreen = function(node)
  {
   Q('#home, .screen').addClass('hidden');
   Q(node).removeClass('hidden');
   Q('#back').removeClass('hidden');
   refreshScroller();
  };

 var addScreen = function(node)
  {
   Q('#content').append(node);
  };

 var initBackButton = function()
  {
   Q('#back').bind('click', goBack);
  };

 var initScroller = function()
  {
   if(navigator.userAgent.toLowerCase().indexOf('mobile') === -1) return;
   setTimeout(function(){
    SCROLLER = new iScroll('container');
    Q(document).bind('touchmove', function(e){
     e.preventDefault();
    });
   }, 200);
  };

 var refreshScroller = function()
  {
   if(!SCROLLER) return;
   setTimeout(function(){
    SCROLLER.refresh();
   }, 400);
  };

 var radioLabels = function(selector, classname, eventname)
  {
   var nodes = Q(selector);
   var classname = (typeof classname == 'string' && Q.trim(classname).length) ? classname : 'selected';
   var eventname = (typeof eventname == 'string' && Q.trim(eventname).length) ? eventname : 'click';
   nodes.bind(eventname, function(e){
    nodes.removeClass(classname);
    Q(this).addClass(classname);
   });
  };

 __construct();

 that.setTitle = setTitle;
 that.goBack = goBack;
 that.addScreen = addScreen;
 that.switchToScreen = switchToScreen;
 that.radioLabels = radioLabels;

 that.refreshScroller = refreshScroller;

}());

ui.screen = (function(o){
 var parent = ui;
 var that = this;
 var o = (o && o.constructor == Object) ? o : {};
 var NODE;
 var TITLE;

 var __construct = function()
  {
   NODE = document.createElement('DIV');
   Q(NODE).addClass('screen').addClass('hidden');
   document.getElementById('content').appendChild(NODE);
   if(typeof o.title != 'undefined') TITLE = String(o.title);
   if(typeof o.content == 'string') Q(NODE).append(o.content);
  };

 var show = function()
  {
   if(TITLE) parent.setTitle(TITLE);
   Q(NODE).removeClass('hidden');
   Q('#home').addClass('hidden');
   Q('#back').removeClass('hidden');
   parent.refreshScroller();
  };

 var hide = function()
  {
   parent.setTitle();
   Q(NODE).addClass('hidden');
   parent.refreshScroller();
  };

 var switchTo = function(screen)
  {
   hide();
   if( screen instanceof ui.screen )
    {
     Q('#home').addClass('hidden');
     Q('#back').removeClass('hidden');
     screen.show();
    }
   else
    {
     Q('#home').removeClass('hidden');
     Q('#back').addClass('hidden');
    }
   parent.refreshScroller();
  };

 var title = function(str)
  {
   TITLE = String(str);
   if(Q(NODE).not('.hidden'))
    {
     parent.setTitle(TITLE);
    }
  };

 __construct();

 that.NODE = NODE;
 that.show = show;
 that.hide = hide;
 that.title = title;
 that.switchTo = switchTo;
});