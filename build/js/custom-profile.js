/**
 * For Profile scripts (/profile/)
 */
'use strict';
$(document).ready(function() {
  
  var gf               = $("#your-profile"),
      wls              = window.localStorage,
      currentPermalink = 'profile';
  SUBMIT_BUTTONS       = [ 'submit', 'assign_avatar', 'remove_avatar', 'delete_account' ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  initHistory();
  checkNotify();
  initFields();
  
  // ----- Event handlers -------------------------------------------------------------------------
  
  /*
   * Clicked Generate Password button (:> パスワード生成ボタン押下時
   */
  $('#generate-pw').on('click', function(){
    $(this).addClass( 'hide' );
    $('#pass1').val( $('#pass1').attr( 'data-pw' ) );
    $('#pass1').addClass('hide').prop( 'disabled', false );
    $('#pass1-text').val( $('#pass1').attr( 'data-pw' ) );
    $('#pass1-text').removeClass('hide').prop( 'disabled', false );
    $('#passwd-ctrl').removeClass( 'hide' );
  });
  
  /*
   * Toggled view of password field (:> パスワード入力欄の表示切替
   */
  $('#toggle-passwd').on('click', function(){
    var shownPasswd = Number( $(this).attr( 'data-toggle' ) ) == 0;
    if ( shownPasswd ) {
      // to hide
      $('#pass1-text').addClass('hide');
      $('#pass1').removeClass('hide');
      $(this).attr( 'data-toggle', 1 ).attr( 'aria-label', localize_messages.hide_passwd );
      $(this).children('i').attr( 'class', 'plt-eye' );
      $(this).children('.text').text( localize_messages.show );
    } else {
      // to show
      $('#pass1').addClass('hide');
      $('#pass1-text').removeClass('hide');
      $(this).attr( 'data-toggle', 0 ).attr( 'aria-label', localize_messages.show_passwd );
      $(this).children('i').attr( 'class', 'plt-eye-blocked' );
      $(this).children('.text').text( localize_messages.hide );
    }
  });
  
  /*
   * Clicked　Cancel button (:> （パスワード変更の）キャンセルボタン押下時
   */
  $('#cancel-passwd').on('click', function(){
    $('#pass1').val('').trigger( 'click' );
    $('#pass1-text').val('').trigger( 'click' );
    $('#pass1').prop( 'disabled', true );
    $('#pass1-text').prop( 'disabled', true );
    $('#passwd-ctrl').addClass( 'hide' );
    $('#generate-pw').removeClass( 'hide' );
    $('#pw_weak').prop( 'checked', false );
    $('.pw-weak').addClass( 'hide' );
    $('#pwd-strength-notice').children('.label').remove();
  });
  
  /*
   * Handling Password Strength Validation (:> パスワード強度検証処理のハンドリング
   */
  $('#pass1, #pass1-text').on('click keypress keyup focus blur', function(){
    var pwdStrength = $('#pass-strength-result').text(),
        pwdStrClass = $('#pass-strength-result').attr( 'class' ),
        labelClass  = '',
        isWeakPass  = false;
    switch( pwdStrClass ) {
      case 'strong':
      case 'good':
        labelClass = 'label label-success';
        isWeakPass = false;
        break;
      case 'bad':
        labelClass = 'label label-warning';
        isWeakPass = true;
        break;
      case 'short':
        labelClass = 'label label-danger';
        isWeakPass = true;
        break;
    }
    $('#pwd-strength-notice').children('.label').remove();
    if ( labelClass !== '' ) {
      var $label = $( '<div>', { class: labelClass } );
      $label.text( pwdStrength );
      $('#pwd-strength-notice').append( $label[0].outerHTML );
    }
    if ( isWeakPass ) {
      $('.pw-weak').removeClass( 'hide' );
    } else {
      $('.pw-weak').addClass( 'hide' );
    }
  });
  
  /*
   * Choose avatar image to upload (:> アップロードするアバター画像を選択時の処理
   */
  $(document).on('change', ':file', function() {
    var input    = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label    = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    $('#preview-upfile').val( label );

    var files = !!this.files ? this.files : [];
    if ( ! files.length || ! window.FileReader ) {
      // no file selected, or no FileReader support
      return;
    }
    if ( /^image/.test( files[0].type ) ){ // only image file
      var reader = new FileReader(); // instance of the FileReader
      reader.readAsDataURL( files[0] ); // read the local file
      reader.onloadend = function(){ // set image data as background of div
        var $avatarViewer = $('#avatar-viewer'),
            $originAvatar = $avatarViewer.find('img.avatar'),
            avatarWidth   = $originAvatar[0].offsetWidth;
        if ( $avatarViewer.width() > avatarWidth * 2 ) {
          var $previewAvatar    = $( '<img>', { class: $originAvatar.attr('class'), src: this.result } ),
              $previewContainer = $avatarViewer.find('.preview-container');
          $previewContainer.empty().append( $previewAvatar[0].outerHTML ).removeClass( 'hide' );
        }
      }
    }
  });
  
  /*
   * Adjust avatar preview container if window resized (:> ウィンドウリサイズ時にアバタープレビュー欄を調整
   */
  $(window).resize(function(){
    var $avatarViewer     = $('#avatar-viewer'),
        $originAvatar = $avatarViewer.find('img.avatar'),
        $previewContainer = $avatarViewer.find('.preview-container'),
        avatarWidth       = $originAvatar[0].offsetWidth;
    if ( $avatarViewer.width() > avatarWidth * 2 ) {
      if ( $previewContainer.hasClass( 'hide' ) ) {
        $previewContainer.removeClass( 'hide' );
      }
    } else {
      $previewContainer.addClass( 'hide' );
    }
  });
  
  /*
   * Clicked Remove Avatar button (:> アバター削除ボタン押下時
   */
  $('#custom-avatar-remove').on('click', function(e){
    e.preventDefault();
    var $removeAction  = $('#remove-avatar-action'),
        $currentAvatar = $('#avatar-viewer').find('img.avatar'),
        noAvatarSrc    = '/assets/uploads/no-avatar.png';
    if ( 'false' === $removeAction.val() ) {
      // enable Remove
      $removeAction.val( 'true' );
      $(this).children('i').attr( 'class', 'plt-checkbox-checked2 green' );
      $('#custom-avatar-assign').val('').prop( 'disabled', true );
      $('#preview-upfile').val('');
      $('#avatar-viewer').find('.preview-container').addClass('hide').empty();
      var $noAvatar = $currentAvatar.clone();
      $noAvatar.attr( 'src', noAvatarSrc ).addClass('no-avatar-image');
      $currentAvatar.addClass( 'hide' );
      $('#avatar-viewer').prepend( $noAvatar[0].outerHTML );
    } else {
      // disable Remove
      $removeAction.val( 'false' );
      $(this).children('i').attr( 'class', 'plt-checkbox-unchecked2 gray' );
      $('#custom-avatar-assign').prop( 'disabled', false );
      $('#avatar-viewer').find('.no-avatar-image').remove();
      $currentAvatar.removeClass('hide');
    }
  });
  
  /*
   * Clicked Delete Account button (:> アカウント削除ボタン押下時
   */
  $('#delete-account').on('click', function(e){
    e.preventDefault();
    dialogOpts.title = localize_messages.delete_account_ttl;
    dialogOpts.text  = [ localize_messages.delete_account_msg, localize_messages.are_you_sure ].join('<br>');
    dialogOpts.modules.Confirm.buttons[0].click = (notice, value) => {
      notice.close();
      // 確認ダイアログ承認後にユーザ削除処理を呼ぶ
      var addField = $( '<input>', { type: 'hidden', name: '_delete_account_nonce', value: $(this).attr( 'data-nonce' ) } );
      gf.append( addField[0].outerHTML );
      controlSubmission();
      showLoading();
      gf.find('#submit').trigger( 'click' );
    };
    PNotify.alert( dialogOpts );
    
  });
  
  
  // ----- 個別処理（関数）------------------------------------------------------------------------
  
  /*
   * Replace a history state in browser after profile is updated (:> プロフィール更新後はブラウザ履歴ステータスを置き換える
   * ※ ?updated=true クエリによるリロード時の再通知表示を抑止するため
   */
  function initHistory() {
    if ( 'updated_profile' === history.state || ( 'updated' in $.QueryString && 'true' === $.QueryString.updated ) ) {
      history.replaceState( 'updated_profile', '', location.pathname );
    }
  }
  
  /*
   * Retrieve system notice, then show notify (:> システム通知を取得して表示する
   */
  function checkNotify() {
    var $notice = $('.profile-notice');
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
        var notice = PNotify.alert( opts );
        /*
        notice.on('pnotify.confirm', function( notice, value ){
          //
        });
        */
      }
      $notice.empty();
    }
  }
  
  /*
   * Initialize avatar preview (:> アバタープレビューを初期化
   */
  function initFields() {
    $('#preview-upfile').val('');
  }
  
  
  // ----- WEBストレージ(ローカルストレージ)関連 ---------------------------------------------------------------
  
  // None on this page
  
});