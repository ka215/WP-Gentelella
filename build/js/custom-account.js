/**
 * For Signin scripts (/account/)
 */
'use strict';
$(document).ready(function() {
  
  var gf               = $("#loginform"),
      wls              = window.localStorage,
      currentPermalink = 'account';
  SUBMIT_BUTTONS       = [ 'signin' ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  initHistory();
  checkNotify();
  
  // ----- Event handlers -------------------------------------------------------------------------
  
  /*
   * Toggle view of password field (:> パスワード入力欄の表示切替
   */
  $('#toggle-password').on('click', function(){
    var pwdFld = gf.find( '#user_pass' );
    if ( pwdFld.attr( 'type' ) === 'password' ) {
      $(this).attr( 'title', localize_messages.hide_passwd ).addClass( 'shown' );
      $(this).children('i').attr( 'class', 'plt-eye-blocked' );
      pwdFld.attr( 'type', 'text' );
    } else {
      $(this).attr( 'title', localize_messages.show_passwd ).removeClass( 'shown' );
      $(this).children('i').attr( 'class', 'plt-eye' );
      pwdFld.attr( 'type', 'password' );
    }
  });
  
  
  // ----- 個別処理（関数）------------------------------------------------------------------------
  
  /*
   * Replace a history state in browser after logged out (:> サインアウト後はブラウザ履歴ステータスを置き換える
   * ※ ?redirect_to=~ クエリによるリロード時の再通知表示を抑止するため
   */
  function initHistory() {
    if ( 'signed_out' === history.state || '1' === $.QueryString.reauth || 'true' === $.QueryString.loggedout ) {
      history.replaceState( 'signed_out', '', location.pathname );
    }
  }
  
  /*
   * Retrieve system notice, then show notify (:> システム通知を取得して表示する
   */
  function checkNotify() {
    var $notice = $('.account-notice');
    if ( $notice.children().length > 0 ) {
      var noticeType    = $notice.children('p').attr('class'),
          noticeTitle   = $notice.find('strong').text(),
          noticeMessage = '';
      $notice.children('p').find('strong').remove();
      noticeMessage = $notice.children('p')[0].innerHTML.trim().replace( /(<([^>]+)>)|\:/ig, '' );
      if ( 'error' === noticeType  ) {
        // noticeTitle = is_empty( noticeTitle ) ? localize_messages.error : noticeTitle;
        noticeTitle = localize_messages.error;
        notify( noticeTitle, noticeMessage, 'error', '', '' );
      } else {
        var opts = {
          title: is_empty( noticeTitle ) ? localize_messages.confirmation : noticeTitle,
          text: noticeMessage,
          textTrusted: true,
          addClass: '',
          type: 'info',
          icon: 'plt-info',
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
                primary: false,
                promptTrigger: true,
                click: (notice, value) => {
                  notice.close();
                  notice.fire('pnotify.confirm', {notice, value});
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
        PNotify.alert( opts );
      }
      $notice.empty();
    }
  }
  
  
  // ----- WEBストレージ(ローカルストレージ)関連 ---------------------------------------------------------------
  
  
  
});