/**
 * For Messages (/messages/)
 */
'use strict';
$(document).ready(function() {
  
  var gf                = $("#messengerForm"),
      wss               = window.sessionStorage,
      currentPermalink  = 'messages',
      tagsinputElement  = $('#selected-sendto-ids'),
      maxMessageLength  = Number( $('.count-strings').attr( 'data-max-length' ) );
  SUBMIT_BUTTONS   = [ 'send-message' ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  clearSessionData();
  adjustMessagesList();
  initHistory();
  initializeCounts();
  tagsinputElement.tagsinput({
    itemValue: 'id',
    itemText: 'name'
  });
  
  // ----- Event handlers -------------------------------------------------------------------------
  
  /*
   * Forcibly reload the message ignoring the default interval (:> 既定間隔を無視して強制的にメッセージをリロードする
   */
  $('.btn-to-reload').on( 'click', function() {
    $(this).find('i.plt-loop3').attr( 'class', 'fa plt-loop3 fa-spin' );
    docCookies.setItem( 'latest_messages', '0', 60*60*24*30, '/', 'plotter.me', 1 );
    showLoading();
    location.reload( true );
  });
  
  /*
   * Fire just after changing a tab (:> タブ切り替え直後のイベント
   */
  $('#messageTab a').on( 'shown.bs.tab', function(e) {
    adjustMessagesList();
  });
  
  /*
   *
   */
  $('.btn-to-read, .btn-to-unread').on( 'click', function() {
    var targetMsgId = $(this).closest('li').attr( 'data-msg-id' ),
        fromStatus  = $(this).hasClass('btn-to-read') ? 'unread' : 'read',
        postDataRaw = conv_kv( gf.serializeArray() );
    postDataRaw.post_action  = 'replace';
    postDataRaw.msg_id       = targetMsgId;
    postDataRaw.from         = fromStatus;
    postDataRaw.selected_tab = $('#messageTab li.active').index();
    delete postDataRaw.to_team;
    delete postDataRaw.to_user;
    delete postDataRaw.parent_id;
    delete postDataRaw.subject;
    delete postDataRaw.content;
    delete postDataRaw.user_content;
    var postData = JSON.stringify( postDataRaw );
    $(this).closest('li').fadeOut('fast').queue(function(){
        this.remove();
        showLoading();
    });
    // ajaxでpost
    callAjax(
      '/'+currentPermalink+'/',
      'post',
      postData,
      'script',
      'application/json; charset=utf-8',
      null,
      true
    );
  });
  
  
  /*
   * Clicked Send Message button (:> メッセージ送信ボタン押下時
   */
  $('#admin-send-message').on('click', function(e){
    gf.find('input[name="post_action"]').val( 'send_message' );
    var postDataRaw = conv_kv( gf.serializeArray() );
    if ( ! validatePostData( postDataRaw ) ) {
      return false;
    }
    // if ( ! debugPostData( postDataRaw ) ) return false; /* ***** For debug, it shows summary on the console before posting data. ****** */
    var postData = JSON.stringify( postDataRaw );
    showLoading();
    // ajaxでpost
    callAjax(
      '/'+currentPermalink+'/',
      'post',
      postData,
      'json',
      'application/json; charset=utf-8',
      'notify',
      true
    );
  });

  /*
   * Show the remain number of characters inputable message (:> 入力可能なメッセージ本文の残り文字数を表示
   */
  $('#message_content').on( 'keydown keyup keypress change', function(){
    var thisValueLength = $(this).val().length,
        countDown       = maxMessageLength - thisValueLength;
    $('.count-strings').text( countDown );
    if ( countDown < 0 ) {
      $('.remain-count').addClass( 'red' );
    } else {
      $('.remain-count').removeClass( 'red' );
    }
  });

  /*
   * Changed Message Type (:> メッセージ種類を変更した時のイベント
   */
  $('input[id^="message_type"]').on('click', function(e){
    var checkType = Number( $('input[name="type"]:checked').val() ),
        typeArray = [ 'broadcast', 'private', 'public' ];
    switch( typeArray[checkType] ) {
      case 'broadcast':
        $('#message_to_user').val( '0' );
        $('#message_to_team').val( '' );
        $('#fixed-sendto-id').removeClass( 'hide' );
        $('#sendto-id-finder').addClass( 'hide' );
        $('#expiration_date').val( '' ).prop( 'disabled', true );
        break;
      case 'public':
        $('#message_to_user').val( '0' );
        $('#message_to_team').val( '' );
        $('#fixed-sendto-id').removeClass( 'hide' );
        $('#sendto-id-finder').addClass( 'hide' );
        $('#expiration_date').prop( 'disabled', false );
        break;
      case 'private':
        $('#message_to_user').val( '' );
        $('#message_to_team').val( '' );
        $('#fixed-sendto-id').addClass( 'hide' );
        $('#sendto-id-finder').removeClass( 'hide' );
        $('#expiration_date').val( '' ).prop( 'disabled', true );
        break;
    }
  });

  /*
   * 
   */
  $('#find-sendto-id').on( 'click', function(e){
    var findIdType = $('#find-id-type').children(':selected').text(),
        findId     = $('#find-id').val(),
        findedName = '';
    alert( sprintf( 'Finding specified %s ID: %s', findIdType, findId ) );
    // find via ajax
    findedName = findIdType + findId;
    
    tagsinputElement.tagsinput( 'add', { id: findId, name: findedName } );
    $('#find-id').val('');
  });

  /*
   *  (:> 画面内の各種キーイベントの制御
   * /
  $(document).on( 'keydown', 'body', function(e){
    var evt = e.originalEvent;
    // Enterキーによるフォームのフォーカス変更
    if ( evt.key === 'Enter' ) {
      e.preventDefault();
      var availableFields = gf.find('.form-control'),
          currentFieldId  = evt.target.id,
          nextFieldIndex  = 0;
      logger.debug( evt, availableFields, currentFieldId );
      if ( currentFieldId === 'user-send-message' ) {
        $('#user-send-message').trigger('click');
      } else {
        availableFields.each(function( i ){
          if ( $(this)[0].id === currentFieldId ) {
            nextFieldIndex = i + 1 < availableFields.length ? i + 1 : 0;
            return false;
          }
        });
        $(availableFields[nextFieldIndex]).trigger('click').trigger('focus');
      }
    }
  });
  */

  /*
   * Fire on resize window (:> ウィンドウリサイズ時のイベント
   */
  $(window).resize(function(){
    //
  });
  
  
  // ----- 個別処理（関数）------------------------------------------------------------------------
  
  /*
   * Replace a history state (:> 
   */
  function initHistory() {
    if ( 'default_tab' === history.state || ! is_empty( $.QueryString.tab ) ) {
      history.replaceState( 'defautl_tab', '', location.pathname );
      if ( $.QueryString.tab ) {
        $('#messageTab li').eq( $.QueryString.tab ).children('a').trigger( 'click' );
      }
    }
  }
  
  /*
   * Adjust Messages List as auto scrolling (:> 
   */
  function adjustMessagesList() {
    if ( $('#messageTabContent').length > 0 ) {
      var msgList = $('#messageTabContent');
      msgList.animate({
        scrollTop: msgList[0].scrollHeight
      }, 100 );
    }
  }
  
  /*
   * Validation
   */
  function validatePostData( postData ) {
    if ( is_empty( postData.type ) ) {
      $('#message_type').closest('.form-group').addClass('has-error');
    } else {
      $('#message_type').closest('.form-group').removeClass('has-error');
    }
    if ( is_empty( postData.to_user ) && is_empty( postData.to_team ) ) {
      $('#selected-sendto-ids').closest('.form-group').addClass('has-error');
    } else {
      $('#selected-sendto-ids').closest('.form-group').removeClass('has-error');
    }
    if ( is_empty( postData.content ) || postData.content.length > maxMessageLength ) {
      $('#message_content').closest('.form-group').addClass('has-error has-feedback');
      $('#message_content_feedback').removeClass('hide');
    } else {
      $('#message_content').closest('.form-group').removeClass('has-error has-feedback');
      $('#message_content_feedback').addClass('hide');
    }
    return gf.find('.form-group.has-error').length == 0;
  }
  
  /*
   * Initialize the remain number of characters inputable message (:> メッセージ残文字数表示の初期化
   */
  function initializeCounts() {
    if ( $('#message_content').length > 0 ) {
      var currentLength = $('#message_content').val().length;
      $('.count-strings').text( maxMessageLength - currentLength );
    }
  }
  
  
  // ----- セッションストレージ関連 ---------------------------------------------------------------
  
  /*
   *  (:> セッションストレージ初期化
   */
  function clearSessionData() {
    logger.debug( 'Clear All SessionStorage' );
    wss.clear();
  }
  
  
  /*
   * Code for debugging (:> デバッグ用コード
   */
  function debugPostData( postdata ) {
    var is_thru_after = false; // その後の処理を止めたい場合はfalseを指定する
    if ( Object.prototype.toString.call( postdata ) !== '[object Object]' ) {
      return is_thru_after;
    }
    for ( var prop in postdata ) {
      console.log( sprintf( 'ParamName: %s = "%s";', prop, postdata[prop] ) );
    };
    return is_thru_after;
  }
  
  
});