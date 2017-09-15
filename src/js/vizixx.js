/**
 * Extended script
 */
$(document).ready(function() {
  
  var toggleFullScreen = function( instance ){
    // toggle full-screen browsing
    var d = document, isFullScreen = false;
    // console.info([ d.fullscreen, d.fullscreenElement, d.mozFullScreen, d.webkitIsFullScreen ]);
    // For Mozilla (firefox etc.)
    if ( ! d.mozFullScreen ) {
      if ( instance.mozRequestFullScreen ) {
        instance.mozRequestFullScreen();
        isFullScreen = true;
      }
    } else {
      if ( d.mozCancelFullScreen ) {
        d.mozCancelFullScreen();
        isFullScreen = false;
      }
    }
    // For Webkit (Chrome, Safari, Opera, Edge etc.)
    if ( ! d.webkitIsFullScreen ) {
      if ( instance.webkitRequestFullscreen ) {
        instance.webkitRequestFullscreen();
        isFullScreen = true;
       }
    } else {
      if ( d.webkitExitFullscreen ) {
        d.webkitExitFullscreen();
        isFullScreen = false;
      }
    }
    // Others
    if ( ! d.fullscreenElement ) {
      if ( instance.requestFullscreen ) {
        instance.requestFullscreen();
        isFullScreen = true;
      }
    } else {
      if ( d.exitFullscreen ) {
        d.exitFullscreen();
        isFullScreen = false;
      }
    }
    return isFullScreen;
  }
  
  $('.sidebar-footer').find('a').on('click', function(){
    var SIDEBAR_FOOTER_MENU = $(this).data('originalTitle').toLowerCase();
    switch (SIDEBAR_FOOTER_MENU) {
      case 'fullscreen':
        var elem = $('body');
        elem.css({ width: '100%', height: '100%', overflow: 'hidden' });
        var instance = elem[0],
            isFullScreen = toggleFullScreen( instance );
        if ( isFullScreen ) {
          $(this).children('span').attr('class', 'fa fa-minus-square-o'); /* fa-window-maximize */
        } else {
          elem.css({ width: '100%', height: 'auto', overflow: 'visible' });
          $(this).children('span').attr('class', 'glyphicon glyphicon-fullscreen');
        }
        break;
      default:
        break;
    }
  });
  
  $('.nav.child_menu> li> a').on('click', function(){
    var $CURRENT_CHILD_MENU = $(this).parent();
    $CURRENT_CHILD_MENU.toggleClass('active');
    $('#registerWidget, #transmitClassC').on('hide.bs.modal', function(e) {
      $CURRENT_CHILD_MENU.removeClass('active');
      $CURRENT_CHILD_MENU.parents('li.active').find('ul').slideUp('fast','swing');
      $CURRENT_CHILD_MENU.parents('li').removeClass('active active-sm');
    });
  });
  
  $('.full_col[role="main"]').ready(function(){
    var $CURRENT_CHILD_MENU = $('.nav.child_menu> li');
    $CURRENT_CHILD_MENU.removeClass('active');
    $CURRENT_CHILD_MENU.parents('li.active').find('ul').hide(); //slideUp('fast','swing');
    //$CURRENT_CHILD_MENU.parents('li').removeClass('active active-sm');
  });
  
  $('.x_panel').on({
    'mouseenter': function(){
      $(this).find('.panel_toolbox').removeAttr('hidden');
    },
    'mouseleave': function(){
      $(this).find('.panel_toolbox').attr('hidden', 'hidden');
    }
  });
  
  if ( $('.right_col> .row> div[class^="col-"]:not(.guidelines)').length == 1 ) {
    $('.right_col> .row> div.guidelines').removeAttr('hidden');
  }
  
  $('#registerWidget').on('show.bs.modal', function(e) {
    $(".colorpicker-component").colorpicker();
    var presetWidget = $.trim( $(e.relatedTarget).text() );
    $(this).find('#view-type> option').each(function(){
      if ( presetWidget === $.trim( $(this).text() ) ) {
        $(this).prop('selected', true);
      } else {
        $(this).prop('selected', false);
      }
    });
  });
  
  $('#transmitClassC').on('show.bs.modal', function(e) {
    if ( e.relatedTarget.id === 'multicast' ) {
      $(this).find('#multicast-opt1').show();
    } else {
      $(this).find('#multicast-opt1').hide();
    }
  });
  
  
  
  
});
/**
 * For DEMO scripts
 */
