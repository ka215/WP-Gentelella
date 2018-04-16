/**
 * For Create New Storyline (/create-new/)
 */
'use strict';
$(document).ready(function() {
  
  var gf                = $("#structureSettings"),
      wss               = window.sessionStorage,
      currentPermalink  = 'create-new',
      currentSrcId      = Number( $('#source_id').val() ),
      structureType     = Number( $('#structure-presets').find('option:selected').val() ),
      presetPlaceholder = [];
  SUBMIT_BUTTONS   = [ 'create' ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  clearSessionData();
  setPresetPlaceholder();
  resizeWizardSteps();
  
  // ----- Event handlers -------------------------------------------------------------------------
  /*
   * Selected Structure Preset (:> プリセットを選択(変更)した時のイベント
   */
  $('#structure-presets').on('change', function(){
    var selectedVar = Number( $(this).val() ),
        selectedActs = JSON.parse( $($(this).find('option:selected')[0]).attr('data-acts').replace(/\'/g, '"') );
    if ( structureType == selectedVar ) {
      // 選択変更なしのためなにもしない
      return;
    } else {
      structureType = selectedVar;
    }
    // WizardのDOM要素を初期化
    $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li').remove();
    // フォーム値を初期化
    $('#act-structure-id').val('');
    $('#act-dependency').val('0');
    $('#act-turn').val('1');
    $('#act-name').val('');
    $('#act-context').val('');
    // Wizardを再生成
    rebuildWizard( selectedActs );
    // sessionStorageを初期化
    clearSessionData();
  });
  
  /*
   * Clicked Reset (:> Resetボタン押下時のイベント
   */
  $('#create-new-btn-reset').on('click', function(e){
    e.preventDefault();
    // Custom Structure選択状態に変更
    $('#structure-presets> option').each(function(){
      if ( $(this).index() == 0 ) {
        $(this).prop('selected', true);
      } else {
        $(this).prop('selected', false);
      }
    });
    // structureTypeを一旦初期化用に変更
    structureType = -1;
    $('#structure-presets').trigger('change');
    logger.debug( 'Reseted Step' );
    // sessionStorageを初期化
    clearSessionData();
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
    $wizardSteps.each(function( i ){
      var currentStep = $(this).attr('data-step'),
          stepNumber  = parseInt( currentStep, 10 ) || false;
      if ( $(this).find('.step_indicator').hasClass('selected') ) {
        selectedStep = stepNumber;
      }
      if ( stepNumber == targetStep ) {
        $(this).remove();
      } else
      if ( stepNumber !== false && stepNumber > targetStep ) {
        $(this).attr( 'data-step', i );
        $(this).find('.step_no').text( i );
      }
    });
    $wizardSteps.parent('ul').removeAttr('style');
    resizeWizardSteps();
    logger.debug( 'Removed Step: ', targetStep, '; Hash: ', hashKey );
    // sessionStorageから対象ステップのフォームデータを削除する
    wSQL.delete( hashKey );
    // セッションストレージのSTEPデータのturn値を更新する
    updateStepTurn();
    // プリセットの選択状態を初期化
    $('#structure-presets> option:selected').prop( 'selected', false );
    structureType = -1;
    // STEPのフォーカス
    if ( targetStep == selectedStep ) {
      // STEP削除後にアクティブなSTEPがなくなる場合はフォーカスを初期化
      focusStep();
    } else {
      focusStep( hashKey );
    }
  });
  
  /*
   * Add New Step (:> Step追加ボタン押下時のイベント
   */
  $(document).on('click', '.add_new a', function(e){
    e.preventDefault();
    var $wizardSteps   = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li'),
        nowSteps       = $wizardSteps.length,
        newHash        = makeHash( Date.now() ),
        step_tmpl      = $('#wizard-templates ul.common-step-template li').clone(),
        last_step_tmpl = $('#wizard-templates ul.last-step-template li').clone();
    step_tmpl.find('button.btn-remove-act').removeClass('hide');
    step_tmpl.attr( 'data-hash', newHash );
    var newStep   = sprintf( step_tmpl[0].outerHTML, nowSteps, nowSteps, nowSteps );
    $wizardSteps.filter(':last-child').remove();
    $wizardSteps.parent('.wizard_steps').append( $(newStep)[0].outerHTML );
    $wizardSteps.parent('.wizard_steps').append( $(last_step_tmpl)[0].outerHTML );
    logger.debug( 'Added Step: ', nowSteps, ' Hash: ', newHash );
    resizeWizardSteps();
    // プリセットの選択状態を初期化
    $('#structure-presets> option:selected').prop( 'selected', false );
    structureType = -1;
    // 新規追加したステップデータをセッションストレージへ保存
    var newStepData = {
      'hash': newHash,
      'id': '',
      'dependency': Number( gf.find('#act-dependency').val() ),
      'group_id': 0,
      'turn': nowSteps,
      'name': '',
      'context': '',
      'diff': true
    };
    saveStepData( newStepData );
    // 新規追加ステップをフォーカス
    focusStep( newHash );
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
        hashKey = $(this).parents('li').attr( 'data-hash' );
    if ( 'last' === newStep ) {
      return false;
    } else {
      newStep = Number( newStep );
    }
    $wizardSteps.find('.step_indicator').removeClass('selected');
    $wizardSteps.each(function( i ){
      if ( newStep == Number( $(this).attr('data-step') ) ) {
        $(this).find('.step_indicator').addClass('selected');
        return false;
      }
    });
    $('#act-turn').val( newStep );
    if ( hashKey ) {
      // sessionStorageから対象ステップのフォームデータを取得する
      logger.debug( 'Selected Step: ', newStep, '; hash: ', hashKey );
      retriveStepData( hashKey );
    }
    // 表示位置調整
    resizeWizardSteps( newStep );
  });
  
  /*
   * Clicked Create (:> 新規作成ボタン押下時のイベント
   */
  $(document).on('click', '#create-new-btn-create', function(e){
    e.preventDefault();
    var postData     = [],
        stepDataKeys = getStoredKeys();
    controlSubmission();
    // 現在のフォームデータをsessionStorageに保存
    saveStepData();
    $('#create-new-post-action').val( 'create' );
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
    if ( ! debugPostData( postData ) ) return false; /* ***** For debug, it shows summary on the console before posting data. ****** */
    if ( postData.length > 0 ) {
      showLoading();
      var addField = $('<input>', { 'type': 'hidden', 'name': 'step_data', 'value': JSON.stringify( postData ) });
      gf.append( addField );
      // 最後に表示されているフォームのデータは送信対象から除外する
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
      logger.debug( 'There are no data to must be inserted.' );
    }
    controlSubmission( 'unlock' );
  });
  
  /*
   *  (:> ACT名の入力同期
   */
  $('#act-name').on('focus keyup', function(){
    // ACT名の入力同期
    if ( ! is_empty( $(this).val() ) ) {
      var currentStep  = Number( $('#act-turn').val() ),
          currentName  = $(this).val(),
          $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li');
      if ( currentStep > 0 ) {
        $wizardSteps.each(function(){
          if ( Number( $(this).data('step') ) == currentStep ) {
            $(this).find('.step_name').text( currentName );
            return false;
          }
        });
      }
    }
  });
  
  /*
   *  (:> 画面内の各種キーイベントの制御
   */
  $(document).on( 'keydown', 'body', function(e){
    var evt = e.originalEvent;
    // Tabキーによるステップのフォーカス変更(Shift+Tabで戻る)
    if ( evt.key === 'Tab' ) {
      e.preventDefault();
      var $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> .wizard_steps> li'),
          allSteps     = $wizardSteps.length - 1, // 末尾STEPは除外
          $currentStep, $prevStep, $nextStep;
      $wizardSteps.each(function( i ){
        if ( $(this).find('.step_indicator').hasClass('selected') ) {
          $currentStep = $(this);
          $prevStep    = i == 0 ? $($wizardSteps[(allSteps - 1)]) : $($wizardSteps[(i - 1)]);
          $nextStep    = i < (allSteps - 1) ? $($wizardSteps[(i + 1)]) : $($wizardSteps[0]);
        }
      });
      logger.debug( evt, $prevStep.attr('data-step'), $currentStep.attr('data-step'), $nextStep.attr('data-step') );
      if ( evt.shiftKey ) {
        $prevStep.find('a.step_no').trigger('click').trigger('focus');
      } else {
        $nextStep.find('a.step_no').trigger('click').trigger('focus');
      }
    }
    // Enterキーによるフォームのフォーカス変更
    if ( evt.key === 'Enter' ) {
      e.preventDefault();
      var availableFields = gf.find('.form-control'),
          currentFieldId  = evt.target.id,
          nextFieldIndex  = 0;
      logger.debug( evt, availableFields, currentFieldId );
      availableFields.each(function( i ){
        if ( $(this)[0].id === currentFieldId ) {
          nextFieldIndex = i + 1 < availableFields.length ? i + 1 : 0;
          return false;
        }
      });
      $(availableFields[nextFieldIndex]).trigger('click').trigger('focus');
    }
  });
  
  /*
   * Fire on resize window (:> ウィンドウリサイズ時のイベント
   */
  $(window).resize(function(){
    resizeWizardSteps();
  });
  
  
  // ----- 個別処理（関数）------------------------------------------------------------------------
  
  /*
   * Initialize the preset placeholder of storyline type (:> ストーリーラインタイプのプリセット値を初期化
   */
  function setPresetPlaceholder() {
    $('#structure-presets> option').each(function() {
      var tmp = JSON.parse( $(this).attr('data-acts').replace(/\'/g, '"') );
      if ( tmp.length > 0 && ! is_empty( tmp[0] ) ) {
        presetPlaceholder = presetPlaceholder.concat( tmp );
      }
    });
    // As arrayUnique
    presetPlaceholder = presetPlaceholder.filter(function( v, i ){
      return i === presetPlaceholder.indexOf( v );
    });
    logger.debug( structureType, presetPlaceholder );
  }
  
  /*
   * On focus step (:> STEPのフォーカス
   */
  function focusStep( stepHash=null ) {
    var $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li');
    if ( is_empty( stepHash ) ) {
      if ( $wizardSteps.find('.step_indicator.selected').length == 0 ) {
        $($wizardSteps[0]).find('a.step_no').trigger('click');
      } else {
        $('.step_indicator.selected> a').trigger( 'focus' );
      }
    } else {
      $wizardSteps.filter('[data-hash="'+ stepHash +'"]').find('a.step_no').trigger('click');
    }
  }
  
  /*
   * Resize Wizard Steps (:> STEP表示欄のリサイズと表示位置の調整
   */
  function resizeWizardSteps( adjustStep=null ) {
    var $wizardSteps       = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps'),
        ws_containerWidth  = $wizardSteps.parent().outerWidth(), // STEP欄のコンテナ表示幅 (overflow-x指定)
        gapWidth           = parseInt( $('#wizard').css('margin-left') ) + parseInt( $('#wizard').css('margin-right') ), // 許容する余白幅
        steps              = $wizardSteps.children('li').length, // STEP欄のSTEP総数
        stepWidth          = $wizardSteps.children('li').outerWidth(), // 単一STEPの横幅
        ws_scrollWidth     = $wizardSteps[0].scrollWidth, // STEP欄の実際の横幅 (= step * stepWidth)
        adjustStep         = is_empty( adjustStep ) ? 0 : adjustStep - 1,
        adjustLeft         = 0;
    // logger.debug( ws_containerWidth, gapWidth, ws_scrollWidth, steps, stepWidth, adjustStep );
    if ( ws_containerWidth + gapWidth < ws_scrollWidth ) {
      // STEP欄の実際の横幅 > STEP欄の表示幅 の場合:
      $('#act-form').addClass('off-mask'); // スクロールバーを表示
      if ( adjustStep < steps ) {
        adjustLeft = stepWidth * adjustStep;
      }
    } else {
      // STEP欄の実際の横幅 <= STEP欄の表示幅 の場合:
      $('#act-form').removeClass('off-mask'); // スクロールバーを非表示
    }
    $wizardSteps.parent().scrollLeft( adjustLeft );
  }
  
  /*
   * Rebuild Wizard Steps (:> STEP欄の再作成
   */
  function rebuildWizard( acts ) {
    logger.debug( 'Build Wizard', acts );
    $.each( acts, function( i, v ){
      var step_tmpl = $('#wizard-templates ul.common-step-template li').clone(),
          step_num  = i + 1,
          step_name = is_empty( v ) ? sprintf( localize_messages.act_num, step_num ) : v;
      step_tmpl.find('ul.step_meta> li.step_name').text( step_name );
      if ( i == 0 ) {
        $('#act-name').val( step_name );
      }
      if ( i > 0 ) {
        step_tmpl.find('button.btn-remove-act').removeClass('hide');
      } else {
        step_tmpl.find('.step_indicator').addClass('selected');
      }
      var newStep = sprintf( step_tmpl[0].outerHTML, step_num, step_num, step_num );
      $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps').append( $(newStep)[0].outerHTML );
    });
    var last_step_tmpl = $('#wizard-templates ul.last-step-template li').clone();
    $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps').append( $(last_step_tmpl)[0].outerHTML );
    resizeWizardSteps();
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
          hashKey       = makeHash( sprintf( '%s|%s', Date.now(), availableStep ) );
      if ( availableStep === 'last' ) {
        return true; // as continue
      }
      $(this).attr( 'data-hash', hashKey );
      var data = {
        'hash': hashKey,
        'id': '',
        'dependency': Number( gf.find('#act-dependency').val() ),
        'group_id': 0,
        'turn': Number( availableStep ),
        'name': $(this).find('.step_name').text(),
        'context': '',
        'diff': true
      };
      saveStepData( data );
      focusStep();
    });
  }
  
  /*
   * Retrive all keys as array on session storage (:> セッションストレージ内の全データのキー名を配列として取得する
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
   * Update step's turn on session storage (:> セッションストレージのSTEPデータをturn値を更新する
   */
  function updateStepTurn() {
    var $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li');
    $wizardSteps.each(function(){
      var stepNum = $(this).attr('data-step');
      if ( 'last' !== stepNum ) {
        var hashKey = $(this).attr('data-hash');
        if ( wss.hasOwnProperty( hashKey ) ) {
          wSQL.update( hashKey, { turn: Number( stepNum ) } );
        }
      }
    });
  }
  
  /*
   *  (:> セッションストレージから指定ステップデータを取得してフォームにセット
   */
  function retriveStepData( hashKey ) {
    if ( ! wss.hasOwnProperty( hashKey ) ) {
      gf.find('#act-structure-id').val( '' );
      gf.find('#act-dependency').val( 0 );
      gf.find('#act-turn').val( 1 );
      gf.find('#act-name').val( sprintf( localize_messages.act_num, 1 ) );
      gf.find('#act-context').val( '' );
    } else {
      var step_data = wSQL.select( hashKey );
      gf.find('#act-structure-id').val( step_data['structure_id'] );
      gf.find('#act-dependency').val( step_data['dependency'] );
      gf.find('#act-turn').val( step_data['turn'] );
      gf.find('#act-name').val( is_empty( step_data['name'] ) ? sprintf( localize_messages.act_num, step_data['turn'] ) : step_data['name'] );
      gf.find('#act-context').val( step_data['context'] );
    }
    gf.find('#act-name').trigger('blur');
  }
  
  /*
   *  (:> 指定のステップデータをセッションストレージへ保存（dataがない場合は現在のフォーム値を使用する）
   */
  function saveStepData( data=null ) {
    var step_data = {};
    if ( is_empty( data ) ) {
      var $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li'),
          currentStep  = Number( gf.find('#act-turn').val() ),
          hashKey      = '';
      $wizardSteps.each(function(){
        if ( currentStep == Number( $(this).attr('data-step') ) ) {
          hashKey = $(this).attr('data-hash');
        }
      });
      step_data = {
        'structure_id': gf.find('#act-structure-id').val(),
        'dependency':   gf.find('#act-dependency').val(),
        'group_id':     0,
        'turn':         currentStep,
        'name':         gf.find('#act-name').val(),
        'context':      gf.find('#act-context').val(),
        'diff':         true,
      };
    } else {
      hashKey = data.hash;
      step_data = {
        'structure_id': data.id,
        'dependency':   data.dependency,
        'group_id':     data.group_id,
        'turn':         data.turn,
        'name':         data.name,
        'context':      data.context,
        'diff':         true,
      };
    }
    wSQL.insert( hashKey, step_data );
  }
  
  
  /*
   * Code for debugging (:> デバッグ用コード
   */
  function debugPostData( postdata ) {
    var is_thru_after = true; // その後の処理を止めたい場合はfalseを指定する
    if ( Object.prototype.toString.call( postdata ) !== '[object Array]' ) {
      return is_thru_after;
    }
    postdata.forEach(function( v, i ){
      if ( is_empty( v.structure_id ) ) {
        console.log( sprintf( 'STEP: %d "%s" will insert.', v.turn, v.name ) );
      } else
      if ( v.turn < 0 ) {
        console.log( sprintf( 'STEP: %d "%s" (ID: %d) will remove.', v.turn, v.name, v.structure_id ) );
      } else
      if ( v.diff ) {
        console.log( sprintf( 'STEP: %d "%s" (ID: %d) will update.', v.turn, v.name, v.structure_id ) );
      }
    });
    return is_thru_after;
  }
  
});