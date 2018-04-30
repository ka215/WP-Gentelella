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
      var post_data = JSON.stringify( conv_kv( gf.serializeArray() ) );
      // showLoading();
      // ajaxでpost
      callAjax(
        '/'+currentPermalink+'/',
        'post',
        post_data,
        'script',
        'application/json; charset=utf-8',
        'location.reload()',
        true
      );
      
    });
    $('#user-policy').modal('hide');
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
  
  // ----- セッションストレージ関連 ---------------------------------------------------------------
  
  /*
   *  (:> セッションストレージ初期化
   */
  function clearSessionData() {
    logger.debug( 'Clear All SessionStorage, then set data of current steps' );
    wss.clear();
  }
  
});