/**
 * For Whole Story (/whole-story/)
 */
'use strict';
$(document).ready(function() {
  
  var gf               = $("#globalSettings"),
      wls              = window.localStorage,
      currentPermalink = 'whole-story',
      currentSrcId     = Number( $('#change_source option:selected').val() );
  SUBMIT_BUTTONS       = [ 'regist', 'remove-confirm', 'update', 'add' ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  //storedSrcCache( currentSrcId );
  
  // ----- Event handlers -------------------------------------------------------------------------
  
  /*
   * Changed Select Box named "Switch or Add Story" (:> セレクトボックス変更時のイベント
   */
  $('#change_source').on('change', function(){
    logger.debug( currentSrcId, $(this).val() );
    if ( $(this).val() === '' ) {
      // Add new story
      storedSrcCache( currentSrcId ); // 現ソースIDをローカルに保存
      gf.find('input[name="source_id"]').val('');
      gf.find('#source_name').val('');
      gf.find('input[id^="wh"]').val('');
      gf.find('#team_writing').prop( 'checked', false );
      rebuildSwitchery( '#team_writing' );
      gf.find('#'+currentPermalink+'-btn-add').removeClass('hide');
      gf.find('#'+currentPermalink+'-btn-update').addClass('hide');
      gf.find('#'+currentPermalink+'-btn-remove-confirm').addClass('hide');
    } else {
      // Switch another story
      var newSrcId = Number( $(this).val() );
      if ( currentSrcId != newSrcId ) {
        // ajax(WP REST API)で新ソースIDのデータを取得する
        currentSrcId = newSrcId;
        controlSubmission();
        callAjax( wpApiSettings.root + 'plotter/v1/src/' + currentSrcId, 'GET' )
        .then( function( data, stat, self ){
          if ( 'success' === stat ) {
            logger.debug( data[0] );
            gf.find('#source_name').val( data[0].name );
            gf.find('input#who').val( data[0].who );
            gf.find('input#what').val( data[0].what );
            gf.find('input#where').val( data[0].where );
            gf.find('input#when').val( data[0].when );
            gf.find('input#why').val( data[0].why );
            gf.find('#team_writing').prop( 'checked', ( data[0].type == 1 ) );
            rebuildSwitchery( '#team_writing' );
            controlSubmission( 'unlock' );
            // validatorを発火させる
            gf.find('#source_name').trigger('blur');
          }
        });
        // Cookie値の lastSource を新ソースIDに更新する
        
        storedSrcCache( currentSrcId ); // 新ソースIDをローカルに保存
      } else {
        restoreSrcCache( currentSrcId ); // 現ソースIDをロード
      }
      gf.find('input[name="source_id"]').val(currentSrcId);
      gf.find('#'+currentPermalink+'-btn-remove-confirm').removeClass('hide');
      gf.find('#'+currentPermalink+'-btn-update').removeClass('hide');
      gf.find('#'+currentPermalink+'-btn-add').addClass('hide');
    }
  });
  
  /*
   * Clicked Each Buttons (:> 各種ボタンクリック時のイベント
   */
  $(document).on('click', 'button.btn[id^="'+currentPermalink+'-btn"]', function(e){
    e.preventDefault();
    var action = $(this).attr('id').replace(currentPermalink+'-btn-', '');
    if ( 'cancel' === action ) {
      return location.href = '/dashboard/';
    } else {
      if ( !validator.checkAll( gf ) ) {
        return false;
      }
      $('#'+currentPermalink+'-post-action').val( action );
      // gf.submit();
      // ajaxでPOST
      var post_data = JSON.stringify( conv_kv( gf.serializeArray() ) );
      callAjax(
        '/'+currentPermalink+'/',
        'post',
        post_data,
        'json',
        'application/json; charset=utf-8',
        'notify',
        true
      );
    }
  });
  
  
  // ----- 個別処理（関数）------------------------------------------------------------------------
  
  /*
   * Add Custom Callback for this page only (:> このページ専用の独自処理をコールバックに追加する
   */
  callbackAjax['executeRemove'] = function( evt ) {
    // 確認ダイアログ後の削除実行
    if ( evt.options.data.addClass === 'C0G001' ) {
      $('#'+currentPermalink+'-post-action').val( 'remove' );
      var post_data = JSON.stringify( conv_kv( gf.serializeArray() ) );
      callAjax(
        '/'+currentPermalink+'/',
        'post',
        post_data,
        'json',
        'application/json; charset=utf-8',
        'notify',
        true
      );
    }
  };
  
  /*
   * Rebuild Switchery Component (:> 
   */
  function rebuildSwitchery( selector ) {
    $('.js-switch').each(function(){
        if ( $(this).is(selector) ) {
            $(this).next('.switchery').remove();
            var switchery = new Switchery( this, { color: '#26B99A' } );
        }
    });
  }
  
  // ----- WEBストレージ(ローカルストレージ)関連 ---------------------------------------------------------------
  
  /*
   *  (:> 指定のソースデータ（source_id）をWEBストレージへ保存する
   */
  function storedSrcCache( sid ) {
    if ( sid == Number( gf.find('[name="source_id"]').val() ) ) {
      var src_data = {
        'source_id': currentSrcId,
        'source_name': gf.find('#source_name').val(),
        'who': gf.find('#who').val(),
        'what': gf.find('#what').val(),
        'where': gf.find('#where').val(),
        'when': gf.find('#when').val(),
        'why': gf.find('#why').val(),
        'team_writing': gf.find('#team_writing').val(),
        'permission': gf.find('#permission').val()
      };
      wls.setItem( 'plt_cursrc', JSON.stringify( src_data ) );
    }
  }
  
  /*
   *  (:> WEBストレージから指定のソースデータ（source_id）を読み込みフォームへ展開する
   */
  function restoreSrcCache( sid ) {
    var src_data = JSON.parse( wls.getItem( 'plt_cursrc' ) );
    if ( sid == Number( src_data.source_id ) ) {
      gf.find('[name="source_id"]').val( sid );
      gf.find('#source_name').val( src_data.source_name );
      gf.find('#who').val( src_data.who );
      gf.find('#what').val( src_data.what );
      gf.find('#where').val( src_data.where );
      gf.find('#when').val( src_data.when );
      gf.find('#why').val( src_data.why );
      gf.find('#team_writing').val( src_data.team_writing );
      gf.find('#permission').val( src_data.permission );
    }
  }
  
  
});