$(document).ready(function() {
  
  function sensor_exists() {
    var sensors = [];
    $('#target-sensors> .alert:not([role="template"])').each(function(){
      sensors.push( $(this).find('.sensor-name').text() );
    });
    return sensors.length > 0;
  }
  
  $('#sensor-groups').on('change', function(e){
    var selected_group = e.target.value === "";
    $('#sensor-list').prop('disabled', selected_group);
  });
  
  $('#search-sensor-keyword').on('click', function(){
    $('#result-list').prop('disabled', false);
  });
  
  $('button[id^="add-sensor-"]').on('click', function(e){
    var sensors = [], addSensor = "", addSensors = [];
    $('#target-sensors> .alert').each(function(){
      sensors.push( $(this).find('.sensor-name').text() );
    });
    if ( e.target.id === 'add-sensor-list' ) {
      addSensor = $('#sensor-list').val();
      if ( addSensor !== "" ) {
        addSensors.push( addSensor );
      }
    } else
    if ( e.target.id === 'add-sensor-search' ) {
      addSensor = $('#result-list').val();
      if ( addSensor !== "" ) {
        addSensors.push( addSensor );
      }
    }
    sensors = sensors.filter( function(x, i, self){
      return self.indexOf(x) === i;
    });
    addSensors.forEach(function(val, i, self){
      if ( $.inArray(val, sensors) == -1 ) {
        var template = $('#target-sensors> .alert[role="template"]').clone();
        template.find('.sensor-name').text(val).end().removeAttr('hidden').removeAttr('role');
        $('#no-selected-sensor').attr('hidden', 'hidden');
        $('#target-sensors').append(template);
      }
    });
    enableViewType();
  });
  
  $(document).on('click', '#target-sensors> .alert button.close', function(){
    if ( $('#target-sensors> .alert:not([role="template"])').length == 1) {
      $('#view-type').prop('disabled', true );
      $('#no-selected-sensor').removeAttr('hidden');
    }
  });
  
  function enableViewType() {
    $('#view-type').prop('disabled', ! sensor_exists() );
  }
  
  $('#add_matching_text').on('click', function(){
    var str = $('#text_to_match').val(), strList = [];
    if ( str !== "" ) {
      $('#matching_text_list> button').each(function(){
        strList.push( $.trim( $(this).text() ) );
      });
      if ( $.inArray( str, strList ) == -1 ) {
        $('#matching_text_list').append('<button type="button" class="btn btn-default btn-sm">' + str +' <i class="fa fa-close"></i></button>');
        $('#text_to_match').val('');
      }
    }
  });
  
  $(document).on('click', '#matching_text_list> button', function(){
    $(this).fadeOut(150, function(){ $(this).remove() });
  });
  
  $('#view-type').on('change', function(e){
    var type = e.target.value;
    $('p.helper-text[id^="desc-"]').attr('hidden','hidden');
    $('.toggled-option').hide();
    switch(type){
      case 'table':
        $('#desc-' + type).removeAttr('hidden');
        $('#period-to-show').show();
        
        break;
      case 'line':
        $('#desc-' + type).removeAttr('hidden');
        $('#period-to-show').show();
        $('#axis-on-graph').show();
        
        break;
      case 'bar':
        $('#desc-' + type).removeAttr('hidden');
        $('#period-to-show').show();
        $('#axis-on-graph').show();
        
        break;
      case 'text':
        $('#desc-' + type).removeAttr('hidden');
        $('#period-to-show').show();
        
        break;
      case 'switch':
        $('#desc-' + type).removeAttr('hidden');
        $('#period-to-show').show();
        $('#switch-onoff').show();
        
        break;
      case 'map':
        $('#desc-' + type).removeAttr('hidden');
        $('#period-to-show').show();
        
        break;
      case 'table_summary':
        $('#desc-' + type).removeAttr('hidden');
        $('#period-to-show').show();
        $('#summary-data-type').show();
        
        break;
      case 'line_summary':
        $('#desc-' + type).removeAttr('hidden');
        $('#period-to-show').show();
        $('#axis-on-graph').show();
        $('#summary-data-type').show();
        
        break;
      case 'bar_summary':
        $('#desc-' + type).removeAttr('hidden');
        $('#period-to-show').show();
        $('#axis-on-graph').show();
        $('#summary-data-type').show();
        
        break;
      case 'pie_summary':
        $('#desc-' + type).removeAttr('hidden');
        $('#period-to-show').show();
        $('#summary-data-type').show();
        
        break;
      default:
        
        break;
    }
    
    $('#select-data-type> label.btn').on('click', function(e){
      var dataType = $(this).find('input').val();
      switch( dataType ) {
        case 'numric':
          $('#summary-condition-text').hide();
          $('#summary-condition-switch').hide();
          $('#summary-condition-number').show();
          
          break;
        case 'text':
          $('#summary-condition-number').hide();
          $('#summary-condition-switch').hide();
          $('#summary-condition-text').show();
          
          break;
        case 'switch':
          $('#summary-condition-number').hide();
          $('#summary-condition-text').hide();
          $('#summary-condition-switch').show();
          
          break;
        default:
          $('#summary-condition-number').hide();
          $('#summary-condition-text').hide();
          $('#summary-condition-switch').hide();
          
      }
      
    });
    
  });
  
  
});
