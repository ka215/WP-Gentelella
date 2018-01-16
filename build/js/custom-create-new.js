/**
 * For Whole Story (Global Settings)
 */
$(document).ready(function() {
  
  var gf              = $("#structureSettings"),
      wls             = window.localStorage,
      currentSrcId    = Number( $('#source_id').val() );
  
  function renderSmartWizard() {
    if ( typeof $.fn.smartWizard === 'function' ) {
      $('#wizard').smartWizard({
        // Properties
        selected: 0,  // Selected Step, 0 = first step   
        keyNavigation: true, // Enable/Disable key navigation(left and right keys are used if enabled)
        enableAllSteps: true,  // Enable/Disable all steps on first load
        transitionEffect: 'fade', // Effect on navigation, none/fade/slide/slideleft
        contentURL:null, // specifying content url enables ajax content loading
        contentURLData:null, // override ajax query parameters
        contentCache:true, // cache step contents, if false content is fetched always from ajax url
        cycleSteps: true, // cycle step navigation
        enableFinishButton: false, // makes finish button enabled always
        hideButtonsOnDisabled: false, // when the previous/next/finish buttons are disabled, hide them instead
        errorSteps:[],    // array of step numbers to highlighting as error steps
        // labelNext:'Next', // label for Next button
        // labelPrevious:'Previous', // label for Previous button
        // labelFinish:'Finish',  // label for Finish button        
        noForwardJumping:false,
        ajaxType: 'POST',
        // Events
        onLeaveStep: null, // triggers when leaving a step
        onShowStep: null,  // triggers when showing a step
        onFinish: null,  // triggers when Finish button is clicked  
        // buttonOrder: ['Next','Previous','Finish']  // button order, to hide a button remove it from the list
      });
    }
  }
  
  // Event handlers
  $('#structure-presets').on('change', function(){
    // プリセットを選択した時のイベント
    var selectedVar = $(this).val(),
        selectedActs = JSON.parse( $($(this).find('option:selected')[0]).data('acts').replace(/\'/g, '"') );
    logger.debug( currentSrcId, selectedVar, selectedActs );
    // 現在のすべてのAct項目をローカルに保存する
    saveActsAsCache();
    
    // ActのDOM要素を初期化
    initActElements();
    
    // Wizardを再度レンダリング
    buildWizard( selectedActs );
    
    // ローカルからすべてのAct項目を読み込む
    loadActsCache();
    
  });
  
  
  
  
  function saveActsAsCache() {
  }
  
  function initActElements() {
    $('#wizard ul.wizard_steps').remove();
    $('#wizard div.stepContainer').remove();
    $('#wizard div.actionBar').remove();
    $('#wizard div[id^="act-"]').remove();
console.info(     $('#wizard') );
  }
  
  function buildWizard( acts ) {
    $('#wizard').append('<ul class="wizard_steps"></ul>');
    $.each( acts, function( i, v ){
console.info( i, v );
      var step_tmpl = $('#wizard-templates ul.wizard-step-template li').clone(),
          newStep   = step_tmpl[0].outerHTML.replace(/\%N/g, i+1);
      $('#wizard ul.wizard_steps').append( $(newStep)[0].outerHTML );
      var act_tmpl = $('#wizard-templates div.wizard-act-template div[id^="act-"]').clone(),
          newAct   = act_tmpl[0].outerHTML.replace(/\%N/g, i+1);
      $('#wizard').append( $(newAct)[0].outerHTML );
    });
    renderSmartWizard();
  }
  
  function loadActsCache() {
  }
  
  /*
    if ( $(this).val() === '' ) {
      // Add new story
      storedSrcCache( currentSrcId ); // 現ソースIDをローカルに保存
      gf.find('input[name="source_id"]').val('');
      gf.find('#source_name').val('');
      gf.find('input[id^="wh"]').val('');
      gf.find('#team_writing').prop( 'checked', false );
      rebuildSwitchery( '#team_writing' );
      gf.find('#global-btn-add').removeClass('hide');
      gf.find('#global-btn-update').addClass('hide');
      gf.find('#global-btn-remove').addClass('hide');
    } else {
      // Switch another story
      var newSrcId = Number( $(this).val() );
      if ( currentSrcId != newSrcId ) {
        // ajaxで新ソースIDのデータを取得する
        currentSrcId = newSrcId;
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
          }
        });
        // Cookie値の lastSource を新ソースIDに更新する
        
        storedSrcCache( currentSrcId ); // 新ソースIDをローカルに保存
      } else {
        restoreSrcCache( currentSrcId ); // 現ソースIDをロード
      }
      gf.find('input[name="source_id"]').val(currentSrcId);
      gf.find('#global-btn-remove').removeClass('hide');
      gf.find('#global-btn-update').removeClass('hide');
      gf.find('#global-btn-add').addClass('hide');
    }
  });
  */
  
  
  $(document).on('click', 'button.btn[id^="create-new-btn"]', function(e){
    e.preventDefault();
    var action = $(this).attr('id').replace('create-new-btn-', '');
    if ( 'cancel' == action ) {
      return location.href = '/dashboard/';
    } else {
      $('#create-new-post-action').val( action );
      gf.submit();
    }
  });
  
  
  function rebuildSwitchery( selector ) {
    $('.js-switch').each(function(){
        if ( $(this).is(selector) ) {
            $(this).next('.switchery').remove();
            var switchery = new Switchery( this, { color: '#26B99A' } );
        }
    });
  }
  
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