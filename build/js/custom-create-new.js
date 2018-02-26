/**
 * For Whole Story (Global Settings)
 */
'use strict';
$(document).ready(function() {
  
  var gf               = $("#structureSettings"),
      wss              = window.sessionStorage,
      currentPermalink = 'create-new',
      currentSrcId     = Number( $('#source_id').val() ),
      structureType    = Number( $('#structure-presets').find('option:selected').val() ),
      SUBMIT_BUTTONS   = [ 'create' ];
  
  // common function
  function controlSubmission( action='lock' ) {
    $.each( SUBMIT_BUTTONS, function(i,v) {
      $('#'+currentPermalink+'-btn-'+v).prop('disabled', ( 'lock' === action ) );
    });
  }
  
  // 初期処理: sessionStorageを初期化
  clearSessionData();
  
  // Event handlers
  $('#structure-presets').on('change', function(){
    // プリセットを選択した時のイベント
    var selectedVar = Number( $(this).val() ),
        selectedActs = JSON.parse( $($(this).find('option:selected')[0]).data('acts').replace(/\'/g, '"') );
    if ( structureType === selectedVar ) {
      // 選択変更なしのためなにもしない
      return;
    } else {
      structureType = selectedVar;
      // sessionStorageを初期化
      clearSessionData();
    }
    // WizardのDOM要素を初期化
    initWizardElements();
    // Wizardを再生成
    rebuildWizard( selectedActs );
  });
  
  $('#create-new-btn-reset').on('click', function(e){
    // Resetボタン押下時
    e.preventDefault();
    $('#structure-presets> option').each(function(){
      if ( $(this).index() == 0 ) {
        $(this).prop('selected', true);
      } else {
        $(this).prop('selected', false);
      }
    });
    $('#structure-presets').trigger('change');
    logger.debug( 'Reseted Step' );
    // sessionStorageを初期化
    clearSessionData();
  });
  
  $(document).on('click', '.btn-remove-act', function(e){
    // step削除ボタン押下時
    e.preventDefault();
    var targetStep = Number( $(this).parents('li').data('step') );
    $('#wizard.wizard_horizontal> ul.wizard_steps> li').each(function(){
      var idx = $(this).index();
      if ( Number( $(this).data('step') ) == targetStep ) {
        $(this).remove();
      } else
      if ( $(this).data('step') !== 'last' && Number( $(this).data('step') ) > targetStep ) {
        $(this).attr('data-step', idx+1);
        $(this).find('.step_no').text(idx+1);
      }
    });
    // プリセットをカスタムへ変更
    $('#structure-presets> option').each(function(){
      if ( $(this).index() == 0 ) {
        $(this).prop('selected', true);
      } else {
        $(this).prop('selected', false);
      }
    });
    logger.debug( 'Removed Step: ', targetStep );
    // sessionStorageから対象ステップのフォームデータを削除する
    removeStepData( targetStep );
  });
  
  $(document).on('click', '.add_new a', function(e){
    // step追加ボタン押下時
    e.preventDefault();
    var nowSteps  = $('#wizard.wizard_horizontal> ul.wizard_steps> li').length,
        step_tmpl = $('#wizard-templates ul.common-step-template li').clone();
    step_tmpl.find('button.btn-remove-act').removeClass('hide');
    var newStep   = step_tmpl[0].outerHTML.replace(/\%N/g, nowSteps);
    $('#wizard.wizard_horizontal> ul.wizard_steps> li:last-child').remove();
    $('#wizard.wizard_horizontal> ul.wizard_steps').append( $(newStep)[0].outerHTML );
    var last_step_tmpl = $('#wizard-templates ul.last-step-template li').clone();
    $('#wizard.wizard_horizontal> ul.wizard_steps').append( $(last_step_tmpl)[0].outerHTML );
    logger.debug( 'Added Step: ', nowSteps );
  });
  
  $(document).on('click', '.step_indicator:not(.add_new) a', function(e){
    // step選択時
    e.preventDefault();
    var newStep = Number( $(this).parents('li').data('step') );
    if ( $('#wizard.wizard_horizontal> ul.wizard_steps> li> div.step_indicator.selected').length > 0 ) {
      // 現在のフォームデータをsessionStorageに保存
      saveStepData();
    }
    $('#wizard.wizard_horizontal> ul.wizard_steps> li').each(function(){
      if ( newStep == Number( $(this).data('step') ) ) {
        $(this).find('.step_indicator').addClass('selected');
      } else {
        $(this).find('.step_indicator').removeClass('selected');
      }
    });
    $('#act-turn').val( newStep );
    logger.debug( 'Selected Step: ', newStep );
    // sessionStorageから対象ステップのフォームデータを取得する
    retriveStepData( newStep );
  });
  
  $(document).on('click', '#create-new-btn-create', function(e){
    // 新規作成ボタン押下時
    e.preventDefault();
    var action = $(this).attr('id').replace('create-new-btn-', ''),
        steps  = $('#wizard.wizard_horizontal> ul.wizard_steps> li').length - 1;
    logger.debug( action, steps );
    // 現在のフォームデータをsessionStorageに保存
    saveStepData();
    $('#create-new-post-action').val( action );
    // セッションストレージ上の全ステップデータをマージする
    var step_data = new Array();
    $('#wizard.wizard_horizontal> ul.wizard_steps> li').each(function(){
      var availableStep = $(this).data('step'),
          searchKey     = 'plt_str_' + availableStep;
      if ( wss.hasOwnProperty( searchKey ) ) {
        step_data[$(this).index()] = JSON.parse( wss.getItem( searchKey ) );
      }
    });
    if ( step_data.length > 0 ) {
      var addField = $('<input>', { 'type': 'hidden', 'name': 'step_data', 'value': _.escape( JSON.stringify( step_data ) ) });
      gf.append( addField );
      $('#act-structure-id').prop('disabled', true);
      $('#act-dependency').prop('disabled', true);
      $('#act-turn').prop('disabled', true);
      $('#act-name').prop('disabled', true);
      $('#act-context').prop('disabled', true);
      gf.submit();
    } else {
      return false;
    }
  });
  
  $('#act-name').on('click blur keyup', function(){
    // ACT名の入力同期
    if ( ! is_empty( $(this).val() ) ) {
      var currentStep = Number( $('#act-turn').val() ),
          currentName = $(this).val();
      if ( currentStep > 0 ) {
        $('#wizard.wizard_horizontal> ul.wizard_steps> li').each(function(){
          if ( Number( $(this).data('step') ) == currentStep ) {
            $(this).find('.step_name').text( currentName );
            return false;
          }
        });
      }
    }
  });
  
  
  
  // 個別処理（関数）
  function initWizardElements() {
    logger.debug( 'Initialize Wizard Elements' );
    $('#wizard.wizard_horizontal> ul.wizard_steps> li').each(function(){
      $(this).remove();
    });
    formatFormItems();
  }
  
  function formatFormItems() {
    // var afc = $('#act-form-current');
    $('#act-structure-id').val('');
    $('#act-dependency').val('0');
    $('#act-turn').val('1');
    $('#act-name').val('');
    $('#act-context').val('');
  }
  
  function rebuildWizard( acts ) {
    logger.debug( 'Build Wizard', acts );
    $.each( acts, function( i, v ){
      var step_tmpl = $('#wizard-templates ul.common-step-template li').clone();
      if ( ! is_empty( v ) ) {
        step_tmpl.find('ul.step_meta> li.step_name').text( v );
        if ( i == 0 ) {
          $('#act-name').val( v );
        }
        var step_data = {
          'structure_id': '',
          'dependency'  : '0',
          'turn'        : i + 1,
          'name'        : v,
          'context'     : '',
        };
        wss.setItem( 'plt_str_' + (i+1), JSON.stringify( step_data ) );
      }
      if ( i > 0 ) {
        step_tmpl.find('button.btn-remove-act').removeClass('hide');
      } else {
        step_tmpl.find('.step_indicator').addClass('selected');
      }
      var newStep   = step_tmpl[0].outerHTML.replace(/\%N/g, i+1);
      $('#wizard.wizard_horizontal> ul.wizard_steps').append( $(newStep)[0].outerHTML );
    });
    var last_step_tmpl = $('#wizard-templates ul.last-step-template li').clone();
    $('#wizard.wizard_horizontal> ul.wizard_steps').append( $(last_step_tmpl)[0].outerHTML );
  }
  
  
  
  // セッションストレージ関連
  function clearSessionData() {
    // セッションストレージ初期化
    logger.debug( 'Clear All SessionStorage' );
    wss.clear();
  }
  
  function removeStepData( step ) {
    // セッションストレージから指定ステップデータを削除
    var key = 'plt_str_' + step;
    if ( wss.hasOwnProperty( key ) ) {
      wss.removeItem( key );
    }
  }
  
  function retriveStepData( step ) {
    // セッションストレージから指定ステップデータを取得してフォームにセット
    var key = 'plt_str_' + step;
    if ( ! wss.hasOwnProperty( key ) ) {
      gf.find('#act-structure-id').val( '' );
      gf.find('#act-dependency').val( '0' );
      gf.find('#act-turn').val( step );
      gf.find('#act-name').val( '' );
      gf.find('#act-context').val( '' );
    } else {
      var step_data = JSON.parse( wss.getItem( key ) );
      gf.find('#act-structure-id').val( step_data['structure_id'] );
      gf.find('#act-dependency').val( step_data['dependency'] );
      gf.find('#act-turn').val( step_data['turn'] );
      gf.find('#act-name').val( step_data['name'] );
      gf.find('#act-context').val( step_data['context'] );
    }
    gf.find('#act-name').trigger('blur');
  }
  
  function saveStepData() {
    // 現在選択中のステップデータをセッションストレージへ保存
    var step_data = {
      'structure_id': gf.find('#act-structure-id').val(),
      'dependency':   gf.find('#act-dependency').val(),
      'turn':         gf.find('#act-turn').val(),
      'name':         gf.find('#act-name').val(),
      'context':      gf.find('#act-context').val(),
      // 'type':         gf.find('#structure-presets> option:selected').val(),
    };
    wss.setItem( 'plt_str_' + step_data.turn, JSON.stringify( step_data ) );
  }
  
  
});