/*!
 * Q JavaScript Library v1.1
 *
 * Author: Max Chuhryaev
 * Email: my@w3core.org
 *
 * SELECTOR data type list:
 * - CSS selector
 * - DOM Node
 * - HTML String
 * - Array of Nodes
 * - Instance of Q
 * - The function that will called on DOM ready event
 *
 * CONTEXT data type list:
 * - CSS selector
 * - DOM Node
 * - HTML String
 * - Array of Nodes
 * - Instance of Q
 *
 *
 * Q(selector, context=document)
 * Q(fnReady)
 * Q().unique()
 * Q().trim(string)
 * Q().each()
 *   Q(selector).each( fn(node,i) )
 *   Q().each( ObjOrArrOrQ, fn(node,i) )
 * Q().inArray(value, array)
 * Q(selector).bind(event, fn)
 * Q(selector).unbind(event, fn)
 * Q(selector).addClass(class)
 * Q(selector).removeClass(class)
 * Q(selector).hasClass(class)
 * Q(selector).empty()
 * Q(selector).html()
 * Q(selector).html(string)
 * Q(selector).text()
 * Q(selector).text(string)
 * Q(selector).val()
 * Q(selector).val( value || function(node,index) )
 * Q().jsonValidate(string)
 * Q().jsonEncode(object)
 * Q().jsonDecode(string)
 * Q().param(object)
 * Q().ajax(object)
 * Q(selector).clone()
 * Q(selector).append(nodelist)
 * Q(selector).prepend(nodelist)
 * Q(selector).attr( name )
 * Q(selector).attr( name, value )
 * Q(selector).attr( name, fn(node,index){} )
 * Q(selector).attr( { name1:value1 , name2:fn2, ... } )
 * Q(selector).is(selector)
 * Q(selector).not(selector)
 * Q(selector).first(nodeOnly)
 * Q(selector).last(nodeOnly)
 *
 * Q extending construction:
 * this - current collection of Q
 *
 * (new function(){
 *
 *   Q.<name> = Q.fn.<name> = <something>;
 *
 * }());
 *
 */
