/**
 * For Add Character scripts (/add-char/)
 */
'use strict';
$(document).ready(function() {
  
  var gf               = $("#character-creation"),
      wls              = window.localStorage,
      currentPermalink = 'add-char';
  SUBMIT_BUTTONS       = [ 'create', 'update', 'remove', 'commit' ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  initHistory();
  initFields();
  
  // ----- Event handlers -------------------------------------------------------------------------
  
  /*
   * Fire when be entered something in the selectable input field (:> 選択可能入項目に入力時
   */
  $(document).on('input', '.editable-select.es-input', function(e) {
    var $currentInputGroup = $(this).closest('.input-group');
    $currentInputGroup.find('.btn-erase').prop( 'disabled', is_empty( $(this).val() ) );
  });
  
  /*
   * Fire when be selected from list in the selectable input field (:> 選択可能入項目にリストから選択時
   */
  $(document).on('select.editable-select', '.editable-select', function(e) {
    var $currentInputGroup = $(this).closest('.input-group');
    $currentInputGroup.find('.btn-erase').prop( 'disabled', is_empty( $(this).val() ) );
  });
  
  /*
   * Clicked erase button with the selectable input field (:> 選択可能入項目の消去ボタン押下時
   */
  $(document).on('click', '.btn-erase', function(e) {
    var $currentField = $(this).closest('.form-group');
    $('.editable-select').editableSelect('hide');
    $currentField.find('.editable-select.es-input').val('').trigger('input');
  });
  
  /*
   * clicked changing visibility button on the tab of settings (:> 設定タブ上の表示切替ボタン押下時
   */
  $(document).on('click', '.btn-visibility', function(e) {
    var $buttonIcon    = $(this).children('i'),
        $visibleField  = $(this).next('input[name^="publish_"]'),
        currentVisible = $visibleField.val() === 'true' ? true : false;
    if ( currentVisible ) {
      $visibleField.val( 'false' );
      $buttonIcon.attr( 'class', 'plt-eye-blocked' );
    } else {
      $visibleField.val( 'true' );
      $buttonIcon.attr( 'class', 'plt-eye' );
    }
  });
  
  /*
   * clicked Reset button on the tab of settings (:> 設定タブ上のリセットボタン押下時
   */
  $(document).on('click', '#btn-reset', function(e) {
    $('#settings input.form-control').each(function(){
      $(this).val( $(this).attr('data-default') );
    });
    // location.href='/'+currentPermalink+'/?tab=3';
  });
  
  /*
   * Clicked Commit button on the tab of settings (:> 設定タブ上のコミットボタン押下時
   */
  $(document).on('click', '#btn-commit', function(e) {
    var fieldsOrder = $('#settings .sortable-container').sortable( 'toArray', { attribute: 'data-sort-id' } ),
        nameOrder   = $('#settings .sortable-horizontal-container').sortable( 'toArray', { attribute: 'data-sort-id' } );
    fieldsOrder = [ 'name' ].concat( fieldsOrder, [ 'secret_info', 'note', 'tags', 'publish' ] );
    $('#'+currentPermalink+'-post-action').val( 'commit' );
    var postDataRaw = narrowDownData( conv_kv( gf.serializeArray() ), 'settings' );
    postDataRaw['fields_order'] = fieldsOrder;
    postDataRaw['name_order'] = nameOrder;
    // if ( ! debugPostData( postDataRaw ) ) return false; /* ***** For debug, it shows summary on the console before posting data. ****** */
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
   * Clicked Add Field button on the tab of settings (:> 設定タブ上の項目追加ボタン押下時
   */
  $(document).on('click', '#btn-add-field', function(e) {
    
  });
  
  /*
   * Field synchronization when entering names (:> 名前入力時のフィールド同期
   */
  $(document).on('input', '#first_name, #middle_name, #last_name', function(e) {
    var nameParts   = [],
        $firstName  = $('#first_name'),
        $middleName = $('#middle_name'),
        $lastName   = $('#last_name'),
        fullName    = '';
    nameParts[Number( $firstName.attr('tabindex') )]  = $firstName.val();
    nameParts[Number( $middleName.attr('tabindex') )] = $middleName.val();
    nameParts[Number( $lastName.attr('tabindex') )]   = $lastName.val();
    fullName = nameParts.join(' ').trim();
    if ( fullName.length > 0 ) {
      $('#full_name').val( fullName );
    } else {
      $('#full_name').val( '' );
      $('#display_name').editableSelect( 'clear' );
    }
  });
  
  /*
   * Set display name candidate (:> 表示名候補をセットする
   */
  $(document).on('focus', '#display_name', function(){
    $(this).editableSelect( 'clear' );
    if ( is_empty( $('#full_name').val() + $('#nickname').val() + $('#aliases').val() ) ) {
      return;
    }
    $(this).queue(function(){
      if ( ! is_empty( $('#full_name').val() ) ) {
        $(this).editableSelect( 'add', $('#full_name').val(), 1 );
      }
      if ( ! is_empty( $('#nickname').val() ) ) {
        $(this).editableSelect( 'add', $('#nickname').val(), Number( $('#nickname').attr('tabindex') ) );
      }
      if ( ! is_empty( $('#aliases').val() ) ) {
        $(this).editableSelect( 'add', $('#aliases').val(), Number( $('#aliases').attr('tabindex') ) );
      }
      $(this).dequeue();
    });
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
   * Automatic resizing of textarea that is entering (:> テキストエリア入力中の自動リサイズ
   */
  $(document).on('input', 'textarea.form-control', function(e){
    if ( e.target.scrollHeight > e.target.offsetHeight ) {
      $(this).height( e.target.scrollHeight );
    } else {
      var lineHeight = parseInt( $(this).css('lineHeight') );
      while ( true ) {
        $(this).height( $(this).height() - lineHeight );
        if ( e.target.scrollHeight > e.target.offsetHeight ) {
          $(this).height( e.target.scrollHeight );
          break;
        }
      }
    }
  });
  
  /*
   * Clicked Cancel Edit button (:> キャンセルボタン押下時
   */
  $(document).on('click', '#btn-cancel', function(){
    location.href = '/dashboard/';
  });
  
  /*
   * Clicked Remove Character button (:> キャラクター削除ボタン押下時
   */
  $(document).on('click', '#btn-remove', function(){
    alert('Execute removing character!');
    
  });
  
  /*
   * Clicked Create Character button (:> キャラクター作成ボタン押下時
   */
  $(document).on('click', '#btn-create', function(){
    $('#'+currentPermalink+'-post-action').val( 'create' );
    var postDataRaw = narrowDownData( conv_kv( gf.serializeArray() ), 'edit-char' );
    if ( ! validatePostData( postDataRaw ) ) {
      return false;
    }
    if ( ! debugPostData( postDataRaw ) ) return false; /* ***** For debug, it shows summary on the console before posting data. ****** */
    
  });
  
  /*
   * Clicked Save Changes button (:> 変更保存ボタン押下時
   */
  $(document).on('click', '#btn-update', function(){
    alert('Execute updating character!');
    
  });
  
  /*
   * Changed to the tab of readonly (:> 読み込み専用タブに変更時
   */
  $('.panel_toolbox a').on('show.bs.tab', function(e) {
    if ( $(e.target).hasClass('readonly-mode') ) {
      $('#edit-char').find('.form-control, [type="hidden"]').each(function(){
        if ( $(this).attr('name') != undefined ) {
          var previewStr = is_empty( $(this).val() ) ? '<span class="gray">'+$(this).attr('placeholder')+'</span>' : $(this).val();
          $('#pv-'+$(this).attr('name')).html( previewStr );
        }
      });
    }
  });
  
  
  // ----- 個別処理（関数）------------------------------------------------------------------------
  
  /*
   * Replace a history state (:> 履歴を更新する
   */
  function initHistory() {
    if ( 'default_tab' === history.state || ! is_empty( $.QueryString.tab ) ) {
      history.replaceState( 'defautl_tab', '', location.pathname );
      /*
      if ( $.QueryString.tab ) {
        $('#messageTab li').eq( $.QueryString.tab ).children('a').trigger( 'click' );
      }
      */
    }
  }
  
  /*
   * Refine data for posting by tab (:> タブ別にデータを絞り込む
   */
  function narrowDownData( postDataRaw, tabId ) {
    var fieldNames = [ 'from_page', 'source_id', 'character_id', 'post_action', '_token', '_wp_http_referer' ];
    $('#'+tabId).find('input,textarea').each(function(){
      fieldNames.push( $(this).attr('name') );
    });
    $.each( postDataRaw, function( key, val ) {
      if ( ! fieldNames.includes( key ) ) {
        delete postDataRaw[key];
      }
    });
    return postDataRaw;
  }
  
  /*
   * Validation
   */
  function validatePostData( postData ) {
    if ( is_empty( postData.full_name ) ) {
      $('#full_name').closest('.form-group').addClass('has-error');
    } else {
      $('#full_name').closest('.form-group').removeClass('has-error');
    }
    if ( is_empty( postData.display_name ) ) {
      $('#display_name').closest('.form-group').addClass('has-error');
    } else {
      $('#display_name').closest('.form-group').removeClass('has-error');
    }
    if ( is_empty( postData.role ) ) {
      $('#role').closest('.form-group').addClass('has-error');
    } else {
      $('#role').closest('.form-group').removeClass('has-error');
    }
    return gf.find('.form-group.has-error').length == 0;
  }
  
  /*
   * Initialize some fields on form (:> フォーム項目の初期化
   */
  function initFields() {
    // Disabled reside button of the message control on top navigator
    $('#msgctl-reside').prop( 'disabled', true );
    
    $('.editable-select').editableSelect();
    
    $('#birth_date').inputmask( '99/99/9999', { placeholder: 'dd/mm/yyyy' });
    $('#died_date').inputmask( '99/99/9999', { placeholder: 'dd/mm/yyyy' });
    
    $('#settings .sortable-container').sortable({
      items: '.form-group:not(.disabled-sort)'
    });
    $('#settings .sortable-horizontal-container').sortable();
    $('#settings .sortable-container, #settings .sortable-horizontal-container').disableSelection();
  }
  
  
  // ----- WEBストレージ(ローカルストレージ)関連 ---------------------------------------------------------------
  
  // None on this page
  
  
  
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