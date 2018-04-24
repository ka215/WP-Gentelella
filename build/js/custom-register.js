/**
 * For Register script (/register/)
 */
'use strict';
$(document).ready(function() {
  
  var gf               = $("#registerform"),
      wls              = window.localStorage,
      currentPermalink = 'register';
  SUBMIT_BUTTONS       = [ 'register' ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  checkNotify();
  
  // ----- Event handlers -------------------------------------------------------------------------
  
  /*
   * Toggle view of password field (:> パスワード入力欄の表示切替
   */
  $('#toggle-password').on('click', function(){
    var pwdFld = gf.find( '#pass1' );
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
  
  /*
   * Clicked register button (:> 登録ボタン押下時の処理
   */
  gf.find('#wp-submit').on('click', function(e){
    e.preventDefault();
    var nameFld = gf.find( '#user_name'),
        pwdFld  = gf.find( '#pass1' ),
        cfmFld  = gf.find( '#pass2' ),
        uname   = nameFld.val(),
        passwd  = pwdFld.val(),
        errMsg  = '';
    if ( uname.length < 3 ) {
      errMsg = localize_messages.shorter_username;
    } else
    if ( ! is_empty( passwd ) && passwd.length > 5 ) {
      cfmFld.val( passwd );
      gf.submit();
    } else {
      errMsg = localize_messages.shorter_passwd;
    }
    if ( ! is_empty( errMsg ) ) {
      return notify( localize_messages.error, errMsg, 'error', '', '' );;
    }
  });
  
  // ----- 個別処理（関数）------------------------------------------------------------------------
  
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
                primary: true,
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
    }
  }
  
  
  // ----- WEBストレージ(ローカルストレージ)関連 ---------------------------------------------------------------
  
  
  
});