(function( window, undefined ) {

  var Q = (function() {

    var Q = function( selector, context ) {
      return new Q.fn.init( selector, context );
    };

    var _Q = window.Q;

    var DOM_READY = false;
    var DOM_READY_HANDLED = false;

    var RE_HTML = /^(?:[^#<]*(<[\w\W]+>)[^>]*$|#([\w\-]*)$)/;

    var _find = function ( selector, context ) {
      context = context || document;
      if(typeof Q.find == 'function') {
        return Q.find(selector, context);
      }
      try {
        return context.querySelectorAll(selector);
      } catch(e){};
      return [];
    };

    var _domReady = function(callback) {
      /* Internet Explorer */
      /*@cc_on
      @if (@_win32 || @_win64)
        document.write('<script id="ieScriptLoad" defer src="//:"><\/script>');
        document.getElementById('ieScriptLoad').onreadystatechange = function() {
          if (this.readyState == 'complete') {
            callback();
          }
        };
        return;
      @end @*/
      /* Mozilla, Chrome, Opera */
      if(document.addEventListener) {
        document.addEventListener('DOMContentLoaded', callback, false);
        return;
      }
      /* Safari, iCab, Konqueror */
      if(/KHTML|WebKit|iCab/i.test(navigator.userAgent)) {
        var DOMLoadTimer = setInterval(function(){
          if(/loaded|complete/i.test(document.readyState)) {
            callback();
            clearInterval(DOMLoadTimer);
          }
        }, 10);
        return;
      }
      /* Other web browsers */
      window.onload = callback;
    };

    var _ready = function(fn) {
      if(!DOM_READY_HANDLED) {
        DOM_READY_HANDLED = true;
        _domReady(function(){
          DOM_READY = true;
        });
      }
      if(DOM_READY) fn();
      else _domReady(fn);
    };

    var _buildDOMNodeByHTMLString = function( string ) {
      var o = document.createElement('DIV');
      o.innerHTML = ( typeof string == 'string' ) ? string : '';
      return o.childNodes;
    };

    var _isNode = function(o) {
      return (
        typeof Node === 'object' ? o instanceof Node :
        typeof o === 'object' && typeof o.nodeType === 'number' && typeof o.nodeName === 'string'
      );
    };

    Q.fn = Q.prototype = {
      constructor: Q,
      // Q( selector, context )
      // Q( onReadyFn )
      init: function( selector, context ) {
        this.length = 0;
        var selector = selector || '';
        var context = context || document;
        if( context.constructor == String ) context = Q(context);
        if( _isNode(context) ) context = [ context ];
        if ( !selector ) return this;

        if( typeof selector == 'number' || typeof selector == 'boolean' ) selector = String(selector);
        if( typeof context == 'number' || typeof context == 'boolean' ) context = String(context);

        if(typeof selector == 'function') {
          _ready(selector);
          return this;
        }

        var matches = RE_HTML.exec(selector);
        var result = [];
        if(matches && selector.charAt(0) != '#') {
          result = _buildDOMNodeByHTMLString(selector);
        }
        else if(matches && selector.charAt(0) == '#' && context == document) {
          var o = document.getElementById(selector.replace('#',''));
          if(o) result.push(o);
        }
        else if(_isNode(selector)){
          result.push(selector);
        }
        else if(selector instanceof Q){
          for(var i=0; i<selector.length; i++) {
            result.push(selector[i]);
          }
        }
        else if(typeof selector == 'string'){
          if(context instanceof Q || context.constructor == Array) {
            for(var i=0; i<context.length; i++) {
              var collection = _find(selector, context[i]);
              for(var j=0; j<collection.length; j++) {
                result.push(collection[j]);
              }
            }
          }
          else result = _find(selector, context);
        }

        this.length = result.length;
        for(var i=0; i<result.length; i++) {
          this[i] = result[i];
        }
        return this;
      }
    };

    Q.fn.init.prototype = Q.fn;

    return Q;

  })();

  window.Q = Q;
})(window);

// Q.isNode(o)
Q.isNode = Q.fn.isNode = function(o) {
  return (
    typeof Node === 'object' ? o instanceof Node :
    typeof o === 'object' && typeof o.nodeType === 'number' && typeof o.nodeName === 'string'
  );
};

// Q.unique()
Q.unique = Q.fn.unique = function() {
  return String(Math.random()).replace(/^0./,'');
};

// Q.trim(str)
Q.trim = Q.fn.trim = function(s) {
  return String(s).replace(/^\s+|\s+$/img, '');
};

// Q.inArray(value, array)
Q.inArray = Q.fn.inArray = function(v,a) {
  if(Array.prototype.indexOf) return a.indexOf(v) != -1;
  var i = a.length;
  while (i--) {
    if (a[i] === v) return true;
  }
  return false;
};

// Q(selector).each( fn(node,i) )
// Q().each( ObjArrQ, fn(node,i) )
Q.each = Q.fn.each = function() {
  var a = arguments, o, f, c;
  if(a.length >= 2 && typeof a[0] == 'object' && typeof a[1] == 'function') {
    o = a[0];
    f = a[1];
    c = (typeof a[2] == 'object') ? a[2] : this;
  }
  else if(a.length >= 1 && this.length > 0 && typeof a[0] == 'function'){
    o = this;
    f = a[0];
    c = (typeof a[1] == 'object') ? a[1] : this;
  }
  if(!o || !f) return this;
  if(typeof o.length == 'number') {
    for(var i=0; i<o.length; i++){
      if(f.call(c, o[i], i) === false) break;
    }
  }
  else{
    for(var i in o){
      if(f.call(c, o[i], i) === false) break;
    }
  }
  return this;
};

// Q(selector).first()
Q.first = Q.fn.first = function(nodeOnly) {
  var node = (this.length && typeof this[0] != 'undefined') ? this[0] : null;
  return (nodeOnly) ? node : Q(node);
};

// Q(selector).last()
Q.last = Q.fn.last = function(nodeOnly) {
  var node = (this.length && typeof this[this.length-1] != 'undefined') ? this[this.length-1] : null;
  return (nodeOnly) ? node : Q(node);
};

// Q(selector).clone()
Q.clone = Q.fn.clone = function(){
  this.each(function(node,i){
    this[i] = this[i].cloneNode(true);
  });
  return this;
};

// Q(selector).bind( name, fn(e){} )
// Q(selector).unbind( name, fn(e){} )
(new function(){

  var _bind = function(node, name, fn) {
    if(node.addEventListener) {
      node.addEventListener(name, fn, false);
      return fn;
    }
    else if(node.attachEvent) {
      var _iefn = function(){ fn.call(node) };
      node.attachEvent('on'+name, fn);
      return _iefn;
    }
    return fn;
  };

  var _unbind = function(node, name, fn) {
    if(node.removeEventListener) {
      node.removeEventListener(name, fn, false);
    }
    else if(node.detachEvent) {
      node.detachEvent('on'+name, fn);
    }
    return;
  };

  var bind = function(name, fn) {
    if(!this.length || !Q.trim(name) || fn.constructor != Function) return this;
    this.each(function(node, i){
      _bind(node, name, fn);
    });
    return this;
  };

  var unbind = function(name, fn) {
    if(!this.length || !Q.trim(name) || fn.constructor != Function) return this;
    this.each(function(node, i){
      _unbind(node, name, fn);
    });
    return this;
  };

  Q.bind = Q.fn.bind = bind;
  Q.unbind = Q.fn.unbind = unbind;

}());


// Q(selector).addClass(name)
// Q(selector).removeClass(name)
// Q(selector).hasClass(name)
(new function(){

  var _classname2array = function(str) {
    var str = Q.trim(str);
    return (str.length > 0) ? str.split(/\s+/img) : [];
  };

  var _hasClass = function(node, name) {
    return node.className.match(new RegExp('(\\s|^)'+name+'(\\s|$)'));
  };

  var _addClass = function(node, name) {
    if(Q.trim(name).length && !_hasClass(node,name)) {
      var classes = _classname2array(node.className);
      classes.push(Q.trim(name));
      node.className = classes.join(' ');
    }
  };

  var _removeClass = function(node, name) {
    if(Q.trim(name).length && _hasClass(node,name)) {
      var classes = _classname2array(node.className);
      var name = Q.trim(name);
      var res = [];
      for(var i=0; i<classes.length; i++) {
        if(name != classes[i]) res.push(classes[i]);
      }
      node.className = res.join(' ');
    }
  };

  var addClass = function(name) {
    if(!this.length || !Q.trim(name)) return this;
    this.each(function(node, i){
      _addClass(node, name);
    });
    return this;
  };

  var removeClass = function(name) {
    if(!this.length || !Q.trim(name)) return this;
    this.each(this, function(node, i){
      _removeClass(node, name);
    });
    return this;
  };

  var hasClass = function(name) {
    if(!this.length || !Q.trim(name)) return false;
    var result = true;
    this.each(this, function(node, i){
      if(!_hasClass(node, name)) {
        result = false;
        return false;
      }
    });
    return result;
  };

  Q.addClass = Q.fn.addClass = addClass;
  Q.removeClass = Q.fn.removeClass = removeClass;
  Q.hasClass = Q.fn.hasClass = hasClass;

}());


//Q(selector).empty()
Q.empty = Q.fn.empty = function(){
  this.each(function(node){
    while(node.firstChild !== null) node.removeChild(node.firstChild);
  });
  return this;
};

(new function(){

  var _clone = function(data) {
    var n = Q(data).clone();
    if(!n.length && typeof data == 'string') {
      n = [ document.createTextNode(data) ];
    }
    return n;
  };

  // Q(selector).append(data)
  Q.append = Q.fn.append = function(data) {
    this.each(function(node){
      var childs = _clone(data);
      Q.each(childs, function(child){
        node.appendChild(child);
      });
    });
    return this;
  };

  // Q(selector).prepend(data)
  Q.prepend = Q.fn.prepend = function(data) {
    this.each(function(node){
      var first = node.firstChild;
      var childs = _clone(data);
      Q.each(childs, function(child){
        node.insertBefore(child, first);
      });
    });
    return this;
  };

}());

// Q(selector).attr( name )
// Q(selector).attr( name, value )
// Q(selector).attr( name, fn(node,index){} )
// Q(selector).attr( { name1:value1 , name2:fn2, ... } )
Q.attr = Q.fn.attr = function(name, value) {
  var args = Array.prototype.slice.call(arguments);
  var a = null;
  if(args.length == 2) {
    a = {};
    a[String(name)] = value;
  }
  else if(args.length == 1) {
    if(args[0].constructor == String) { // Getter
      var r = [];
      this.each(function(node){
        r.push(node.getAttribute(args[0]));
      });
      return r;
    }
    else if(args[0].constructor && args[0].constructor == Object) {
      a = args[0];
    }
  }
  if(a) {
    this.each(function(node,i){
      Q.each(a, function(v,n){
        var v = (v.constructor == Function) ? String(v(node,i)) : String(v);
        var n = String(n);
        node.setAttribute(n,v);
      });
    });
  }
  return this;
};

// Q(selector).val()
// Q(selector).val(value) // string || function(node,index)
(new function(){

  var _get = function(nodelist) {
    var result = [];
    Q(nodelist).each(function(node){
      if(!node.disabled) {
        if(
          typeof node.type == 'string'
          && Q.inArray(node.type.toLowerCase(), ['checkbox','radio'])
        ) {
          if(node.checked && typeof node.value == 'string') {
            result.push(node.value);
          }
        }
        else if(node.tagName.toLowerCase() == 'select') {
          Q('option[value]', node).each(function(n){
            if(!n.disabled && n.selected){
              result.push(n.value);
            }
          });
        }
        else if (typeof node.value == 'string') {
          result.push(node.value);
        }
      }
    });
    return result;
  };

  var _set = function(value, nodelist) {
    Q(nodelist).each(function(node, index){
      var val = String( (typeof value == 'function') ? value(node, index) : value );
      if(node.tagName.toLowerCase() == 'select') {
        Q('option[value]', node).each(function(n){
          if(typeof n.value == 'string' && n.value == val) {
            Q(n).attr('selected', 'selected');
          }
        });
      }
      else if (node.tagName.toLowerCase() == 'textarea') {
        node.value = val;
        Q(node).empty();
        node.appendChild(document.createTextNode(val));
      }
      else {
       Q(node).attr('value', val);
      }
    });
    return this;
  };

  Q.val = Q.fn.val = function() {
    return (!arguments.length) ? _get(this) : _set(arguments[0], this);
  };

}());

// Q(selector).html(string)
// Q(selector).html()
(new function(){

  var _parseHTML = function(string) {
   var result = { 'html':string, 'call':function(){} };
   if(typeof string != 'string') return result;
   // FIXME: Remove HTML comments (<!--...-->) but not in script tags.
   //var string = string.replace(/<!--([\s\S]*?)-->/g, '');
   var re = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi;
   var tags = string.match(re);
   if(!tags) return result;
   result.html = string.replace(re,'');
   result.call = function() {
     var head = document.head || document.getElementsByTagName( 'head' )[0] || document.documentElement;
     Q.each(tags, function(script){
       // MSIE does not select script tags by querySelectorAll. So, we need to parse response by REGEXP
       var content = script.replace(/^<script[^<>]*?>|<[\/\!]*?script>$/ig,'');
       var src = /^<script[^<>]*src[\s]*=["'\s]*([^"'\s]+)[^<>]*>/img.exec(script);
       src = (src && typeof src[1] != 'undefined') ? src[1] : null;
       if(src){
         var s = document.createElement('SCRIPT');
         s.type = 'text/javascript';
         s.src = src;
         Q(s).bind('load', function(e){
           var el = this;
           setTimeout(function(){
             head.removeChild(el);
           }, 100);
         });
         head.appendChild(s);
       }
       if(content){
         (new Function( content ))();
       }
     });
   };
   return result;
  };

  var _get = function(node) {
    return (Q.isNode(node)) ? node.innerHTML : null;
  };

  var _set = function(node, string) {
    if(!Q.isNode(node)) return this;
    Q(node).empty();
    if(typeof string == 'number' || typeof string == 'boolean') var string = String(string);
    var a = Q(string);
    if(!a.length && typeof string == 'string'){
      a = Q(document.createTextNode(string));
    }
    Q.each(a, function(child){
      node.appendChild(child.cloneNode(true));
    });
  };

  var html = function() {
    var a = arguments;
    if (a.length == 1) {
      var r = _parseHTML(a[0]);
      this.each(function(node){
        _set(node, r.html);
      });
      r.call();
      return this;
    }
    else {
      var r = [];
      this.each(function(node){
        r.push(_get(node));
      });
      return r.join('');
    }
  };

  Q.html = Q.fn.html = html;

}());


// Q(selector).text(string)
// Q(selector).text()
(new function(){

  var _text = function(html) {
    return (typeof html != 'undefined') ? Q.trim(String(html).replace(/<[\/\!]*?[^<>]*?>/img, '')) : '';
  };

  var _get = function(node) {
    if(!Q.isNode(node)) return null;
    return node.textContent || node.innerText || _text(node.innerHTML) || '';
  };

  var _set = function(node, string) {
    if(!Q.isNode(node)) return this;
    Q(node).empty();
    node.appendChild(document.createTextNode(string));
  };

  var text = function() {
    var a = arguments;
    if (a.length == 1) {
      this.each(function(node){
        _set(node, a[0]);
      });
      return this;
    }
    else {
      var r = [];
      this.each(function(node){
        r.push(_get(node));
      });
      return r.join('');
    }
  };

  Q.text = Q.fn.text = text;

}());


// Q.jsonEncode(object)
// Q.jsonDecode(string)
// Q.jsonValidate(string)
(new function(){

  var _jsonEncode = function(object) {
    if ( window.JSON && window.JSON.stringify ) {
      return window.JSON.stringify( object );
    }
    if(typeof object != 'object' || object === null) {
      if(typeof object == 'string') object = '"'+object+'"';
      return String(object);
    }
    else {
      var n, v, t, json = [], arr = (object && object.constructor == Array);
      for(n in object) {
        v = object[n]; t = typeof(v);
        if (t == 'string') v = '"'+v+'"';
        else if (t == 'object' && v !== null) v = _jsonEncode(v);
          json.push((arr ? "" : '"' + n + '":') + String(v));
        }
        return (arr ? '[' : '{') + String(json) + (arr ? ']' : '}');
    }
  };

  var _jsonValidate = function(s) {
    var chars  = /^[\],:{}\s]*$/,
        escape = /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,
        tokens = /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
        braces = /(?:^|:|,)(?:\s*\[)+/g;
    return ( chars.test(s.replace(escape, '@').replace(tokens, ']').replace(braces, '') )) ? true : false;
  };

  var _jsonDecode = function(string) {
    if(typeof string != 'string') return null;
    string = Q.trim(string);
    if ( window.JSON && window.JSON.parse ) {
      return window.JSON.parse( string );
    }
    if(_jsonValidate(string)){
     return (new Function( 'return ' + string ))();
    }
  };

  Q.jsonValidate = Q.fn.jsonValidate = _jsonValidate;
  Q.jsonEncode = Q.fn.jsonEncode = _jsonEncode;
  Q.jsonDecode = Q.fn.jsonDecode = _jsonDecode;

}());

// Q.(object) => url param string
Q.param = Q.fn.param = function(o){
  var a = arguments, r = [];
  var n = (typeof a[1] == 'string') ? (a[1] + '[%s]') : '%s';
  for(var i in o){
    if(typeof o[i] == 'object' && o[i] != null) r.push(Q.param(o[i], n.replace('%s', i)));
    else r.push(n.replace('%s', i) + '=' + encodeURIComponent(o[i]));
  }
  return r.join('&');
};

// Q.ajax({  })
(new function(){

  var makeXHR = function(){
    var xhr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

    xhr.getResponse = function(){
      var r = null;
      if(typeof this.responseText == 'string'){
        r = (Q.jsonValidate(this.responseText)) ? Q.jsonDecode(this.responseText) : this.responseText;
      }
      return r;
    };

    return xhr;
  };

  Q.ajax = Q.fn.ajax = function(o){
    if(typeof o != 'object' || o == null) return this;
    var q = {};
    q.url = (typeof o.url == 'string') ? Q.trim(o.url) : location.href.replace(/#.*$/,'');
    q.type = (typeof o.type == 'string' && Q.trim(o.type).length > 0) ? Q.trim(o.type) : 'POST';
    q.async = (typeof o.async == 'boolean') ? o.async : true;
    q.contentType = (typeof o.contentType == 'string' && Q.trim(o.contentType).length > 0) ? Q.trim(o.contentType) : 'application/x-www-form-urlencoded';
    q.xRequestedWith = (typeof o.xRequestedWith == 'string') ? o.xRequestedWith : 'XMLHttpRequest';
    q.data = (typeof o.data == 'object' && o.data != null) ? Q.param(o.data) : (typeof o.data == 'string' ? o.data : '');
    q.headers = (typeof o.headers == 'object' && o.headers != null) ? o.headers : {};
    q.timeout = (typeof o.timeout == 'number') ? o.timeout : 30;
    q.error = (typeof o.error == 'function') ? o.error : function(){};
    q.success = (typeof o.success == 'function') ? o.success : function(){};
    q.complete = (typeof o.complete == 'function') ? o.complete : function(){};

    if(q.type == 'GET' && Q.trim(q.data).length > 0){
      q.url += (q.url.indexOf('?') >= 0) ? '&' : '?';
      q.url += q.data;
      q.data = null;
    }

    var xhr = makeXHR();
    xhr.open(q.type, q.url, q.async);
    xhr.setRequestHeader('Content-type', q.contentType);
    xhr.setRequestHeader('X-Requested-With', q.xRequestedWith);
    for(var i in q.headers){
      xhr.setRequestHeader(String(i), String(q.headers[i]));
    }
    var done = false;
    xhr.onreadystatechange = function(e){
      if(xhr.readyState==4){
        done = true;
        if(xhr.status>=200 && xhr.status<=399){
          q.success(xhr.getResponse(), xhr);
        }
        else q.error(xhr);
        q.complete(xhr);
      }
    };
    xhr.send(q.data);
    setTimeout(function(){
      if(!done){
        q.error(xhr);
        q.complete(xhr);
      }
    },q.timeout);
    return this;
  };
}());

// Q(selector).is(selector)
Q.is = Q.fn.is = function(selector) {
  if(!this.length) return false;
  var owner = document.createElement('div');
  var status = true;
  this.clone().each(function(node){
    Q(owner).empty().append( Q(node).empty() );
    if( !Q(selector, owner).length ){
      status = false;
      return false;
    }
  });
  return status;
};

// Q(selector).not(selector)
Q.not = Q.fn.not = function(selector) {
  return !this.is(selector);
};

// Q.tpl(template, data)
// See: http://ejohn.org/blog/javascript-micro-templating/
Q.tpl = Q.fn.tpl = function(){};