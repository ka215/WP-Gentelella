/**
 * Resize function without multiple trigger
 * 
 * Usage:
 * $(window).smartresize(function(){  
 *     // code here
 * });
 */
(function($,sr){
    // debouncing function from John Hann
    // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
    var debounce = function (func, threshold, execAsap) {
      var timeout;

        return function debounced () {
            var obj = this, args = arguments;
            function delayed () {
                if (!execAsap)
                    func.apply(obj, args); 
                timeout = null; 
            }

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100); 
        };
    };

    // smartresize 
    jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');
/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
    $BODY = $('body'),
    $MENU_TOGGLE = $('#menu_toggle'),
    $SIDEBAR_MENU = $('#sidebar-menu'),
    $SIDEBAR_FOOTER = $('.sidebar-footer'),
    $LEFT_COL = $('.left_col'),
    $RIGHT_COL = $('.right_col'),
    $NAV_MENU = $('.nav_menu'),
    $FOOTER = $('footer');

// Sidebar
$(document).ready(function() {
    // TODO: This is some kind of easy fix, maybe we can improve this
    var setContentHeight = function () {
        // reset height
        $RIGHT_COL.css('min-height', $(window).height());

        var bodyHeight = $BODY.outerHeight(),
            footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
            leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
            contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

        // normalize content
        contentHeight -= $NAV_MENU.height() + footerHeight;

        $RIGHT_COL.css('min-height', contentHeight);
    };

    $SIDEBAR_MENU.find('a').on('click', function(ev) {
        var $li = $(this).parent();

        if ($li.is('.active')) {
            $('ul:first', $li).slideUp('fast','swing',function() {
                $li.removeClass('active active-sm');
                setContentHeight();
            });
        } else {
            // prevent closing menu if we are on child menu
            if (!$li.parent().is('.child_menu')) {
                $SIDEBAR_MENU.find('li').removeClass('active active-sm');
                $SIDEBAR_MENU.find('li ul').slideUp('fast','swing');
            }

            $li.addClass('active');

            var items = $('ul:first', $li).children().length,
                duration = items > 6 ? 300 : items * 50;
            $('ul:first', $li).slideDown(duration,'linear',function() {
                setContentHeight();
            });
        }
    });

    // toggle small or large menu
    $MENU_TOGGLE.on('click', function() {
        if ($BODY.hasClass('nav-md')) {
            $SIDEBAR_MENU.animate({ width: '70px' },50,'linear',function(){
                $SIDEBAR_MENU.find('li.active ul').hide();
                $SIDEBAR_MENU.find('li.active').addClass('active-sm').removeClass('active');
            });
        } else {
            $SIDEBAR_MENU.animate({ width: '230px' },100,'linear',function(){
                $SIDEBAR_MENU.find('li.active-sm ul').show();
                $SIDEBAR_MENU.find('li.active-sm').addClass('active').removeClass('active-sm');
            });
        }

        $BODY.toggleClass('nav-md nav-sm');

        setContentHeight();

        $('.dataTable').each ( function () { $(this).dataTable().fnDraw(); });
    });

    // check active menu
    $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').parent('li').addClass('current-page');

    $SIDEBAR_MENU.find('a').filter(function () {
        return this.href == CURRENT_URL;
    }).parent('li').addClass('current-page').parents('ul').slideDown(function() {
        setContentHeight();
    }).parent().addClass('active');

    // recompute content when resizing
    $(window).smartresize(function(){  
        setContentHeight();
    });

    setContentHeight();

    // fixed sidebar
    if ($.fn.mCustomScrollbar) {
        $('.menu_fixed').mCustomScrollbar({
            autoHideScrollbar: true,
            theme: 'minimal',
            mouseWheel:{ preventDefault: true }
        });
    }
});
// /Sidebar

// Panel toolbox
$(document).ready(function() {
    $('.collapse-link').on('click', function() {
        var $BOX_PANEL = $(this).closest('.x_panel'),
            $ICON = $(this).find('i'),
            $BOX_CONTENT = $BOX_PANEL.find('.x_content');
        
        // fix for some div with hardcoded fix class
        if ($BOX_PANEL.attr('style')) {
            $BOX_CONTENT.slideToggle(200, function(){
                $BOX_PANEL.removeAttr('style');
            });
        } else {
            $BOX_CONTENT.slideToggle(200); 
            $BOX_PANEL.css('height', 'auto');  
        }

        $ICON.toggleClass('fa-chevron-up fa-chevron-down');
    });

    $('.close-link').click(function () {
        var $BOX_PANEL = $(this).closest('.x_panel').parent();

        $BOX_PANEL.remove();
    });
});
// /Panel toolbox

// Tooltip
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });
});
// /Tooltip

