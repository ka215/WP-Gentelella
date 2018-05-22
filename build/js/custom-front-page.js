/**
 * For Front Page (/)
 */
'use strict';
$(document).ready(function() {
  
  var wss               = window.sessionStorage;
  SUBMIT_BUTTONS   = [  ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  initHistory();
  clearSessionData();
  
  // ----- Event handlers -------------------------------------------------------------------------
  
  /*
   * Fire on resize window (:> ウィンドウリサイズ時のイベント
   */
  $(window).resize(function(){
    //
  });
  
  
  // ----- 個別処理（関数）------------------------------------------------------------------------
  
  /*
   * Replace a history state in browser after 404 error (:> 404エラー時はブラウザ履歴ステータスを置き換える
   */
  function initHistory() {
    if ( '404_not_found' === history.state || '404' === $.QueryString.error ) {
      history.replaceState( '404_not_found', '', location.pathname );
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
  
});