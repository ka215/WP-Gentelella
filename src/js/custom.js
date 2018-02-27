/**
 * Resize function without multiple trigger
 * 
 * Usage:
 * $(window).smartresize(function(){  
 *     // code here
 * });
 */
(function($,sr){
    // debouncing function from John Hann
    // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
    var debounce = function (func, threshold, execAsap) {
      var timeout;

        return function debounced () {
            var obj = this, args = arguments;
            function delayed () {
                if (!execAsap)
                    func.apply(obj, args); 
                timeout = null; 
            }

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100); 
        };
    };

    // smartresize 
    jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');
/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*\
 |*|
 |*|  :: cookies.js ::
 |*|
 |*|  A complete cookies reader/writer framework with full unicode support.
 |*|
 |*|  https://developer.mozilla.org/en-US/docs/DOM/document.cookie
 |*|
 |*|  Syntaxes:
 |*|
 |*|  * docCookies.setItem(name, value[, end[, path[, domain[, secure]]]])
 |*|  * docCookies.getItem(name)
 |*|  * docCookies.removeItem(name[, path])
 |*|  * docCookies.hasItem(name)
 |*|  * docCookies.keys()
 |*|
 \*/
var docCookies = {
  getItem: function (sKey) {
    if (!sKey || !this.hasItem(sKey)) { return null; }
    return unescape(document.cookie.replace(new RegExp("(?:^|.*;\\s*)" + escape(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*((?:[^;](?!;))*[^;]?).*"), "$1"));
  },
  setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
    if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) { return; }
    var sExpires = "";
    if (vEnd) {
      switch (vEnd.constructor) {
        case Number:
          sExpires = vEnd === Infinity ? "; expires=Tue, 19 Jan 2038 03:14:07 GMT" : "; max-age=" + vEnd;
          break;
        case String:
          sExpires = "; expires=" + vEnd;
          break;
        case Date:
          sExpires = "; expires=" + vEnd.toGMTString();
          break;
      }
    }
    document.cookie = escape(sKey) + "=" + escape(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
  },
  removeItem: function (sKey, sPath) {
    if (!sKey || !this.hasItem(sKey)) { return; }
    document.cookie = escape(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sPath ? "; path=" + sPath : "");
  },
  hasItem: function (sKey) {
    return (new RegExp("(?:^|;\\s*)" + escape(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
  },
  keys: /* optional method: you can safely remove it! */ function () {
    var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
    for (var nIdx = 0; nIdx < aKeys.length; nIdx++) { aKeys[nIdx] = unescape(aKeys[nIdx]); }
    return aKeys;
  }
};

var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
    $BODY = $('body'),
    $MENU_TOGGLE = $('#menu_toggle'),
    $SIDEBAR_MENU = $('#sidebar-menu'),
    $SIDEBAR_FOOTER = $('.sidebar-footer'),
    $LEFT_COL = $('.left_col'),
    $RIGHT_COL = $('.right_col'),
    $NAV_MENU = $('.nav_menu'),
    $FOOTER = $('footer'),
    SUBMIT_BUTTONS = []; // defined per pages

// Sidebar
$(document).ready(function() {
    // TODO: This is some kind of easy fix, maybe we can improve this
    var setContentHeight = function () {
        // reset height
        $RIGHT_COL.css('min-height', $(window).height());

        var bodyHeight = $BODY.outerHeight(),
            footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
            leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
            contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

        // normalize content
        contentHeight -= $NAV_MENU.height() + footerHeight;

        $RIGHT_COL.css('min-height', contentHeight);
    };

    $SIDEBAR_MENU.find('a').on('click', function(ev) {
        var $li = $(this).parent();

        if ($li.is('.active')) {
            $li.removeClass('active active-sm');
            $('ul:first', $li).slideUp('fast', function() {
                setContentHeight();
            });
        } else {
            // prevent closing menu if we are on child menu
            if (!$li.parent().is('.child_menu')) {
                if ($BODY.hasClass('nav-md')){
                $SIDEBAR_MENU.find('li').removeClass('active active-sm');
                }
                $SIDEBAR_MENU.find('li ul').slideUp('fast');
            }
            
            $li.addClass('active');

            $('ul:first', $li).slideDown('fast', function() {
                setContentHeight();
            });
        }
    });

    // toggle small or large menu
    $MENU_TOGGLE.on('click', function() {
        if ($BODY.hasClass('nav-md')) {
            $SIDEBAR_MENU.find('li.active ul').hide();
            $SIDEBAR_MENU.find('li.active').addClass('active-sm').removeClass('active');
            docCookies.setItem('current_sidebar', 'small', 60*60*24*30, '/');
        } else {
            $SIDEBAR_MENU.find('li.active-sm ul').show();
            $SIDEBAR_MENU.find('li.active-sm').addClass('active').removeClass('active-sm');
            docCookies.setItem('current_sidebar', 'large', 60*60*24*30, '/');
        }

        $BODY.toggleClass('nav-md nav-sm');

        setContentHeight();

        $('.dataTable').each ( function () { $(this).dataTable().fnDraw(); });
    });

    // check active menu (fire on load)
    $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').parent('li').addClass('active current-page');

    $SIDEBAR_MENU.find('a').filter(function () {
        return this.href == CURRENT_URL;
    }).parent('li').addClass('active current-page').parents('ul').slideDown(0, function() {
        setContentHeight();
        if ($BODY.hasClass('nav-sm')) {
            $(this).find('.child_menu').hide();
            $(this).parent('li').addClass('current-page');
        } else {
            $(this).parent('li').addClass('active current-page');
        }
    });

    // recompute content when resizing
    $(window).smartresize(function(){  
        setContentHeight();
    });

    setContentHeight();

    // fixed sidebar
    if ($.fn.mCustomScrollbar) {
        $('.menu_fixed').mCustomScrollbar({
            autoHideScrollbar: true,
            theme: 'minimal',
            mouseWheel:{ preventDefault: true }
        });
    }
    
    // sidebar-footer
    $SIDEBAR_FOOTER.find('a').on('click', function(ev){
        ev.preventDefault();
        switch ( $(this).attr('name') ) {
            case 'settings':
                // PNotify.info('Location to settings page.');
                PNotify.alert({text: 'Location to settings page.', addClass: 'dark' });
                break;
            case 'fullscreen':
                var requestFullscreen = ['requestFullscreen','webkitRequestFullScreen','mozRequestFullScreen','msRequestFullscreen'], 
                    //fullscreenElement = ['fullscreenElement','webkitFullscreenElement','mozFullScreenElement','msFullscreenElement'], 
                    exitFullscreen    = ['exitFullscreen','webkitExitFullscreen','mozCancelFullScreen','msExitFullscreen'],
                    is_fullscreen = function(){ return ( document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null ) };
                if ( ! is_fullscreen() ) {
                    $.each(requestFullscreen, function(i,v){
                        if ( document.documentElement[v] ) {
                            document.documentElement[v]();
                            return false;
                        }
                    });
                    $(this).children('span').attr('class', 'plt-shrink2');
                } else {
                    $.each(exitFullscreen, function(i,v){
                        if ( document[v] ) {
                            document[v]();
                            return false;
                        }
                    });
                    $(this).children('span').attr('class', 'plt-enlarge2');
                }
                break;
            case 'lock':
                PNotify.error('Undefined action, yet.');
                break;
            case 'signout':
                location.href = ev.currentTarget.href;
                break;
        }
    });
    
    // all links as location.href
    $BODY.filter(function(){ return $(this).hasClass('logged-in'); }).find('a').on('click', function(ev) {
        if ( ! is_empty( $(this).attr('href') ) && ! /^(#.*|javascript\:.*)$/.test( $(this).attr('href') ) ) {
            // ev.preventDefault();
            showLoading();
        }
    });
    
});
// /Sidebar

// Panel toolbox
$(document).ready(function() {
    $('.collapse-link').on('click', function() {
        var $BOX_PANEL = $(this).closest('.x_panel'),
            $ICON = $(this).find('i'),
            $BOX_CONTENT = $BOX_PANEL.find('.x_content');
        
        // fix for some div with hardcoded fix class
        if ($BOX_PANEL.attr('style')) {
            $BOX_CONTENT.slideToggle(200, function(){
                $BOX_PANEL.removeAttr('style');
            });
        } else {
            $BOX_CONTENT.slideToggle(200); 
            $BOX_PANEL.css('height', 'auto');  
        }

        $ICON.toggleClass('fa-chevron-up fa-chevron-down');
    });

    $('.close-link').click(function () {
        var $BOX_PANEL = $(this).closest('.x_panel');

        $BOX_PANEL.remove();
    });
});
// /Panel toolbox

// Tooltip
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });
});
// /Tooltip

// Progressbar
$(document).ready(function() {
	if ($(".progress .progress-bar")[0]) {
	    $('.progress .progress-bar').progressbar();
	}
});
// /Progressbar

// Switchery
$(document).ready(function() {
    if ($(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#26B99A'
            });
            // console.info( $(html).removeAttr('style') );
        });
    }
});
// /Switchery

// iCheck
$(document).ready(function() {
    if ($("input.flat")[0]) {
        $(document).ready(function () {
            $('input.flat').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    }
});
// /iCheck

// Table
$('table input').on('ifChecked', function () {
    checkState = '';
    $(this).parent().parent().parent().addClass('selected');
    countChecked();
});
$('table input').on('ifUnchecked', function () {
    checkState = '';
    $(this).parent().parent().parent().removeClass('selected');
    countChecked();
});

var checkState = '';

$('.bulk_action input').on('ifChecked', function () {
    checkState = '';
    $(this).parent().parent().parent().addClass('selected');
    countChecked();
});
$('.bulk_action input').on('ifUnchecked', function () {
    checkState = '';
    $(this).parent().parent().parent().removeClass('selected');
    countChecked();
});
$('.bulk_action input#check-all').on('ifChecked', function () {
    checkState = 'all';
    countChecked();
});
$('.bulk_action input#check-all').on('ifUnchecked', function () {
    checkState = 'none';
    countChecked();
});

function countChecked() {
    if (checkState === 'all') {
        $(".bulk_action input[name='table_records']").iCheck('check');
    }
    if (checkState === 'none') {
        $(".bulk_action input[name='table_records']").iCheck('uncheck');
    }

    var checkCount = $(".bulk_action input[name='table_records']:checked").length;

    if (checkCount) {
        $('.column-title').hide();
        $('.bulk-actions').show();
        $('.action-cnt').html(checkCount + ' Records Selected');
    } else {
        $('.column-title').show();
        $('.bulk-actions').hide();
    }
}

// Accordion
$(document).ready(function() {
    $(".expand").on("click", function () {
        $(this).next().slideToggle(200);
        $expand = $(this).find(">:first-child");

        if ($expand.text() == "+") {
            $expand.text("-");
        } else {
            $expand.text("+");
        }
    });
});

// NProgress
if (typeof NProgress != 'undefined') {
    //$(document).ready(function () {
    $(window).on('unload',function() {
        NProgress.start();
    });

    $(window).on('load', function() {
        NProgress.done();
    });
}

// PNotify 4.0.0
if ( typeof PNotify != 'undefined' ) {
  PNotify.defaults.styling = "bootstrap3";
  PNotify.defaults.icons = "fontawesome4";
  var notify = function ( headline, message, notify_type, notice_code, add_class ) {
    var icon_class = true;
    add_class = ! is_empty( add_class ) ? add_class : '';
    switch ( notify_type ) {
      case 'notice':
        icon_class = false; // 'fa fa-bell';
        break;
      case 'info':
        icon_class = 'fa fa-info-circle';
        break;
      case 'success':
        icon_class = 'fa fa-check-circle';
        break;
      case 'error':
        icon_class = 'fa fa-exclamation-triangle';
        message += ' <small class="notice-code">' + notice_code + '</small>';
        break;
    }
    // Options
    var opts = {
      title: headline,
      text: message,
      textTrusted: true,
      addClass: add_class,
      cornerClass: '',
      autoDisplay: true,
      width: '360px',
      minHeight: '16px',
      type: notify_type, // 'notice','info','success','error'
      icon: icon_class,
      animation: 'fade',
      animateSpeed: 'normal', // 'slow'(400ms),'normal'(250ms),'fast'(100ms)
      shadow: true,
      hide: true,
      delay: 3500,
      mouseReset: true,
      remove: true,
      destroy: true,
      modules: {
        Buttons: {
          closer: false,
          sticker: false
        }
      }
    };
    var notice = PNotify.alert( opts );
    notice.on('click', function() {
      notice.close();
    });
  };
}

function showLoading() {
  if ( typeof PNotify != 'undefined' ) {
    var loading = PNotify.info({
      text: localize_messages.loading,
      icon: 'fa fa-spinner fa-pulse',
      addClass: 'plotter-loading',
      hide: false,
      width: '200px',
      stack: {
        'dir1': 'down',
        'firstpos1': 60,
        'modal': true,
        'overlayClose': false
      },
      modules: {
        Buttons: {
          closer: false,
          sticker: false
        }
      }
    });
  }
}

// Init localStorage
if ( ! window.localStorage ) {
  window.localStorage = {
    getItem: function ( sKey ) {
      if ( ! sKey || ! this.hasOwnProperty( sKey ) ) { return null; }
      return unescape( document.cookie.replace( new RegExp( "(?:^|.*;\\s*)" + escape( sKey ).replace( /[\-\.\+\*]/g, "\\$&" ) + "\\s*\\=\\s*((?:[^;](?!;))*[^;]?).*" ), "$1" ) );
    },
    key: function ( nKeyId ) {
      return unescape( document.cookie.replace( /\s*\=(?:.(?!;))*$/, "" ).split( /\s*\=(?:[^;](?!;))*[^;]?;\s*/ )[nKeyId] );
    },
    setItem: function ( sKey, sValue ) {
      if ( ! sKey ) { return; }
      document.cookie = escape( sKey ) + "=" + escape( sValue ) + "; expires=Tue, 19 Jan 2038 03:14:07 GMT; path=/";
      this.length = document.cookie.match(/\=/g).length;
    },
    length: 0,
    removeItem: function ( sKey ) {
      if ( ! sKey || ! this.hasOwnProperty( sKey ) ) { return; }
      document.cookie = escape( sKey ) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
      this.length--;
    },
    hasOwnProperty: function ( sKey ) {
      return ( new RegExp( "(?:^|;\\s*)" + escape( sKey ).replace( /[\-\.\+\*]/g, "\\$&" ) + "\\s*\\=" ) ).test( document.cookie );
    }
  };
  window.localStorage.length = ( document.cookie.match( /\=/g ) || window.localStorage ).length;
}

// Init sessionStorage
if ( ! window.sessionStorage ) {
  window.sessionStorage = {
    getItem: function ( sKey ) {
      if ( ! sKey || ! this.hasOwnProperty( sKey ) ) { return null; }
      return unescape( document.cookie.replace( new RegExp( "(?:^|.*;\\s*)" + escape( sKey ).replace( /[\-\.\+\*]/g, "\\$&" ) + "\\s*\\=\\s*((?:[^;](?!;))*[^;]?).*" ), "$1" ) );
    },
    key: function ( nKeyId ) {
      return unescape( document.cookie.replace( /\s*\=(?:.(?!;))*$/, "" ).split( /\s*\=(?:[^;](?!;))*[^;]?;\s*/ )[nKeyId] );
    },
    setItem: function ( sKey, sValue ) {
      if ( ! sKey ) { return; }
      document.cookie = escape( sKey ) + "=" + escape( sValue ) + "; expires=Tue, 19 Jan 2038 03:14:07 GMT; path=/";
      this.length = document.cookie.match(/\=/g).length;
    },
    length: 0,
    removeItem: function ( sKey ) {
      if ( ! sKey || ! this.hasOwnProperty( sKey ) ) { return; }
      document.cookie = escape( sKey ) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
      this.length--;
    },
    hasOwnProperty: function ( sKey ) {
      return ( new RegExp( "(?:^|;\\s*)" + escape( sKey ).replace( /[\-\.\+\*]/g, "\\$&" ) + "\\s*\\=" ) ).test( document.cookie );
    }
  };
  window.sessionStorage.length = ( document.cookie.match( /\=/g ) || window.sessionStorage ).length;
}

// is_empty()
function is_empty( _var ) {
  if ( _var == null ) {
    // typeof null -> object : for hack a bug of ECMAScript
    // Refer: https://developer.mozilla.org/ja/docs/Web/JavaScript/Reference/Operators/typeof
    return true;
  }
  switch( typeof _var ) {
    case 'object':
      if ( Array.isArray( _var ) ) {
        // When object is array:
        return ( _var.length === 0 );
      } else {
        // When object is not array:
        if ( Object.keys( _var ).length > 0 || Object.getOwnPropertySymbols(_var).length > 0 ) {
          return false;
        } else
        if ( _var.valueOf().length !== undefined ) {
          return ( _var.valueOf().length === 0 );
        } else
        if ( typeof _var.valueOf() !== 'object' ) {
          return is_empty( _var.valueOf() );
        } else {
          return true;
        }
      }
    case 'string':
      return ( _var === '' );
    case 'number':
      return ( _var == 0 );
    case 'boolean':
      return ! _var;
    case 'undefined':
    case 'null':
      return true;
    case 'symbol': // Since ECMAScript6
    case 'function':
    default:
      return false;
  }
}
// /is_empty()

// Convert serialized array ( eq. $.serializeArray() ) to key values type
function conv_kv( _array ) {
  var kv_object = {};
  for ( idx in _array ) {
    var key   = _array[idx]['name'];
    var value = _array[idx]['value'];
    kv_object[key] = value;
  }
  return kv_object;
}
// /conv_kv

// sprintf
function sprintf( format ) {
  var i = 0, j = 0, r = "", len = format.length, 
      next = function( args ) {
        j += 1; i += 1;
        return args[j] !== void 0 ? args[j] : "";
      };
  
  for( i = 0; i < len; i++ ) {
    if ( format.charCodeAt( i ) === 37 ) {
      switch ( format.charCodeAt( i + 1 ) ) {
        case 115:
          r += next( arguments );
          break;
        case 100:
          r += Number( next( arguments ) );
          break;
        default:
          r += format[i];
          break;
      }
    } else {
      r += format[i];
    }
  }
  return r;
}
// /sprintf

/* Closure : Ajax call corresponding to WP REST API
 * Usage : callAjax( ajax_url, method [, ...] )
 *
 * @param string ajax_url      (required)
 * @param string method        (required: "post" or "get")
 * @param mixed  post_data     (optional: default null)
 * @param string data_type     (optional: default "json")
 * @param string content_type  (optional: default "application/x-www-form-urlencoded")
 * @param string callback_func (optional: default null)
 * @param bool   debug_mode    (optional: default false)
 */
// var xhrResponse = {},
// dfd = new $.Deferred,
var callbackAjax = {
      notify: function ( data ) {
        if ( typeof notify != 'undefined' ) {
          if ( is_empty( data.action ) ) {
            // 即時通知（レスポンスを受け取った直後に通知を表示）
            notify( data.title, data.text, data.type, data.code, data.addclass );
          } else
          if ( 'stack' === data.action ) {
            // セッションストレージに通知をスタック後、カレントページをリロードする
            sessionStorage.setItem( 'stackNotify', JSON.stringify( data ) );
            location.reload( false );
          } else
          if ( 'dialog' === data.action ) {
            // 対話シーケンスを実行する
            dialog( data );
          } else {
            // セッションストレージに通知をスタック後、指定先へ遷移する
            sessionStorage.setItem( 'stackNotify', JSON.stringify( data ) );
            location.href = data.action;
          }
        }
      }
    },
    callAjax = function() {
      if ( arguments.length < 2 ) {
        return false;
      }
      var ajax_url      = arguments[0],
          method        = arguments[1],
          post_data     = ! is_empty( arguments[2] ) ? arguments[2] : null,
          data_type     = ! is_empty( arguments[3] ) ? arguments[3] : 'json',
          content_type  = ! is_empty( arguments[4] ) ? arguments[4] : 'application/x-www-form-urlencoded',
          callback_func = ! is_empty( arguments[5] ) ? arguments[5] : null,
          debug_mode    = ! is_empty( arguments[6] ) ? Boolean( arguments[6] ) : false,
          jqXHR = $.ajax({
            async: true,
            url:   ajax_url,
            type:  method,
            data:  post_data,
            dataType: data_type,
            contentType: content_type,
            cache: false,
            beforeSend: function( xhr, set ) {
              if ( debug_mode ) {
                console.log({ xhr: xhr, set: set });
              }
              if ( wpApiSettings ) {
                xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
              }
            }
          });

      jqXHR.done(function( data, stat, xhr ) {
        if ( debug_mode ) {
          console.log({ done: stat, data: data, xhr: xhr });
        }
        // Object.assign( xhrResponse, { 'responseText': jqXHR.responseText, 'status': jqXHR.status, 'statusText': jqXHR.statusText } );
        if ( 'script' === data_type ) {
          return data;
        }
        if ( ! is_empty( callback_func ) ) {
          if ( $.inArray( callback_func, Object.keys( callbackAjax ) ) !== -1 ) {
            return callbackAjax[callback_func]( data );
          } else {
            if ( debug_mode ) {
              console.error( 'Callback method "' + callback_func + '" does not exist.' );
            }
            return false;
          }
        }
      });

      jqXHR.fail(function( xhr, stat, err ) {
        if ( debug_mode ) {
          console.log({ fail: stat, error: err, xhr: xhr });
        }
      });

      jqXHR.always(function( res1, stat, res2 ) {
        if ( debug_mode ) {
          console.log({ always: stat, res1: res1, res2: res2 });
        }
        // dfd.resolve();
      });

      // for method chain on the jQuery
      return jqXHR;
    };
// /Closure

// Stack Notify
$(document).ready(function() {
  if ( sessionStorage.hasOwnProperty( 'stackNotify' ) ) {
    var data = JSON.parse( sessionStorage.getItem( 'stackNotify' ) );
    notify( data.title, data.text, data.type, data.code, data.addclass );
    sessionStorage.removeItem( 'stackNotify' );
  }
});
// /Stack Notify

// dialog() via Ajax
function dialog( data ) {
  var opts = {
    title: data.title,
    text: data.text,
    addClass: data.code,
    type: 'notice',
    icon: 'fa fa-question-circle',
    hide: false,
    stack: {
      'dir1': 'down',
      'modal': true,
      'firstpos1': 25
    },
    modules: {
      Confirm: {
        confirm: true,
        buttons: [{
          text: is_empty( data.extend.primary ) ? localize_messages.dialog_yes : data.extend.primary,
          textTrusted: false,
          addClass: '',
          primary: true,
          promptTrigger: true,
          click: (notice, value) => {
            notice.close();
            notice.fire('pnotify.confirm', {notice, value});
            if ( $.inArray( data.extend.callback, Object.keys( callbackAjax ) ) !== -1 ) {
              callbackAjax[data.extend.callback]( notice );
            }
          }
        }, {
          text: is_empty( data.extend.secondary ) ? localize_messages.dialog_no : data.extend.secondary,
          textTrusted: false,
          addClass: '',
          click: (notice) => {
            notice.close();
            notice.fire('pnotify.cancel', {notice});
          }
        }]
      },
      Buttons: {
        closer: false,
        sticker: false
      },
      History: {
        history: false
      }
    }
  };
  var dialog = PNotify.alert( opts );
}
// /dialog()

// Control Common Object on jQuery
$(document).ready(function() {
  
  var $TOPNAV_FORM = $('#topnav-form'),
      CURRENT_SOURCE_ID = Number( $TOPNAV_FORM.find('input[name="source_id"]').val() );
  
  // Top Navigation
  $('#topnav-switch-source').on('change', function(){
    var newSrcId = Number( $(this).val() );
    if ( newSrcId != CURRENT_SOURCE_ID ) {
      PNotify.alert({
        title: localize_messages.switch_src_ttl,
        text: localize_messages.switch_src_msg,
        addClass: '',
        type: 'notice',
        icon: 'fa fa-question-circle',
        hide: false,
        stack: {
          'dir1': 'down',
          'modal': true,
          'firstpos1': 25
        },
        modules: {
          Confirm: {
            confirm: true,
            buttons: [{
              text: localize_messages.dialog_yes,
              textTrusted: false,
              addClass: '',
              primary: true,
              promptTrigger: true,
              click: (notice, value) => {
                notice.close();
                // notice.fire('pnotify.confirm', {notice, value});
                docCookies.setItem( 'lastSource', newSrcId, 60*60*24*30, '/' );
                showLoading();
                location.reload();
              }
            }, {
              text: localize_messages.dialog_no,
              textTrusted: false,
              addClass: '',
              click: (notice) => {
                notice.close();
                // notice.fire('pnotify.cancel', {notice});
                $('#topnav-switch-source').children('option').each(function(){
                  if ( Number( $(this).val() ) == CURRENT_SOURCE_ID ) {
                    $(this).prop('selected', true);
                  } else {
                    $(this).prop('selected', false);
                  }
                });
              }
            }]
          },
          Buttons: {
            closer: false,
            sticker: false
          },
          History: {
            history: false
          }
        }
      });
    }
  });
  
});

// Lock & Unlock the submission buttons
function controlSubmission( action='lock', currentPermalink='', buttons=[] ) {
  buttons = typeof SUBMIT_BUTTONS != 'undefined' && SUBMIT_BUTTONS.length > 0 ? SUBMIT_BUTTONS : buttons;
  currentPermalink = is_empty( currentPermalink ) ? location.pathname.replace(/^\/(.*)\/$/, '$1') : currentPermalink;
  $.each( buttons, function(i,v) {
    $('#'+currentPermalink+'-btn-'+v).prop('disabled', ( 'lock' === action ) );
  });
}