// Progressbar
$(document).ready(function() {
	if ($(".progress .progress-bar")[0]) {
	    $('.progress .progress-bar').progressbar();
	}
});
// /Progressbar

// Switchery
$(document).ready(function() {
    if ($(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#26B99A'
            });
        });
    }
});
// /Switchery

// iCheck
$(document).ready(function() {
    if ($("input.flat")[0]) {
        $(document).ready(function () {
            $('input.flat').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    }
});
// /iCheck

// Table
$('table input').on('ifChecked', function () {
    checkState = '';
    $(this).parent().parent().parent().addClass('selected');
    countChecked();
});
$('table input').on('ifUnchecked', function () {
    checkState = '';
    $(this).parent().parent().parent().removeClass('selected');
    countChecked();
});

var checkState = '';

$('.bulk_action input').on('ifChecked', function () {
    checkState = '';
    $(this).parent().parent().parent().addClass('selected');
    countChecked();
});
$('.bulk_action input').on('ifUnchecked', function () {
    checkState = '';
    $(this).parent().parent().parent().removeClass('selected');
    countChecked();
});
$('.bulk_action input#check-all').on('ifChecked', function () {
    checkState = 'all';
    countChecked();
});
$('.bulk_action input#check-all').on('ifUnchecked', function () {
    checkState = 'none';
    countChecked();
});

function countChecked() {
    if (checkState === 'all') {
        $(".bulk_action input[name='table_records']").iCheck('check');
    }
    if (checkState === 'none') {
        $(".bulk_action input[name='table_records']").iCheck('uncheck');
    }

    var checkCount = $(".bulk_action input[name='table_records']:checked").length;

    if (checkCount) {
        $('.column-title').hide();
        $('.bulk-actions').show();
        $('.action-cnt').html(checkCount + ' Records Selected');
    } else {
        $('.column-title').show();
        $('.bulk-actions').hide();
    }
}

// Accordion
$(document).ready(function() {
    $(".expand").on("click", function () {
        $(this).next().slideToggle(200);
        $expand = $(this).find(">:first-child");

        if ($expand.text() == "+") {
            $expand.text("-");
        } else {
            $expand.text("+");
        }
    });
});

// NProgress
if (typeof NProgress != 'undefined') {
    //$(document).ready(function () {
    $(window).on('unload',function() {
        NProgress.start();
    });

    $(window).on('load', function() {
        NProgress.done();
    });
}

/**
 * Extended script (No Used)
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
  
});
/**
 * Extended script (Required)
 */
$(document).ready(function() {

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
    // Initialized items on modal
    $(this).find('select option:selected').prop('selected', false);
    $('#target-sensors .alert:not([role="template"])').remove();
    $('#no-selected-sensor').removeAttr('hidden');
    $('#view-type').prop('disabled', true );
    $('p.helper-text[id^="desc-"]').attr('hidden','hidden');
    $('.toggled-option').hide();
    $('#in-realtime').iCheck('disable');
    
    // Initialized plugins on modal
    $(".colorpicker-component").colorpicker();
    $("#text-to-match").select2({
      placeholder: "Enter text to match",
      width: '100%',
      tags: !0,
      tokenSeparators: [',', ' ', ';', "\n"],
      allowClear: !0,
    });
    
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
  
  // For choose sensors on modal of "#registerWidget".
  function sensor_exists() {
    var sensors = [];
    $('#target-sensors> .alert:not([role="template"])').each(function(){
      sensors.push( $(this).find('.sensor-name').text() );
    });
    return sensors.length > 0;
  }
  
  $('#sensor-groups').on('click change', function(e){
    var selected_group = e.target.value === "";
    $('#sensor-list').prop('disabled', selected_group);
  });
  
  $('#search-sensor-keyword').on('click', function(){
    $('#result-list').prop('disabled', false);
  });
  
  $('button[id^="add-sensor-"]').on('click', function(e){
    e.preventDefault();
    var sensors = [], addSensor = "", addSensors = [];
    $('#target-sensors> .alert:not([role="template"])').each(function(){
      sensors.push( $(this).find('.sensor-name').text() );
    });
    if ( e.target.id === 'add-sensor-list' ) {
      addSensor = $('#sensor-list').val();
      //if ( addSensor !== "" ) {
        if ( addSensor !== "all" ) {
          addSensors.push( addSensor );
        } else {
          $('#sensor-list> option').each(function(){
            if ( $.inArray( $(this).val(), [ "", "all" ] ) == -1 ) {
              addSensors.push( $(this).val() );
            }
          });
        }
      //}
    } else
    if ( e.target.id === 'add-sensor-search' ) {
      addSensor = $('#result-list').val();
      //if ( addSensor !== "" ) {
        if ( addSensor !== "all" ) {
          addSensors.push( addSensor );
        } else {
          $('#result-list> option').each(function(){
            if ( $.inArray( $(this).val(), [ "", "all" ] ) == -1 ) {
              addSensors.push( $(this).val() );
            }
          });
        }
      //}
    }
    sensors = sensors.filter( function(x, i, self){
      return self.indexOf(x) === i;
    });
    addSensors.forEach(function(val, i, self){
      if ( $.inArray(val, sensors) == -1 ) {
        var template = $('#target-sensors> .alert[role="template"]').clone();
        template.find('.sensor-name').text(val).end().removeAttr('hidden').removeAttr('role');
        template.find('.colorpicker-component').colorpicker();
        $('#no-selected-sensor').attr('hidden', 'hidden');
        $('#target-sensors').append(template);
      }
    });
    enableViewType();
    if ( $('#view-type').val() !== "" ) {
      var type = $('#view-type').val();
      enableAdvancedSettings( type );
    }
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
    enableAdvancedSettings( type );
  });
  
  function enableAdvancedSettings( type ){
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
  
  
  $('#reporting-time').ready(function(){
    var start = moment().subtract(29, 'days');
    var end = moment();
    
    function cb(start, end) {
      $('#reporting-time span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reporting-time').daterangepicker({
      startDate: start,
      endDate: end,
      timePicker: !0,
      timePickerIncrement: 1,
      alwaysShowCalendars: !0,
      opens: 'center',
      drops: 'up',
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      locale: {
        format: "MM/DD/YYYY h:mm A"
      }
    }, cb);

    cb(start, end);

    $('#reporting-time').on('apply.daterangepicker', function(e, picker) {
      if ( picker.chosenLabel !== "Custom Range" ) {
        $('#in-realtime-period').text(picker.chosenLabel);
        $('#in-realtime').iCheck('enable').iCheck('check');
        $('#reporting-time').attr('readonly', true);
      } else {
        $('#in-realtime-period').text('');
        $('#in-realtime').iCheck('uncheck').iCheck('disable');
        $('#reporting-time').attr('readonly', false);
      }

    });

    $(document).on('ifClicked', '#in-realtime', function(e){
      if ( $('#in-realtime-period').text() === "" ) {
        $(this).iCheck('disable');
        return false;
      }
    });
    $(document).on('ifUnchecked', '#in-realtime', function(e){
      $('#reporting-time').attr('readonly', false);
    });

  });
  
  /*
  $(".split_range_view").ionRangeSlider({
    type: "double",
    min: 0,
    max: 256,
    disable : !0,
    hide_min_max: !0
  });
  */
  
  $('#start-position .btn').on('click', function(e){
    var choosen = $(this).find('input').val();
    $('[id^="new-current-position-from-"]').prop('hidden', true);
    $('#new-current-position-from-'+choosen).prop('hidden', false);
    $('#add-split-position').prop('disabled', false);
  });
  
  $('#add-split-position').on('click', function(e){
    var splitPosition = Number( $('#new-split-position').val() ), addItem = $('#split-range-list> .alert[role="template"]').clone(), 
        nowItems = $('#split-range-list> .alert:not([role="template"])').length, eop = 1, val, 
        firstPrefix = $.trim( $('#new-current-position-from-'+$('#start-position .btn input:checked').val()).text() );
    if ( nowItems > 0 ) {
      $('#split-range-list> .alert:not([role="template"])').each(function(){
        eop = Number( $(this).find('.split-prefix').data('startPosition') );
        val = Number( $(this).find('input[name^="split_range"]').val() );
        eop += val;
      });
    }
    addItem.find('.split-prefix').attr('data-start-position', eop);
    if ( eop == 1 ) {
      addItem.find('.split-prefix').text( firstPrefix );
    } else {
      addItem.find('.split-prefix').text( "From " + eop + " byte To " );
    }
    addItem.find('input[name^="split_range"]').val(splitPosition);
    addItem.prop('disabled', false).prop('hidden', false);
    addItem.attr('role', 'alert');
    $('#split-range-list').append(addItem);
  });
  
  
  // For choose sensors on static page (Split Data etc.)
  $('#sensor-groups-sp').on('click change', function(e){
    var selected_group = e.target.value === "";
    $('#sensor-list-sp').prop('disabled', selected_group);
  });
  
  $('#search-sensor-keyword-sp').on('click', function(){
    $('#result-list-sp').prop('disabled', false);
  });
  
  $('button[id^="add-sp-sensor-"]').on('click', function(e){
    e.preventDefault();
    var sensors = [], addSensor = "", addSensors = [];
    $('#target-sensors-sp> .alert:not([role="template"])').each(function(){
      sensors.push( $(this).find('.sensor-name').text() );
    });
    if ( e.target.id === 'add-sp-sensor-list' ) {
      addSensor = $('#sensor-list-sp').val();
      //if ( addSensor !== "" ) {
        if ( addSensor !== "all" ) {
          addSensors.push( addSensor );
        } else {
          $('#sensor-list-sp> option').each(function(){
            if ( $.inArray( $(this).val(), [ "", "all" ] ) == -1 ) {
              addSensors.push( $(this).val() );
            }
          });
        }
      //}
    } else
    if ( e.target.id === 'add-sp-sensor-search' ) {
      addSensor = $('#result-list-sp').val();
      //if ( addSensor !== "" ) {
        if ( addSensor !== "all" ) {
          addSensors.push( addSensor );
        } else {
          $('#result-list-sp> option').each(function(){
            if ( $.inArray( $(this).val(), [ "", "all" ] ) == -1 ) {
              addSensors.push( $(this).val() );
            }
          });
        }
      //}
    }
    sensors = sensors.filter( function(x, i, self){
      return self.indexOf(x) === i;
    });
    addSensors.forEach(function(val, i, self){
      if ( $.inArray(val, sensors) == -1 ) {
        var template = $('#target-sensors-sp> .alert[role="template"]').clone();
        template.find('.sensor-name').text(val).end().removeAttr('hidden').removeAttr('role');
        template.find('.colorpicker-component').colorpicker();
        $('#no-selected-sensor-sp').attr('hidden', 'hidden');
        $('#target-sensors-sp').append(template);
      }
    });
  });
  
  $(document).on('click', '#target-sensors-sp> .alert button.close', function(){
    if ( $('#target-sensors-sp> .alert:not([role="template"])').length == 1) {
      $('#no-selected-sensor-sp').removeAttr('hidden');
    }
  });


  $("#grouping-group-name").select2({
    width: '50%',
    allowClear: 0,
    tags: !0
  }).on('select2:select', function(e) {
    var data = e.params.data,
        selectedGroup = data.id,
        hasSensors = parseInt($.trim(data.text.match(/\s\(\d*\)$/gi)).slice(1,-1));
    selectSensors( selectedGroup, hasSensors );
  });

  function selectSensors( selectedGroup, choosenItems ) {
    var gs   = $('#groupable-sensors'),
        rows = gs.find('tbody tr').length,
        i    = 0,
        selectedRow = [];
    // initialize
    gs.find('input[type="checkbox"]').iCheck('uncheck');
    gs.find('tbody td.last').text('');
    gs.find('tbody tr').removeClass('selected');
    
    // define checking rows
    while ( i < rows ) {
      var row_id = i;
      if ( choosenItems > 0 ) {
        if ( Math.round( ( Math.random() * choosenItems + 1 ) ) > 1 ) {
          selectedRow.push( row_id );
          choosenItems--;
        }
      }
      i++;
    }
    
    // check rows
    gs.find('tbody tr').each(function(idx){
      if ( $.inArray( idx, selectedRow ) != -1 ) {
        $(this).addClass('selected').find('input.check-one-row').iCheck('check');
        $(this).find('td.last').text( selectedGroup );
      }
    });

  }

  $(document).on('ifChecked', 'input#check-all', function(e){
    $('#groupable-sensors').find('input.check-one-row').iCheck('check');
  });

  $(document).on('ifUnchecked', 'input#check-all', function(e){
    $('#groupable-sensors').find('input.check-one-row').iCheck('uncheck');
  });


});
