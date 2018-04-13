/**
 * For Edit Storyline (/edit-storyline/)
 */
'use strict';
$(document).ready(function() {
  
  var gf               = $("#structureSettings"),
      wss              = window.sessionStorage,
      currentPermalink = 'edit-storyline',
      currentSrcId     = Number( $('#source_id').val() );
  SUBMIT_BUTTONS   = [ 'add', 'update', 'remove' ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  clearSessionData();
  
  // ----- Event handlers -------------------------------------------------------------------------
  
  /*
   * Clicked Cancel (:> Cancelボタン押下時のイベント
   */
  $('#'+currentPermalink+'-btn-cancel').on('click', function(e){
    e.preventDefault();
    logger.debug( 'Canceled' );
    // sessionStorageを初期化
    clearSessionData();
    // Cookieを初期化
    docCookies.removeItem( 'dependency', '/' );
    docCookies.removeItem( 'group_id', '/' );
    showLoading();
    // reload?
    location.reload(false);
  });
  
  /*
   * Remove Step (:> Step削除ボタン押下時のイベント
   */
  $(document).on('click', '.btn-remove-act', function(e){
    e.preventDefault();
    var targetStep   = Number( $(this).parents('li').attr('data-step') ),
        hashKey      = $(this).parents('li').attr('data-hash'),
        $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li'),
        selectedStep;
    $wizardSteps.each(function(){
      if ( $(this).find('.step_indicator').hasClass('selected') ) {
        selectedStep = Number( $(this).attr( 'data-step' ) );
      }
      if ( $(this).attr( 'data-hash' ) === hashKey ) {
        // Wizard上の表示を消す
        $(this).remove();
      }
    });
    $wizardSteps.parent('ul').removeAttr('style');
    resizeWizardSteps();
    logger.debug( 'Removed Step: ', targetStep, ' Hash: ', hashKey );
    // セッションストレージのステップデータを更新／削除する
    if ( ! wSQL.select( hashKey, 'structure_id' ) ) {
      // structure_idを持たないデータは物理削除
      wSQL.delete( hashKey );
    } else {
      // structure_idを持つデータは論理削除
      wSQL.update( hashKey, { turn: -1, diff: true } );
    }
    // リナンバリング処理
    reorderSteps();
    // アクティブなSTEPがない場合はフォーカスを初期化
    if ( targetStep == selectedStep ) {
      $($wizardSteps[0]).find('a.step_no').trigger('click').trigger('focus');
    }
    // Previous Act のセレクトボックスを更新
    updatePrevAct();
  });
  
  /*
   * Add New Step (:> Step追加ボタン押下時のイベント
   */
  $(document).on('click', '.add_new a', function(e){
    e.preventDefault();
    var $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li'),
        nowSteps     = $wizardSteps.length,
        newHash      = makeHash( Date.now() ),
        step_tmpl    = $('#wizard-templates ul.common-step-template li').clone();
    step_tmpl.find('button.btn-remove-act').removeClass('hide');
    step_tmpl.attr( 'data-hash', newHash );
    var newStep   = sprintf( step_tmpl[0].outerHTML, '', nowSteps, nowSteps * 10, nowSteps, nowSteps );
    $wizardSteps.filter(':last-child').remove();
    $wizardSteps.parent('.wizard_steps').append( $(newStep)[0].outerHTML );
    var last_step_tmpl = $('#wizard-templates ul.last-step-template li').clone();
    last_step_tmpl = sprintf( $(last_step_tmpl)[0].outerHTML, (nowSteps + 1) * 10 );
    $wizardSteps.parent('.wizard_steps').append( $(last_step_tmpl)[0].outerHTML );
    logger.debug( 'Added Step: ', nowSteps, ' Hash: ', newHash );
    resizeWizardSteps();
    // 新規追加したステップデータをセッションストレージへ保存
    var newStepData = {
      'hash': newHash,
      'id': '',
      'dependency': Number( gf.find('input[name="dependency"]').val() ),
      'group_id': Number( gf.find('#act-group-id').val() ),
      'turn': nowSteps,
      'name': '',
      'context': '',
      'diff': true
    };
    saveStepData( newStepData );
    // リナンバリング処理
    reorderSteps();
    // 新規追加ステップをフォーカス
    $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li[data-hash="'+ newHash +'"]').find('a.step_no').trigger('click');
    // Previous Act のセレクトボックスを更新
    updatePrevAct();
  });
  
  /*
   * Selected Step (:> Step選択時のイベント
   */
  //$(document).on('click', '.step_indicator:not(.add_new) a', function(e){
  $(document).on('click', '.step_indicator a', function(e){
    e.preventDefault();
    var $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li');
    if ( $wizardSteps.children('div.step_indicator.selected').length > 0 ) {
      // 現在のフォームデータをsessionStorageに保存
      saveStepData();
    }
    var newStep = $(this).parents('li').attr( 'data-step' ),
        hashKey = $(this).parents('li').attr( 'data-hash' ),
        previousStep = 0;
    if ( 'last' === newStep ) {
      return false;
    } else {
      newStep = Number( newStep );
    }
    $wizardSteps.find('.step_indicator').removeClass('selected');
    $wizardSteps.each(function( i ){
      if ( newStep == Number( $(this).attr('data-step') ) ) {
        $(this).find('.step_indicator').addClass('selected');
        previousStep = Number( $($wizardSteps[i - 1]).attr( 'data-step' ) ) || 0;
      }
    });
    $('#act-turn').val( newStep );
    updatePrevAct( previousStep );
    if ( hashKey ) {
      // sessionStorageから対象ステップのフォームデータを取得する
      logger.debug( 'Selected Step: ', newStep, '; hash: ', hashKey );
      retriveStepData( hashKey );
    }
  });
  
  /*
   * Changed the Dependency (:> 依存関係の変更時
   */
  $(document).on('change', '#act-dependency', function(e){
    // logger.debug( e.target, $(this).val(), gf.find('input[name="dependency"]').val() );
    var newDependency = Number( $(this).val() );
    if ( newDependency != Number( gf.find('input[name="dependency"]').val() ) ) {
      gf.find('input[name="dependency"]').val( newDependency );
      var stepDataKeys = getStoredKeys();
      stepDataKeys.forEach(function( key ) {
        if ( wss.hasOwnProperty( key ) ) {
          var step_data = JSON.parse( wss.getItem( key ) );
          if ( newDependency != step_data.dependency ) {
            step_data.dependency = newDependency;
            step_data.diff = true;
            wSQL.update( key, step_data );
          }
        }
      });
    }
  });
  
  /*
   * Changed the Previous Act (:> Previous Act変更時
   */
  $(document).on('change', '#change-turn', function(e){
    var $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> .wizard_steps> li'),
        nowStep      = null, // 順序を変更するSTEP（turn）
        newPrevStep  = Number( $(this).val() ), // 新たな順序における直前STEP
        currentSteps = [], // 現在の全STEP順
        currentOrder = [], // 現在の全Order順
        nowStepIndex = [],
        afterIndex   = -1,
        beforeIndex  = null;
    $wizardSteps.each(function( i ){
      if ( 'last' !== $(this).attr( 'data-step' ) ) {
        var _step = Number( $(this).attr( 'data-step' ) );
        currentSteps.push( _step );
        currentOrder.push( Number( $(this).css( 'order' ) ) );
        nowStepIndex.push( i );
        if ( $(this).find('.step_indicator').hasClass('selected') ) {
          nowStep = _step;
          beforeIndex = i;
        }
        if ( newPrevStep == _step ) {
          afterIndex = i;
        }
      }
    });
//console.log( currentSteps, currentOrder, nowStep, beforeIndex, afterIndex );
    currentOrder[beforeIndex] = afterIndex < 0 ? 1 : currentOrder[afterIndex] + 1;
    var sortOrder = currentOrder.slice();
    sortOrder.sort(function( a, b ){ return a- b; });
//console.log( sortOrder );
    currentOrder.forEach(function( v, i ){
      sortOrder.find(function( d, j ){ if ( d == v ) currentOrder[i] = nowStepIndex[j] });
    });
//console.log( currentSteps, currentOrder, sortOrder, nowStepIndex );
    var newStepList = [];
    $wizardSteps.each(function( i ){
      var $tmpStep = $(this).clone();
//console.log( $tmpStep, $tmpStep.attr('data-step') );
      if ( 'last' !== $tmpStep.attr('data-step') ) {
        //$tmpStep.attr( 'data-step', currentSteps[i] );
        //$tmpStep.css( 'order', sortOrder[i] );
        newStepList[currentOrder[i]] = $tmpStep;
//console.log( i, currentOrder[i], $tmpStep.attr('data-hash'), currentSteps[i], $tmpStep.attr('data-step'), sortOrder[i], $tmpStep.css('order') );
      } else {
        newStepList[i]= $tmpStep;
      }
    });
//console.log( newStepList );
    $('#wizard.wizard_horizontal> .wizard_steps_container> .wizard_steps').html('');
    newStepList.forEach(function( elm, i ){
      $(elm[0]).attr( 'data-step', currentSteps[i] );
      $(elm[0]).css( 'order', sortOrder[i] );
      if ( i == 0 ) {
        $(elm[0]).find('.btn-remove-act').addClass('hide');
      } else {
        $(elm[0]).find('.btn-remove-act').removeClass('hide');
      }
      $('#wizard.wizard_horizontal> .wizard_steps_container> .wizard_steps').append( elm[0].outerHTML );
//console.log( elm,     elm[0].outerHTML);
    });
    reorderSteps();
    updatePrevAct();
  });
  
  /*
   * Clicked Sub-Storyline and Parent-Storyline (:> サブストーリーラインおよび親ストーリーライン選択時のイベント
   */
  $(document).on('click', 'a.sub_storyline, a.parent_storyline', function(e){
    e.preventDefault();
    var strAtts = $(this).parent('li').data();
    dialogOpts.title = localize_messages.move_cross_dependency_ttl;
    dialogOpts.text  = [ localize_messages.move_cross_dependency_msg, localize_messages.are_you_sure ].join('<br>');
    dialogOpts.modules.Confirm.buttons[0].click = (notice, value) => {
      notice.close();
      docCookies.setItem( 'dependency', strAtts.dependency, 60*60*24*30, '/' );
      docCookies.setItem( 'group_id', strAtts.groupId, 60*60*24*30, '/' );
      showLoading();
      location.reload(false);
    };
    PNotify.alert( dialogOpts );
  });
  
  /*
   * Clicked "Add Sub-Storyline" (:> サブストーリーライン追加時のイベント
   */
  $(document).on('click', 'a.add_sub', function(e){
    e.preventDefault();
    var strAtts = $(this).parent('li').data();
    dialogOpts.title = localize_messages.add_sub_storyline_ttl;
    dialogOpts.text  = [ localize_messages.move_cross_dependency_msg, localize_messages.are_you_sure ].join('<br>');
    dialogOpts.modules.Confirm.buttons[0].click = (notice, value) => {
      notice.close();
      docCookies.setItem( 'dependency', strAtts.parentStructureId, 60*60*24*30, '/' );
      docCookies.setItem( 'group_id', '-1', 60*60*24*30, '/' );
      showLoading();
      location.reload(false);
    };
    PNotify.alert( dialogOpts );
  });
  
  /*
   * Clicked Remove (:> 削除ボタンクリック時のイベント
   */
  // $(document).on('click', '#'+currentPermalink+'-btn-remove', function(e){
  $('#'+currentPermalink+'-btn-remove').on('click', function(e){
    e.preventDefault();
    dialogOpts.title = localize_messages.remove_dependent_storylines_ttl;
    dialogOpts.text  = [ localize_messages.remove_dependent_storylines_msg, localize_messages.are_you_sure ].join('<br>');
    dialogOpts.modules.Confirm.buttons[0].click = (notice, value) => {
      notice.close();
      // 確認ダイアログ承認後の削除処理
      $('#'+currentPermalink+'-post-action').val( 'remove' );
      var base_data = conv_kv( gf.serializeArray() );
      delete base_data.structure_id;
      delete base_data.context;
      delete base_data.name;
      delete base_data.turn;
      delete base_data.diff;
      var post_data = JSON.stringify( base_data );
      logger.debug( value, base_data, post_data );
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
   * Clicked Add (:> (サブストーリーライン)追加ボタンクリック時のイベント
   */
  $('#'+currentPermalink+'-btn-add').on('click', function(e){
    e.preventDefault();
    var postData     = [],
        stepDataKeys = getStoredKeys();
    controlSubmission();
    // 現在のフォームデータをsessionStorageに保存
    saveStepData();
    $('#'+currentPermalink+'-post-action').val( 'add' );
    // セッションストレージ上の全ステップデータをマージする
    stepDataKeys.forEach(function( key ) {
      if ( wss.hasOwnProperty( key ) ) {
        var step_data = JSON.parse( wss.getItem( key ) );
        if ( step_data.diff ) {
          if ( step_data.turn < 0 ) {
            step_data.name = '';
            step_data.context = '';
          }
          postData.push( step_data );
        }
      }
    });
    // logger.debug( stepDataKeys, postData, postData.length );
    if ( postData.length > 0 ) {
      showLoading();
      var addField = $('<input>', { type: 'hidden', name: 'step_data', value: JSON.stringify( postData ) });
      gf.append( addField );
      // 画面上に表示されているフォームのデータは送信対象から除外する
      $('#act-form-current').find('input,select,textarea').each(function(){
        $(this).prop('disabled', true);
      });
      // ajaxでpost
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
      //controlSubmission( 'unlock' );
    } else {
      logger.debug( 'There are no changes to must be updated.' );
    }
    controlSubmission( 'unlock' );
  });
  
  /*
   * Clicked Commit (:> コミットボタンクリック時のイベント
   */
  $('#'+currentPermalink+'-btn-update').on('click', function(e){
    e.preventDefault();
    var postData     = [],
        stepDataKeys = getStoredKeys();
    controlSubmission();
    // 現在のフォームデータをsessionStorageに保存
    saveStepData();
    $('#'+currentPermalink+'-post-action').val( 'update' );
    // POSTする全ステップデータをマージする
    stepDataKeys.forEach(function( key ) {
      if ( wss.hasOwnProperty( key ) ) {
        var step_data = JSON.parse( wss.getItem( key ) );
        if ( step_data.diff ) {
          if ( step_data.turn < 0 ) {
            step_data.name = '';
            step_data.context = '';
          }
          postData.push( step_data );
        }
      }
    });
    // logger.debug( stepDataKeys, postData, postData.length );
    if ( postData.length > 0 ) {
      showLoading();
      var addField = $('<input>', { type: 'hidden', name: 'step_data', value: JSON.stringify( postData ) });
      gf.append( addField );
      // 画面上に表示されているフォームのデータは送信対象から除外する
      $('#act-form-current').find('input,select,textarea').each(function(){
        $(this).prop('disabled', true);
      });
      // ajaxでpost
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
      //controlSubmission( 'unlock' );
    } else {
      logger.debug( 'There are no changes to must be updated.' );
    }
    controlSubmission( 'unlock' );
  });
  
  /*
   *  (:> ACT名の入力同期
   */
  $('#act-name').on('focus keyup', function(){
    if ( ! is_empty( $(this).val() ) ) {
      var hashKey     = gf.find('#act-hash').val(),
          currentName = $(this).val(),
          $wizardStep = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li[data-hash="'+hashKey+'"]');
      $wizardStep.find('.step_name').text( currentName );
      if ( wSQL.select( hashKey, 'name' ) !== currentName ) {
        wSQL.update( hashKey, { diff: true } );
      }
    }
  });
  
  /*
   *  (:> 画面内の各種キーイベントの制御
   */
  $(document).on( 'keydown', 'body', function(e){
    var evt = e.originalEvent;
    // Tabキーによるステップ変更
    if ( evt.key === 'Tab' && evt.target.hash === '#act-form' ) {
      // logger.debug( evt );
      var currentStep = $(evt.target).parents('li'),
          nextStep    = currentStep.next('li');
      e.preventDefault();
      if ( nextStep.attr('data-step') !== 'last' ) {
        nextStep.find('a.step_no').trigger('click').trigger('focus');
      } else {
        $('#wizard.wizard_horizontal> .wizard_steps_container> .wizard_steps> li[data-step="1"]').find('a.step_no').trigger('click').trigger('focus');
      }
    }
    // Enterキーによるフォーム変更
    if ( ( evt.key === 'Enter' || evt.key === 'Tab' ) && $(evt.target).hasClass('form-control') ) {
      // logger.debug( evt );
      var availableFields = gf.find('.form-control'),
          currentFieldId  = evt.target.id,
          nextFieldIndex  = 0;
      e.preventDefault();
      availableFields.each(function( i ){
        if ( $(this)[0].id === currentFieldId ) {
          nextFieldIndex = i + 1 < availableFields.length ? i + 1 : 0;
          return false;
        }
      });
      $(availableFields[nextFieldIndex]).trigger('click').trigger('focus');
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
  
  function defaultFocus() {
    $('.step_indicator.selected> a').trigger( 'focus' );
  }
  
  /*
   * reOrder Steps (:> STEPのorder番号をリナンバリング（正規化）する（セッションストレージの対応データも更新する）
   */
  function reorderSteps() {
    var nowOrder     = [],
        optOrder     = [],
        $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> .wizard_steps> li');
    $wizardSteps.each(function( i ){
      nowOrder.push( Number( $(this).css('order') ) );
      optOrder.push( i + 1 );
    });
    var sortOrder = nowOrder.slice();
    sortOrder.sort(function(a,b){ return a - b; });
    nowOrder.forEach(function(v,i){
      sortOrder.find(function(d,j){ if ( d == v ) nowOrder[i] = optOrder[j]; });
    });
    $wizardSteps.each(function( i ){
      var hashKey      = $(this).data('hash'),
          newStep      = nowOrder[i],
          newSortOrder = nowOrder[i] * 10;
      $(this).css( 'order', newSortOrder );
      $(this).find('.step_indicator:not(.add_new) .step_no').text( newStep );
      if ( ! is_empty( hashKey ) ) {
        $(this).attr( 'data-step', newStep );
        if ( wSQL.select( hashKey, 'turn' ) !== newStep ) {
          wSQL.update( hashKey, { turn: newStep, diff: true } );
          //logger.debug( hashKey, wSQL.select( hashKey, 'turn' ), newStep );
        }
      }
    });
    //logger.debug( { 'newOrder': nowOrder, 'sortOrder': sortOrder } );
  }
  
  /*
   * Modify the select-box named "Previous Act" (:> Previous Actのセレクトボックスを更新する
   */
  function updatePrevAct( previousStep = null ) {
    var $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> .wizard_steps> li'),
        $changeTurn  = $('#change-turn');
    if ( ! previousStep ) {
      // 現在のセレクトボックスを初期化
      $changeTurn.children('option').each(function( i ){
        var optValue = Number( $(this).val() );
        if ( optValue == 0 ) {
          return;
        } else {
          $(this).remove();
        }
      });
      // セレクトボックスを再構築
      $wizardSteps.each(function( i ){
        var hashKey = $(this).attr( 'data-hash' );
        if ( ! is_empty( hashKey ) ) {
          var stepNum     = Number( $(this).attr( 'data-step' ) ),
              newOption   = $('<option>', { value: stepNum }),
              actName     = wSQL.select( hashKey, 'name' ) || $(this).find('.step_name').text(),
              is_selected = $(this).find('.step_indicator').hasClass('selected');
          newOption.text( actName );
          $changeTurn.append( newOption[0].outerHTML );
          if ( is_selected ) {
            previousStep = Number( $($wizardSteps[i - 1]).attr( 'data-step' ) ) || 0;
          }
        }
      });
    }
    // 選択状態を更新
    $changeTurn.children('option').each(function(){
      if ( previousStep == Number( $(this).val() ) ) {
        $(this).prop('selected', true);
        return false;
      }
    });
  }
  
  /*
   * Resize Wizard Steps (:> 
   */
  function resizeWizardSteps() {
    var $wizardStep = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps'),
        wizard_steps_container_width = $wizardStep.parent().width(),
        wizard_steps_width = Math.ceil( $wizardStep.width() ),
        error_width = Math.ceil( wizard_steps_width / ( (window.innerWidth) ? window.innerWidth : document.body.clientWidth ) * 2 ),
        wizard_steps_scrollwidth = Math.ceil( $wizardStep[0].scrollWidth ),
        nowSteps    = $wizardStep.children('li').length,
        step_width  = $wizardStep.children('li').width(),
        expected_width = Math.ceil( nowSteps * step_width );
    //if ( wizard_steps_width + error_width < wizard_steps_scrollwidth || wizard_steps_width + error_width < expected_width ) {
    if ( wizard_steps_container_width + error_width < wizard_steps_scrollwidth || wizard_steps_container_width + error_width < expected_width ) {
      logger.debug( 'onScroll', wizard_steps_container_width, wizard_steps_width, wizard_steps_scrollwidth, expected_width, error_width );
      $('#act-form').addClass('off-mask');
      $wizardStep.width( expected_width );
    } else {
      logger.debug( 'offScroll', wizard_steps_container_width, wizard_steps_width, wizard_steps_scrollwidth, expected_width, error_width );
      $('#act-form').removeClass('off-mask');
      $wizardStep.width( '100%' );
    }
    $wizardStep.parent().scrollLeft( expected_width - wizard_steps_container_width );
  }
  
  
  
  // ----- セッションストレージ関連 ---------------------------------------------------------------
  
  /*
   *  (:> セッションストレージ初期化
   */
  function clearSessionData() {
    logger.debug( 'Clear All SessionStorage, then set data of current steps' );
    wss.clear();
    var $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li'),
        totalSteps   = $wizardSteps.length - 1;
    $wizardSteps.each(function(){
      var availableStep = $(this).attr('data-step'),
          structureId   = is_empty( $(this).attr('data-structure-id') ) ? '' : Number( $(this).attr('data-structure-id') ),
          hashKey       = is_empty( structureId ) ? makeHash( Date.now() ) : makeHash( structureId );
      if ( availableStep === 'last' ) {
        return true; // as continue
      }
      $(this).attr( 'data-hash', hashKey );
      if ( ! is_empty( structureId ) ) {
        // structure_idを持つステップデータをDBから読み込む
        if ( ! window.isLoading ) {
          showLoading();
        }
        callAjax( wpApiSettings.root + 'plotter/v1/str/' + structureId, 'GET' )
        .then( function( data, stat, self ){
          if ( 'success' === stat ) {
            data[0]['hash'] = hashKey;
            if ( Number( availableStep ) != data[0]['turn'] ) {
              data[0]['turn'] = Number( availableStep );
              data[0]['diff'] = true;
            }
            logger.debug( data[0] );
            saveStepData( data[0] );
            totalSteps--;
            if ( totalSteps < 1 && window.isLoading ) {
              hideLoading();
              reorderSteps();
              defaultFocus();
            }
          }
        });
      } else {
        // structure_idがないサブストーリーライン追加時はDBにデータがないので非同期処理はせずに、現フォームから補完
        var data = {
          'hash': hashKey,
          'id': structureId,
          'dependency': Number( gf.find('input[name="dependency"]').val() ),
          'group_id': Number( $(this).attr('data-group-id') ),
          'turn': Number( availableStep ),
          'name': gf.find('#act-name').val(),
          'context': gf.find('#act-context').val(),
          'diff': true
        };
        saveStepData( data );
        if ( $(this).index() == 0 && is_empty( gf.find('#act-hash').val() ) ) {
          gf.find('#act-hash').val( hashKey );
        }
        defaultFocus();
      }
    });
  }
  
  /*
   *   (:> セッションストレージ内の全データのキー名を配列として取得する
   */
  function getStoredKeys() {
    var keys = [];
    for ( var _key in wss ) {
      if ( _key !== 'length' && typeof wss[_key] !== 'function' && ! /^wp\-api\-schema\-model/.test( _key ) ) {
        keys.push( _key );
      }
    }
    return keys;
  }
  
  /*
   *  (:> セッションストレージの指定ストラクチャーを持つステップデータの差分をチェックする（差分があれば true が返る）
   */
  function checkDiffStep( hashKey ) {
    var currentData = {
          'structure_id': is_empty( gf.find('#act-structure-id').val() ) ? '' : Number( gf.find('#act-structure-id').val() ),
          'dependency':   Number( gf.find('input[name="dependency"]').val() ),
          'group_id':     Number( gf.find('#act-group-id').val() ),
          'turn':         Number( gf.find('#act-turn').val() ),
          'name':         gf.find('#act-name').val(),
          'context':      gf.find('#act-context').val()
        },
        diff = false;
    if ( wss.hasOwnProperty( hashKey ) ) {
      var storedData = wSQL.select( hashKey );
      for ( var prop in currentData ) {
        // logger.debug( prop, storedData[prop] !== currentData[prop], storedData[prop], currentData[prop] );
        diff = storedData[prop] !== currentData[prop];
        if ( diff ) { break; }
      }
    }
    return diff;
  }
  
  /* 不要になった
  / *
   *  (:> セッションストレージから指定ステップデータを削除
   * /
  function removeStepData( hashKey ) {
    var rm_list = 'plt_rm_str';
    if ( wss.hasOwnProperty( hashKey ) ) {
      var structureId = wSQL.select( hashKey, 'structure_id' );
      if ( ! is_empty( structureId ) ) {
        // StructureIdを持つステップデータの削除は、削除リストに追加する:
        var remove_steps;
        if ( wss.hasOwnProperty( rm_list ) ) {
          remove_steps = JSON.parse( wss.getItem( rm_list ) );
          remove_steps.push( structureId );
        } else {
          remove_steps = [ structureId ];
        }
        wss.setItem( rm_list, JSON.stringify( remove_steps ) );
      }
      wss.removeItem( hashKey );
    }
  }
  
  / *
   *  (:> セッションストレージ内のキー名をリナンバリングする
   * /
  function updateStepkeys( fromStep = 1 ) {
    var $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li'),
        stepDataSize = wss.length,
        stepDataKeys = [],
        renumber     = fromStep;
    for ( var i = 0; i < stepDataSize; i++ ) {
      var key_str = wss.key( i );
      if ( /^plt_str_\d{1,}/.test( key_str ) ) {
        stepDataKeys.push( key_str );
      }
    }
    $wizardSteps.each(function() {
      var currentStep = Number( $(this).attr( 'data-step' ) ),
          structureId = Number( $(this).data( 'structureId' ) ),
          checkHash   = is_empty( $(this).data( 'hash' ) ) ? '' : $(this).data( 'hash' ),
          checkKey    = 'plt_str_' + currentStep;
      if ( currentStep >= fromStep ) {
        logger.debug( currentStep, structureId, checkHash, checkKey );
        if ( ! is_empty( checkHash ) ) {
          $.each( stepDataKeys, function( _i, _key ) {
            var stepData = JSON.parse( wss.getItem( _key ) );
            if ( checkHash === stepData.hash ) {
              if ( checkKey !== _key ) {
                wss.setItem( checkKey, JSON.stringify( stepData ) );
              }
            }
          });
        } else {
          wss.removeItem( checkKey );
        }
      }
    });
  }
  */
  
  /*
   *  (:> セッションストレージもしくはDBから指定ステップデータを取得してフォームにセット
   */
  function retriveStepData( hashKey ) {
    if ( ! wss.hasOwnProperty( hashKey ) ) {
      var $wizardStep  = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps').find('li[data-hash="'+ hashKey +'"]'),
          structureId  = is_empty( $wizardStep.attr( 'data-structure-id' ) ) ? '' : Number( $wizardStep.attr( 'data-structure-id' ) ),
          groupId      = is_empty( $wizardStep.attr( 'data-group-id' ) ) ? gf.find('#act-group-id').val() : $wizardStep.attr( 'data-group-id' ),
          stepNumber   = Number( $wizardStep.attr( 'data-step' ) ),
          step_name    = $wizardStep.find('.step_name').text();
      gf.find('#act-structure-id').val( structureId );
      gf.find('#act-dependency').val( gf.find('input[name="dependency"]').val() );
      gf.find('#act-group-id').val( groupId );
      gf.find('#act-turn').val( stepNumber );
      gf.find('#act-name').val( is_empty( step_name ) ? sprintf( localize_messages.act_num, stepNumber ) : step_name );
      gf.find('#act-diff').val( 'false' );
      gf.find('#act-hash').val( hashKey );
      if ( ! is_empty( structureId ) ) {
        controlSubmission();
        callAjax( wpApiSettings.root + 'plotter/v1/str/' + structureId, 'GET' )
        .then( function( data, stat, self ){
          if ( 'success' === stat ) {
            logger.debug( data[0] );
            gf.find('#act-context').val( data[0].context );
            controlSubmission( 'unlock' );
          }
        });
      } else {
        gf.find('#act-context').val( '' );
      }
    } else {
      var step_data = wSQL.select( hashKey );
      gf.find('#act-structure-id').val( step_data['structure_id'] );
      gf.find('#act-dependency').val( step_data['dependency'] );
      gf.find('#act-group-id').val( step_data['group_id'] );
      gf.find('#act-turn').val( step_data['turn'] );
      gf.find('#act-name').val( is_empty( step_data['name'] ) ? sprintf( localize_messages.act_num, step_data['turn'] ) : step_data['name'] );
      gf.find('#act-context').val( step_data['context'] );
      gf.find('#act-diff').val( step_data['diff'] );
      gf.find('#act-hash').val( hashKey );
    }
    gf.find('#act-name').trigger('blur');
  }
  
  /*
   *  (:> 指定のステップデータをセッションストレージへ保存（dataがない場合は現在のフォーム値を使用する）
   */
  function saveStepData( data=null ) {
    var step_data = {};
    if ( is_empty( data ) ) {
      var structureId = is_empty( gf.find('#act-structure-id').val() ) ? '' : Number( gf.find('#act-structure-id').val() ),
          hashKey     = gf.find('#act-hash').val();
      step_data = {
        'structure_id': structureId,
        'dependency':   Number( gf.find('input[name="dependency"]').val() ),
        'group_id':     Number( gf.find('#act-group-id').val() ),
        'turn':         Number( gf.find('#act-turn').val() ),
        'name':         gf.find('#act-name').val(),
        'context':      gf.find('#act-context').val(),
        'diff':         gf.find('#act-diff').val() === 'false' ? checkDiffStep( hashKey ) : true,
      };
    } else {
      var hashKey = is_empty( data.hash ) ? makeHash( data.id ) : data.hash;
      step_data = {
        'structure_id': data.id,
        'dependency':   data.dependency,
        'group_id':     data.group_id,
        'turn':         data.turn,
        'name':         is_empty( data.name ) ? '' : data.name,
        'context':      is_empty( data.context ) ? '' : data.context,
        'diff':         is_empty( data.diff ) ? false : data.diff,
      };
    }
    wSQL.insert( hashKey, step_data );
  }
  
  
});