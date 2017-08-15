/**
 * Extended script
 */
$(document).ready(function() {
  
  var toggleFullScreen = function( instance ){
    // toggle full-screen browsing
    var d = document, isFullScreen = false;
    // console.info([ d.fullscreen, d.fullscreenElement, d.mozFullScreen, d.webkitIsFullScreen ]);
    // For Mozilla (firefox etc.)
    if ( ! d.mozFullScreen ) {
      if ( instance.mozRequestFullScreen ) {
        instance.mozRequestFullScreen();
        isFullScreen = true;
      }
    } else {
      if ( d.mozCancelFullScreen ) {
        d.mozCancelFullScreen();
        isFullScreen = false;
      }
    }
    // For Webkit (Chrome, Safari, Opera, Edge etc.)
    if ( ! d.webkitIsFullScreen ) {
      if ( instance.webkitRequestFullscreen ) {
        instance.webkitRequestFullscreen();
        isFullScreen = true;
       }
    } else {
      if ( d.webkitExitFullscreen ) {
        d.webkitExitFullscreen();
        isFullScreen = false;
      }
    }
    // Others
    if ( ! d.fullscreenElement ) {
      if ( instance.requestFullscreen ) {
        instance.requestFullscreen();
        isFullScreen = true;
      }
    } else {
      if ( d.exitFullscreen ) {
        d.exitFullscreen();
        isFullScreen = false;
      }
    }
    return isFullScreen;
  }
  
  $('.sidebar-footer').find('a').on('click', function(){
    var SIDEBAR_FOOTER_MENU = $(this).data('originalTitle').toLowerCase();
    switch (SIDEBAR_FOOTER_MENU) {
      case 'fullscreen':
        var elem = $('body');
        elem.css({ width: '100%', height: '100%', overflow: 'hidden' });
        var instance = elem[0],
            isFullScreen = toggleFullScreen( instance );
        if ( isFullScreen ) {
          $(this).children('span').attr('class', 'fa fa-minus-square-o'); /* fa-window-maximize */
        } else {
          elem.css({ width: '100%', height: 'auto', overflow: 'visible' });
          $(this).children('span').attr('class', 'glyphicon glyphicon-fullscreen');
        }
        break;
      default:
        break;
    }
  });
  
  $('.nav.child_menu> li> a').on('click', function(){
    var $CURRENT_CHILD_MENU = $(this).parent();
    $CURRENT_CHILD_MENU.toggleClass('active');
    $('#registerWidget').on('hidden.bs.modal', function(e) {
      $CURRENT_CHILD_MENU.removeClass('active');
    });
  });
  
  
});
/**
 * For DEMO scripts
 */
$(document).ready(function() {
  
  
  
  
});
