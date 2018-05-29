/**
 * For Ideas (/idea-note/)
 */
'use strict';
$(document).ready(function() {
  
  var gf                = $("#ideasForm"),
      wss               = window.sessionStorage,
      currentPermalink  = 'idea-note',
      defaultPerLimit   = 10,
      $currentOffsetElm = $('.list-more-cell'),
      doingAsync        = false,
      currentOffset, activedTab, lastListOffset, totalIdeas;
  SUBMIT_BUTTONS   = [ 'save-idea', 'find-idea', 'edit-idea', 'remove-idea' ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  clearSessionData();
  initHistory();
  getPageState();
  
  // ----- Event handlers -------------------------------------------------------------------------
  
  /*
   * Fire just after changing a tab (:> タブ切り替え直後のイベント
   */
  $('#ideasTab a').on( 'shown.bs.tab', function(e) {
    getPageState();
  });
  
  /*
   * Clicked Save Idea button (:> アイデア保存ボタン押下時
   */
  $('#save-idea').on('click', function(e){
    $('#'+ currentPermalink +'-post-action').val( 'register' );
    var postDataRaw = conv_kv( gf.serializeArray() );
    delete postDataRaw.search_keywords;
    delete postDataRaw['selected_ideas[]'];
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
   * Clicked Update Idea button (:> アイデア更新ボタン押下時
   */
  $('#update-idea').on('click', function(e){
    $('#'+ currentPermalink +'-post-action').val( 'update' );
    var postDataRaw = conv_kv( gf.serializeArray() );
    delete postDataRaw.search_keywords;
    delete postDataRaw['selected_ideas[]'];
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
   * Clicked Cancel button (:> アイデア編集キャンセルボタン押下時
   */
  $('#cancel').on('click', function(e){
    $('#'+ currentPermalink +'-post-action').val( '' );
    $('#idea_id').remove();
    $('#idea_title').val( '' );
    $('#idea_content').val( '' ).css( 'height', 'auto' );
    $('#save-idea').removeClass('hide');
    $('#update-idea, #cancel').addClass('hide');
  });

  /*
   * Clicked find idea button (:> アイデア検索ボタン押下時
   */
  $('#find-idea').on('click', function(e){
    $('#'+ currentPermalink +'-post-action').val( 'retrieve' );
    var postDataRaw = conv_kv( gf.serializeArray() );
    delete postDataRaw.idea_title;
    delete postDataRaw.idea_content;
    delete postDataRaw.idea_extension;
    postDataRaw.limit = defaultPerLimit;
    // if ( ! debugPostData( postDataRaw ) ) return false; /* ***** For debug, it shows summary on the console before posting data. ****** */
    var postData = JSON.stringify( postDataRaw );
    $('#idea-list tbody').empty();
    lastListOffset = 0;
    loadingList( 'show' );
    // ajaxでpost
    callAjax(
      '/'+currentPermalink+'/',
      'post',
      postData,
      'json',
      'application/json; charset=utf-8',
      'addList',
      true
    );
  });

  /*
   * Load additionally when scroll to the bottom of the idea list (:> アイデアリスト最下部までスクロール時にアイデアを追加で読み込む
   */
  $(window).scroll(function(){
    if ( activedTab === 'tab-content2' && $(window).scrollTop() + $(window).height() > currentOffset ) {
      if ( ! doingAsync && lastListOffset < totalIdeas ) {
//console.log( 'fire!', lastListOffset, totalIdeas );
        var nextOffset = Math.ceil( lastListOffset / defaultPerLimit ) * defaultPerLimit;
        $('#'+ currentPermalink +'-post-action').val( 'retrieve' );
        var postDataRaw = conv_kv( gf.serializeArray() );
        delete postDataRaw.idea_title;
        delete postDataRaw.idea_content;
        delete postDataRaw.idea_extension;
        postDataRaw.limit = defaultPerLimit;
        postDataRaw.offset = nextOffset;
        // if ( ! debugPostData( postDataRaw ) ) return false; /* ***** For debug, it shows summary on the console before posting data. ****** */
        var postData = JSON.stringify( postDataRaw );
        loadingList( 'show' );
        // ajaxでpost
        callAjax(
          '/'+currentPermalink+'/',
          'post',
          postData,
          'json',
          'application/json; charset=utf-8',
          'addList',
          true
        );
      }
    }
  });

  /*
   * Clicked edit idea button (:> アイデア編集ボタン押下時
   */
  $(document).on( 'click', '.edit-idea', function(e){
    var $ideaElm    = $(this).closest('tr'),
        ideaId      = $ideaElm.attr( 'data-idea-id' ),
        addField    = $( '<input />', { "type": 'hidden', "id": 'idea_id', "name": 'idea_id', "value": ideaId } ),
        ideaTitle   = '',
        ideaContent = '';
    // $('#'+ currentPermalink +'-post-action').val( 'update' );
    gf.append( addField );
    if ( $ideaElm.find('.list-idea-cell label').length > 0 ) {
      ideaTitle = $ideaElm.find('.list-idea-cell label').text();
    }
    $('#idea_title').val( ideaTitle );
    if ( $ideaElm.find('.list-idea-cell p').length > 0 ) {
      //ideaContent = strip_tags( $ideaElm.find('.list-idea-cell p').html() );
      // decode html entities
      //ideaContent = $('<div/>').html( ideaContent ).text();
      ideaContent = rasterize_str( $ideaElm.find('.list-idea-cell p').html() );
    }
    $('#idea_content').val( ideaContent );
    $('#save-idea').addClass('hide');
    $('#update-idea, #cancel').removeClass('hide');
    
    $('#idea_content').trigger('click').trigger('focus');
    $('#register-idea-tab').trigger('click');
    $('html, body').animate({
      scrollTop: 0
    }, 300, 'swing');
  });

  /*
   * Clicked hide idea button (:> アイデア非表示ボタン押下時
   */
  $(document).on( 'click', '.hide-idea', function(e){
    $(this).closest('tr').fadeOut('fast', function(){
      $(this).addClass('hide').removeAttr('style');
    });
  });

  /*
   * Clicked remove idea button (:> アイデア削除ボタン押下時
   */
  $(document).on( 'click', '.remove-idea', function(e){
    dialogOpts.title = localize_messages.remove_idea_ttl;
    dialogOpts.text  = [ localize_messages.remove_idea_msg, localize_messages.are_you_sure ].join('<br>');
    dialogOpts.modules.Confirm.buttons[0].click = (notice, value) => {
      notice.close();
      // 確認ダイアログ承認後の削除処理
      $('#'+currentPermalink+'-post-action').val( 'remove' );
      var postDataRaw = conv_kv( gf.serializeArray() ),
          $ideaElm    = $(this).closest('tr'),
          ideaId      = $ideaElm.attr( 'data-idea-id' );
      postDataRaw.idea_id = ideaId;
      delete postDataRaw.idea_title;
      delete postDataRaw.idea_content;
      delete postDataRaw.idea_extension;
      delete postDataRaw.search_keywords;
      delete postDataRaw['selected_ideas[]'];
      // if ( ! debugPostData( postDataRaw ) ) return false; /* ***** For debug, it shows summary on the console before posting data. ****** */
      var post_data = JSON.stringify( postDataRaw );
      // logger.debug( value, base_data, post_data );
      controlSubmission();
      showLoading();
      callAjax(
        '/'+currentPermalink+'/',
        'post',
        post_data,
        'json',
        'application/json; charset=utf-8',
        'notify',
        true
      );
      controlSubmission( 'unlock' );
    };
    PNotify.alert( dialogOpts );
  });

  /*
   * Auto resize of textarea when focused (:> フォーカス時にテキストエリアを自動リサイズ
   */
  $('textarea').on('focus', function(evt){
    if ( ! is_empty( $(this).val() ) ) {
      var contentH = this.scrollHeight;
      if ( contentH > 72 ) {
        $(this).css( 'height', contentH + 'px' );
      }
    }
  })

  /*
   *  (:> 画面内の各種キーイベントの制御
   */
  $(document).on( 'keydown', 'body', function(e){
    var evt = e.originalEvent;
    // Enterキーによるフォームのフォーカス変更
    if ( evt.key === 'Enter' ) {
      if ( evt.target.tagName === 'TEXTAREA' ) {
        return;
      }
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
  /* */

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
   * Add Custom Callback for this page only (:> このページ専用の独自処理をコールバックに追加する
   */
  callbackAjax['addList'] = function( data ) {
    // Add search result list (:> 検索結果リストを追加する
//console.log( data );
    var $describe = $('.describe-finding'),
        $listBody = $('#idea-list tbody'),
        $tmplList = $('#list-template').clone(),
        $descHtml = $( '<p></p>', { "class": "help-block" } ),
        newList;
    $tmplList.removeAttr('id');
    $descHtml.html( '<b>'+ data.describe +'</b>' );
    if ( data.idea_length > 0 ) {
      $descHtml.append( '<span>'+ data.idea_length +'</span>' );
    }
    $listBody.attr( 'data-total-ideas', data.idea_length );
    $describe.html( $descHtml[0].outerHTML ).removeClass('hide');
    $listBody.find( '.dummy-list' ).remove();
    $('#no-idea').remove();
    if ( data.ideas.length > 0 ) {
      // $('#idea-list').fadeIn('fast');
      Object.keys( data.ideas ).forEach(function( _key, _idx ) {
//console.log( _key, _idx );
        var idea = data.ideas[_key],
            bodyContent = '',
            offset = lastListOffset + _idx;
        bodyContent += ! is_empty( idea.head ) ? '<label>' + idea.head + '</label>' : '';
        bodyContent += ! is_empty( idea.body ) ? '<p>' + idea.body + '</p>' : '';
        newList = sprintf( $tmplList[0].outerHTML, idea.id, offset, idea.id, bodyContent, idea.upd, idea.upd, idea.htd );
        $listBody.append( newList );
      });
      $listBody.children('tr').fadeIn('fast');
//console.log( newList, $(newList) );
      //try {
      loadingList( 'hide' );
      //} catch(exit_msg) {
      //  console.log('exit:',exit_msg);
      //}
    } else {
      loadingList( 'hide' );
      $('#idea-list').hide();
    }
  };
  
  /*
   * Toggle an effect for updating ideas list (:> リスト更新エフェクトの切り替え
   */
  function loadingList( state ) {
    var $listBody = $('#idea-list tbody'),
        $loading  = $( '<div></div>', { "class": "loading-list" } ),
        hasItems  = $listBody.children().length > 0;
//console.log( hasItems );
    switch ( state ) {
      case 'show':
        doingAsync = true;
        if ( ! hasItems ) {
          $listBody.append( '<tr><td class="dummy-list" colspan="4"></td></tr>' );
        }
        $loading.prepend( '<i class="fa plt-spinner2 fa-pulse fa-2x fa-fw"></i>' ).append( localize_messages.loading_list );
        $listBody.prepend( $loading );
        // throw('debug');
        break;
      case 'hide':
        doingAsync = false;
        $listBody.find('.loading-list').fadeOut('fast', function(){ $(this).remove(); });
        getPageState();
        break;
    }
  }
  
  /*
   * Get and update status of current page
   */
  function getPageState() {
    currentOffset  = $currentOffsetElm.offset().top + $currentOffsetElm.outerHeight();
    activedTab     = $('.tab-pane.active').attr('id');
    //lastListOffset = Number( $('#idea-list tbody tr:last-child').attr( 'data-offset' ) );
    lastListOffset = $('#idea-list tbody tr').filter('[data-idea-id]').length;
    totalIdeas     = Number( $('#idea-list tbody').attr( 'data-total-ideas' ) );
//console.log( currentOffset, activedTab, lastListOffset, totalIdeas );
  }
  
  
  /*
   * Adjust Messages List as auto scrolling (:> 
   * /
  function adjustMessagesList() {
    if ( $('#messageTabContent').length > 0 ) {
      var msgList = $('#messageTabContent');
      msgList.animate({
        scrollTop: msgList[0].scrollHeight
      }, 100 );
    }
  }
  */
  
  /*
   * Validation
   */
  function validatePostData( postData ) {
    if ( is_empty( postData.idea_content ) ) {
      $('#idea_content').closest('.form-group').addClass('has-error has-feedback');
      $('#idea_content_feedback').removeClass('hide');
    } else {
      $('#idea_content').closest('.form-group').removeClass('has-error has-feedback');
      $('#idea_content_feedback').addClass('hide');
    }
    return gf.find('.form-group.has-error').length == 0;
  }
  
  /*
   * Initialize the remain number of characters inputable message (:> メッセージ残文字数表示の初期化
   * /
  function initializeCounts() {
    if ( $('#message_content').length > 0 ) {
      var currentLength = $('#message_content').val().length;
      $('.count-strings').text( maxMessageLength - currentLength );
    }
  }
  */
  
  
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