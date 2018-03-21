/**
 * For Edit Storyline (/edit-storyline/)
 */
'use strict';
$(document).ready(function() {
  
  var gf               = $("#structureSettings"),
      wss              = window.sessionStorage,
      currentPermalink = 'edit-storyline',
      currentSrcId     = Number( $('#source_id').val() );
  SUBMIT_BUTTONS   = [ 'commit', 'update', 'remove' ];
  
  // ----- 初期処理: sessionStorageを初期化 -----------------------------------------------------------
  clearSessionData();
  
  // ----- Event handlers -------------------------------------------------------------------------
  
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
    var targetStep   = Number( $(this).parents('li').data('step') ),
        $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li');
    $wizardSteps.each(function(){
      var idx = $(this).index();
      if ( Number( $(this).data('step') ) == targetStep ) {
        $(this).remove();
      } else
      if ( $(this).data('step') !== 'last' && Number( $(this).data('step') ) > targetStep ) {
        $(this).attr('data-step', idx+1);
        $(this).find('.step_no').text(idx+1);
      }
    });
    $wizardSteps.parent('ul').removeAttr('style');
    resizeWizardSteps();
    logger.debug( 'Removed Step: ', targetStep );
    // sessionStorageから対象ステップのフォームデータを削除する
    removeStepData( targetStep );
    reorderSteps();
  });
  
  /*
   * Add New Step (:> Step追加ボタン押下時のイベント
   */
  $(document).on('click', '.add_new a', function(e){
    e.preventDefault();
    var $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li'),
        nowSteps     = $wizardSteps.length,
        step_tmpl    = $('#wizard-templates ul.common-step-template li').clone();
    step_tmpl.find('button.btn-remove-act').removeClass('hide');
    var newStep   = sprintf( step_tmpl[0].outerHTML, '', nowSteps, nowSteps * 10, nowSteps, nowSteps );
    // $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li:last-child').remove();
    $wizardSteps.filter(':last-child').remove();
    // $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps').append( $(newStep)[0].outerHTML );
    $wizardSteps.parent('.wizard_steps').append( $(newStep)[0].outerHTML );
    var last_step_tmpl = $('#wizard-templates ul.last-step-template li').clone();
    last_step_tmpl = sprintf( $(last_step_tmpl)[0].outerHTML, (nowSteps + 1) * 10 );
    // $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps').append( $(last_step_tmpl)[0].outerHTML );
    $wizardSteps.parent('.wizard_steps').append( $(last_step_tmpl)[0].outerHTML );
    logger.debug( 'Added Step: ', nowSteps );
    resizeWizardSteps();
    reorderSteps();
  });
  
  /*
   * Selected Step (:> Step選択時のイベント
   */
  $(document).on('click', '.step_indicator:not(.add_new) a', function(e){
    e.preventDefault();
    var newStep = Number( $(this).parents('li').data('step') ),
        $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li');
    if ( $wizardSteps.children('div.step_indicator.selected').length > 0 ) {
      // 現在のフォームデータをsessionStorageに保存
      saveStepData( $wizardSteps.children('div.step_indicator.selected').parent('li').data().structureId );
    }
    $wizardSteps.each(function(){
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
  
  /*
   * Clicked Sub-Storyline and Parent-Storyline (:> サブストーリーラインおよび親ストーリーライン選択時のイベント
   */
  $(document).on('click', 'a.sub_storyline, a.parent_storyline', function(e){
    e.preventDefault();
    var strAtts = $(this).parent('li').data();
    docCookies.setItem( 'dependency', strAtts.dependency, 60*60*24*30, '/' );
    docCookies.setItem( 'group_id', strAtts.groupId, 60*60*24*30, '/' )
    showLoading();
    location.reload(false);
  });
  
  /*
   * Clicked "Add Sub-Storyline" (:> サブストーリーライン追加時のイベント
   */
  $(document).on('click', 'a.add_sub', function(e){
    e.preventDefault();
    var strAtts = $(this).parent('li').data();
    docCookies.setItem( 'dependency', strAtts.parentStructureId, 60*60*24*30, '/' );
    docCookies.setItem( 'group_id', '-1', 60*60*24*30, '/' );
    showLoading();
    location.reload(false);
  });
  
  
  
  
  /*
   *  (:> 時のイベント
   */
  $(document).on('click', '#create-new-btn-create', function(e){
    // 新規作成ボタン押下時
    e.preventDefault();
    var action       = $(this).attr('id').replace('create-new-btn-', ''),
        $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li'),
        steps        = $wizardSteps.length - 1;
    logger.debug( action, steps );
    controlSubmission();
    // 現在のフォームデータをsessionStorageに保存
    saveStepData();
    $('#create-new-post-action').val( action );
    // セッションストレージ上の全ステップデータをマージする
    var step_data = new Array();
    $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li').each(function(){
      var availableStep = $(this).data('step'),
          searchKey     = 'plt_str_' + availableStep;
      if ( wss.hasOwnProperty( searchKey ) ) {
        step_data[$(this).index()] = JSON.parse( wss.getItem( searchKey ) );
      }
    });
    if ( step_data.length > 0 ) {
      showLoading();
      var addField = $('<input>', { 'type': 'hidden', 'name': 'step_data', 'value': JSON.stringify( step_data ) });
      gf.append( addField );
      // 最後に表示されているフォームのデータは送信対象から除外する
      $('#act-structure-id').prop('disabled', true);
      $('#act-dependency').prop('disabled', true);
      $('#act-turn').prop('disabled', true);
      $('#act-name').prop('disabled', true);
      $('#act-context').prop('disabled', true);
      // gf.submit();
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
      controlSubmission( 'unlock' );
    } else {
      return false;
    }
  });
  
  /*
   *  (:> ACT名の入力同期
   */
  $('#act-name').on('focus keyup', function(){
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
  
  
  
  // ----- 個別処理（関数）------------------------------------------------------------------------
  
  /*
   *  (:> 
   */
  function initWizardElements() {
    logger.debug( 'Initialize Wizard Elements' );
    var $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li');
    $wizardSteps.each(function(){
      $(this).remove();
    });
    formatFormItems();
  }
  
  /*
   * reOrder Steps (:> STEPのorder番号を再採番（正規化）する
   */
  function reorderSteps() {
    var nowOrder     = [],
        optOrder     = [],
        $wizardSteps = $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps> li');
    $wizardSteps.each(function(){
      nowOrder.push( Number( $(this).css('order') ) );
      optOrder.push( $(this).index() + 1 );
    });
    var sortOrder = nowOrder.slice();
    sortOrder.sort(function(a,b){ return a - b; });
    nowOrder.forEach(function(v,i,m){
      sortOrder.find(function(d,j){ if ( d == v ) nowOrder[i] = optOrder[j]; });
    });
    $wizardSteps.each(function(){
      $(this).css( 'order', nowOrder[$(this).index()] * 10 );
      // set turn...
      
    });
    logger.debug( nowOrder, optOrder, sortOrder );
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
  
  /*
   *  (:> 
   */
  function formatFormItems() {
    // var afc = $('#act-form-current');
    $('#act-structure-id').val('');
    $('#act-dependency').val('0');
    $('#act-turn').val('1');
    $('#act-name').val('');
    $('#act-context').val('');
  }
  
  /*
   *  (:> 
   */
  function rebuildWizard( acts ) {
    logger.debug( 'Build Wizard', acts );
    $.each( acts, function( i, v ){
      var step_tmpl = $('#wizard-templates ul.common-step-template li').clone(),
          step_name = is_empty( v ) ? sprintf( localize_messages.act_num, i + 1 ) : v;
      step_tmpl.find('ul.step_meta> li.step_name').text( step_name );
      if ( i == 0 ) {
        $('#act-name').val( step_name );
      }
      var step_data = {
        'structure_id': '',
        'dependency'  : '0',
        'turn'        : i + 1,
        'name'        : step_name,
        'context'     : '',
      };
      wss.setItem( 'plt_str_' + (i+1), JSON.stringify( step_data ) );
      
      if ( i > 0 ) {
        step_tmpl.find('button.btn-remove-act').removeClass('hide');
      } else {
        step_tmpl.find('.step_indicator').addClass('selected');
      }
      var newStep   = step_tmpl[0].outerHTML.replace(/\%N/g, i+1);
      $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps').append( $(newStep)[0].outerHTML );
    });
    var last_step_tmpl = $('#wizard-templates ul.last-step-template li').clone();
    $('#wizard.wizard_horizontal> .wizard_steps_container> ul.wizard_steps').append( $(last_step_tmpl)[0].outerHTML );
  }
  
  
  
  // ----- セッションストレージ関連 ---------------------------------------------------------------
  
  /*
   *  (:> セッションストレージ初期化
   */
  function clearSessionData() {
    logger.debug( 'Clear All SessionStorage' );
    wss.clear();
  }
  
  /*
   *  (:> セッションストレージから指定ステップデータを削除
   */
  function removeStepData( step ) {
    var key = 'plt_str_' + step;
    if ( wss.hasOwnProperty( key ) ) {
      wss.removeItem( key );
    }
  }
  
  /*
   *  (:> セッションストレージから指定ステップデータを取得してフォームにセット
   */
  function retriveStepData( step ) {
    var key = 'plt_str_' + step;
    if ( ! wss.hasOwnProperty( key ) ) {
      gf.find('#act-structure-id').val( '' );
      gf.find('#act-dependency').val( '0' );
      gf.find('#act-turn').val( step );
      gf.find('#act-name').val( sprintf( localize_messages.act_num, step ) );
      gf.find('#act-context').val( '' );
    } else {
      var step_data = JSON.parse( wss.getItem( key ) );
      gf.find('#act-structure-id').val( step_data['structure_id'] );
      gf.find('#act-dependency').val( step_data['dependency'] );
      gf.find('#act-turn').val( step_data['turn'] );
      gf.find('#act-name').val( is_empty( step_data['name'] ) ? sprintf( localize_messages.act_num, step ) : step_data['name'] );
      gf.find('#act-context').val( step_data['context'] );
    }
    gf.find('#act-name').trigger('blur');
  }
  
  /*
   *  (:> 指定のステップデータ（structure_id）をセッションストレージへ保存
   */
  function saveStepData( structureId=null ) {
    var step_data = {
      'structure_id': is_empty( structureId ) ? '' : structureId,
      'dependency':   Number( gf.find('#act-dependency').val() ),
      'turn':         Number( gf.find('#act-turn').val() ),
      'name':         gf.find('#act-name').val(),
      'context':      gf.find('#act-context').val(),
      // 'type':         gf.find('#structure-presets> option:selected').val(),
    };
    wss.setItem( 'plt_str_' + step_data.turn, JSON.stringify( step_data ) );
  }
  
  
});