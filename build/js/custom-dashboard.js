/**
 * For Dashboard (/dashboard/)
 */
'use strict';
$(document).ready(function() {
  
  var gf                = $("#initialSettings"),
      wss               = window.sessionStorage,
      currentPermalink  = 'dashboard';
  SUBMIT_BUTTONS   = [  ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  initHistory();
  clearSessionData();
  showUserPolicy();
  
  // ----- Event handlers -------------------------------------------------------------------------
  
  /*
   * Clicked Unapprove button on User Policy modal (:> 利用規約の「承認しない」押下時
   */
  $(document).on('click', '#unapprove-user-policy', function(e){
    $('.main_container').fadeOut( 'fast', function(){
      showLoading();
    });
    location.href = $(this).attr( 'data-redirect-url' );
  });

  /*
   * Clicked Approve button on User Policy modal (:> 利用規約の「承認する」押下時
   */
  $(document).on('click', '#approve-user-policy', function(e){
    $('#user-policy').on('hidden.bs.modal', function(){
      gf.find('input[name="post_action"]').val( 'approve' );
      var post_data = JSON.stringify( conv_kv( gf.serializeArray() ) );
      // showLoading();
      // ajaxでpost
      callAjax(
        '/'+currentPermalink+'/',
        'post',
        post_data,
        'script',
        'application/json; charset=utf-8',
        null,
        true
      );
    });
    $('#user-policy').modal('hide');
  });

  /*
   * Clicked link in body of User Policies (:> 利用規約本文中のリンクをクリック時
   */
  $('#user-policy-container a').on('click', function(e){
    e.preventDefault();
    var toURL = $(this).attr('href') + '?from=/dashboard/';
    location.href = toURL;
  });

  /*
   * Clicked Register button (:> 登録ボタン押下時
   */
  $('#dashboard-register').on('click', function(e){
    gf.find('input[name="post_action"]').val( 'regist' );
    var postDataRaw = conv_kv( gf.serializeArray() );
    if ( ! validatePostData( postDataRaw ) ) {
      return false;
    }
    var post_data = JSON.stringify( postDataRaw );
    showLoading();
    // ajaxでpost
    callAjax(
      '/'+currentPermalink+'/',
      'post',
      post_data,
      'json',
      'application/json; charset=utf-8',
      'notify',
      true
    );
  });

  /*
   *  (:> 画面内の各種キーイベントの制御
   */
  $(document).on( 'keydown', 'body', function(e){
    var evt = e.originalEvent;
    // Enterキーによるフォームのフォーカス変更
    if ( evt.key === 'Enter' ) {
      e.preventDefault();
      var availableFields = gf.find('.form-control'),
          currentFieldId  = evt.target.id,
          nextFieldIndex  = 0;
      logger.debug( evt, availableFields, currentFieldId );
      if ( currentFieldId === 'dashboard-register' ) {
        $('#dashboard-register').trigger('click');
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

  /*
   * Fire on resize window (:> ウィンドウリサイズ時のイベント
   */
  $(window).resize(function(){
    //
  });
  
  
  // ----- 個別処理（関数）------------------------------------------------------------------------
  
  /*
   * Replace a history state in browser after signined (:> サインイン後はブラウザ履歴ステータスを置き換える
   */
  function initHistory() {
    if ( 'signined' === history.state || '#signin' === location.hash ) {
      history.replaceState( 'signined', '', location.pathname );
    }
  }
  
  /*
   * Show User Policy (:> 利用規約の表示
   */
  function showUserPolicy() {
    if ( $('#user-policy').length == 0 ) {
      return;
    }
    
    $('#user-policy').modal({
      keyboard: false,
      backdrop: 'static',
    });
  }
  
  /*
   * Validate post data before sending (:> 送信前のデータ検証
   */
  function validatePostData( data ) {
    var countError = 0;
    for ( var _k in data ) {
      var targetField = gf.find('[name="'+ _k +'"]');
      if ( targetField.prop( 'required' ) ) {
        if ( is_empty( data[_k] ) ) {
          // empty error
          targetField.parent().append( '<span class="plt-warning form-control-feedback"></span>' );
          targetField.closest('.form-group').addClass('has-error has-feedback');
          countError++;
        } else {
          // ok
          targetField.parent().find('span.form-control-feedback').remove();
          targetField.closest('.form-group').removeClass('has-error has-feedback');
        }
      }
    }
    return countError == 0;
  }
  
  
  // ----- セッションストレージ関連 ---------------------------------------------------------------
  
  /*
   *  (:> セッションストレージ初期化
   */
  function clearSessionData() {
    logger.debug( 'Clear All SessionStorage, then set data of current steps' );
    wss.clear();
  }
  
});