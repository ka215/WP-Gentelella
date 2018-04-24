/**
 * For Profile scripts (/profile/)
 */
'use strict';
$(document).ready(function() {
  
  var gf               = $("#your-profile"),
      wls              = window.localStorage,
      currentPermalink = 'profile';
  SUBMIT_BUTTONS       = [ 'submit', 'assign_avatar', 'remove_avatar' ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  checkNotify();
  
  // ----- Event handlers -------------------------------------------------------------------------
  
  $('#generate-pw').on('click', function(){
    $(this).addClass( 'hide' );
    $('#pass1').val( $('#pass1').attr( 'data-pw' ) );
    $('#pass1').addClass('hide').prop( 'disabled', false );
    $('#pass1-text').val( $('#pass1').attr( 'data-pw' ) );
    $('#pass1-text').removeClass('hide').prop( 'disabled', false );
    $('#passwd-ctrl').removeClass( 'hide' );
  });
  
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
  
  $('#cancel-passwd').on('click', function(){
    $('#pass1').val('').prop( 'disabled', true );
    $('#pass1-text').val('').prop( 'disabled', true );
    $('#passwd-ctrl').addClass( 'hide' );
    $('#generate-pw').removeClass( 'hide' );
  });
  
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
        $('#preview-image').css( 'background-image', 'url('+ this.result +')' ).removeClass( 'hide' );
      }
    }
  });
  
  
  // ----- 個別処理（関数）------------------------------------------------------------------------
  
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
        PNotify.alert( opts );
      }
      $notice.empty();
    }
  }
  
  
  // ----- WEBストレージ(ローカルストレージ)関連 ---------------------------------------------------------------
  
  
  
});