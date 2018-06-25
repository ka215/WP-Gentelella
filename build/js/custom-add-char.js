/**
 * For Add Character scripts (/add-char/)
 */
'use strict';
$(document).ready(function() {
  
  var gf               = $("#character-creation"),
      wls              = window.localStorage,
      currentPermalink = 'add-char',
      maxTags          = 5;
  SUBMIT_BUTTONS       = [ 'create', 'update', 'remove', 'commit', 'retrieve' ];
  
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
    generateFullname();
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
        $(this).editableSelect( 'add', strip_tags( $('#full_name').val() ), 1 );
      }
      if ( ! is_empty( $('#nickname').val() ) ) {
        $(this).editableSelect( 'add', strip_tags( $('#nickname').val() ), Number( $('#nickname').attr('tabindex') ) );
      }
      if ( ! is_empty( $('#aliases').val() ) ) {
        $(this).editableSelect( 'add', strip_tags( $('#aliases').val() ), Number( $('#aliases').attr('tabindex') ) );
      }
      $(this).dequeue();
    });
  });
  
  
  /*
   * Choose thumbnail image to upload (:> アップロードするサムネイル画像を選択時の処理
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
        var $thumbViewer = $('.current-image-preview'),
            $originThumb = $thumbViewer.find('img'),
            thumbWidth   = $originThumb[0].offsetWidth,
            thumbHeight  = $originThumb[0].offsetHeight,
            $previewThumbnail = $( '<img>', { class: $originThumb.attr('class'), src: this.result, width: thumbWidth } ),
            $previewContainer = $('.new-image-preview');
        $previewContainer.empty().append( $previewThumbnail[0].outerHTML );
        $('.arrow-right').height( thumbHeight );
        $('.arrow-right, .new-image-preview, #remove-image').removeClass( 'hide' );
        $('input[name^="images["]').prop( 'disabled', true );
      }
    }
  });
  
  /*
   * Adjust avatar preview container if window resized (:> ウィンドウリサイズ時にアバタープレビュー欄を調整
   * /
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
   * Clicked Remove thumbnail image button (:> サムネイル画像削除ボタン押下時
   */
  $('#remove-image').on('click', function(e){
    e.preventDefault();
    var $removeAction  = $('#remove-image-action'),
        $currentThumb  = $('.current-image-preview').find('img'),
        noAvatarSrc    = '/assets/uploads/no-avatar.png';
    if ( $removeAction.prop( 'disabled' ) ) {
      // enable Remove
      $removeAction.prop( 'disabled', false );
      $(this).children('i').attr( 'class', 'plt-checkbox-checked2 green' );
      $('#assign-image').val('').prop( 'disabled', true ); // disabled input:file
      $('#preview-upfile').val(''); // empty display upfile
      $('.new-image-preview').addClass('hide').empty(); // hide preview image
      $('.arrow-right').addClass('hide');
      var $noThumb = $currentThumb.clone();
      $noThumb.attr( 'src', noAvatarSrc ).addClass('no-avatar-image');
      $currentThumb.addClass( 'hide' );
      $('.current-image-preview').prepend( $noThumb[0].outerHTML );
      $('input[name^="images["]').prop( 'disabled', false );
    } else {
      // disable Remove
      $removeAction.prop( 'disabled', true );
      $(this).children('i').attr( 'class', 'plt-checkbox-unchecked2 gray' );
      $('#assign-image').prop( 'disabled', false ); // enable input:file
      $('.current-image-preview').find('.no-avatar-image').remove();
      $currentThumb.removeClass( 'hide' );
      $('input[name^="images["]').prop( 'disabled', true );
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
   * Event handlers of tagsinput (:> タグ入力のイベントハンドラ
   */
  $('#tags').on('beforeItemAdd itemAdded itemRemoved', function(evt){
    var tags = evt.target.value.split(',');
    switch ( evt.type ) {
      case 'beforeItemAdd':
        var originTag = evt.item;
        if ( /\x22|\x27|<|>/.test( originTag ) ) {
          evt.cancel = true;
        }
        break;
      case 'itemAdded':
        if ( tags.length == maxTags ) {
          $('.plt-tagsinput').hide();
        }
        break;
      case 'itemRemoved':
        if ( tags.length < maxTags ) {
          $('.plt-tagsinput').show();
        }
        break;
    }
  });

  /*
   * Clicked Cancel Creation button (:> 作成キャンセルボタン押下時
   */
  $(document).on('click', '#btn-cancel', function(){
    location.href = '/dashboard/';
  });
  
  /*
   * Clicked Cancel Edit button (:> 編集キャンセルボタン押下時
   */
  $(document).on('click', '#btn-cancel-edit', function(){
    initEditableFields();
    $('input[name="character_id"]').val( '' );
    $('#btn-cancel, #btn-create').removeClass('hide');
    $('#btn-cancel-edit, #btn-remove, #btn-update').addClass('hide');
    $('.panel_toolbox a.edit-mode').trigger('click');
  });

  /*
   * Clicked Remove Character button (:> キャラクター削除ボタン押下時
   */
  $(document).on('click', '#btn-remove', function(){
    dialogOpts.title = localize_messages.remove_character_ttl;
    dialogOpts.text  = [ localize_messages.remove_character_msg, localize_messages.are_you_sure ].join('<br>');
    dialogOpts.modules.Confirm.buttons[0].click = (notice, value) => {
      notice.close();
      // 確認ダイアログ承認後の削除処理
      $('#'+currentPermalink+'-post-action').val( 'remove' );
      var postDataRaw = narrowDownData( conv_kv( gf.serializeArray() ), 'view-char' );
      // if ( ! debugPostData( postDataRaw ) ) return false; /* ***** For debug, it shows summary on the console before posting data. ****** */
      var post_data = JSON.stringify( postDataRaw );
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
   * Clicked Create Character or Save Changes button (:> キャラクター作成もしくは変更保存ボタン押下時
   */
  $(document).on('click', '#btn-create, #btn-update', function(){
    $('#'+currentPermalink+'-post-action').val( $(this).attr('id').replace('btn-', '') );
    var postDataRaw = narrowDownData( conv_kv( gf.serializeArray() ), 'edit-char' );
    if ( ! validatePostData( postDataRaw ) ) {
      $('html,body').animate({ scrollTop: $('.has-error').eq(0).offset().top - 15 }, 'fast', 'swing');
      $('.has-error').eq(0).find('input').eq(0).trigger('focus');
      return false;
    }
    // if ( ! debugPostData( postDataRaw ) ) return false; /* ***** For debug, it shows summary on the console before posting data. ****** */
    if ( ! is_empty( $('#assign-image').val() ) ) {
      logger.debug( 'Post via ajax with image' );
      var fd = new FormData();
      fd.append( 'assign_image', $('#assign-image').prop('files')[0] );
      // fd.append( 'dir',  $('#assign-image').val() );
      for ( var prop in postDataRaw ) {
        switch ( prop ) {
          case 'remove_image_action':
          case 'publish':
            fd.append( prop, postDataRaw[prop] ? '1' : '' );
            break;
          default:
            fd.append( prop, postDataRaw[prop] );
        }
      }
      fd.append( 'response_type', 'json' );
      var post_data = {
        type: 'POST',
        dataType: 'json',
        data: fd,
        processData: false,
        contentType: false
      };
      showLoading();
      // ajaxでpost
      $.ajax( '/'+currentPermalink+'/', post_data )
      .done(function( response, status, xhr ) {
        logger.debug( response, status, xhr );
        callbackAjax.notify( response );
      })
      .fail(function( xhr, status, error ){
        logger.debug( error, status, xhr );
      });
    } else {
      logger.debug( 'Post via ajax without image' );
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
    }
  });
  
  /*
   * Changed to the tab of readonly (:> 読み込み専用タブに変更時
   */
  $('.panel_toolbox a').on('show.bs.tab', function(e) {
    if ( $(e.target).hasClass('readonly-mode') ) {
      cloneFieldData();
    }
  });
  
  /*
   * Clicked an character on the character list (:> キャラリスト上のキャラクターをクリック時
   */
  $(document).on('click', '.list-item', function(e) {
    $('#'+currentPermalink+'-post-action').val( 'retrieve' );
    var charId = Number( $(this).attr('data-item-id') ),
        postDataRaw = narrowDownData( conv_kv( gf.serializeArray() ), 'view-char' );
    postDataRaw.character_id = charId;
    // if ( ! debugPostData( postDataRaw ) ) return false; /* ***** For debug, it shows summary on the console before posting data. ****** */
    var post_data = JSON.stringify( postDataRaw );
    controlSubmission();
    showLoading();
    // ajaxでpost
    callAjax(
      '/'+currentPermalink+'/',
      'post',
      post_data,
      'json',
      'application/json; charset=utf-8',
      'setData',
      true
    );
  });
  
  /*
   * Clicked Load More button (:> 追加読み込みボタン押下時
   */
  $('#btn-load-more').on('click', function(){
    $('#'+currentPermalink+'-post-action').val( 'load_more' );
    var loadedIds = [],
        postDataRaw = narrowDownData( conv_kv( gf.serializeArray() ), 'view-char' );
    $('.list-item').each(function(){
      loadedIds.push( Number( $(this).attr('data-item-id') ) );
    });
    postDataRaw.exclude_id = loadedIds;
    // if ( ! debugPostData( postDataRaw ) ) return false; /* ***** For debug, it shows summary on the console before posting data. ****** */
    var post_data = JSON.stringify( postDataRaw );
    loadingList( 'show' );
    // ajaxでpost
    callAjax(
      '/'+currentPermalink+'/',
      'post',
      post_data,
      'json',
      'application/json; charset=utf-8',
      'addList',
      true
    );
  });
  
  /*
   * Changed Sort item selector (:> ソート項目変更時
   */
  $('#sort-item-list').on('change', function() {
    var toOrder = $('#btn-sort-item').attr('data-sort-by'),
        iconClass = 'plt-sort-';
    if ( 'name' === $(this).val() ) {
      iconClass += 'alpha-' + toOrder;
    } else {
      iconClass += ( toOrder === 'asc' ? 'numeric-' : 'numberic-' ) + toOrder;
    }
    $('#btn-sort-item> i').attr( 'class', iconClass );
  });
  
  /*
   * Clicked Sort by button (:> ソートボタン押下時
   */
  $('#btn-sort-item').on('click', function(){
    var sortBy    = $('#sort-item-list').val(),
        sortOrder = $(this).attr('data-sort-by'),
        charOrder = {};
    switch ( sortBy ) {
      case 'created':
        charOrder['created_at'] = sortOrder;
        break;
      case 'last_modified':
        charOrder['updated_at'] = sortOrder;
        break;
      case 'name':
        charOrder['display_name'] = sortOrder;
        break;
    }
    docCookies.setItem( 'char_order', JSON.stringify( charOrder ), 60*60*24*30, '/', 'plotter.me', 1 );
    $('#'+currentPermalink+'-post-action').val( 'sort_list' );
    var loadedIds = [],
        postDataRaw = narrowDownData( conv_kv( gf.serializeArray() ), 'view-char' );
    $('.list-item').each(function(){
      loadedIds.push( Number( $(this).attr('data-item-id') ) );
    });
    postDataRaw.character_ids = loadedIds;
    // if ( ! debugPostData( postDataRaw ) ) return false; /* ***** For debug, it shows summary on the console before posting data. ****** */
    var post_data = JSON.stringify( postDataRaw );
    loadingList( 'show' );
    // ajaxでpost
    callAjax(
      '/'+currentPermalink+'/',
      'post',
      post_data,
      'json',
      'application/json; charset=utf-8',
      'rebuildList',
      true
    );
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
    if ( 'edit-char' === tabId ) {
      if ( $('input[name^="images["]').length > 0 ) {
        $('input[name^="images["]').each(function(){
          fieldNames.push( $(this).attr('name') );
        });
      }
    }
    $.each( postDataRaw, function( key, val ) {
      if ( ! fieldNames.includes( key ) ) {
        delete postDataRaw[key];
      }
    });
    return postDataRaw;
  }
  
  /*
   * Validation for tab of edit-char (:> 
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
    // postData.remove_image_action = postData.remove_image_action === '1';
    postData.publish = ! is_empty( postData.publish );
    delete postData.full_name;
    return gf.find('.form-group.has-error').length == 0;
  }
  
  /*
   * Initialize some fields on form (:> フォーム項目の初期化
   */
  function initFields() {
    // Disabled reside button of the message control on top navigator
    $('#msgctl-reside').prop( 'disabled', true );
    
    $('.editable-select').editableSelect();
    
    // input-mask
    $('#birth_date').inputmask( '99/99/9999', { placeholder: 'mm/dd/yyyy' });
    $('#died_date').inputmask( '99/99/9999', { placeholder: 'mm/dd/yyyy' });
    
    // bootstrap-tagsinput
    var tagsinput = $('#tags').tagsinput( 'input' );
    tagsinput.$input.addClass( 'plt-tagsinput' )
      .attr( 'tabindex', tagsinput.$element.attr( 'tabindex' ) )
      .attr( 'pattern', "[^\x22\x27]*" )
      .removeAttr( 'size' );
    $('#tags').tagsinput({
      maxTags: maxTags,
      trimValue: true,
    });
    
    // jquery-UI sortable
    $('#settings .sortable-container').sortable({
      items: '.form-group:not(.disabled-sort)'
    });
    $('#settings .sortable-horizontal-container').sortable();
    $('#settings .sortable-container, #settings .sortable-horizontal-container').disableSelection();
  }
  
  /*
   * initialize all editable fields on tab of edit-char (:> edit-charタブ上のすべての編集可能フィールドを初期化
   */
  function initEditableFields() {
    $('#edit-char').find('.form-control, [type="hidden"]').each(function(){
      var elmId = $(this).attr('id');
      if ( 'remove-image-action' !== elmId ) {
        $(this).val('');
      }
      $(this).closest('.form-group').removeClass('has-error');
    });
    $('.current-image-preview> img').attr( 'src', '/assets/uploads/no-avatar.png' );
    $('#tags').tagsinput('removeAll');
    if ( $('input#publish').prop( 'checked' ) === true ) {
      $('input#publish').trigger('click').prop('checked', false);
    }
  }
  
  
  /*
   * Generate full name from parts of name (:> 名前の部分からフルネームを生成する
   */
  function generateFullname() {
    var nameParts   = [],
        $firstName  = $('#first_name'),
        $middleName = $('#middle_name'),
        $lastName   = $('#last_name'),
        fullName    = '';
    nameParts[Number( $firstName.attr('tabindex') )]  = strip_tags( $firstName.val() );
    nameParts[Number( $middleName.attr('tabindex') )] = strip_tags( $middleName.val() );
    nameParts[Number( $lastName.attr('tabindex') )]   = strip_tags( $lastName.val() );
    fullName = nameParts.join(' ').trim();
    if ( fullName.length > 0 ) {
      $('#full_name').val( fullName );
    } else {
      $('#full_name').val( '' );
      $('#display_name').editableSelect( 'clear' );
    }
  }
  
  /*
   * Clone values from editable fields (:> 編集可能フィールドから値を複製する
   */
  function cloneFieldData() {
    generateFullname();
    $('#edit-char').find('.form-control, [type="hidden"]').each(function(){
      var previewDoc = '';
      if ( $(this).attr('name') != undefined ) {
        if ( 'tags' === $(this).attr('name') && ! is_empty( $(this).val() ) ) {
          var tags = $(this).val().split( ',' );
          tags.forEach(function( tag ){
            previewDoc += sprintf( '<span class="plt-tag">%s</span>', tag );
          });
        } else {
          previewDoc = is_empty( $(this).val() ) ? '<span class="gray">'+$(this).attr('placeholder')+'</span>' : $(this).val();
        }
        $('#pv-'+$(this).attr('name')).html( previewDoc );
      }
    });
    // For thumbnail
    var current_thumbnail = $('.current-image-preview> img').attr( 'src' );
    $('#view-char').find('.thumbnail> img').attr( 'src', current_thumbnail );
  }
  
  /*
   * Add Custom Callback for this page only (:> このページ専用の独自処理をコールバックに追加する
   */
  callbackAjax['setData'] = function( data ) {
    // Set to edit field the loaded character data (:> 読み込んだキャラデータを編集フィールドにセットする
    if ( data.status == 200 ) {
      initEditableFields();
      for ( var key in data.chardata ) {
        var val = data.chardata[key];
        switch ( key ) {
          case 'id':
            $('input[name="character_id"]').val( val );
            break;
          case 'images':
            var img_src = '/assets/uploads/no-avatar.png';
            if ( val.length > 0 && ! is_empty( val[0].url ) ) {
              img_src = val[0].url;
              $('#remove-image').removeClass( 'hide' );
              val.forEach(function( v, i ) {
                gf.append( $( '<input />', { type: 'hidden', name: 'images['+i+'][url]', value: v.url } ) );
                gf.append( $( '<input />', { type: 'hidden', name: 'images['+i+'][file]', value: v.file } ) );
              });
            } else {
              $('#remove-image').addClass( 'hide' );
            }
            $('.current-image-preview> img').attr( 'src', img_src );
            break;
          case 'tags':
            if ( ! is_empty( val ) ) {
              val.forEach(function( tag ){
                $('#tags').tagsinput( 'add', tag );
              });
            }
            break;
          case 'publish':
            if ( $('input#publish').prop( 'checked' ) !== val ) {
              $('input#publish').trigger('click').prop('checked', val);
            }
            break;
          default:
            $('[name="'+key+'"]').val( data.chardata[key] );
        }
      }
      $('#btn-cancel, #btn-create').addClass('hide');
      $('#btn-cancel-edit, #btn-remove, #btn-update').removeClass('hide');
      cloneFieldData();
      controlSubmission( 'unlock' );
      hideLoading();
    } else {
      $('input[name="character_id"]').val( '' );
      $('#btn-cancel, #btn-create').removeClass('hide');
      $('#btn-cancel-edit, #btn-remove, #btn-update').addClass('hide');
      controlSubmission( 'unlock' );
      hideLoading();
      notify( data.subject, data.message, 'error', '', '' );
    }
  };
  callbackAjax['addList'] = function( data ) {
    if ( data.length > 0 ) {
      data.forEach(function( charObj ) {
        var $listItem = $( '<div></div>', { 'class': 'list-item' } ),
            pubClass  = charObj.publish ? 'item-published' : 'item-private',
            thumbSrc  = '/assets/uploads/no-avatar.png';
        if ( 'images' in charObj && 'url' in charObj.images[0] ) {
          thumbSrc = charObj.images[0].url;
        }
        $listItem.addClass( pubClass ).attr( 'data-item-id', charObj.id );
        $listItem.append( '<div class="thumbnail item-thumbnail"><img src="'+ thumbSrc +'" class="img-responsive img-rounded"></div>' );
        $listItem.append( '<div class="item-details"><label class="item-name">'+ charObj.display_name +'</label><div class="item-meta"></div></div>' );
        $listItem.find('.item-meta').append( '<span class="label label-default hide">'+ charObj.role +'</span>' );
        $listItem.find('.item-meta').append( '<span class="text-right">'+ localize_messages.last_modified +': <time class="last-updated" datetime="'+ charObj.updated_at +'" title="'+ charObj.updated_at +'">'+ charObj.updated_at_htd +'</time></span>' );
        
        $('.char-list').append( $listItem );
      });
    }
    docCookies.setItem( 'loaded_chars', $('.list-item').length, 60*60*24*30, '/', 'plotter.me', 1 );
    loadingList( 'hide' );
  };
  callbackAjax['rebuildList'] = function( data ) {
    if ( data.length > 0 ) {
      $('.char-list').empty();
      data.forEach(function( charObj ) {
        var $listItem = $( '<div></div>', { 'class': 'list-item' } ),
            pubClass  = charObj.publish ? 'item-published' : 'item-private',
            thumbSrc  = '/assets/uploads/no-avatar.png';
        if ( 'images' in charObj && 'url' in charObj.images[0] ) {
          thumbSrc = charObj.images[0].url;
        }
        $listItem.addClass( pubClass ).attr( 'data-item-id', charObj.id );
        $listItem.append( '<div class="thumbnail item-thumbnail"><img src="'+ thumbSrc +'" class="img-responsive img-rounded"></div>' );
        $listItem.append( '<div class="item-details"><label class="item-name">'+ charObj.display_name +'</label><div class="item-meta"></div></div>' );
        $listItem.find('.item-meta').append( '<span class="label label-default hide">'+ charObj.role +'</span>' );
        $listItem.find('.item-meta').append( '<span class="text-right">'+ localize_messages.last_modified +': <time class="last-updated" datetime="'+ charObj.updated_at +'" title="'+ charObj.updated_at +'">'+ charObj.updated_at_htd +'</time></span>' );
        
        $('.char-list').append( $listItem );
      });
      var currentOrder = JSON.parse( docCookies.getItem( 'char_order' ) ),
          orderBy = Object.keys( currentOrder )[0],
          toOrder = currentOrder[orderBy] === 'asc' ? 'desc' : 'asc',
          ClassPrefix = orderBy === 'display_name' ? 'plt-sort-alpha-' : ( toOrder === 'asc' ? 'plt-sort-numeric-' : 'plt-sort-numberic-' );
      $('#btn-sort-item').attr( 'data-sort-by', toOrder );
      $('#btn-sort-item> i').attr( 'class', ClassPrefix + toOrder );
    }
    loadingList( 'hide' );
  };
  
  /*
   * Toggle an effect for updating ideas list (:> リスト更新エフェクトの切り替え
   */
  function loadingList( state ) {
    var $listBody = $('.char-list'),
        $loading  = $( '<div></div>', { "class": "loading-list" } );
    switch ( state ) {
      case 'show':
        //doingAsync = true;
        $loading.append( '<i class="fa plt-spinner2 fa-pulse fa-2x fa-fw"></i><span>'+ localize_messages.loading_list +'</span>' );
        $listBody.append( $loading );
        // throw('debug');
        break;
      case 'hide':
        //doingAsync = false;
        $listBody.find('.loading-list').fadeOut('fast', function(){ $(this).remove(); });
        break;
    }
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